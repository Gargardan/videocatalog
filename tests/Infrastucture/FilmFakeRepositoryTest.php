<?php

namespace App\Tests\Infrastucture;

use App\Domain\VOB\FilmRating;
use App\Domain\VOB\FilmTitle;
use App\Domain\VOB\FilmYear;
use App\Domain\VOB\FilteringCriteria;
use App\Infrastructure\Output\FilmFakeRepository;
use PHPUnit\Framework\TestCase;

class FilmFakeRepositoryTest extends TestCase
{

    public function testSearch()
    {
        // Test search without filtering

        $filmFakeRepository = new FilmFakeRepository();

        $filmList = $filmFakeRepository->search(null, null, null);

        $this->assertCount(20, $filmList->get(), 'Unfilterid results count failed');
    }

    public function testByTitle()
    {
        $filmFakeRepository = new FilmFakeRepository();
        $filmList = $filmFakeRepository->search(
            new FilmTitle('Caballero', FilteringCriteria::Contains),
            null,
            null
        );

        $this->assertCount(1,$filmList->get(), 'Filtered by title results failed');

        $film = $filmList->get()[0];
        $this->assertEquals('El Caballero de la Noche', $film->title->value, 'Contains string failed');

        // Title begins with
        $filmList = $filmFakeRepository->search(
            new FilmTitle('El C', FilteringCriteria::StartsWith),
            null,
            null
        );

        $this->assertCount(2,$filmList->get(), 'Filtered by title results failed');

        $film = $filmList->get()[0];
        $this->assertEquals('El Caballero de la Noche', $film->title->value, 'Begins with string failed');

        // Title ends with
        $filmList = $filmFakeRepository->search(
            new FilmTitle('oche', FilteringCriteria::EndsWith),
            null,
            null
        );

        $this->assertCount(1,$filmList->get(), 'Filtered by title results failed');

        $film = $filmList->get()[0];
        $this->assertEquals('El Caballero de la Noche', $film->title->value, 'Begins with string failed');
    }

    public function testSearchByYear()
    {
        $filmFakeRepository = new FilmFakeRepository();
        $filmList = $filmFakeRepository->search(null, new FilmYear(1994, FilteringCriteria::Equal), null);
        $this->assertCount(4, $filmList->get(), 'Filtered by year results count failed');
    }

    public function testSearchByRating()
    {
        $filmFakeRepository = new FilmFakeRepository();

        // Rating menor qué
        $filmList = $filmFakeRepository->search(null, null, new FilmRating(2, FilteringCriteria::LesserEqual));
        $this->assertCount(1,$filmList->get(), 'Filtered by title results failed');

        $film = $filmList->get()[0];

        $this->assertEquals('Matrix', $film->title->value, 'Search by rating');

        // Rating mayor qué
        $filmList = $filmFakeRepository->search(
            null,
            null,
            new FilmRating(8, FilteringCriteria::GreaterEqual)
        );
        $this->assertCount(7,$filmList->get(), 'Filtered by title results failed');

        $film = $filmList->get()[0];

        $this->assertEquals('Sueño de fuga', $film->title->value, 'Search by rating');
    }

    public function testSearchByAll()
    {
        $filmFakeRepository = new FilmFakeRepository();

        $filmList = $filmFakeRepository->search(
            new FilmTitle('o', FilteringCriteria::Contains),
            new FilmYear(1994, FilteringCriteria::Equal),
            new FilmRating(5, FilteringCriteria::GreaterEqual)
        );

        $this->assertCount(2,$filmList->get(), 'Filtered by all criteria');
    }
}
