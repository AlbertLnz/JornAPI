<?php

declare(strict_types=1);

namespace App\DTO;

use Illuminate\Database\Eloquent\Model;

interface DTOInterface
{
    public static function fromModel(Model $object);

    public static function toArray(array $data);
}
