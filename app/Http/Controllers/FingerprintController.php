<?php

namespace App\Http\Controllers;
use App\Models\Fingerprint;
use Illuminate\Support\Facades\Log;

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
        try {

            $request->validate([
                'employee_id' => 'required|integer',
                'fingerprint_data.rawId' => 'required|string',
                'fingerprint_data.clientDataJSON' => 'required|string',
                'fingerprint_data.attestationObject' => 'required|string'
            ]);

             // Decode the base64-encoded fingerprint data
            $rawId = base64_decode($request->input('fingerprint_data.rawId'));
            $clientDataJSON = base64_decode($request->input('fingerprint_data.clientDataJSON'));
            $attestationObject = base64_decode($request->input('fingerprint_data.attestationObject'));


            // Retrieve stored fingerprint data
            $storedFingerprint = Fingerprint::where('employee_id', $request->input('employee_id'))->first();
                    
            if (!$storedFingerprint) {

                // Save fingerprint data to the database
                Fingerprint::updateOrCreate(
                    ['employee_id' => $request->input('employee_id')],
                    [
                        'raw_id' => $rawId,
                        'client_data_json' => $clientDataJSON,
                        'attestation_object' => $attestationObject
                    ]
                );

                return response()->json(['status' => 'success', 'message' => 'Fingerprint Data Saved']);
            }


            dd([
                'received_rawId' => $rawId,
                'stored_rawId' => $storedFingerprint->raw_id,
                'received_clientDataJSON' => $clientDataJSON,
                'stored_clientDataJSON' => $storedFingerprint->client_data_json,
                'received_attestationObject' => $attestationObject,
                'stored_attestationObject' => $storedFingerprint->attestation_object
            ]);

            // Compare data
            if (
                hash_equals($rawId, $storedFingerprint->raw_id) &&
                hash_equals($clientDataJSON, $storedFingerprint->client_data_json) &&
                hash_equals($attestationObject, $storedFingerprint->attestation_object)
            ) {
                return response()->json(['status' => 'success', 'message' => 'Fingerprint match']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Fingerprint mismatch']);
            }


        } catch (\Exception $exception) {
            Log::error('Error processing fingerprint:', ['error' => $exception->getMessage()]);
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()]);

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

    private function compareFingerprint($storedData, $newData)
    {
        // Misalnya, kita bisa menggunakan JSON string comparison
        return $storedData['rawId'] === $newData['rawId'];

    }
}
