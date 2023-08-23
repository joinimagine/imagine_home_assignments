<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Http\Services\Books\BookQueryService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    protected $bookQueryService;

    public function __construct(Request $request, BookQueryService $bookQueryService)
    {

        $this->setConstruct($request, BookResource::class);
        $this->bookQueryService = $bookQueryService;
    }

    public function index() {

        return $this->collection($this->bookQueryService->index($this->perPage, $this->page));
    }

    public function show($id) {

        return $this->resource($this->bookQueryService->show($id));
    }
}
