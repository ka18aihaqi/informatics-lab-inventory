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
        Schema::create('transfer_logs', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('item_id');
            $table->string('item_type'); // 'App\Models\AllocatedItem' atau 'App\Models\AllocatedComputer'      
            
            $table->string('item_name')->nullable();
        
            $table->foreignId('from_location_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('to_location_id')->constrained('locations')->onDelete('cascade');
        
            $table->integer('quantity')->default(1);
            $table->text('note')->nullable();
            $table->timestamp('transferred_at')->useCurrent();
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_logs');
    }
};
