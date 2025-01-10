<?php

declare(strict_types=1);

namespace App\DTO\User;

use App\DTO\DTOInterface;
use Illuminate\Database\Eloquent\Model;

class UserDTO implements DTOInterface
{
    public function __construct(
        public ?string $id,
        public string $email,

    ) {}

    public static function fromModel(Model $object): UserDTO
    {

        return new self(
            $object->id,
            $object->email,

        );

    }

    public static function toArray(array $data)
    {

        return [
            'id' => $data['id']?? null,
            'email' => $data['email'],
        ];
    }
}
