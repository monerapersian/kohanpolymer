<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InquiryController extends Controller
{
    /**
     * Display the inquiries in the admin panel.
     */
    public function index(Request $request)
    {
        $query = Inquiry::query();

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->input('status') === 'unread') {
            $query->where('is_read', false);
        } elseif ($request->input('status') === 'read') {
            $query->where('is_read', true);
        }

        $inquiries = $query
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $totalInquiries = Inquiry::count();
        $unreadInquiries = Inquiry::where('is_read', false)->count();

        return view('admin.inquiries.index', compact(
            'inquiries',
            'totalInquiries',
            'unreadInquiries'
        ));
    }

    /**
     * Store a new inquiry submitted from the public website.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullName' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'subject' => [
                'required',
                Rule::in(['product', 'technical', 'cooperation', 'other']),
            ],
            'message' => ['nullable', 'string', 'max:10000'],
        ]);

        Inquiry::create([
            'full_name' => trim($validated['fullName']),
            'phone' => trim($validated['phone']),
            'subject' => $validated['subject'],
            'message' => $validated['message'] ?? null,
        ]);

        return response()->json([
            'message' => 'درخواست شما با موفقیت ثبت شد.',
        ], 201);
    }

    /**
     * Display an inquiry and mark it as read.
     */
    public function show(Inquiry $inquiry)
    {
        if (! $inquiry->is_read) {
            $inquiry->update(['is_read' => true]);
        }

        return response()->json([
            'id' => $inquiry->id,
            'full_name' => $inquiry->full_name,
            'phone' => $inquiry->phone,
            'subject' => $inquiry->subject,
            'message' => $inquiry->message,
            'is_read' => $inquiry->is_read,
            'created_at' => $inquiry->created_at,
        ]);
    }
}