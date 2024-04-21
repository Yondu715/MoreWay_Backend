<?php

namespace App\Infrastructure\Database\Repositories\Place;

use App\Application\Contracts\Out\Repositories\Place\IPlaceRepository;
use App\Application\DTO\In\Place\GetPlaceDto;
use App\Application\DTO\In\Place\GetPlacesDto;
use App\Application\Exceptions\Place\PlaceNotFound;
use App\Infrastructure\Database\Models\Filters\Place\PlaceFilterFactory;
use App\Infrastructure\Database\Models\Place;
use App\Infrastructure\Database\Repositories\BaseRepository\BaseRepository;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Throwable;

class PlaceRepository extends BaseRepository implements IPlaceRepository
{
    public function __construct(
        private readonly PlaceFilterFactory $placeFilterFactory,
        Place $place
    ) {
        parent::__construct($place);
    }

    /**
     * @param GetPlaceDto $getPlaceDto
     * @return Place
     * @throws PlaceNotFound
     */
    public function getPlaceById(GetPlaceDto $getPlaceDto): Place
    {
        try {
            /** @var Place */
            return $this
                ->model
                ->query()
                ->where('id', $getPlaceDto->id)
                ->first();
        } catch (Throwable $th) {
            throw new PlaceNotFound();
        }
    }

    /**
     * @param GetPlacesDto $getPlacesDto
     * @return CursorPaginator
     */
    public function getPlaces(GetPlacesDto $getPlacesDto): CursorPaginator
    {
        return $this->model
            ->filter($this->placeFilterFactory->create($getPlacesDto->filter))
            ->cursorPaginate(perPage: $getPlacesDto->limit ?? 2, cursor: $getPlacesDto->cursor);
    }
}
