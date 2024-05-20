<?php

namespace Domains\Store\Repositories;

use Domains\Store\Models\Store;

class StoreRepository
{
    public function index()
    {
        return Store::select('name', 'address')->get();
    }

    public function getById($storeId)
    {
        return Store::select('name', 'address')->find($storeId);
    }

    public function store(array $data)
    {
        return Store::create($data);
    }

    public function update(array $data, Store $store)
    {
        $store->update($data);

        return $store;
    }

    public function destroy(Store $store)
    {
        return $store->delete();
    }
} 