<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use PDO;

class PdoController extends AbstractController
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO(
            $_ENV['DATABASE_URL'],
            'symfony',
            'symfony',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    #[Route('/api/pdo/test', name: 'pdo_test', methods: ['GET'])]
    public function test(): JsonResponse
    {
        $stmt = $this->pdo->query('SELECT NOW() as time');
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $this->json([
            'status' => 'success',
            'message' => 'PDO connection successful',
            'data' => $result
        ]);
    }
} 