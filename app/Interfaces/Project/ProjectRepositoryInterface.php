<?php

namespace App\Interfaces\Project;

interface ProjectRepositoryInterface
{

    public function store($request, $projectData, $realEstateData);

    public function update($project, $request, $projectData, $realEstateData);

}
