<?php 

declare(strict_types=1);

namespace App\DTO;

use Illuminate\Database\Eloquent\Model;

interface DTOInterface{
    static function fromModel(Model $object);
    static function toArray(array $data);
}