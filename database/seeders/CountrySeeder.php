<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $countries = Storage::get('countries.csv');
            $countries = explode("\n", $countries);

            foreach ($countries as $country) {
                $value = explode(",", $country);
                $active = false;

                if (trim($value[1]) == 'Nigeria' || trim($value[1]) == 'Canada') {
                    $active = true;
                }

                DB::beginTransaction();
                DB::table('countries')->insert(
                    [
                        'name' => trim($value[1]),
                        'code' => trim($value[2]),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'active' => $active
                    ]
                );
                DB::commit();
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
        }
    }
}
