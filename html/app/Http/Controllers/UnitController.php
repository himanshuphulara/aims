<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unit = Unit::first(); // Get the single unit if it exists
        $canAdd = !$unit; // Can only add if no unit exists
        $title = "Army Unit Management";
        return view('units.index', compact('unit', 'canAdd', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unit_name' => 'required|string|max:255|unique:units,unit_name',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors.');
        }

        try {
            Unit::create([
                'unit_name' => $request->unit_name,
                'description' => $request->description,
                'is_active' => $request->has('is_active') ? 1 : 0
            ]);

            return redirect()->route('units.index')
                ->with('success', 'Army Unit created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating army unit: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $unit = Unit::first(); // Get the single unit
        $canAdd = !$unit; // Can only add if no unit exists
        $title = "Army Unit Management";
        $editUnit = Unit::findOrFail($id); // Unit to edit
        return view('units.index', compact('unit', 'canAdd', 'title', 'editUnit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unit_id' => 'required|exists:units,id',
            'unit_name' => 'required|string|max:255|unique:units,unit_name,' . $request->unit_id,
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors.');
        }

        try {
            $unit = Unit::findOrFail($request->unit_id);
            
            $unit->update([
                'unit_name' => $request->unit_name,
                'description' => $request->description,
                'is_active' => $request->has('is_active') ? 1 : 0
            ]);

            return redirect()->route('units.index')
                ->with('success', 'Army Unit updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating army unit: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $unit = Unit::findOrFail($id);
            $unitName = $unit->unit_name;
            $unit->delete();

            return redirect()->route('units.index')
                ->with('success', 'Army Unit "' . $unitName . '" deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting army unit: ' . $e->getMessage());
        }
    }
}
