<?php

namespace App\Infrastructure\Http\Resources\Achievement\UserAchievement;

use Illuminate\Http\Request;
use App\Application\DTO\Collection\CursorDto;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin CursorDto
 */
class UserAchievementCursorResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => UserAchievementResource::collection($this->data),
            'meta' => [
                'cursor' => $this->cursor
            ]
        ];
    }
}
