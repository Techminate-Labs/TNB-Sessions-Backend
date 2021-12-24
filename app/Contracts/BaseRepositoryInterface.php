<?php

namespace App\Contracts;

interface BaseRepositoryInterface
{
    public function storeInDB($model, $data);
    
    public function findById($model, $id);

    public function findByIdGet($model, $id, $prop);

    public function findByIdfirst($model, $id, $prop);

    public function listWithoutPagination($model, $limit);

    public function listWithPagination($model, $limit);

    public function listwithCount($model, $limit, $countAttrib);

    public function listInRandomOrder($model, $limit);
}