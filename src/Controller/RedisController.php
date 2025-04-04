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
        // $this->redis = new Client($_ENV['REDIS_URL'], [
        $this->redis = new Client([
            'scheme'   => 'tcp',
            'host'     => 'redis',
            'port'     => 6379,
            'persistent' => false,
        ]);
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

    #[Route('/api/redis/test-pipelined', name: 'redis_test_pipelined', methods: ['GET'])]
    public function testPipelined(): JsonResponse
    {
        $key = 'test_key_' . time();
        $value = 'test_value_' . time();
        
        $responses = $this->redis->pipeline(function ($pipe) use ($key, $value) {
            $pipe->set($key, $value);
            $pipe->get($key);
            $pipe->del($key);
        });

        return $this->json([
            'status' => 'success',
            'message' => 'Redis pipelined connection successful',
            'data' => [
                'key' => $key,
                'value' => $responses[1] // The second response is the result of GET
            ]
        ]);
    }
} 