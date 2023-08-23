<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookGenere\StoreRequest;
use App\Http\Requests\BookGenere\UpdateRequest;
use App\Http\Resources\BookGenreResource;
use App\Http\Services\BookGenres\BookGenreService;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Config;

class BookGenreController extends Controller
{
    protected $bookGenreService;

    public function __construct(Request $request, BookGenreService $bookGenreService)
    {
        $this->setConstruct($request, BookGenreResource::class);
        $this->bookGenreService = $bookGenreService;
    }

    public function index() {

        return $this->collection($this->bookGenreService->index($this->perPage, $this->page));
    }

    public function show($id) {

        return $this->resource($this->bookGenreService->show($id));
    }

    public function store(StoreRequest $request) {

        return $this->resource($this->bookGenreService->store($request->validated()));
    }

    public function update(UpdateRequest $request, $id) {

        return $this->resource($this->bookGenreService->update($id, $request->validated()));
    }

    public function destroy($id) {

        return $this->bookGenreService->destroy($id)
                ? $this->success([], Config::get('messages.api.book_genres.deleted'))
                : $this->error(500, Config::get('messages.api.errors.server_error'));
    }
}
