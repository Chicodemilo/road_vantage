<?php

class CarFactory{
    public static function create($make, $base_warranty_term, $base_warranty_miles, $vehicle_mileage, $vehicle_model_year, $suffix1)
    {
        return new car($make, $base_warranty_term, $base_warranty_miles, $vehicle_mileage, $vehicle_model_year, $suffix1);
    }
}

?>