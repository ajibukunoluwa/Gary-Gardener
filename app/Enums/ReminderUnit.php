<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use InvalidArgumentException;

/**
 * @method static static Year()
 * @method static static Month()
 * @method static static Week()
 * @method static static Day()
 * @method static static Hour()
 * @method static static Minute()
 */
final class ReminderUnit extends Enum
{
    // const Year = 1;
    const Month = 2;
    const Week = 3;
    const Day = 4;
    // const Hour = 5;
    // const Minute = 6;

    public static function CarbonMethod(int $type)
    {
        switch ($type) {
            // case self::Year :
            //     return 'subYear';
            case self::Month :
                return 'subMonth';
            case self::Week :
                return 'subWeek';
            case self::Day :
                return 'subDay';
            // case self::Hour :
            //     return 'subHour';
            // case self::Minute :
            //     return 'subMinute';
        }

        Throw new InvalidArgumentException("Invalid type supplied `{$type}`");
    }
}
