<?php

namespace App\Services\Place\Review\Interfaces;

use App\DTO\In\Place\Review\CreateReviewDto;
use App\DTO\In\Place\Review\GetReviewsDto;
use App\Models\PlaceReview;
use Illuminate\Contracts\Pagination\CursorPaginator;

interface IReviewService
{
    /**
     * @param CreateReviewDto $createReviewDto
     * @return PlaceReview
     */
    public function createReviews(CreateReviewDto $createReviewDto): PlaceReview;

    /**
     * @param GetReviewsDto $getReviewsDto
     * @return CursorPaginator
     */
    public function getReviews(GetReviewsDto $getReviewsDto): CursorPaginator;
}
