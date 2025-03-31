<?php

namespace App\Controller;

use App\Repository\JsonDataRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class JsonDataController extends AbstractController
{
    public function __construct(
        private readonly JsonDataRepository $repository
    ) {}

    #[Route('/json-data/{id}', name: 'json_data', methods: ['GET'])]
    public function getData(Request $request, int $id): JsonResponse
    {
        $data = $this->repository->find($id);
        
        if (!$data) {
            return $this->json(['error' => 'Record not found'], 404);
        }

        return $this->json($data->getData());
    }

    #[Route('/string-data/{id}', name: 'string_data', methods: ['GET'])]
    public function getStringData(Request $request, int $id): JsonResponse
    {
        $data = $this->repository->getRawData($id);
        
        if (!$data) {
            return $this->json(['error' => 'Record not found'], 404);
        }

        return $this->json($data);
    }
} 