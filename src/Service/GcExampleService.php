<?php

namespace App\Service;

class GcExampleService
{
    private int $callCount = 0;
    private int $gcThreshold;

    public function __construct(int $gcThreshold = 10)
    {
        $this->gcThreshold = $gcThreshold;
    }

    public function getLargeData(): array
    {
        $largeData = [];
        for ($i = 0; $i < 1000; $i++) {
            $largeData[] = [
                'id' => $i,
                'data' => str_repeat('x', 1000),
                'metadata' => [
                    'timestamp' => time(),
                    'random' => bin2hex(random_bytes(1000)),
                ]
            ];
        }

        $this->callCount++;
        
        return $largeData;
    }

    public function getDataWithGc(): array
    {
        $data = $this->getLargeData();
        
        $shouldCollectGarbage = $this->shouldCollectGarbage();
        if ($shouldCollectGarbage) {
            gc_collect_cycles();
            $this->resetCallCount();
        }

        return [
            'data' => $data,
            'gc_threshold' => $this->gcThreshold,
            'garbage_collected' => $shouldCollectGarbage,
        ];
    }

    private function shouldCollectGarbage(): bool
    {
        return $this->callCount >= $this->gcThreshold;
    }

    private function resetCallCount(): void
    {
        $this->callCount = 0;
    }

    public function setGcThreshold(int $threshold): void
    {
        $this->gcThreshold = $threshold;
    }
} 