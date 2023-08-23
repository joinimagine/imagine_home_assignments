<?php


namespace App\Http\Services\Orders;

interface OrderQueryService
{

    public function index($perPage, $page);

    public function show($id);

}
