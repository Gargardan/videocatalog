<?php

namespace App\Domain\VOB;

class FilmInfo
{
    public readonly FilmTitle $title;
    public readonly FilmYear $year;
    public readonly FilmRating $rating;

    /**
     * @throws AppException
     */
    public function __construct(string $title, int $year, int $rating)
    {
        $this->title = new FilmTitle($title);
        $this->year = new FilmYear($year);
        $this->rating = new FilmRating($rating);
    }
}