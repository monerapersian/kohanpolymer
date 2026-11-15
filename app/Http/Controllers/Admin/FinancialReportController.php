<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\ManualIncome;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class FinancialReportController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'start_date' => [
                'nullable',
                'required_if:period,custom',
                'date_format:Y-m-d',
            ],
            'end_date' => [
                'nullable',
                'required_if:period,custom',
                'date_format:Y-m-d',
                'after_or_equal:start_date',
            ],
            'include_unofficial' => ['nullable', 'boolean'],
            'period' => [
                'nullable',
                'in:current,previous,three-months,six-months,year,custom',
            ],
        ], [
            'start_date.required_if' => 'تاریخ شروع را انتخاب کنید.',
            'end_date.required_if' => 'تاریخ پایان را انتخاب کنید.',
            'end_date.after_or_equal' => 'تاریخ پایان نمی‌تواند قبل از تاریخ شروع باشد.',
        ]);

        $includeUnofficial = $request->boolean('include_unofficial');

        [$startDate, $endDate] = $this->resolveDateRange(
            $validated['period'] ?? 'current',
            $validated['start_date'] ?? null,
            $validated['end_date'] ?? null
        );

        $officialSales = $this->invoiceSales(
            $startDate,
            $endDate,
            true
        );

        $unofficialSales = $includeUnofficial
            ? $this->invoiceSales($startDate, $endDate, false)
            : 0;

        $sales = $officialSales + $unofficialSales;

        $manualIncome = (float) ManualIncome::query()
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $expenses = (float) Expense::query()
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $financialStats = [
            'sales' => $sales,
            'expenses' => $expenses,
            'income' => $sales + $manualIncome,
            'manual_income' => $manualIncome,
            'net_income' => $sales + $manualIncome - $expenses,
        ];

        $chartData = $this->buildChartData(
            $startDate,
            $endDate,
            $includeUnofficial
        );

        $financialPeriods = [
            'current' => 'ماه جاری',
            'previous' => 'ماه گذشته',
            'three-months' => 'سه ماه اخیر',
            'six-months' => 'شش ماه اخیر',
            'year' => 'سال جاری',
            'custom' => 'بازه دلخواه',
        ];

        return view('admin.reports.financial', compact(
            'financialStats',
            'financialPeriods',
            'chartData',
            'startDate',
            'endDate',
            'includeUnofficial'
        ));
    }

    public function storeIncome(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:1'],
            'transaction_date' => ['required', 'date_format:Y-m-d'],
            'description' => ['nullable', 'string', 'max:5000'],
            'document' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ], [
            'title.required' => 'عنوان درآمد را وارد کنید.',
            'amount.required' => 'مبلغ درآمد را وارد کنید.',
            'amount.numeric' => 'مبلغ درآمد باید عددی باشد.',
            'amount.min' => 'مبلغ درآمد باید بیشتر از صفر باشد.',
            'transaction_date.required' => 'تاریخ درآمد را انتخاب کنید.',
            'transaction_date.date_format' => 'تاریخ درآمد معتبر نیست.',
            'document.image' => 'فایل انتخاب‌شده باید تصویر باشد.',
            'document.mimes' => 'فرمت تصویر باید JPG، PNG یا WebP باشد.',
            'document.max' => 'حجم تصویر نباید بیشتر از ۵ مگابایت باشد.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_modal', 'income');
        }

        $validated = $validator->validated();

        DB::transaction(function () use ($request, $validated) {
            $documentPath = null;

            if ($request->hasFile('document')) {
                $documentPath = $request->file('document')
                    ->store('financial-documents/incomes', 'local');
            }

            ManualIncome::create([
                'title' => $validated['title'],
                'amount' => $validated['amount'],
                'transaction_date' => $validated['transaction_date'],
                'description' => $validated['description'] ?? null,
                'document_path' => $documentPath,
                'user_id' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('admin.reports.financial')
            ->with('success', 'درآمد با موفقیت ثبت شد.');
    }

    public function storeExpense(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:1'],
            'transaction_date' => ['required', 'date_format:Y-m-d'],
            'description' => ['nullable', 'string', 'max:5000'],
            'document' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ], [
            'title.required' => 'عنوان هزینه را وارد کنید.',
            'amount.required' => 'مبلغ هزینه را وارد کنید.',
            'amount.numeric' => 'مبلغ هزینه باید عددی باشد.',
            'amount.min' => 'مبلغ هزینه باید بیشتر از صفر باشد.',
            'transaction_date.required' => 'تاریخ هزینه را انتخاب کنید.',
            'transaction_date.date_format' => 'تاریخ هزینه معتبر نیست.',
            'document.image' => 'فایل انتخاب‌شده باید تصویر باشد.',
            'document.mimes' => 'فرمت تصویر باید JPG، PNG یا WebP باشد.',
            'document.max' => 'حجم تصویر نباید بیشتر از ۵ مگابایت باشد.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_modal', 'expense');
        }

        $validated = $validator->validated();

        DB::transaction(function () use ($request, $validated) {
            $documentPath = null;

            if ($request->hasFile('document')) {
                $documentPath = $request->file('document')
                    ->store('financial-documents/expenses', 'local');
            }

            Expense::create([
                'title' => $validated['title'],
                'amount' => $validated['amount'],
                'transaction_date' => $validated['transaction_date'],
                'description' => $validated['description'] ?? null,
                'document_path' => $documentPath,
                'user_id' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('admin.reports.financial')
            ->with('success', 'هزینه با موفقیت ثبت شد.');
    }

    private function invoiceSales(
        string $startDate,
        string $endDate,
        bool $isFormal
    ): float {
        return (float) Invoice::query()
            ->where('type', 'invoice')
            ->where('status', 'valid')
            ->where('is_formal', $isFormal)
            ->whereBetween('issue_date', [$startDate, $endDate])
            ->withSum('items as total_amount', 'grand_total')
            ->get()
            ->sum('total_amount');
    }

    private function resolveDateRange(
        string $period,
        ?string $customStart,
        ?string $customEnd
    ): array {
        if ($period === 'custom' && $customStart && $customEnd) {
            return [$customStart, $customEnd];
        }

        $today = Carbon::today();

        return match ($period) {
            'previous' => [
                $today->copy()->subMonthNoOverflow()->startOfMonth()->toDateString(),
                $today->copy()->subMonthNoOverflow()->endOfMonth()->toDateString(),
            ],

            'three-months' => [
                $today->copy()->subMonths(2)->startOfMonth()->toDateString(),
                $today->toDateString(),
            ],

            'six-months' => [
                $today->copy()->subMonths(5)->startOfMonth()->toDateString(),
                $today->toDateString(),
            ],

            'year' => [
                $today->copy()->startOfYear()->toDateString(),
                $today->toDateString(),
            ],

            default => [
                $today->copy()->startOfMonth()->toDateString(),
                $today->toDateString(),
            ],
        };
    }

    private function monthExpression(string $column): string
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            return "strftime('%Y-%m', {$column})";
        }

        return "DATE_FORMAT({$column}, '%Y-%m')";
    }

    private function buildChartData(
        string $startDate,
        string $endDate,
        bool $includeUnofficial
    ): array {
        $start = Carbon::parse($startDate)->startOfMonth();
        $end = Carbon::parse($endDate)->startOfMonth();

        $labels = [];
        $official = [];
        $unofficial = [];
        $manualIncome = [];
        $expenses = [];

        $incomesByMonth = ManualIncome::query()
            ->selectRaw(
                $this->monthExpression('transaction_date') . ' as month, SUM(amount) as total'
            )
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->groupBy('month')
            ->pluck('total', 'month');

        $expensesByMonth = Expense::query()
            ->selectRaw(
                $this->monthExpression('transaction_date') . ' as month, SUM(amount) as total'
            )
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->groupBy('month')
            ->pluck('total', 'month');

        $officialByMonth = Invoice::query()
            ->join('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->selectRaw(
                $this->monthExpression('invoices.issue_date') . ' as month, SUM(invoice_items.grand_total) as total'
            )
            ->where('invoices.type', 'invoice')
            ->where('invoices.status', 'valid')
            ->where('invoices.is_formal', true)
            ->whereBetween('invoices.issue_date', [$startDate, $endDate])
            ->groupBy('month')
            ->pluck('total', 'month');

        $unofficialByMonth = collect();

        if ($includeUnofficial) {
            $unofficialByMonth = Invoice::query()
                ->join('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')
                ->selectRaw(
                    $this->monthExpression('invoices.issue_date') . ' as month, SUM(invoice_items.grand_total) as total'
                )
                ->where('invoices.type', 'invoice')
                ->where('invoices.status', 'valid')
                ->where('invoices.is_formal', false)
                ->whereBetween('invoices.issue_date', [$startDate, $endDate])
                ->groupBy('month')
                ->pluck('total', 'month');
        }

        for ($date = $start->copy(); $date->lte($end); $date->addMonth()) {
            $month = $date->format('Y-m');

            $labels[] = \App\Support\DateHelper::toPersian(
                $date->copy()->startOfMonth(),
                'Y/m'
            );

            $official[] = (float) ($officialByMonth[$month] ?? 0);

            $unofficial[] = $includeUnofficial
                ? (float) ($unofficialByMonth[$month] ?? 0)
                : 0;

            $manualIncome[] = (float) ($incomesByMonth[$month] ?? 0);
            $expenses[] = (float) ($expensesByMonth[$month] ?? 0);
        }

        return [
            'labels' => $labels,
            'official' => $official,
            'unofficial' => $unofficial,
            'manual_income' => $manualIncome,
            'expenses' => $expenses,
        ];
    }
}