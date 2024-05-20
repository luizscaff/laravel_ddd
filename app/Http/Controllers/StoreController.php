<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Domains\Store\Services\StoreService;

class StoreController extends Controller
{
    protected $storeService;

    public function __construct(StoreService $storeService) {
        $this->storeService = $storeService;
    }

    public function index()
    {
        return $this->storeService->index();
    }

    public function show($storeId)
    {
        return $this->storeService->show($storeId);
    }

    public function store(Request $request)
    {
        return $this->storeService->store($request->all());
    }

    public function update(Request $request, $storeId)
    {
        return $this->storeService->update($request->all(), $storeId);
    }

    public function destroy(Request $request, $storeId)
    {
        return $this->storeService->destroy($storeId);
    }
}
