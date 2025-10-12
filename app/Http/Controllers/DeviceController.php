<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
 public function index()
    {
        $printers = Printer::all();
        return view('admin.printers.index', compact('printers'));
    }

    /**
     * Store a newly created printer in storage.
     */

	public function create()
	{
		// Return a view for creating a printer (if using Blade templates)
		return view('admin.printers.create');
	}
	
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:printers,name|max:255',
            'type' => 'required|in:windows,network,serial,linux_usb',
            'connector_value' => 'required|string|max:255',
            'section' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        $printer = Printer::create($request->all());
       return redirect('/printers')->with('success', 'Printer created successfully!');
    }

    /**
     * Display the specified printer.
     */
    public function show($id)
    {
        $printer = Printer::findOrFail($id);
        return response()->json($printer);
    }

	public function edit($id) {
	$printer = Printer::findOrFail($id);
	return view('admin.printers.edit', compact('printer'));
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
            'section' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        $printer->update($request->all());

     return redirect('admin/printers')->with('success', 'Printer updated successfully!');
    }

    /**
     * Remove the specified printer from storage.
     */
    public function destroy($id)
    {
        $printer = Printer::findOrFail($id);
        $printer->delete();

     return redirect('admin/printers')->with('success', 'Printer deleted successfully!');
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
