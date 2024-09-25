<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Stevebauman\Location\Facades\Location;
use App\Models\EmployeeModel;
use App\Models\AbsensiModel;
use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // Mengambil semua data dari tabel employees
        $items = EmployeeModel::all();

        // Mengirim data ke view
        return view('dashboard', compact('items'));
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
            'nik' => 32112323,
            'name' => $request->name,
            'email' => $request->email,
            'position' => 'STaff',
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
        $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}&addressdetails=1";

        try {
            $response = Http::get($url);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['address'])) {
                    return $data['display_name'];
                } else {
                    return 'Address not found';
                }
            } else {
                return 'Failed to connect to the geocoding service';
            }
        } catch (\Exception $e) {
            \Log::error('Geocoding error: ' . $e->getMessage());
            return 'Error during geocoding';
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

      
            $address = $this->reverseGeocode($latitude, $longitude);
            // $position = Location::get('125.163.109.138');

            return response()->json([
                'address' => $request->input('latitude'),
            ], 200);

        }catch (\Exception $e) {
            \Log::error($e);

        }
    }

    public function absensi($id)
    {
        $items = EmployeeModel::findOrFail($id);
        return view('absensi', compact('items'));
    }

    public function insertAbsensi(Request $request)
    {
        try {

            $latitude = $request->lat; 
            $longitude = $request->lng;

            $currentTime = now();
            $eightAM = now()->setTime(8, 0, 0);

            $status;
            $timeFormat = now()->format('H:i:s');
            $dateFormat = now()->format('Y-m-d'); 
            $address = $this->reverseGeocode($latitude, $longitude);

            if ($currentTime->greaterThan($eightAM)) {

                $status = "Terlambat";

        
                // $data = AbesensiModel::create([
                //     'employee_id' => $request->employee_id,
                //     'jam' => $timeFormat,
                //     'tanggal' => $dateFormat,
                //     'address' => $address,
                //     'status' => $status,
                //     'alasan' => $request->input('reason'),
                //     'created_at' => now(),
                //     'updated_at' => now(),
                // ]);


                return $status;
                
            } else {
                $status = "Tepat Waktu";
            }

            // return $request->employee_id;

        }catch (\Exception $e) {
            \Log::error($e);

        }

    }

    public function lateAbsensi(Request $request)
    {
        try {
            $latitude = $request->lat; 
            $longitude = $request->lng;
        
            $currentTime = now();
            $eightAM = now()->setTime(8, 0, 0);
        
            $status = "Terlambat";
            $timeFormat = now()->format('H:i:s');
            $dateFormat = now()->format('Y-m-d'); 
            $address = $this->reverseGeocode($latitude, $longitude);
        
            $employeeId = $request->employee_id;
        
            $existingRecord = DB::table('absensi')
                                ->where('employee_id', $employeeId)
                                ->where('tanggal', $dateFormat)
                                ->exists();
        
            if ($existingRecord) {
                return 'absen-2x';
            }

            $data = AbsensiModel::create([
                'employee_id' => $request->employee_id,
                'jam' => $timeFormat,
                'tanggal' => $dateFormat,
                'address' => $address,
                'status' => $status,
                'alasan' => $request->alasan
            ]);
        
            if ($data) {
                return 'success';
            } else {
                return 'Failed to save data';
            }
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
    
}
