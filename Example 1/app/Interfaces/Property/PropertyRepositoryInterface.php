<?php

namespace App\Interfaces\Property;

interface PropertyRepositoryInterface
{

    public function store($request, $propertyData, $realEstateData);

    public function update($property, $request, $propertyData, $realEstateData);

}
