<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AllocatedComputer extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'location_id', 
        'desk_number', 
        'computer_id', 
        'disk_drive_1_id', 
        'disk_drive_2_id', 
        'processor_id',
        'vga_card_id', 
        'ram_id', 
        'monitor_id', 
        'year_approx',
        'ups_status',
        'qr_code'
    ];

    public function location() 
    { 
        return $this->belongsTo(Location::class); 
    }

    public function computer()
    {
        return $this->belongsTo(Item::class, 'computer_id')->where('item_type', 'computer');
    }

    public function diskDrive1()
    {
        return $this->belongsTo(Item::class, 'disk_drive_1_id')->where('item_type', 'disk_drive');
    }
    
    public function diskDrive2()
    {
        return $this->belongsTo(Item::class, 'disk_drive_2_id')->where('item_type', 'disk_drive');
    }
    
    public function processor()
    {
        return $this->belongsTo(Item::class, 'processor_id')->where('item_type', 'processor');
    }
    
    public function vgaCard()
    {
        return $this->belongsTo(Item::class, 'vga_card_id')->where('item_type', 'vga_card');
    }
    
    public function ram()
    {
        return $this->belongsTo(Item::class, 'ram_id')->where('item_type', 'ram');
    }    

    public function monitor()
    {
        return $this->belongsTo(Item::class, 'monitor_id')->where('item_type', 'monitor');
    }    

    public function transferLogs()
    {
        return $this->morphMany(TransferLog::class, 'item');
    }
}
