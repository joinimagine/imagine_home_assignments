<?php


namespace App\Traits;

trait JSONResponse
{
    private string $resource;

    public function getResource(): string
    {
        return $this->resource;
    }

    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    public function collection($collection, $status = true, $errors = null)
    {
        $resource = $this->getResource();

        return $resource::collection($collection)
            ->additional(
                [
                    'success' => $status,
                    'total' => $collection->count()>0?$collection->total():0,
                    'errors' => $errors,
                ]
            );
    }

    public function resource($collection, $status = true, $errors = null)
    {
        $resourceInstance = new $this->resource($collection);
        $resourceInstance->additional(
            [
                'success' => $status,
                'errors' => $errors,
            ]
        );
        return $resourceInstance;
    }
}

