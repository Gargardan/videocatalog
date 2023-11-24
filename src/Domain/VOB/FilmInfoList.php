<?php

namespace App\Domain\VOB;

/**
 * FilmInfoList encapsulates an array of FilmInfo items
 *
 * TODO: Convertir esta clase en un Iterable
 */
class FilmInfoList
{

    private array $list = [];
    private int $idx = 0;

    public function append(FilmInfo $film) : void
    {
        $this->list[] = $film;
    }

    public function get() : array
    {
        return $this->list;
    }
}