<?php

namespace Database\Seeders;

use App\Models\AllocatedComputer;
use App\Models\AllocatedItem;
use App\Models\Pc;
use App\Models\Ram;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Desk;
use App\Models\Item;
use App\Models\User;
use App\Models\Asset;
use App\Models\VgaCard;
use App\Models\Location;
use App\Models\DiskDrive;
use App\Models\OtherItem;
use App\Models\Processor;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'admin'
        ]);

        Location::create([
            "name" => "TULT-06-01"
        ]);

        Location::create([
            "name" => "TULT-06-02"
        ]);

        Item::create([
            "item_type" => "computer",
            "item_name" => "HP",
            "description" => "AMD 285 G3",
            "stock" => 100
        ]);

        Item::create([
            "item_type" => "disk_drive",
            "item_name" => "SSD",
            "description" => "512 GB - WDC",
            "stock" => 100
        ]);

        Item::create([
            "item_type" => "processor",
            "item_name" => "AMD",
            "description" => "2400G - 3.6 GHz",
            "stock" => 100
        ]);

        Item::create([
            "item_type" => "vga_card",
            "item_name" => "AMD Radeon Vega 11",
            "description" => "1 GB",
            "stock" => 100
        ]);

        Item::create([
            "item_type" => "ram",
            "item_name" => "DDR4",
            "description" => "8 GB",
            "stock" => 100
        ]);

        Item::create([
            "item_type" => "monitor",
            "item_name" => "Dell E2016H",
            "description" => "1920x1080 - 18.5 inch",
            "stock" => 100
        ]);

        Item::create([
            "item_type" => "other_item",
            "item_name" => "Whiteboard",
            "stock" => 100
        ]);
    }
}
