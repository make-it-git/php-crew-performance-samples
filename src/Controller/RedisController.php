<?php

namespace App\Controller;

use Predis\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RedisController extends AbstractController
{
    private Client $redis;

    public function __construct()
    {
        $this->redis = new Client($_ENV['REDIS_URL']);
    }

    #[Route('/api/redis/test', name: 'redis_test', methods: ['GET'])]
    public function test(): JsonResponse
    {
        $key = 'test_key_' . time();
        $value = 'test_value_' . time();
        
        $this->redis->set($key, $value);
        $result = $this->redis->get($key);
        
        $this->redis->del($key);

        return $this->json([
            'status' => 'success',
            'message' => 'Redis connection successful',
            'data' => [
                'key' => $key,
                'value' => $result
            ]
        ]);
    }
} 