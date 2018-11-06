<?php

include('RoadVantageArrays.php');

// save $coverage to a json file
$fp = fopen('coverage.json', 'w');
fwrite($fp, json_encode($coverages));
fclose($fp);

// open json file and convert json back to array
 $coverages = json_decode(file_get_contents('coverage.json'), true);

include('Car.php');

include('CarFactory.php');

//models 2 x mileage cases 149 x year cases 16 x coverages cases 18 = 85,824 total tests. Get ready for a lot of output.
//test each of the models in the array
foreach ($base_warranties as $base_warranty){

    //set test mileage test BETWEEN 0 and 150000 - meaning we are not testing for 0 or 150000 but every 1000 miles in between
    $mileage = 1000;
    while($mileage < 150000){

        //for every model, for this current mileage, test all years in the array
        foreach($years as $year){

            // make the car with make, base_warranty_term, base_warranty_miles, vehicle_mileage, vehicle_model_year, suffix1
            //appending a 0 to the front of suffix1 if needed
            $suf_1 = $year['suffix1'];
            if(strlen("$suf_1") == 1){
                $suf_1 = '0'.$suf_1;
            }
            $test_car = CarFactory::create($base_warranty['make'], $base_warranty['term'], $base_warranty['miles'], $mileage, $year['modelyear'], $suf_1);
            Car::$number_of_cars += 1;

            // set $usage_flag
            $test_car->setUsageFlag();

            // set $vehicle_age_months
            $test_car->setVehicleAgeMonths();

            // set $suffix2 - where in the $issue_mileage array the current $mileage falls
            $test_car->setSuffix2();

            //test the car for each of the coverage options
            foreach($coverages as $coverage){
                // Coverage Test 1 - will the this car have mileage over 153000 before the end of the new coverage?
                $failure_array[] = $test_car->testMileageAtEndOfCoverage($coverage);

                // Coverage Test 2 - will the this car have an age over 147 months before the end of the new coverage?
                $failure_array[] = $test_car->testTermAtEndOfCoverage($coverage);

                // Coverage Test 3 - will the term of the new coverage be complete before the end of the base model coverage?
                $failure_array[] = $test_car->testTermOverBeforeBaseWarranty($coverage);

                // Coverage Test 4 - will the millage of the new coverage be used before millage runs out on the base coverage?
                $failure_array[] = $test_car->testMileageOverBeforeBaseWarranty($coverage);

                // Echo Coverage information
                //on the failure_array doing a var_dump didn't match your output in the file because of return chars - that's why it's hacky down there - I wasn't sure how much of a stickler you'd be on output :)
                $echo_string = $test_car->make."  "
                .$test_car->vehicle_model_year."  "
                .$test_car->vehicle_mileage."  "
                .$test_car->usage_flag."  "
                ."\"".$coverage['name']."\""
                ."  suffix1:".$test_car->suffix1
                ."  suffix2:".$test_car->suffix2
                ."  RESULT: ".$test_car->coverage_granted."  ";
                if($test_car->coverage_granted == 'FAILURE'){
                    $echo_string .= "array(";
                    foreach ($failure_array as $failure){
                        if($failure != ''){
                            $echo_string .= "'".$failure."' ";
                        }
                    }
                    $echo_string .= ")";
                }
                echo $echo_string;
                echo "\n";

                //reset test_car->coverage_granted and failure array
                $test_car->coverage_granted = '';
                $failure_array = array();
            }//end $coverage loop
        }//end $years loop
        $mileage += 1000;
    }//end $mileage loop
}//end foreach make loop

?>