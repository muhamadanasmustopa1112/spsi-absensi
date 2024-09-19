<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Http\Resources\EmployeeResource;
use App\Models\EmployeeModel;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      // Mengambil semua data employee
      $employees = EmployeeModel::all();

      // Mengembalikan data dalam bentuk resource collection, termasuk metadata tambahan
      return EmployeeResource::collection($employees)       
            ->additional([
                'status' => 'success',
                'code' => 200,
            ]);;    
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
         $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employee',
            'no_hp' => 'required|string|max:255',
            'address' => 'required|string'
        ]);
        
        $attendanceLink = route('absensi');
        
        $qrCodeName = 'qrcodes/user-' . time() . '-' . $request->name . '.png';
        $qrCodePath = 'public/' . $qrCodeName;
        QrCode::format('png')->size(200)->generate($attendanceLink, storage_path('app/' . $qrCodePath));

        $user = EmployeeModel::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'address' => $request->address,
            'qr' => $qrCodeName
        ]);

        return new EmployeeResource($user);
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
