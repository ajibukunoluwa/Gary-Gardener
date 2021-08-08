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
    const Month = 'month';
    const Week = 'week';
    const Day = 'day';

    public static function CarbonMethod(string $type)
    {
        switch ($type) {
            case self::Month :
                return 'subMonth';
            case self::Week :
                return 'subWeek';
            case self::Day :
                return 'subDay';
        }

        Throw new InvalidArgumentException("Invalid type supplied `{$type}`");
    }
}
