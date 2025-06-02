<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'item_type',
        'item_name',
        'from_location_id',
        'to_location_id',
        'quantity',
        'note',
        'transferred_at',
    ];

    protected $dates = ['transferred_at'];

    public function fromLocation()
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    public function toLocation()
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }

    public function item()
    {
        return $this->morphTo();
    }
}
