<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // SPPG 1 - Jakarta cluster
        $vendorUser1 = User::updateOrCreate(
            ['email' => 'vendor1@mbgood.test'],
            [
                'name' => 'SPPG Jakarta',
                'password' => Hash::make('vendor123'),
                'role' => 'vendor',
            ]
        );

        Vendor::updateOrCreate(
            ['user_id' => $vendorUser1->id],
            [
                'company_name' => 'SPPG Jakarta',
                'contact' => '081200000001',
            ]
        );

        // SPPG 2 - Bandung & Jawa Barat cluster
        $vendorUser2 = User::updateOrCreate(
            ['email' => 'vendor2@mbgood.test'],
            [
                'name' => 'SPPG Bandung',
                'password' => Hash::make('vendor123'),
                'role' => 'vendor',
            ]
        );

        Vendor::updateOrCreate(
            ['user_id' => $vendorUser2->id],
            [
                'company_name' => 'SPPG Bandung',
                'contact' => '081200000002',
            ]
        );

        // SPPG 3 - Surabaya & Makassar cluster
        $vendorUser3 = User::updateOrCreate(
            ['email' => 'vendor3@mbgood.test'],
            [
                'name' => 'SPPG Surabaya',
                'password' => Hash::make('vendor123'),
                'role' => 'vendor',
            ]
        );

        Vendor::updateOrCreate(
            ['user_id' => $vendorUser3->id],
            [
                'company_name' => 'SPPG Surabaya',
                'contact' => '081200000003',
            ]
        );
    }
}
