<?php


namespace App\Traits;

use Illuminate\Http\Request;

trait GroupAll
{
    use JSONResponse, Pagination, ApiResponser;

    protected $perPage;
    protected $page;
    protected $keyword;

    public function setConstruct(Request $request, $resource)
    {
        $this->setResource($resource);
        $this->perPage = $this->checkPerPageValue($request);
        $this->page = $this->checkPageValue($request);
        $this->keyword = $request->input('keyword','');
    }

}
