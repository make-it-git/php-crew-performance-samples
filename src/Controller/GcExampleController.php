<?php

namespace App\Controller;

use App\Service\GcExampleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class GcExampleController extends AbstractController
{
    public function __construct(
        private readonly GcExampleService $service
    ) {}

    #[Route('/gc-example', name: 'gc_example', methods: ['GET'])]
    public function getData(Request $request): JsonResponse
    {
        if ($request->query->has('collect_gc')) {
            return $this->json($this->service->getDataWithGc(true));
        } else {
            return $this->json($this->service->getDataWithGc(false));
        }
    }
} 