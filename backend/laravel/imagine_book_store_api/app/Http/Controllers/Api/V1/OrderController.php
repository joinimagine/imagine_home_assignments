<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreRequest;
use App\Http\Resources\OrderResource;
use App\Http\Services\Orders\OrderQueryService;
use App\Http\Services\Orders\OrderStoreService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderQueryService;
    protected $orderStoreService;

    public function __construct(Request $request, OrderQueryService $orderQueryService, OrderStoreService $orderStoreService)
    {
        $this->setConstruct($request, OrderResource::class);
        $this->orderQueryService = $orderQueryService;
        $this->orderStoreService = $orderStoreService;
    }

    public function index() {

        return $this->collection($this->orderQueryService->index($this->perPage, $this->page));
    }

    public function show($id) {

        return $this->resource($this->orderQueryService->show($id));
    }

    public function store() {

        return $this->resource($this->orderStoreService->store());
    }
}
