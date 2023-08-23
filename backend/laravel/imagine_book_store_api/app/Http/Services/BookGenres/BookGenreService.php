<?php

namespace App\Http\Services\BookGenres;

interface BookGenreService {

    public function index($perPage, $page);

    public function show($id);

    public function store($data);

    public function update($id, $data);

    public function destroy($id);
}
