<?php

namespace App\DTO\Out\Auth;

use App\Models\Friend;
use App\Models\User;

class UserDto
{
    public readonly int $id;
    public readonly string $name;
    public readonly string $avatar;
    public readonly string $email;

    public function __construct(
        int $id,
        string $name,
        string $avatar,
        string $email
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->avatar = $avatar;
        $this->email = $email;
    }

    /**
     * @param User $user
     * @return self
     */
    public static function fromUserModel(User $user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            avatar: $user->avatar,
            email: $user->email
        );
    }

    public static function fromFriendModel(Friend $friend) {
        return new self(
            id: $friend->friend->id,
            name: $friend->friend->name,
            avatar: $friend->friend->avatar,
            email: $friend->friend->email
        );
    }
}
