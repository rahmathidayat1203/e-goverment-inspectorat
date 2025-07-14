<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // $this->call([
        //     CHATBOTKNOWLEDGEseeder::class,
        //     INSTANSIseeder::class,
        //     JENISSBTseeeder::class,
        //     MUTASIASNseeder::class,
        //     PENGAJUANSBTseeder::class,
        //     RIWAYATCHATseeder::class,
        //     VERIFIKASIseeder::class,
        // ]);
        $this->call([
            CreateAdminUserSeeder::class
        ]);   

         
    }
}
