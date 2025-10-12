<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Printer;
use App\Models\Section;

class OrderTokenController extends Controller
{
    //
	public function index()
    {
        $orders = Order::latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

   public function create()
{
    // Printers table se unique section names nikalo (null ya empty exclude karo)
    $sections = Printer::whereNotNull('section')
        ->where('section', '!=', '')
        ->select('section')
        ->distinct()
        ->get();

		$menuItems = [
    (object)['id' => 1, 'name' => 'Burger'],
    (object)['id' => 2, 'name' => 'Pizza'],
    (object)['id' => 3, 'name' => 'Pasta'],
];
    return view('admin.orders.create', compact('sections', 'menuItems'));
}


	public function store(Request $request)
{
    $request->validate([
        'table_no' => 'required',
        'section_id' => 'required',
        'items' => 'required|string',
    ]);

    $order = Order::create([
        'table_no' => $request->table_no,
        'section_id' => $request->section_id,
        'description' => $request->description,
        'items' => $request->items,
        'status' => 'pending',
    ]);

    // Print send logic
    $section = Section::find($request->section_id);
    if ($section->name === 'Kitchen') {
        \App\Helpers\PrintHelper::printKitchenOrder($order);
    } elseif ($section->name === 'Cold Bar') {
        \App\Helpers\PrintHelper::printColdBarOrder($order);
    }

    return redirect()->back()->with('success', 'Order sent for preparation successfully!');
}


    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

   public function update(Request $request, Order $order)
{
    // 1️⃣ Validation rules
    $validated = $request->validate([
        'table_no' => 'required|string|max:50',
        'section' => 'required|exists:sections,id',
        'items' => 'required|array',
        'status' => 'required|string|in:pending,preparing,completed,cancelled',
        'description' => 'nullable|string',
    ]);

    // 2️⃣ Update order
    $order->update([
        'table_no' => $validated['table_no'],
        'section_id' => $validated['section'],
        'items' => json_encode($validated['items']),
        'status' => $validated['status'],
        'description' => $validated['description'] ?? null,
    ]);

    // 3️⃣ Print Order if section changed or status = "preparing"
    if ($order->status === 'preparing') {
        if ($order->section_id == 1) {
            $this->printKitchenOrder($order);
        } elseif ($order->section_id == 2) {
            $this->printColdBarOrder($order);
        }
    }

    // 4️⃣ Return with success message
    return redirect()->route('orders.index')->with('success', 'Order updated successfully!');
}

public function destroy(Request $request, Order $order)
{
    $request->validate([
        'reason' => 'required|string|max:255',
    ]);

    $section = Section::find($order->section_id);
    $orderDetails = $order->toArray();
    $reason = $request->reason;

    if ($section) {
        switch (strtolower($section->name)) {
            case 'kitchen':
                \App\Helpers\PrintHelper::printKitchenDeletionSlip($orderDetails, $reason);
                break;
            case 'cold bar':
                \App\Helpers\PrintHelper::printColdBarDeletionSlip($orderDetails, $reason);
                break;
        }
    }

    $order->delete();

    return response()->json(['success' => true, 'message' => 'Order deleted and deletion slip sent to printer.']);
}

}
