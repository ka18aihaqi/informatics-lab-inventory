<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AllocatedItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'other_item_id',
        'description',
        'stock',
    ];
    
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function otherItem()
    {
        return $this->belongsTo(Item::class, 'other_item_id');
    }    

    public function transferLogs()
    {
        return $this->morphMany(TransferLog::class, 'item');
    }
}
