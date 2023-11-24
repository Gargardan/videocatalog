<?php

namespace App\Domain\Interface;

use App\Domain\VOB\FilteringCriteria;

interface IFilter
{
    /**
     * Returns the filtering criteria or null for no filtering
     * @return FilteringCriteria
     */
    public function filter() : ?FilteringCriteria;
}