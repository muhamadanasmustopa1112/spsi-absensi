<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class EmployeeModel extends Model
{
    use HasFactory;

    protected $table = 'employee'; 

    protected $fillable = ['nik', 'name', 'email', 'position' , 'no_hp', 'address', 'qr'];

    public function absensi(): HasMany
    {
        return $this->hasMany(AbsensiModel::class, 'employee_id');
    }
}
