<?php

namespace App\Domain\VOB;

use App\Domain\Exceptions\ValidationException;

class FilmTitle
{
    public readonly string $value;
    private readonly ?FilteringCriteria $filteringCriteria;

    /**
     * @param string $title
     * @param FilteringCriteria|null $filteringCriteria
     * @throws ValidationException
     */
    public function __construct(string $title, ?FilteringCriteria $filteringCriteria=null)
    {
        $this->value = $title;

        if (!in_array($filteringCriteria, [null, FilteringCriteria::StartsWith, FilteringCriteria::EndsWith, FilteringCriteria::Contains])) {
            throw new ValidationException('Filtering criterion for title must be starts with (sw), ends with (ew) or contains (ct)');
        }

        $this->filteringCriteria = $filteringCriteria;
    }

    public function filter() : ?FilteringCriteria {
        return $this->filteringCriteria;
    }
}