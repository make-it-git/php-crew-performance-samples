<?php

namespace App\Service;

class NoStrictTypesService
{
    public function calculate($a, $b, $c)
    {
        // This function will perform type coercion
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
        
        // Test with mixed types
        for ($i = 0; $i < $iterations; $i++) {
            $start = microtime(true);
            // $this->calculate("1", "2", "3"); // String inputs
            $this->calculate(1, 2, 3);
            $totalTime += microtime(true) - $start;
        }
        
        $avgTime = $totalTime / $iterations;
        return [
            'iterations' => $iterations,
            'total_time' => $totalTime,
            'average_time' => $avgTime,
            'message' => "Non-strict types benchmark completed. Average time per iteration: " . number_format($avgTime * 1000, 4) . "ms"
        ];
    }
} 