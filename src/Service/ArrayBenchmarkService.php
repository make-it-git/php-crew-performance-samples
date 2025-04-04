<?php

declare(strict_types=1);

namespace App\Service;

class ArrayBenchmarkService
{
    private const ITERATIONS = 1000000;
    private const ARRAY_SIZE = 10000;

    public function benchmark(): array
    {
        $results = [];

        // Native array benchmark
        $nativeArrayTime = $this->benchmarkNativeArray();
        $results['native_array'] = [
            'time' => $nativeArrayTime,
            'message' => "Native array benchmark completed. Time: " . number_format($nativeArrayTime * 1000, 4) . "ms"
        ];

        // SPL ArrayObject benchmark
        $splArrayTime = $this->benchmarkSplArray();
        $results['spl_array'] = [
            'time' => $splArrayTime,
            'message' => "SPL ArrayObject benchmark completed. Time: " . number_format($splArrayTime * 1000, 4) . "ms"
        ];

        // Calculate difference
        $difference = $splArrayTime - $nativeArrayTime;
        $results['comparison'] = [
            'difference' => $difference,
            'message' => sprintf(
                "Native array is %s faster by %s ms",
                $difference > 0 ? '' : 'not',
                number_format(abs($difference) * 1000, 4)
            )
        ];

        return $results;
    }

    private function benchmarkNativeArray(): float
    {
        $start = microtime(true);
        
        $array = [];
        for ($i = 0; $i < self::ARRAY_SIZE; $i++) {
            $array[] = $i;
        }

        for ($i = 0; $i < self::ITERATIONS; $i++) {
            $array[rand(0, self::ARRAY_SIZE - 1)] = $i;
            $value = $array[rand(0, self::ARRAY_SIZE - 1)];
        }

        return microtime(true) - $start;
    }

    private function benchmarkSplArray(): float
    {
        $start = microtime(true);
        
        $array = new \ArrayObject();
        for ($i = 0; $i < self::ARRAY_SIZE; $i++) {
            $array[] = $i;
        }

        for ($i = 0; $i < self::ITERATIONS; $i++) {
            $array[rand(0, self::ARRAY_SIZE - 1)] = $i;
            $value = $array[rand(0, self::ARRAY_SIZE - 1)];
        }

        return microtime(true) - $start;
    }
} 