<?php


namespace App\Traits;

use Illuminate\Http\Request;

trait Pagination
{
    public $model_perPage = 20;
    public $model_page = 1;

    public function checkPerPageValue(Request $request)
    {
        return $request->perPage ?? $this->model_perPage;
    }

    public function checkPageValue(Request $request)
    {
        return $request->page ?? $this->model_page;
    }
}
