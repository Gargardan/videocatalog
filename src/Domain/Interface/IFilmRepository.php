<?php

namespace App\Domain\Interface;

use App\Domain\VOB\FilmInfoList;
use App\Domain\VOB\FilmRating;
use App\Domain\VOB\FilmTitle;
use App\Domain\VOB\FilmYear;

interface IFilmRepository
{
    /**
     * @param FilmTitle|null $filmTitle
     * @param FilmYear|null $filmYear
     * @param FilmRating|null $filmRating
     * @return FilmInfoList
     */
    public function search(?FilmTitle $filmTitle, ?FilmYear $filmYear, ?FilmRating $filmRating) : FilmInfoList;
}