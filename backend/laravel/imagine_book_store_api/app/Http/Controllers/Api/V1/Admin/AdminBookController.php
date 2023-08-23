<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Book\StoreRequest;
use App\Http\Requests\Book\UpdateRequest;
use App\Http\Resources\BookResource;
use App\Http\Services\Books\BookModificationService;
use App\Http\Services\Books\BookQueryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class AdminBookController extends Controller
{
    protected $bookQueryService;
    protected $bookModificationService;

    public function __construct(Request $request, BookQueryService $bookQueryService, BookModificationService $bookModificationService)
    {
        $this->setConstruct($request, BookResource::class);
        $this->bookQueryService = $bookQueryService;
        $this->bookModificationService = $bookModificationService;
    }

    public function index() {

        return $this->collection($this->bookQueryService->index($this->perPage, $this->page));
    }

    public function show($id) {

        return $this->resource($this->bookQueryService->show($id));
    }

    public function store(StoreRequest $request) {

        return $this->resource($this->bookModificationService->store($request->validated()));
    }

    public function update(UpdateRequest $request, $id) {

        return $this->resource($this->bookModificationService->update($request->validated(), $id));
    }

    public function destroy($id) {

        return $this->bookModificationService->destroy($id)
            ? $this->success([], Config::get('messages.api.book.deleted'))
            : $this->error(500, Config::get('messages.api.errors.server_error'));
    }
}
