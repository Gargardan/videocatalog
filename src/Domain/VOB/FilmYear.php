<?php

namespace App\Domain\VOB;

use App\Domain\Exceptions\ValidationException;
use App\Domain\Interface\IFilter;

class FilmYear implements IFilter
{

    public readonly int $value;
    private readonly ?FilteringCriteria $filter;

    /**
     * @param int $year
     * @param FilteringCriteria|null $filter Possible values for year are: Match
     * @throws ValidationException
     */
    public function __construct(int $year, ?FilteringCriteria $filter=null) {

        if ($year < 1895) {
            throw new ValidationException('No film was made before 1895 ;)');
        }

        if ($year > date('Y')) {
            throw new ValidationException('This film is yet to be released!');
        }

        $this->value = $year;

        if (!in_array($filter, [null, FilteringCriteria::Equal])) {
            throw new ValidationException("Invalid filtering criteria for year. Try exact match");
        }

        $this->filter = $filter;
    }

    public function filter(): ?FilteringCriteria
    {
        return $this->filter;
    }
}