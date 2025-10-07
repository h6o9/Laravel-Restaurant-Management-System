<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('printers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Printer name like "Kitchen_Printer"
            $table->enum('type', ['windows', 'network', 'serial', 'linux_usb']); // Type of printer
            $table->string('connector_value'); // e.g., "Kitchen_Printer", "192.168.1.50", "/dev/usb/lp0"
            $table->string('section')->nullable(); // NEW: Section name like 'Kitchen', 'Bar', 'Counter'
            $table->boolean('is_active')->default(true); // Enable/Disable printer
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('printers');
    }
};
