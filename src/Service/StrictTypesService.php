<?php

declare(strict_types=1);

namespace App\Service;

class StrictTypesService
{
    public function calculate(int $a, int $b, int $c)
    {
        $result = 0;
        for ($i = 0; $i < 10000000; $i++) {
            $result += $a + $b + $c;
        }
        return $result;
    }

    public function benchmark()
    {
        $iterations = 1000;
        $totalTime = 0;
        
        // Test with proper types
        for ($i = 0; $i < $iterations; $i++) {
            $start = microtime(true);
            $this->calculate(1, 2, 3); // Integer inputs
            $totalTime += microtime(true) - $start;
        }
        
        $avgTime = $totalTime / $iterations;
        return [
            'iterations' => $iterations,
            'total_time' => $totalTime,
            'average_time' => $avgTime,
            'message' => "Strict types benchmark completed. Average time per iteration: " . number_format($avgTime * 1000, 4) . "ms"
        ];
    }
} 