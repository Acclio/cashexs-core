<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $cities = Storage::get('cities.csv');
            $cities = explode("\n", $cities);

            foreach ($cities as $city) {
                $value = explode(",", $city);
                DB::beginTransaction();
                DB::table('cities')->insert(
                    [
                        'state_id' => trim($value[0]),
                        'name' => trim($value[3]),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
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
