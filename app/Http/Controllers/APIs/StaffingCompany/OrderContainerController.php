<?php

namespace App\Http\Controllers\APIs\StaffingCompany;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderWasteContainerRequest;
use App\Models\StaffingCompany\ContainerOperation;
use App\Models\StaffingCompany\ContainerSupplier;
use App\Models\StaffingCompany\ContainerType;
use App\Models\StaffingCompany\ContainerWasteBreakdown;
use App\Models\StaffingCompany\OrderContainer;
use App\Models\StaffingCompany\StaffingProject;
use App\Models\User;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderContainerController extends Controller
{

    public function index()
    {
        $orderContainer=OrderContainer::all();

       return  response()->json($orderContainer,200);

    }
    public function create()
    {
        try {
//            $suppliers = ContainerSupplier::all();
            return response()->json([
                'success' => true,
//                'suppliers' => $suppliers,
                'message' => 'This API is no longer needed because the project data will be retrieved from the sign-in process, and the supplier data is not required as per the discussion in the meeting'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


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
                'notes' => $request->notes,
                'comments' => $request->comments,
                'status' => 'open', // Default status if needed
            ]);
            $operationMap=[];
            // Save container operations
            foreach ($request->containers as $container) {
                $containerType = ContainerType::where('name', $container['type'])->first();

                if ($containerType) {
                    $operation= ContainerOperation::create([
                        'order_container_id' => $orderContainer->id,
                        'container_type_id' => $containerType->id,
                        'placement' => $container['placement'],
                        'exchange' => $container['exchange'],
                        'discharge' => $container['discharge'],
                        ]);
                    // Store in map by container type ID
                    $operationMap[$containerType->id] = $operation->id;

                }
            }

            // Save waste breakdown
            foreach ($request->wastes as $waste) {
                $containerType = ContainerType::where('name', $waste['type'])->first();

                if ($containerType) {
                    ContainerWasteBreakdown::create([
                        'order_container_id' => $orderContainer->id,
                        'container_type_id' => $containerType->id,
                        'container_operation_id' => $operationMap[$containerType->id] ?? null,
                        'bsa' => $waste['bsa'],
                        'debris' => $waste['debris'],
                        'wood' => $waste['wood'],
                        'plastic_foil' => $waste['plastic_foil'],
                        'paper' => $waste['paper'],
                        'diverse' => $waste['diverse'],
                        'comment' => $waste['comment'],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order container created successfully.',
                'data' => [
                    'order_container_id' => $orderContainer->id
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create order.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function updateStatus(Request $request, $id)
    {
        // Validate incoming request
        $request->validate([
            'status' => 'required|in:open,in progress,send to supplier,pick up,picked,canceled,close'
        ]);

        try {
            $orderContainer = OrderContainer::findOrFail($id);

            $orderContainer->update([
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully.',
                'data' => [
                    'order_container_id' => $orderContainer->id,
                    'status' => $orderContainer->status
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function listOfOrders($id)
    {

        $user = User::with([
            'contact' => function ($q) {
                $q->without('user')
                    ->with([
                        'staffingprojects.orderContainers.containerOperation.containerType',
                        'staffingprojects.orderContainers.containerOperation.wasteBreakdown'
                    ]);
            }
        ])->find($id);

        return response()->json($user);

    }
    public function pdf($id)
    {

        $data=OrderContainer::with(['project.projectPerformer', 'project.customer','containerOperation.containerType','containerOperation.wasteBreakdown','supplier'])->where('id', $id)->first();
        $today = Carbon::now();
        $currentTime = $today->format('H:i');
        $dayName = $today->format('l');
        $today   =$today->format('Y-m-d');

        $pdf = PDF::loadView('StaffingCompany/OrderWasteContainer/pdf',compact('data','currentTime','dayName','today'))->setPaper('a4', 'landscape');
        return $pdf->download("container_$currentTime.pdf");

        return redirect()->back()->with('message', 'Order Deleted Successfully!');
    }

}
