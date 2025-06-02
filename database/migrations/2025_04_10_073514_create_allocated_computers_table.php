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
        Schema::create('allocated_computers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
        
            $table->integer('desk_number')->nullable();
            $table->unique(['location_id', 'desk_number']);

            $table->foreignId('computer_id')->nullable()->constrained('items');
            $table->foreignId('disk_drive_1_id')->nullable()->constrained('items');
            $table->foreignId('disk_drive_2_id')->nullable()->constrained('items');
            $table->foreignId('processor_id')->nullable()->constrained('items');
            $table->foreignId('vga_card_id')->nullable()->constrained('items');
            $table->foreignId('ram_id')->nullable()->constrained('items');
            $table->foreignId('monitor_id')->nullable()->constrained('items');
        
            $table->year('year_approx')->nullable(); // Tahun (approx)
            $table->enum('ups_status', ['Active', 'Inactive'])->nullable()->default(null);

            $table->string('qr_code')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allocated_computers');
    }
};
