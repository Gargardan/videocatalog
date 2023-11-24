<?php

namespace App\Application;

use App\Domain\Exceptions\ValidationException;
use App\Domain\Interface\IFilmFilterMapper;
use App\Domain\VOB\FilmRating;
use App\Domain\VOB\FilmTitle;
use App\Domain\VOB\FilmYear;
use App\Domain\VOB\FilteringCriteria;


class RequestFilterMapper implements IFilmFilterMapper
{
    private  ?FilmTitle $title = null;
    private  ?FilmYear $year = null;
    private  ?FilmRating $rating = null;
    public function __construct(string $dataInput)
    {
        if ($dataInput == '') {
            return;
        }

        $data = json_decode($dataInput, true);

        if (empty($data)) {
            throw new ValidationException('Malformed payload');
        }

        $year = $data['year']??false;
        $title = $data['title']??false;
        $rating = $date['rating']??false;

        if ($year) {
            $this->validateYear($year);
            $this->year = new FilmYear($year, FilteringCriteria::Equal);
        }

        if ($title) {
            $this->validateArray($title);
            list($filter, $search) = $title;
            $this->title = new FilmTitle($search, FilteringCriteria::from($filter));
        }

        if ($rating) {
            $this->validateArray($rating);
            list($filter, $search) = $rating;
            $this->rating = new FilmRating($search, FilteringCriteria::from($filter));
        }
    }

    /**
     * Validates a filter + search array.
     * @param $array
     * @return void
     * @throws ValidationException
     */
    private function validateArray($array) : void
    {
        if (!(is_array($array) && count($array) == 2)) {
            throw new ValidationException('title must be an array with a string and a filtering criteria');
        }

        if (FilteringCriteria::tryFrom($array[0]) === null) {
            throw new ValidationException('Unknown filtering criteria: ' . $array[0]);
        }
    }

    private function validateYear($year) : void
    {
        if ((int)$year != $year) {
            throw new ValidationException('year must be an integer');
        }
    }

    // Getters
    public function filmTitle(): ?FilmTitle
    {
        return $this->title;
    }

    public function filmYear(): ?FilmYear
    {
        return $this->year;
    }

    public function filmRating(): ?FilmRating
    {
        return $this->rating;
    }
}