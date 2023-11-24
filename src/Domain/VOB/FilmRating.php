<?php

namespace App\Domain\VOB;

use App\Domain\Exceptions\ValidationException;

class FilmRating
{
    public readonly int $value;
    private readonly ?FilteringCriteria $filteringCriteria;

    /**
     * @param int $rating
     * @param FilteringCriteria|null $filteringCriteria
     * @throws ValidationException
     */
    public function __construct(int $rating, ?FilteringCriteria $filteringCriteria=null)
    {
        if ($rating < 0 || $rating > 10) {

            throw new ValidationException('Rating must be between 0 and 10');
        }

        $this->value = $rating;

        if (!in_array($filteringCriteria, [null, FilteringCriteria::LesserEqual, FilteringCriteria::GreaterEqual])) {
            throw new ValidationException('Filtering criteria for rating must be lesser equal (le) or greater equal (ge)');
        }

        $this->filteringCriteria = $filteringCriteria;
    }

    public function filter() : ?FilteringCriteria {
        return $this->filteringCriteria;
    }
}