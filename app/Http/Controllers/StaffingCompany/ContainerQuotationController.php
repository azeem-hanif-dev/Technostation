<?php

namespace App\Http\Controllers\StaffingCompany;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\StaffingCompany\containerquotation;
use App\Models\StaffingCompany\containerquotationitem;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use PDF;

class ContainerQuotationController extends Controller
{
    public function index()
    {
        $quotations   = ContainerQuotation::with('customer')->latest()->get();
        $translations = __('Staffing_Company/quotations/index');
        return view('StaffingCompany.quotations.index', compact('quotations', 'translations'));
    }

    public function create()
    {
        $customers = \App\Models\Customer::all();
        $translations = __('Staffing_Company/quotations/create');
        return view('staffingCompany.quotations.create', compact('customers', 'translations'));
    }
    public function store(Request $request)
    {
        $quotation = containerquotation::create([
            'user_id' => auth()->id(),
            'project' => $request->project,
            'customer_id' => $request->customer_id,
            'contact_person' => $request->contact_person,
            'date' => now(),
            'total' => 0
        ]);

        $total = 0;
        foreach ($request->items as $item) {
            $amount = ($item['weight'] * $item['price_per_ton']) + ($item['extra_charge'] ?? 0);
            $total += $amount;

            containerquotationitem::create([
                'container_quotation_id' => $quotation->id,
                'date' => $item['date'],
                'content' => $item['content'],
                'waste_type' => $item['waste_type'],
                'weight' => $item['weight'],
                'price_per_ton' => $item['price_per_ton'],
                'extra_charge' => $item['extra_charge'] ?? 0,
                'amount' => $amount,
                'remarks' => $item['remarks'] ?? null,
            ]);
        }

        $quotation->update(['total' => $total]);

        return response()->json([
            'message' => __('Staffing_Company/quotations/create.success_message'),
            'status'  => 200,
            'id'      => $quotation->id,
        ]);
    }
    public function show($id)
    {
        $quotation = ContainerQuotation::with(['customer', 'items'])->findOrFail($id);
        return view('StaffingCompany.quotations.show', compact('quotation'));
    }
    public function edit($id)
    {
        $quotation = ContainerQuotation::with('items')->findOrFail($id);
        $translations = __('Staffing_Company/quotations/create');
        $customers = Customer::all();

        return view('StaffingCompany.quotations.edit', compact('quotation', 'customers', 'translations'));
    }
    public function update(Request $request, $id)
    {
        $quotation = containerquotation::findOrFail($id);

        $quotation->update([
            'project' => $request->project,
            'customer_id' => $request->customer_id,
            'contact_person' => $request->contact_person,
        ]);
        $quotation->items()->delete();

        $total = 0;
        foreach ($request->items as $item) {
            $amount = ($item['weight'] * $item['price_per_ton']) + ($item['extra_charge'] ?? 0);
            $total += $amount;

            $quotation->items()->create([
                'date' => $item['date'],
                'content' => $item['content'],
                'waste_type' => $item['waste_type'],
                'weight' => $item['weight'],
                'price_per_ton' => $item['price_per_ton'],
                'extra_charge' => $item['extra_charge'] ?? 0,
                'amount' => $amount,
                'remarks' => $item['remarks'] ?? null,
            ]);
        }

        $quotation->update(['total' => $total]);

        return response()->json([
            'message' => __('Staffing_Company/quotations/create.update_message'),
            'status'  => 200,
            'id'      => $quotation->id,
        ]);
    }
    public function downloadPdf($id)
    {
        $quotation = ContainerQuotation::with('items')->findOrFail($id);
        $pdf = PDF::loadView('StaffingCompany.Quotations.pdf', compact('quotation'));
        return $pdf->download('quotation_' . $quotation->id . '.pdf');
    }

    public function destroy($id)
    {
        $quotation = ContainerQuotation::findOrFail($id);
        $quotation->delete();

        return redirect()->route('quotations.index')->with('success', 'Quotation deleted successfully.');
    }
}
