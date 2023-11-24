<?php

namespace App\Application;

use App\Domain\Interface\IFilmFilterMapper;
use App\Domain\Interface\IFilmRepository;
use App\Domain\VOB\FilmInfoList;

class ListFilmsService
{
    private IFilmRepository $filmRepository;

    public function __construct(IFilmRepository $filmRepository)  {

        $this->filmRepository = $filmRepository;

    }

    public function __invoke(IFilmFilterMapper $filters) : FilmInfoList
    {

        return $this->filmRepository->search($filters->filmTitle(), $filters->filmYear(), $filters->filmRating());

    }

}