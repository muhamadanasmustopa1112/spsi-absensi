<?php

namespace App\Http\Controllers;
use App\Models\Fingerprint;

use Illuminate\Http\Request;

class FingerprintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $fingerprintData = $request->input('fingerprint_data');
        $employee_id = $request->input('employee_id');

        // Simpan data fingerprint ke database terkait user
        Fingerprint::create([
            'employee_id' => $employee_id,
            'fingerprint_data' => json_encode($fingerprintData), 
        ]);

        return response()->json(['status' => 'success', 'message' => 'Fingerprint saved!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
