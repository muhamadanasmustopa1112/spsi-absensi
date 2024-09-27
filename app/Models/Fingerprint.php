<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fingerprint extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'fingerprint_data'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(EmployeeModel::class, 'employee_id', 'id');
    }
}
