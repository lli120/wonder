<?php


define('REGIONS', [
    'Auckland' => ['Auckland Surgical Centre', 'Gillies Hospital', 'Southern Cross Hospital - Brightside', 'Southern Cross Hospital - North Harbour'],
    'Christchurch' => ['Southern Cross Hospital - Christchurch'],
    'Hamilton' => ['Hospital : Southern Cross Hospital - Hamilton'],
    'Invercargill' => ['Southern Cross Hospital - Invercargill'],
    'New Plymouth' => ['Southern Cross Hospital - New Plymouth'],
    'Rotorua' => ['Southern Cross Hospital - Rotorua'],
    'Wellington' => ['Southern Cross Hospital - Wellington'],
]
);
    $input = file_get_contents("php://input");
    $decodeInput = json_decode($input);

    $specialist = $decodeInput->specialist;
    $region = $decodeInput->region;

    $dataSource = file_get_contents('data.json');
    $dataSource = json_decode($dataSource, TRUE);

    $regionSource = [];
    $result = [];
    if (!empty($region)) {
        foreach ($region as $key) {
            foreach(REGIONS[$key] as $region => $name){
                if (array_key_exists($name, $dataSource)) {
                    $regionSource[$name] = $dataSource[$name];
                }
            }
        }
    }

    if (!empty($specialist)) {
        $dataSource = (!empty($regionSource)) ? $regionSource : $dataSource;
        foreach($dataSource as $hos => $services) {
            foreach ($services as $key => $value) {
                if (strpos($key, $specialist)) {
                    $result[$hos][$key] = $value;
                }
                foreach ($value as $item) {
                    if (!array_key_exists('name', $item)) {
                        foreach ($item as $k => $v) {
                            if (strpos($v['name'], $specialist) !== false) {
                                $result[$hos][$key][] = $v;
                            }
                        }
                    }
                    else {
                        if (strpos($item['name'], $specialist) !== false) {
                            $result[$hos][$key][] = $item;
                        }                        
                    }
                }
            }
        }
    }

    echo (!empty($result)) ? json_encode($result) : json_encode($regionSource);
?>