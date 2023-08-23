<?php

namespace App\Http\Services\Books;

interface BookQueryService {

    public function index($perPage, $page);

    public function show($id);

}
