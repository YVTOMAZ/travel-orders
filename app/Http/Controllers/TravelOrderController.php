<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreTravelOrderRequest;
use App\Http\Requests\UpdateTravelOrderStatusRequest;
use App\Models\TravelOrder;
use Illuminate\Http\Request;

class TravelOrderController extends Controller
{
    public function store(StoreTravelOrderRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $travelOrder = TravelOrder::create($data);

        return response()->json($travelOrder, 201);
    }

    public function index(Request $request)
    {
        $query = TravelOrder::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('destination')) {
            $query->where('destination', 'like', '%' . $request->destination . '%');
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('departure_date', [$request->start_date, $request->end_date]);
        }

        $query->where('user_id', auth()->id());

        return response()->json($query->get());
    }

    public function show($id)
    {
        $travelOrder = TravelOrder::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return response()->json($travelOrder);
    }

    public function updateStatus(UpdateTravelOrderStatusRequest $request, $id)
    {
        $travelOrder = TravelOrder::findOrFail($id);

        if ($travelOrder->user_id === auth()->id()) {
            return response()->json(['error' => 'User not authorized to update their own order status'], 403);
        }

        $newStatus = $request->status;

        if ($travelOrder->status === 'approved' && $newStatus !== 'cancelled') {
            return response()->json(['error' => 'Only cancellation is allowed for approved orders'], 422);
        }

        $travelOrder->update(['status' => $newStatus]);

        $travelOrder->user->notify(new \App\Notifications\OrderStatusNotification($travelOrder));

        return response()->json($travelOrder);
    }
}
