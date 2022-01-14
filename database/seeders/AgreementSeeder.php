<?php

namespace Database\Seeders;

use App\Http\Libraries\Encrypter\Encrypter;
use App\Models\Agreement;
use Illuminate\Database\Seeder;

class AgreementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Encrypter $encrypter)
    {

        Agreement::insert([
            [
                'filename' => $encrypter->encrypt($encrypter->generateToken(64, Agreement::class, 'filename', '.pdf')),
                'description' => $encrypter->encrypt('Regulamin', 100),
                'signature' => $encrypter->encrypt('REGULAMIN_SERWISU', 30),
                'version' => 1,
                'agreement_type_id' => 5,
                'effective_date' => now(),
                'is_required' => 1,
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
