<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id'     => 'required|exists:fee_invoices,id',
            'amount'         => 'required|numeric|min:0.01',
            'payment_date'   => 'required|date',
            'payment_method' => 'nullable|string',
        ]);
        $invoice = FeeInvoice::find($request->invoice_id);
        FeePayment::create([
            'invoice_id'     => $invoice->id,
            'amount'         => $request->amount,
            'payment_date'   => $request->payment_date,
            'payment_method' => $request->payment_method,
        ]);
        // Update invoice status to paid (or partially paid if needed)
        $invoice->update(['status' => 'paid']);
        return back()->with('success', 'Payment recorded.');
    }

}
