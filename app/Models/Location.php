<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;

    protected $table = 'locations'; // Nama tabel di database

    protected $fillable = [
        'name',
        'description',
    ];

    public function allocatedComputer()
    {
        return $this->hasMany(AllocatedComputer::class);
    }

    public function allocatedItem()
    {
        return $this->hasMany(AllocatedItem::class);
    }
}
