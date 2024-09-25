<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiModel extends Model
{
    use HasFactory;

    protected $table = 'absensi'; 

    protected $fillable = ['jam', 'tanggal' , 'address', 'status', 'alasan', 'employee_id'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(EmployeeModel::class, 'employee_id', 'id');
    }
}
