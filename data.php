<?php

define('HOSPITALS', 
[
    'Auckland Surgical Centre' => 'https://www.healthpoint.co.nz/auckland-surgical-centre.xml',
    'Gillies Hospital' => 'https://www.healthpoint.co.nz/gillies-hospital-clinic.xml',
    'Southern Cross Hospital - Brightside' => 'https://www.healthpoint.co.nz/southern-cross-hospital-brightside.xml',
    'Southern Cross Hospital - Christchurch' => 'https://www.healthpoint.co.nz/southern-cross-hospital-christchurch.xml',
    'Hospital : Southern Cross Hospital - Hamilton' => 'https://www.healthpoint.co.nz/southern-cross-hospital-hamilton.xml',
    'Southern Cross Hospital - Invercargill' => 'https://www.healthpoint.co.nz/southern-cross-hospital-invercargill.xml',
    'Southern Cross Hospital - New Plymouth' => 'https://www.healthpoint.co.nz/southern-cross-hospital-new-plymouth.xml',
    'Southern Cross Hospital - North Harbour' => 'https://www.healthpoint.co.nz/southern-cross-hospital-north-harbour.xml',
    'Southern Cross Hospital - Rotorua' => 'https://www.healthpoint.co.nz/southern-cross-hospital-rotorua.xml',
    'Southern Cross Hospital - Wellington' => 'https://www.healthpoint.co.nz/southern-cross-hospital-wellington.xml',
]
);

$jsonData = [];

function getData() {


    foreach(HOSPITALS as $key => $item) {
        error_log('***** BEGIN FORMATTING *****');
        $xmlRequest = file_get_contents($item);
        $xmlFormater = (array)simplexml_load_string($xmlRequest);
        $services = (array)$xmlFormater['services'];

        $services = (array)$services['service-ref'];

        foreach ($services as $serviceKey) {
            $serviceName = (array)$serviceKey->attributes()->name;
            $serviceLink = (array)$serviceKey->attributes()->src;
            
            $getPeople = file_get_contents($serviceLink[0]);
            $peopleXML = (array)simplexml_load_string($getPeople);
            
            if (array_key_exists('people', $peopleXML)) {
                $people = (array)$peopleXML['people'];
                
                if (is_array($people['person-ref'])) {
                    $peoples = [];
                    foreach ($people['person-ref'] as $ppl => $value) {
                        $peoples[] = [         
                            'name' => (string)$value->attributes()->{'name'},
                            'src' => (string)$value->attributes()->{'src'},
                            'title' => (string)$value->attributes()->{'title'}
                        ];
                    }
                    $jsonData[$key][$serviceName[0]][] = $peoples;
                }
                else {
                    $jsonData[$key][$serviceName[0]][] = [
                        'name' => (string)$people['person-ref']->attributes()->{'name'},
                        'src' => (string)$people['person-ref']->attributes()->{'src'},
                        'title' => (string)$people['person-ref']->attributes()->{'title'}
                    ];                
                }
            }
            else {
                $jsonData[$key][$serviceName[0]] = [];
            }

        }
        error_log('**** DONE FORMATTING *****');

    }

    error_log('**** BEGIN TOUCHING DATA FILE *****');
    $myfile = fopen("data.json", "w") or die("Unable to open file!");
    $content = json_encode($jsonData);
    fwrite($myfile, $content);
    fclose($myfile);
    error_log('**** DONE TOUCHING FILE *****');
}

$dataFileExist = file_exists('data.json');

if (!$dataFileExist) {
    getData();
}

?>
