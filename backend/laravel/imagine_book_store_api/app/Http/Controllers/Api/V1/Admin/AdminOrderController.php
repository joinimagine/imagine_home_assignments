<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Services\Orders\OrderQueryService;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    protected $orderQueryService;

    public function __construct(Request $request, OrderQueryService $orderQueryService)
    {
        $this->setConstruct($request, OrderResource::class);
        $this->orderQueryService = $orderQueryService;
    }

    public function index() {

        return $this->collection($this->orderQueryService->index($this->perPage, $this->page));
    }

    public function show($id) {

        return $this->resource($this->orderQueryService->show($id));
    }
}
