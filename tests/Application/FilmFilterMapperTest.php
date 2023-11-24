<?php

namespace App\Tests\Application;

use App\Application\RequestFilterMapper;
use App\Domain\Exceptions\ValidationException;
use App\Domain\Interface\IFilmFilterMapper;
use PHPUnit\Framework\TestCase;

class FilmFilterMapperTest extends TestCase
{
    /**
     * En estos test me centro en la validación de la petición del usuario.
     * @return void
     */

    private static function buildFilmFilterMapper(array $data) : IFilmFilterMapper {
        return new RequestFilterMapper(json_encode($data));
    }
    public function testTitleRequest()
    {
        try {
            $mapper = self::buildFilmFilterMapper([
                'title' => ['']
            ]);

            $this->assertTrue(false, 'A validation exception was expected');

        } catch(ValidationException $e) {
            $this->assertStringContainsString('title must be an array with a string and a filtering criteria', $e->getMessage());
        }
    }
}
