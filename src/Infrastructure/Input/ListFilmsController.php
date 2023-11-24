<?php

namespace App\Infrastructure\Input;

use App\Application\ListFilmsService;
use App\Application\RequestFilterMapper;
use App\Infrastructure\Output\FilmFakeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ListFilmsController extends AbstractController
{
    public function list(Request $request) : JsonResponse
    {
        $data = $request->getContent();
        $requestFilterMapper = new RequestFilterMapper($data);

        $listingService = new ListFilmsService(new FilmFakeRepository());
        return $this->json(
            $listingService($requestFilterMapper)->get()
        );
    }
}