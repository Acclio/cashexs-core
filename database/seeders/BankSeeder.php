<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $banks = Storage::get('banks.csv');
            $banks = explode("\n", $banks);

            foreach ($banks as $bank) {
                $value = explode(",", $bank);
                DB::beginTransaction();
                DB::table('banks')->insert(
                    [
                        'name' => trim($value[0]),
                        'code' => trim($value[1]),
                        'country_id' => trim($value[2]),
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
