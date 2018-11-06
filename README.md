# road_vantage
To run please run the the file /road_vantage/app/road_vantage.php in your terminal.

To run the unit tests please composer dump-autoload and run ./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/RoadTests.php. I do have a phpunit.xml file - but that's probably not too important.

It conforms to php 5.6 standards and up.
You may need to recursively chmod 777 the folder on your end so that I can write the json file that mimicks the api.

Assumptions:
1. A cars age is the 12 months to every year. 
Meaning since no months were given to test for in a car's age, a cars months-old age is: (current_year - model_year)*12 
For example a 2017 BMW is (2019 - 2017)*12 = 24 months.

2. The line about not using frameworks didn't include phpunit - since you ask for phpunit in the instructions.
But if I'm wrong about that please let me know and I'll build some test without phpunit.

3. You're ready for a lot of output! Models * Mileage * $Years * $coverages = 85,824 total tests each with an output.

Thanks again and let me know if you have any questions.
Miles
