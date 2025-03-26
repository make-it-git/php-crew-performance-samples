<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DoctrineController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/api/doctrine/test', name: 'doctrine_test', methods: ['GET'])]
    public function test(): JsonResponse
    {
        $result = $this->entityManager->getConnection()->executeQuery('SELECT NOW() as time')->fetchAssociative();

        return $this->json([
            'status' => 'success',
            'message' => 'Doctrine connection successful',
            'data' => $result
        ]);
    }
} 