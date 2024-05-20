<?php

namespace Domains\Store\Services;

use Illuminate\Support\Facades\Validator;
use Domains\Store\Repositories\StoreRepository;
use Domains\Store\Models\Store;

class StoreService
{
    protected $storeRepository;

    public function __construct(StoreRepository $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    public function index()
    {
        $list = $this->storeRepository->index();

        return response()->json($list);
    }

    public function show($storeId)
    {
        $store = $this->storeRepository->getById($storeId);

        return response()->json($store);
    }

    public function store($data)
    {
        $validator = Validator::make($data, self::saveRules());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $storeData['name'] = $data['name'];
        $storeData['address'] = $data['address'];

        $store = $this->storeRepository->store($storeData);

        return response()->json($store);
    }

    public function update($data, $storeId)
    {
        $validator = Validator::make($data, self::saveRules());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $storeData['name'] = $data['name'];
        $storeData['address'] = $data['address'];
        $store = Store::find($storeId);

        if ($store) {
            $updatedStore = $this->storeRepository->update($storeData, $store);
            return response()->json($updatedStore);
        } else {
            return response()->json(['message' => 'Store not found'], 404);
        }
    }

    private function saveRules()
    {
        return [
            'name' => 'required|string',
            'address' => 'required|string'
        ];
    }

    public function destroy($storeId)
    {
        $store = Store::find($storeId);

        if ($store) {
            $this->storeRepository->destroy($store);
            return response()->json(['message' => 'Store deleted', 200]);
        } else {
            return response()->json(['message' => 'Store not found'], 404);
        }
    }
}
