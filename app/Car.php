<?php
class Car{
    public $make;
    public $base_warranty_term;
    public $base_warranty_miles;
    public $vehicle_mileage;
    public $vehicle_model_year;
    public $suffix1;
    public $usage_flag;
    public $vehicle_age_months;
    public $suffix2;
    public $coverage_granted;
    public $fail_reasons;

    public static $number_of_cars = 0;

    public function __construct($make, $base_warranty_term, $base_warranty_miles, $vehicle_mileage, $vehicle_model_year, $suffix1)
    {
        $this->make = $make;
        $this->base_warranty_term = $base_warranty_term;
        $this->base_warranty_miles = $base_warranty_miles;
        $this->vehicle_mileage = $vehicle_mileage;
        $this->vehicle_model_year = $vehicle_model_year;
        $this->suffix1 = $suffix1;
    }

    public function setUsageFlag()
    {
        if($this->vehicle_mileage <= $this->base_warranty_miles){
            $this->usage_flag = 'NEW';
        }else{
            $this->usage_flag = 'USED';
        }
    }

    public function setVehicleAgeMonths(){
        $this->vehicle_age_months = (2019 - $this->vehicle_model_year)*12;
    }

    public function setSuffix2(){
        $issue_mileage = array(
            array("min" => 0, "max" => 12000, "suffix2" => "A"),
            array("min" => 12001, "max" => 24000, "suffix2" => "A"),
            array("min" => 24001, "max" => 36000, "suffix2" => "B"),
            array("min" => 36001, "max" => 48000, "suffix2" => "C"),
            array("min" => 48001, "max" => 60000, "suffix2" => "D"),
            array("min" => 60001, "max" => 72000, "suffix2" => "E"),
            array("min" => 72001, "max" => 84000, "suffix2" => "F"),
            array("min" => 84001, "max" => 96000, "suffix2" => "G"),
            array("min" => 96001, "max" => 108000, "suffix2" => "H"),
            array("min" => 108001, "max" => 120000, "suffix2" => "I"),
            array("min" => 120001, "max" => 132000, "suffix2" => "J"),
            array("min" => 132001, "max" => 144000, "suffix2" => "K"),
            array("min" => 144001, "max" => 150000, "suffix2" => "L")
        );
        foreach($issue_mileage as $issue_mile){
            if($this->vehicle_mileage >= $issue_mile['min'] && $this->vehicle_mileage <= $issue_mile['max']){
                $this->suffix2 = $issue_mile['suffix2'];
            }
        }
    }

    public function testMileageAtEndOfCoverage($coverage){
        $tot_mile = $this->vehicle_mileage + $coverage['miles'];
        if($tot_mile > 153000){
            $this->coverage_granted = 'FAILURE';
            return 'Mileage above 153000 before end of new coverage.';
        }else{
            if($this->coverage_granted == 'FAILURE'){
                $this->coverage_granted = 'FAILURE';
            }else{
                $this->coverage_granted = 'SUCCESS';
            }
        }
    }

    public function testTermAtEndOfCoverage($coverage){
        $tot_term = $this->vehicle_age_months + $coverage['terms'];
        if($tot_term > 147){
            $this->coverage_granted = 'FAILURE';
            return 'Age above 147 months before end of new coverage.';
        }else{
            if($this->coverage_granted == 'FAILURE'){
                $this->coverage_granted = 'FAILURE';
            }else{
                $this->coverage_granted = 'SUCCESS';
            }
        }
    }

    public function testTermOverBeforeBaseWarranty($coverage){
        $tot_coverage_terms = $this->vehicle_age_months + $coverage['terms'];
        if($tot_coverage_terms < $this->base_warranty_term){
            $this->coverage_granted = 'FAILURE';
            return 'Term expires before warranty.';
        }else{
            if($this->coverage_granted == 'FAILURE'){
                $this->coverage_granted = 'FAILURE';
            }else{
                $this->coverage_granted = 'SUCCESS';
            }
        }
    }

    public function testMileageOverBeforeBaseWarranty($coverage){
        $tot_coverage_miles = $this->vehicle_mileage + $coverage['miles'];
        if($tot_coverage_miles < $this->base_warranty_miles){
            $this->coverage_granted = 'FAILURE';
            return 'Miles expire before warranty.';
        }else{
            if($this->coverage_granted == 'FAILURE'){
                $this->coverage_granted = 'FAILURE';
            }else{
                $this->coverage_granted = 'SUCCESS';
            }
        }
    }
}

?>