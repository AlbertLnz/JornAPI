<?php 

namespace App\Enums;

enum WorkTypeEnum :string{
case HOLIDAY = 'is_holiday';
case OVERTIME = 'is_overtime';
case NORMAL = 'is_normal';

public static function fromValue(?string $value): WorkTypeEnum{
    return match($value){
        'is_holiday' => self::HOLIDAY,
        'is_overtime' => self::OVERTIME,
        default => self::NORMAL
    };
}

}