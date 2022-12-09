<?php

namespace App\Repositories;


interface BlogRepositoryInterface
{
    public function all();

    public function store($request);

    public function show($slug);

    public function destory($id);

    public function deletePhoto($id);

    public function addPhotos($request, $id);

    public function update( $request, $id);
}
