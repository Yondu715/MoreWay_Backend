<?php

namespace App\Application\Services\Place;

use App\Application\Contracts\In\Services\Place\IPlaceService;
use App\Application\Contracts\Out\Repositories\Place\IPlaceRepository;
use App\Application\DTO\Collection\CursorDto;
use App\Application\DTO\In\Place\GetPlaceDto;
use App\Application\DTO\In\Place\GetPlacesDto;
use App\Application\DTO\Out\Place\PlaceDto;
use App\Application\Exceptions\Place\PlaceNotFound;
use App\Domain\Contracts\In\DomainManagers\IDistanceManager;
use App\Utils\Mappers\Out\Place\PlaceCursorDtoMapper;
use App\Utils\Mappers\Out\Place\PlaceDtoMapper;
use App\Domain\Factories\Distance\DistanceManagerFactory;

class PlaceService implements IPlaceService
{
    private readonly IDistanceManager $distanceManager;

    public function __construct(
        private readonly IPlaceRepository $placeRepository,
    ) {
        $this->distanceManager = DistanceManagerFactory::createInstance();
    }

    /**
     * @param GetPlaceDto $getPlaceDto
     * @return PlaceDto
     * @throws PlaceNotFound
     */
    public function getPlaceById(GetPlaceDto $getPlaceDto): PlaceDto
    {
        $place = $this->placeRepository->getPlaceById($getPlaceDto);
        return PlaceDtoMapper::fromPlaceModel($place, $this->distanceManager
            ->calculate(
            $place->lat,
            $place->lon,
            $getPlaceDto->lat,
            $getPlaceDto->lon
        ));
    }

    /**
     * @param GetPlacesDto $getPlacesDto
     * @return CursorDto
     */
    public function getPlaces(GetPlacesDto $getPlacesDto): CursorDto
    {
        if ($getPlacesDto->filter['distance']) {
            $getPlacesDto->filter['distance']['calculate'] = function ($lat, $lon) use ($getPlacesDto) {
                return $this->distanceManager->calculate($lat, $lon, $getPlacesDto->lat, $getPlacesDto->lon);
            };
        }
        if ($getPlacesDto->filter['sort']) {
            if ($getPlacesDto->filter['sort']['sort'] === 'distance')
                $getPlacesDto->filter['sort']['calculate'] = function ($lat, $lon) use ($getPlacesDto) {
                    return $this->distanceManager->calculate($lat, $lon, $getPlacesDto->lat, $getPlacesDto->lon);
                };
        }

        $places = $this->placeRepository->getPlaces($getPlacesDto);

        return PlaceCursorDtoMapper::fromPaginator(collect($places->items())->map(function ($place) use ($getPlacesDto){
            return PlaceDtoMapper::fromPlaceModel($place, $this->distanceManager
                ->calculate($place->lat, $place->lon, $getPlacesDto->lat, $getPlacesDto->lon
                ));
        }), $places->nextCursor() ? $places->nextCursor()->encode() : null);
    }
}
