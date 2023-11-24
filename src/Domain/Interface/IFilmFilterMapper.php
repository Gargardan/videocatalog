<?php

namespace App\Domain\Interface;

use App\Domain\VOB\FilmRating;
use App\Domain\VOB\FilmTitle;
use App\Domain\VOB\FilmYear;

interface IFilmFilterMapper
{
    public function filmTitle() : ?FilmTitle;

    public function filmYear() : ?FilmYear;
    public function filmRating() : ?FilmRating;
}