<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $states = Storage::get('states.csv');
            $states = explode("\n", $states);

            foreach ($states as $state) {
                $value = explode(",", $state);
                DB::beginTransaction();
                DB::table('states')->insert(
                    [
                        'country_id' => trim($value[0]),
                        'name' => trim($value[2]),
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
