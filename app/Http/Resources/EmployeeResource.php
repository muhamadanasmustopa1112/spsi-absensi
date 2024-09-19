<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'no_hp' => $this->no_hp,
            'address' => $this->address,
            'qr_code_url' => $this->qr ? asset('storage/' . $this->qr) : null,
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
