<?php

namespace App\Http\Controllers\StaffingCompany;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderWasteContainerRequest;
use App\Models\Role;
use App\Models\StaffingCompany\ContainerWasteBreakdown;
use App\Models\StaffingCompany\ContainerOperation;
use App\Models\StaffingCompany\ContainerSupplier;
use App\Models\StaffingCompany\ContainerType;
use App\Models\StaffingCompany\OrderContainer;
use App\Models\StaffingCompany\StaffingProject;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderContainerController extends Controller
{
    public function index($status = null)
    {
        $query = OrderContainer::with(['project', 'supplier'])->latest();
        if ($status) {
            $query->where('status', $status);
        }

        $order_containers = $query->get();

        return view('StaffingCompany.OrderWasteContainer.index', compact('order_containers', 'status'));
    }


    public function create()
    {

        $projects = StaffingProject::all();
        //        $suppliers = ContainerSupplier::all();
        $containerType = ContainerType::all();
        $translations = __('Staffing_Company/Order_Container/crud');
        $user = Auth::user()->only(['id', 'name']);
        $suppliers = ContainerSupplier::all();

        return view('StaffingCompany.OrderWasteContainer.create', compact('translations', 'projects', 'suppliers', 'containerType', 'user', 'suppliers'));
    }

    //    public function store(OrderWasteContainerRequest $request)
    //    {
    //
    //        $order_container = OrderContainer::create([
    //            'project_id' => $request->project,
    //            'container_supplier_id' => $request->supplier,
    //            'order_date_time' => $request->order_date_time,
    //            'execution_date' => $request->execution_date,
    //            'approved_by' => $request->approved_by,
    //            'order_by' => $request->order_by,
    //            'part_of_day' => $request->desired_time,
    //            'notes' => $request->notes,
    //            'comments' => $request->comments,
    //        ]);
    //    }
    public function store(OrderWasteContainerRequest $request)
    {
        DB::beginTransaction();

        try {
            $orderContainer = OrderContainer::create([
                'project_id' => $request->project,
                'container_supplier_id' => $request->supplier,
                'order_date_time' => $request->order_date_time,
                'execution_date' => $request->execution_date,
                'approved_by' => $request->approved_by,
                'order_by' => $request->order_by,
                'part_of_day' => $request->desired_time,
                'notes' => $request->container_notes,
                'comments' => $request->comments,
            ]);

            foreach ($request->fields as $container) {

                $containerType = ContainerType::where('name', $container['label'])->first();
                if ($containerType) {
                    $operation = ContainerOperation::create([
                        'order_container_id' => $orderContainer->id,
                        'container_type_id' => $containerType->id,
                        'placement' => $container['place'],
                        'exchange' => $container['vary'],
                        'discharge' => $container['disposal'],
                    ]);

                    ContainerWasteBreakdown::create([
                        'order_container_id' => $orderContainer->id,
                        'container_type_id' => $containerType->id,
                        'container_operation_id' =>  $operation->id ?? null,
                        'bsa' => $container['bsa'],
                        'debris' => $container['debris'],
                        'wood' => $container['wood'],
                        'plastic_foil' => $container['plastic_foil'],
                        'paper' => $container['paper'],
                        'diverse' => $container['diverse'],
                        'comment' => $container['comment'],
                    ]);
                }
            }

            DB::commit();

            return response()->json(['message' => __('Staffing_Company/Order_Container/crud.created_successfully')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Failed to create order.', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        return response()->json(OrderContainer::with(['project', 'containerOperation.containerType', 'containerOperation.wasteBreakdown', 'supplier'])->find($id));
    }

    public function view($id)
    {

        $orderContainer = $this->show($id);
        $translations = __('Staffing_Company/Order_Container/crud');
        return view('StaffingCompany.OrderWasteContainer.show', compact('id', 'translations', 'orderContainer'));
    }

    public function edit($id)
    {
        $containerType = ContainerType::all();
        $suppliers = ContainerSupplier::all();
        $translations = __('Staffing_Company/Order_Container/crud');
        return view('StaffingCompany.OrderWasteContainer.update', compact('id', 'suppliers', 'translations', 'containerType'));
    }

    public function update(OrderWasteContainerRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $orderContainer = OrderContainer::findOrFail($id);
            $orderContainer->update([
                'project_id' => $request->project,
                'container_supplier_id' => $request->supplier,
                'order_date_time' => $request->order_date_time,
                'execution_date' => $request->execution_date,
                'approved_by' => $request->approved_by,
                'order_by' => $request->order_by,
                'part_of_day' => $request->desired_time,
                'notes' => $request->container_notes,
                'comments' => $request->comments,
            ]);

            // Delete existing operations and waste breakdowns
            ContainerOperation::where('order_container_id', $orderContainer->id)->delete();
            ContainerWasteBreakdown::where('order_container_id', $orderContainer->id)->delete();

            foreach ($request->fields as $container) {
                $containerType = ContainerType::where('name', $container['label'])->first();
                if ($containerType) {
                    $operation = ContainerOperation::create([
                        'order_container_id' => $orderContainer->id,
                        'container_type_id' => $containerType->id,
                        'placement' => $container['place'],
                        'exchange' => $container['vary'],
                        'discharge' => $container['disposal'],
                    ]);

                    ContainerWasteBreakdown::create([
                        'order_container_id' => $orderContainer->id,
                        'container_type_id' => $containerType->id,
                        'container_operation_id' =>  $operation->id ?? null,
                        'bsa' => $container['bsa'],
                        'debris' => $container['debris'],
                        'wood' => $container['wood'],
                        'plastic_foil' => $container['plastic_foil'],
                        'paper' => $container['paper'],
                        'diverse' => $container['diverse'],
                        'comment' => $container['comment'],
                    ]);
                }
            }


            DB::commit();

            return response()->json(['message' => __('Staffing_Company/Order_Container/crud.update_successfully')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Failed to update order.', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        OrderContainer::where('id', $id)->delete();

        return redirect()->back()->with('message', 'Order Deleted Successfully!');
    }
    public function pdf($id)
    {

        //        $data=OrderContainer::with(['project.projectPerformer', 'project.customer','supplier.priceLists','containerOperation.containerType','containerOperation.wasteBreakdown'])->where('id', $id)->first();
        $data = OrderContainer::with(['project.projectPerformer', 'project.customer', 'containerOperation.containerType', 'containerOperation.wasteBreakdown', 'supplier'])->where('id', $id)->first();
        $today = Carbon::now();
        $currentTime = $today->format('H:i');
        $dayName = $today->format('l');
        $today   = $today->format('Y-m-d');

        $pdf = PDF::loadView('StaffingCompany/OrderWasteContainer/pdf', compact('data', 'currentTime', 'dayName', 'today'))->setPaper('a4', 'landscape');
        return $pdf->download("container_$currentTime.pdf");

        return redirect()->back()->with('message', 'Order Deleted Successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,in progress,send to supplier,pick up,picked,canceled,close'
        ]);
        try {
            $orderContainer = OrderContainer::findOrFail($id);

            $orderContainer->update([
                'status' => $request->status,
            ]);
            return redirect()->back()->with('message', 'Order status updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update order status: ' . $e->getMessage());
        }
    }
}
