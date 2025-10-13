<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\MenuItem;
use App\Models\Printer;
use App\Models\Section;

class OrderTokenController extends Controller
{
    //
	public function index()
    {
        $orders = Order::all();
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

		$menuItems = MenuItem::all();
    return view('admin.orders.create', compact('sections', 'menuItems'));
}


// 	public function store(Request $request)
// {
// 	return $request->all();
//     $request->validate([
//         'table_no' => 'required',
//         'section_id' => 'required',
//         'items' => 'required|string',
//     ]);

//     $order = Order::create([
//         'table_no' => $request->table_no,
//         'section_id' => $request->section_id,
//         'description' => $request->description,
//         'items' => $request->items,
//         'status' => 'pending',
//     ]);

//     // Print send logic
//     $section = Section::find($request->section_id);
//     if ($section->name === 'Kitchen') {
//         \App\Helpers\PrintHelper::printKitchenOrder($order);
//     } elseif ($section->name === 'Cold Bar') {
//         \App\Helpers\PrintHelper::printColdBarOrder($order);
//     }

//     return redirect()->back()->with('success', 'Order sent for preparation successfully!');
// }
public function store(Request $request)
{
    $request->validate([
        'table_no'   => 'required|string|max:255',
        'section'    => 'required|string|max:100',
        'quantities' => 'required|array', // e.g. ['1' => 2, '3' => 5] where keys = menu_item IDs
    ]);

    $user = auth()->user();

    // ✅ Generate one order number for all items
    $orderNo = 'ORD-' . strtoupper(uniqid());

    foreach ($request->quantities as $itemId => $qty) {
        // ✅ Find item name from menu_items table
        $menuItem = \App\Models\MenuItem::find($itemId);

        if (!$menuItem) {
            // Skip or handle missing item safely
            continue;
        }

        // ✅ Create order row for each item
        \App\Models\Order::create([
            'order_no'   => $orderNo,
            'table_no'   => $request->table_no,
            'section'    => $request->section,
            'items'      => $menuItem->name,  // Store item name, not ID
            'quantities' => $qty,              // Store quantity
            'status'     => 'pending',
            'created_by' => ($user && $user->role === 'subadmin') ? $user->name : 'Admin',
            'description'=> $request->description,
        ]);
    }

    
    // ✅ Fix printing: pass Order model (singular)
    $section = strtolower($request->section);
    $orderForPrint = \App\Models\Order::where('order_no', $orderNo)->first(); // First row

    if ($orderForPrint) {
        if ($section === 'kitchen') {
            \App\Helpers\PrintHelper::printKitchenOrder($orderForPrint);
        } elseif ($section === 'cold_bar') {
            \App\Helpers\PrintHelper::printColdBarOrder($orderForPrint);
        }
    }

return redirect()->route('orders.index')->with('success', 'Order sent for preparation successfully!');
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

    $section = Section::find($order->section);
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
