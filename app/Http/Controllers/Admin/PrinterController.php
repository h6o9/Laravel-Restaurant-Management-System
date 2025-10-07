<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use Illuminate\Http\Request;

class PrinterController extends Controller
{
    /**
     * Display a listing of the printers.
     */
    public function index()
    {
        $printers = Printer::all();
        return response()->json($printers);
    }

    /**
     * Store a newly created printer in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:printers,name|max:255',
            'type' => 'required|in:windows,network,serial,linux_usb',
            'connector_value' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        $printer = Printer::create($request->all());
        return response()->json([
            'message' => 'Printer created successfully!',
            'data' => $printer
        ], 201);
    }

    /**
     * Display the specified printer.
     */
    public function show($id)
    {
        $printer = Printer::findOrFail($id);
        return response()->json($printer);
    }

    /**
     * Update the specified printer.
     */
    public function update(Request $request, $id)
    {
        $printer = Printer::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|unique:printers,name,' . $printer->id,
            'type' => 'sometimes|required|in:windows,network,serial,linux_usb',
            'connector_value' => 'sometimes|required|string|max:255',
            'section' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        $printer->update($request->all());

        return response()->json([
            'message' => 'Printer updated successfully!',
            'data' => $printer
        ]);
    }

    /**
     * Remove the specified printer from storage.
     */
    public function destroy($id)
    {
        $printer = Printer::findOrFail($id);
        $printer->delete();

        return response()->json(['message' => 'Printer deleted successfully!']);
    }

	public function toggleStatus(Request $request)
{
    $printer = Printer::find($request->id);
    if (!$printer) {
        return response()->json(['success' => false, 'message' => 'Printer not found']);
    }

    $printer->is_active = $request->is_active ? 1 : 0;
    $printer->save();

    return response()->json([
        'success' => true,
        'message' => $request->is_active ? 'Printer activated successfully.' : 'Printer deactivated successfully.'
    ]);
}

}
