<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use App\Repository\RawJsonDataRepository;
use App\Repository\JsonDataRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class DataController extends AbstractController
{
    public function __construct(
        private readonly RawJsonDataRepository $rawRepository,
        private readonly JsonDataRepository $jsonRepository,
        private readonly LoggerInterface $logger
    ) {}

    #[Route('/json-data/{id}', name: 'json_data', methods: ['GET'])]
    public function getData(Request $request, int $id): JsonResponse
    {
        $data = $this->jsonRepository->find($id);
        
        if (!$data) {
            return $this->json(['error' => 'Record not found'], 404);
        }

        return $this->json($data->getData());
    }

    #[Route('/string-data/{id}', name: 'string_data', methods: ['GET'])]
    public function getStringData(Request $request, int $id): Response
    {
        $data = $this->rawRepository->getRawData($id);
        
        if (!$data) {
            throw new NotFoundHttpException();
        }

        return new Response($data);
    }
} 