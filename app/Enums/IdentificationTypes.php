<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class IdentificationTypes extends Enum
{
    const NationalID =   0;
    const InternationalPassport =   1;
    const DriversLicense = 2;
}
