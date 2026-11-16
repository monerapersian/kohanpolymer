<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display the customer list.
     */
    public function index(Request $request): View
    {
        $customers = Customer::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->input('search'));

                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('national_id', 'like', "%{$search}%");
                });
            })
            ->when(
                in_array($request->input('type'), ['company', 'person'], true),
                function ($query) use ($request) {
                    $query->where('type', $request->input('type'));
                }
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalCustomers = Customer::count();

        $companyCount = Customer::where('type', 'company')->count();

        $personCount = Customer::where('type', 'person')->count();

        return view('admin.customers.index', compact(
            'customers',
            'totalCustomers',
            'companyCount',
            'personCount'
        ));
    }

    /**
     * Store a new customer.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['company', 'person'])],
            'name' => ['required', 'string', 'max:200'],
            'phone' => ['nullable', 'string', 'max:16'],
            'national_id' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:5000'],
        ]);

        $customer = Customer::create($validated);

        app(ActivityLogger::class)->log(
            action: 'customer_created',
            description: 'مشتری جدید «' . $customer->name . '» ثبت شد.',
            subject: $customer,
            properties: [
                'name' => $customer->name,
                'type' => $customer->type,
                'has_phone' => ! empty($customer->phone),
            ]
        );

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'مشتری جدید با موفقیت ثبت شد.');
    }

    /**
     * Display customer details and their documents.
     */
    public function show(Customer $customer): View
    {
        $documents = $customer->documents()
            ->latest()
            ->paginate(10);

        return view('admin.customers.show', compact(
            'customer',
            'documents'
        ));
    }

    /**
     * Update an existing customer.
     */
    public function update(
        Request $request,
        Customer $customer
    ): RedirectResponse {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['company', 'person'])],
            'name' => ['required', 'string', 'max:200'],
            'phone' => ['nullable', 'string', 'max:16'],
            'national_id' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:5000'],
        ]);

        $oldValues = [
            'name' => $customer->name,
            'type' => $customer->type,
            'has_phone' => ! empty($customer->phone),
        ];

        $customer->update($validated);

        $changes = [];

        foreach (['name', 'type'] as $field) {
            $oldValue = $oldValues[$field];
            $newValue = $customer->{$field};

            if ($oldValue !== $newValue) {
                $changes[$field] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        $newHasPhone = ! empty($customer->phone);

        if ($oldValues['has_phone'] !== $newHasPhone) {
            $changes['phone'] = [
                'old' => $oldValues['has_phone'],
                'new' => $newHasPhone,
            ];
        }

        app(ActivityLogger::class)->log(
            action: 'customer_updated',
            description: 'اطلاعات مشتری «' . $customer->name . '» ویرایش شد.',
            subject: $customer,
            properties: [
                'changes' => $changes,
            ]
        );

        return redirect()
            ->route('admin.customers.show', $customer)
            ->with('success', 'اطلاعات مشتری با موفقیت ویرایش شد.');
    }
}