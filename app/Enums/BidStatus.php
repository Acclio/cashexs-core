<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class BidStatus extends Enum
{
    const Open =   0;
    const Locked =   1;
    const Completed =   2;
    const Cancelled =   3;
}
