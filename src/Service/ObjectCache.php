<?php

namespace App\Service;

use Symfony\Component\Cache\Adapter\ArrayAdapter;
use WeakReference;

class ObjectCache
{
    private ArrayAdapter $cache;

    public function __construct()
    {
        $this->cache = new ArrayAdapter();
    }

    public function get(string $key, callable $factory): ?object
    {
        $weakRef = $this->cache->getItem($key)->get();

        if ($weakRef instanceof WeakReference) {
            $object = $weakRef->get();
            if ($object !== null) {
                return $object;
            }
        }

        $object = $factory();
        $this->set($key, $object);

        return $object;
    }

    public function set(string $key, object $object): void
    {
        $weakRef = WeakReference::create($object);
        $item = $this->cache->getItem($key);
        $item->set($weakRef);
        $this->cache->save($item);
    }
}