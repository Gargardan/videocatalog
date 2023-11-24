<?php

namespace App\Infrastructure\Output;

use App\Domain\Interface\IFilmRepository;
use App\Domain\VOB\FilmInfo;
use App\Domain\VOB\FilmInfoList;
use App\Domain\VOB\FilmRating;
use App\Domain\VOB\FilmTitle;
use App\Domain\VOB\FilmYear;
use App\Domain\VOB\FilteringCriteria;

class FilmFakeRepository implements IFilmRepository
{

    /**
     * @inheritDoc
     */
    public function search(?FilmTitle $filmTitle, ?FilmYear $filmYear, ?FilmRating $filmRating): FilmInfoList
    {
        // Load data and apply filters
        $resultSet = array_filter(
            $this->fakeData(),
            function ($item) use($filmTitle, $filmYear, $filmRating) {
                $testTitle = $filmTitle == null || $this->applyFilter($filmTitle->value, $item['title'], $filmTitle->filter());
                $testYear = $filmYear == null || $this->applyFilter($filmYear->value, $item['year'], $filmYear->filter());
                $testRating = $filmRating == null || $this->applyFilter($filmRating->value, $item['rating'], $filmRating->filter());

                return $testTitle && $testYear && $testRating;
            }
        );

        $filmInfoList = new FilmInfoList();

        foreach ($resultSet as $result) {
            try {
                $filmInfoList->append(new FilmInfo(
                    $result['title'],
                    $result['year'],
                    $result['rating']
                ));
            } catch(\Exception) {
                // TODO: We have wrong data and this should be logged somewhere
            }
        }

        return $filmInfoList;
    }

    private function applyFilter(mixed $search, mixed $value, ?FilteringCriteria $criteria) : bool
    {
        return match ($criteria) {
            FilteringCriteria::Equal =>  ($value == $search),
            FilteringCriteria::StartsWith => str_starts_with(strtolower($value), strtolower($search)),
            FilteringCriteria::EndsWith =>  str_ends_with(strtolower($value), strtolower($search)),
            FilteringCriteria::LesserEqual => ($value <= $search),
            FilteringCriteria::GreaterEqual => ($value >= $search),
            FilteringCriteria::Contains =>  str_contains(strtolower($value), strtolower($search)),
            null => true
        };
    }
    /**
     * This function translates filtering criteria to a callable that performs the actual comparison
     * Returned function may be used as a callback in array_filter.
     *
     * @param mixed $searchValue The value expected in the filter
     * @param FilteringCriteria $criteria
     * @return array
     */
    private function filter(mixed $searchValue, FilteringCriteria $criteria) : callable
    {
        return match ($criteria) {
            FilteringCriteria::Equal => function ($k, $v) use ($searchValue) {
                return $v == $searchValue;
            },
            FilteringCriteria::StartsWith => function ($k, $v) use ($searchValue) {
                return str_starts_with($v, $searchValue);
            },
            FilteringCriteria::EndsWith => function ($k, $v) use ($searchValue) {
                return str_ends_with($v, $searchValue);
            },
            FilteringCriteria::LesserEqual => function ($k, $v) use ($searchValue) {
                return $v <= $searchValue;
            },
            FilteringCriteria::GreaterEqual => function ($k, $v) use ($searchValue) {
                return $v >= $searchValue;
            },
            FilteringCriteria::Contains => function ($k, $v) use ($searchValue) {
                return str_ends_with($v, $searchValue);
            }
        };
    }

    private function fakeData() : array {
        return $movies = [
            [
                "title" => "Origen",
                "year" => 2010,
                "rating" => 5
            ],
            [
                "title" => "Sueño de fuga",
                "year" => 1994,
                "rating" => 8
            ],
            [
                "title" => "Pulp Fiction",
                "year" => 1994,
                "rating" => 3
            ],
            [
                "title" => "El Caballero de la Noche",
                "year" => 2008,
                "rating" => 7
            ],
            [
                "title" => "Forrest Gump",
                "year" => 1994,
                "rating" => 6
            ],
            [
                "title" => "El Padrino",
                "year" => 1972,
                "rating" => 9
            ],
            [
                "title" => "La lista de Schindler",
                "year" => 1993,
                "rating" => 4
            ],
            [
                "title" => "El Señor de los Anillos: El retorno del Rey",
                "year" => 2003,
                "rating" => 8
            ],
            [
                "title" => "Matrix",
                "year" => 1999,
                "rating" => 2
            ],
            [
                "title" => "Titanic",
                "year" => 1997,
                "rating" => 7
            ],
            [
                "title" => "El Resplandor",
                "year" => 1980,
                "rating" => 5
            ],
            [
                "title" => "El club de la pelea",
                "year" => 1999,
                "rating" => 9
            ],
            [
                "title" => "El Rey León",
                "year" => 1994,
                "rating" => 6
            ],
            [
                "title" => "Gladiador",
                "year" => 2000,
                "rating" => 8
            ],
            [
                "title" => "La La Land",
                "year" => 2016,
                "rating" => 3
            ],
            [
                "title" => "Ciudad de Dios",
                "year" => 2002,
                "rating" => 9
            ],
            [
                "title" => "El Laberinto del Fauno",
                "year" => 2006,
                "rating" => 7
            ],
            [
                "title" => "El Gran Lebowski",
                "year" => 1998,
                "rating" => 4
            ],
            [
                "title" => "Interestelar",
                "year" => 2014,
                "rating" => 8
            ],
            [
                "title" => "El silencio de los corderos",
                "year" => 1991,
                "rating" => 6
            ]
        ];
    }

}