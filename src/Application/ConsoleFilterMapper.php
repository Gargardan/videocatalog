<?php

namespace App\Application;

use App\Domain\Exceptions\ValidationException;
use App\Domain\Interface\IFilmFilterMapper;
use App\Domain\VOB\FilmRating;
use App\Domain\VOB\FilmTitle;
use App\Domain\VOB\FilmYear;
use App\Domain\VOB\FilteringCriteria;


class ConsoleFilterMapper implements IFilmFilterMapper
{
    private  ?FilmTitle $title = null;
    private  ?FilmYear $year = null;
    private  ?FilmRating $rating = null;
    public function __construct(?array $data)
    {
        if ($data == null) {
            return;
        }

        $year = $data['year'] ?? false;
        $title = $data['title'] ?? false;
        $rating = $data['rating'] ?? false;

        if ($year) {
            $this->validateYear($year);
            $this->year = new FilmYear($year, FilteringCriteria::Equal);
        }

        if ($title) {
            list($filter, $search) = $this->explode($title);
            $this->validateFilter($filter);

            $this->title = new FilmTitle($search, FilteringCriteria::from($filter));
        }

        if ($rating) {
            list($filter, $search) = $this->explode($rating);
            $this->validateFilter($filter);

            $this->rating = new FilmRating($search, FilteringCriteria::from($filter));
        }
    }

    /**
     * It verifies the users input
     * @param $data
     * @return array
     */
    private function explode($data) : array
    {
        if (is_string($data)) {
            $params = explode(':', $data);

            if (count($params) == 2) {
                return $params;
            }
        }

        throw new ValidationException('Invalid argument');
    }

    private function validateFilter($filter) : void
    {
        if (FilteringCriteria::tryFrom($filter) === null) {
            throw new ValidationException('Unknown filtering criteria: ' . $filter);
        }
    }

    private function validateYear($year) : void
    {
        // Es una sutileza del lenguaje, pero ni is_numeric(), ni is_int() sirven para hacer esta comprobación
        if ($year != (int)$year) {
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