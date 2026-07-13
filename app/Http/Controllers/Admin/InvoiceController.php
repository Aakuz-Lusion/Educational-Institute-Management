<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = FeeInvoice::with(['student', 'feeStructure'])->paginate(20);
        return view('admin.invoices.index', compact('invoices'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'student_id'       => 'required|exists:users,id',
            'fee_structure_id' => 'required|exists:fee_structures,id',
            'due_date'         => 'required|date',
        ]);
        $fee = FeeStructure::find($request->fee_structure_id);
        FeeInvoice::create([
            'student_id'       => $request->student_id,
            'fee_structure_id' => $fee->id,
            'amount'           => $fee->amount,
            'due_date'         => $request->due_date,
            'status'           => 'pending',
        ]);
        return back()->with('success', 'Invoice generated.');
    }
}
