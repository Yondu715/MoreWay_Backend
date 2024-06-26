<?php

namespace App\Infrastructure\Http\Resources\Achievement;

use App\Application\DTO\Collection\CursorDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin CursorDto
 */
class AchievementCursorResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => AchievementResource::collection($this->data),
            'meta' => [
                'cursor' => $this->cursor
            ]
        ];
    }
}
