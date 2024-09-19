<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Stevebauman\Location\Facades\Location;
use App\Models\EmployeeModel;

use Illuminate\Http\Request;

class EmployeeController extends Controller
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
        // Validate and create the user
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employee',
            'no_hp' => 'required|string|max:255',
            'address' => 'required|string'
        ]);
        
        // Create the user
        $user = EmployeeModel::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'address' => $request->address
        ]);
        
        // Generate a link for attendance with the user's ID
        $attendanceLink = route('absensi');
        
        // Generate QR code containing the attendance link
        $qrCode = QrCode::size(200)->generate($attendanceLink);

        $qrCodePath = 'public/qrcodes/user-' . $user->name . '.png';
        $qrCodeContent = QrCode::format('png')->size(200)->generate($attendanceLink);


        // Store QR code
        Storage::put($qrCodePath, $qrCodeContent);

         // Optionally, save the QR code path to the user record or return it
        $user->qr = $qrCodePath;
        $user->save();

        // Return user and the QR code to the view
        return back()->with('success',$user->id );
      
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

    public function reverseGeocode($latitude, $longitude)
    {
        // Nominatim API URL
        $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}&addressdetails=1";

        // Make the API request
        $response = Http::get($url);

        // Parse the response JSON
        $data = $response->json();

        // Check if address is available in the response
        if (isset($data['address'])) {
            // Return the display name (full address) or specific address components
            return $data['display_name'];
        } else {
            return 'Address not found';
        }
    }

    public function showLocation(Request $request)
    {

        try {

            // Example coordinates (you can get them from a request or another source)
            $latitude = -6.9479393; 
            $longitude = 107.6274989;
            // $ipAddress = $request->ip();
            \Log::info($request->all());

            // dd($request->all());
            // // Get the address using the reverse geocode method
            $address = $this->reverseGeocode($latitude, $longitude);
            // $position = Location::get('125.163.109.138');

            return response()->json([
                'address' => $request->input('latitude'),
            ], 200);

        }catch (\Exception $e) {
            \Log::error($e);

        }
    }

    public function absensi()
    {
        return view('absensi');
    }
    
}
