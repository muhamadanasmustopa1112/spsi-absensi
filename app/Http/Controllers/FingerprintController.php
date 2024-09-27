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

        $storedFingerprint = Fingerprint::where('employee_id', $employee_id)->first();

        // Jika tidak ada fingerprint yang tersimpan, simpan data fingerprint
        if (!$storedFingerprint) {
            
            // Simpan fingerprint baru
            Fingerprint::create([
                'employee_id' => $employee_id,
                'fingerprint_data' => json_encode($fingerprintData)
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Fingerprint data saved successfully.'
            ], 201);
        }

        // Jika data fingerprint sudah ada, lakukan verifikasi
        $storedFingerprintData = json_decode($storedFingerprint->fingerprint_data, true);

        if ($this->compareFingerprint($storedFingerprintData, $fingerprintData)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Fingerprint verification successful.'
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Fingerprint verification failed.'
            ], 401);
        }
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

    private function compareFingerprint($storedData, $receivedData)
    {
        // Misalnya, kita bisa menggunakan JSON string comparison
        return $storedData['rawId'] === $receivedData['rawId']
            && $storedData['clientDataJSON'] === $receivedData['clientDataJSON']
            && $storedData['attestationObject'] === $receivedData['attestationObject'];
    }
}
