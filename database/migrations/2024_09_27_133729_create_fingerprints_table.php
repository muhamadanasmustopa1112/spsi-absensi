<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fingerprints', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id'); // Foreign key for employee
            $table->binary('raw_id'); // To store rawId binary data
            $table->binary('client_data_json'); // To store clientDataJSON binary data
            $table->binary('attestation_object'); // To store attestationObject binary data
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fingerprints');
    }
};
