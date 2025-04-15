<?php

namespace App\Service;

class GcExampleService
{
    private ObjectCache $cache;

    public function __construct(ObjectCache $cache)
    {
        $this->cache = $cache;
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
        
        return $largeData;
    }

    public function getDataWithGc(bool $collectGc): array
    {
        $data = $this->getLargeData();
        
        if ($collectGc) {
            gc_collect_cycles();
        }

        return [
            'data' => $data,
        ];
    }
} 
