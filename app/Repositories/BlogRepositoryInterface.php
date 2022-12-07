<?php

namespace App\Repositories;

interface BlogRepositoryInterface
{
    public function all();

    public function store($request);
}
