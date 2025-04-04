<?php

declare(strict_types=1);

namespace Crew\Benchmarks;

use Crew\Benchmark;
use Crew\BenchmarkResult;
use WeakMap;

class WeakMapBenchmark extends Benchmark
{
    private WeakMap $weakMap;
    private array $objects;

    public function setUp(): void
    {
        $this->weakMap = new WeakMap();
        $this->objects = [];
        
        // Create test objects
        for ($i = 0; $i < 1000; $i++) {
            $this->objects[] = new \stdClass();
        }
    }

    public function benchmarkSet(): BenchmarkResult
    {
        $start = microtime(true);
        
        foreach ($this->objects as $key => $object) {
            $this->weakMap[$object] = "value_$key";
        }
        
        $end = microtime(true);
        
        return new BenchmarkResult(
            'Set',
            $end - $start,
            count($this->objects)
        );
    }

    public function benchmarkGet(): BenchmarkResult
    {
        // First set some values
        foreach ($this->objects as $key => $object) {
            $this->weakMap[$object] = "value_$key";
        }
        
        $start = microtime(true);
        
        foreach ($this->objects as $object) {
            $value = $this->weakMap[$object];
        }
        
        $end = microtime(true);
        
        return new BenchmarkResult(
            'Get',
            $end - $start,
            count($this->objects)
        );
    }

    public function benchmarkExists(): BenchmarkResult
    {
        // First set some values
        foreach ($this->objects as $key => $object) {
            $this->weakMap[$object] = "value_$key";
        }
        
        $start = microtime(true);
        
        foreach ($this->objects as $object) {
            $exists = isset($this->weakMap[$object]);
        }
        
        $end = microtime(true);
        
        return new BenchmarkResult(
            'Exists',
            $end - $start,
            count($this->objects)
        );
    }

    public function benchmarkUnset(): BenchmarkResult
    {
        // First set some values
        foreach ($this->objects as $key => $object) {
            $this->weakMap[$object] = "value_$key";
        }
        
        $start = microtime(true);
        
        foreach ($this->objects as $object) {
            unset($this->weakMap[$object]);
        }
        
        $end = microtime(true);
        
        return new BenchmarkResult(
            'Unset',
            $end - $start,
            count($this->objects)
        );
    }

    public function benchmarkGarbageCollection(): BenchmarkResult
    {
        // First set some values
        foreach ($this->objects as $key => $object) {
            $this->weakMap[$object] = "value_$key";
        }
        
        // Unset some objects to trigger garbage collection
        for ($i = 0; $i < 500; $i++) {
            unset($this->objects[$i]);
        }
        
        $start = microtime(true);
        
        // Force garbage collection
        gc_collect_cycles();
        
        $end = microtime(true);
        
        return new BenchmarkResult(
            'Garbage Collection',
            $end - $start,
            500
        );
    }
} 