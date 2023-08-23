<?php


namespace App\Http\Services\Books;

interface BookModificationService {

    public function store($data);

    public function update($data, $id);

    public function destroy($id);

}
