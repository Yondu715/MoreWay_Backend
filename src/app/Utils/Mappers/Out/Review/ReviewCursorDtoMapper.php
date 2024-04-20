<?php

namespace App\Utils\Mappers\Out\Review;

use App\Application\DTO\Collection\CursorDto;
use App\Infrastructure\Database\Models\PlaceReview;
use App\Infrastructure\Database\Models\RouteReview;
use App\Utils\Mappers\Collection\CursorDtoMapper;
use Illuminate\Contracts\Pagination\CursorPaginator;

class ReviewCursorDtoMapper
{
    /**
     * @param CursorPaginator $paginator
     * @return CursorDto
     */
    public static function fromPaginator(CursorPaginator $paginator): CursorDto
    {
        return CursorDtoMapper::fromPaginatorAndMapper($paginator, function (PlaceReview|RouteReview $review) {
            return ReviewDtoMapper::fromReviewModel($review);
        });
    }
}
