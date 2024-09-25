<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AbsensiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'jam' => $this->jam,
            'tanggal' => $this->tanggal,
            'address' => $this->address,
            'status' => $this->status,
            'alasan' => $this->alasan,
            'employee' => new EmployeeResource($this->whenLoaded('employee')),
        ]; 

        
    }

    public function with($request)
    {
        return [
            'status' => 'success',
            'code' => 200,
        ];
    }
}
