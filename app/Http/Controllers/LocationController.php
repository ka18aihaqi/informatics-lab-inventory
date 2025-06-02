<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::all();
        return view('locations.index', compact('locations'));
    }

    public function create()
    {
        return view('locations.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:locations|max:255',
            'description' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->with('error', "<strong>Add Failed.</strong><br><strong>$request->name</strong> has already been taken.");
        }
    
        Location::create($request->all());
    
        return redirect()->route('locations.index')->with('success', "<strong>Added Successfully.</strong><br><strong>$request->name</strong> added successfully.");
    }

    public function show(Location $location)
    {
        return view('locations.show', compact('location'));
    }

    public function edit(Location $location)
    {
        return view('locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:locations,name,' . $location->id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', "<strong>Update Failed.</strong><br><strong>$request->name</strong> has already been taken.");
        }

        $location->update($request->all());

        return redirect()->route('locations.index')->with('success', "<strong>Updated successfully.</strong><br><strong>$request->name</strong> has been successfully updated.");
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()->route('locations.index')->with('success', "<strong>Deleted successfully.</strong><br><strong>$location->name</strong> deleted successfully.");
    }

    public function downloadPDF($location_id)
    {
        $location = Location::with([
            'desks.pcs.diskDrive',
            'desks.pcs.processor',
            'desks.pcs.vgaCard',
            'desks.pcs.ram',
            'items.asset'
        ])->findOrFail($location_id);

        $pdf = Pdf::loadView('pdf.location_report', compact('location'));

        return $pdf->download('laporan_inventaris_' . $location->name . '.pdf');
    }
}
