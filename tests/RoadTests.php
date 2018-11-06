<?php
use PHPUnit\Framework\TestCase;

include('./app/Car.php');
include('./app/CarFactory.php');

///// ******** GREG ******** /////
// Since you referenced phpunit in the instructions, I'm assuming that you do want us to use that framework - even though you said no frameworks or libraries are to be used.
//  I'm happy to write a some simple tests without phpunit if you'd like. But I'm pretty sure you weren't counting phpunit in the instructions for no libraries.

class RoadTests extends TestCase
{
    //make sure our test environment is working
    public function testTrueIsTrue(){
        $foo = true;
        $this->assertTrue($foo);
    }

    //make sure we can make a car
    public function testMakeCar(){
        $test_car = CarFactory::create('Audi', 24, 10000, 700, 2013, 42);
        $bar = (
            ($test_car->make == 'Audi') &&
            ($test_car->base_warranty_term == 24) &&
            ($test_car->base_warranty_miles == 10000) &&
            ($test_car->vehicle_mileage == 700) &&
            ($test_car->vehicle_model_year == 2013) &&
            ($test_car->suffix1 == 42)
        );
        $this->assertTrue($bar);
    }

    //make sure we can set the usage flag
    public function testUsageFlag(){
        $test_car = CarFactory::create('Audi', 24, 10000, 700, 2013, 42);
        $test_car->setUsageFlag();
        $this->assertEquals($test_car->usage_flag, 'NEW');
    }

    //make sure we can set the test car age in months
    public function testVehicleAgeMonths(){
        $test_car = CarFactory::create('Audi', 24, 10000, 700, 2013, 42);
        $test_car->setVehicleAgeMonths();
        $this->assertEquals($test_car->vehicle_age_months, 72);
    }

    //make sure we can set the correct Suffix2
    public function testSuffix2(){
        $test_car = CarFactory::create('Audi', 24, 10000, 700, 2013, 42);
        $test_car->setSuffix2();
        $this->assertEquals($test_car->suffix2, 'A');
    }

    //make sure we can get SUCCESS for the is car for this coverage based on mileage at end of term
    public function testtMileageAtEndOfCoverageSuccess(){
        $coverage = array("name" => "120 Months/120,000 Miles", "terms" => 120, "miles" => 120000);
        $test_car = CarFactory::create('Audi', 24, 10000, 700, 2013, 42);
        $test_car->testMileageAtEndOfCoverage($coverage);
        $this->assertEquals($test_car->coverage_granted, 'SUCCESS');

    }

    //make sure we can get FAILURE for this car for this coverage based on mileage at end of term - ie returning FAILURE for the correct reason on this test is a successful unit test
    public function testtMileageAtEndOfCoverageFailure(){
        $coverage = array("name" => "120 Months/120,000 Miles", "terms" => 120, "miles" => 120000);
        $test_car = CarFactory::create('Audi', 24, 10000, 100000, 2013, 42);
        $test_fail = $test_car->testMileageAtEndOfCoverage($coverage);
        $this->assertEquals($test_fail, 'Mileage above 153000 before end of new coverage.');
    }

    //make sure we can get SUCCESS for the is car for this coverage based on months old at end of term
    public function testTermAtEndOfCoverageSuccess(){
        $coverage = array("name" => "3 Months/3,000 Miles", "terms" => 3, "miles" => 3000);
        $test_car = CarFactory::create('Audi', 24, 10000, 700, 2013, 42);
        $test_car->setVehicleAgeMonths();
        $test_car->testTermAtEndOfCoverage($coverage);
        $this->assertEquals($test_car->coverage_granted, 'SUCCESS');

    }

    //make sure we can get FAILURE for this car for this coverage based on months old at end of term - ie returning FAILURE for the correct reason on this test is a successful unit test
    public function testTermAtEndOfCoverageFailure(){
        $coverage = array("name" => "120 Months/120,000 Miles", "terms" => 120, "miles" => 120000);
        $test_car = CarFactory::create('Audi', 24, 10000, 100000, 2010, 42);
        $test_car->setVehicleAgeMonths();
        $test_fail = $test_car->testTermAtEndOfCoverage($coverage);
        $this->assertEquals($test_fail, 'Age above 147 months before end of new coverage.');
    }

    //make sure we can get SUCCESS for the is car for this coverage based on length of term vs warranty
    public function testTermOverBeforeBaseWarrantySuccess(){
        $coverage = array("name" => "120 Months/120,000 Miles", "terms" => 120, "miles" => 120000);
        $test_car = CarFactory::create('Audi', 24, 10000, 700, 2019, 42);
        $test_car->setVehicleAgeMonths();
        $test_car->testTermOverBeforeBaseWarranty($coverage);
        $this->assertEquals($test_car->coverage_granted, 'SUCCESS');

    }

    //make sure we can get FAILURE for this car for this coverage based on length of term vs warranty - ie returning FAILURE for the correct reason on this test is a successful unit test
    public function testTermOverBeforeBaseWarrantyFailure(){
        $coverage = array("name" => "3 Months/3,000 Miles", "terms" => 3, "miles" => 3000);
        $test_car = CarFactory::create('Audi', 24, 10000, 100000, 2019, 42);
        $test_car->setVehicleAgeMonths();
        $test_fail = $test_car->testTermOverBeforeBaseWarranty($coverage);
        $this->assertEquals($test_fail, 'Term expires before warranty.');
    }

    //make sure we can get SUCCESS for the is car for this coverage based on mileage vs warranty
    public function testMileageOverBeforeBaseWarrantySuccess(){
        $coverage = array("name" => "120 Months/120,000 Miles", "terms" => 120, "miles" => 120000);
        $test_car = CarFactory::create('Audi', 24, 10000, 700, 2019, 42);
        $test_car->testMileageOverBeforeBaseWarranty($coverage);
        $this->assertEquals($test_car->coverage_granted, 'SUCCESS');

    }

    //make sure we can get FAILURE for this car for this coverage based on mileage vs warranty - ie returning FAILURE for the correct reason on this test is a successful unit test
    public function testMileageOverBeforeBaseWarrantyFailure(){
        $coverage = array("name" => "3 Months/3,000 Miles", "terms" => 3, "miles" => 3000);
        $test_car = CarFactory::create('Audi', 24, 10000, 700, 2019, 42);
        $test_fail = $test_car->testMileageOverBeforeBaseWarranty($coverage);
        $this->assertEquals($test_fail, 'Miles expire before warranty.');
    }

}
?>