<?php

namespace App\Contracts;

interface FilterRepositoryInterface
{
    public function filterBy1PropFirst($model, $query, $prop1);

    public function filterBy1PropEmail($model, $query, $prop1);

    public function filterBy1Prop($model, $query, $prop1);

    public function filterBy1PropPaginated($model, $query, $limit, $prop1);

    public function filterBy1PropWithCount($model, $query, $limit, $countObj, $prop1);

    public function filterBy2PropFirst($model, $query1, $query2, $prop1, $prop2);

    public function filterBy2Prop($model, $query, $prop1, $prop2);

    public function filterBy2PropPaginated($model, $query, $limit, $prop1, $prop2);

    public function filterBy3Prop($model, $query, $prop1, $prop2, $prop3);

    public function filterBy4PropPaginated($model, $query, $limit, $prop1, $prop2, $prop3, $prop4);

    public function filterBy4PropWithCount($model, $query, $limit, $countObj, $prop1, $prop2, $prop3, $prop4);
}