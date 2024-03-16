<?php

namespace App\DTO\In\User;

use App\Http\Requests\User\ChangeUserAvatarRequest;
use App\Lib\HashId\HashManager;
use Illuminate\Http\UploadedFile;

class ChangeUserAvatarDto
{
    public readonly int $userId;
    public readonly UploadedFile $avatar;

    public function __construct(
        int $userId,
        UploadedFile $avatar
    ) {
        $this->userId = $userId;
        $this->avatar = $avatar;
    }

    public static function fromRequest(ChangeUserAvatarRequest $changeUserAvatarRequest): self
    {
        return new self(
            userId: HashManager::decrypt($changeUserAvatarRequest->route('userId')),
            avatar: $changeUserAvatarRequest->file('avatar')
        );
    }
}
