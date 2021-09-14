<?php

use App\Enums\IdentificationTypes;

return [

    IdentificationTypes::class => [
        IdentificationTypes::NationalID => 'National ID',
        IdentificationTypes::InternationalPassport => 'International Passport',
        IdentificationTypes::DriversLicense => 'Drivers License',
    ],

];
