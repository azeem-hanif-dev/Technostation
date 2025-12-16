<?php

namespace App\Http\Controllers\APIs\StaffingCompany\Supplier;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\StaffingCompany\OrderContainer;
use App\Models\StaffingCompany\ContainerSupplier;


class SupplierController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = _user();

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
        ]);

        $user->update([
            'name' => $request->name,
            'address' => $request->address,
        ]);
        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    }

    public function listOfOrders()
    {
        $user = _user();


        $supplier = ContainerSupplier::where('user_id', $user->id)->first();

        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }

        $orderData = OrderContainer::with([
            'project:id,name,address,performer',
            'project.projectPerformer:id,mobile',
            'containerOperation.wasteBreakdown'
        ])
            ->where('container_supplier_id', $supplier->id)
            ->get();

        return response()->json($orderData);
    }
}
