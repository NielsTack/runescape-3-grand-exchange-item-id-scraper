<?php
$alphabetrange = range('a', 'z');
$categoryrange = range(1,37);
$pagerange = 1;
$items = [];
// loop over category and alphabet to get each item
foreach ($categoryrange as $category) {
    foreach ($alphabetrange as $alphabet) {
        // also loop over each page
        $pagerange = 1;
        $error = false;
        while($error == false){
          
            try {
                $start = microtime(true);
                $json = file_get_contents('https://secure.runescape.com/m=itemdb_rs/api/catalogue/items.json?category='.$category.'&alpha='.$alphabet.'&page='.$pagerange);
                $objects = json_decode($json, true);
                echo nl2br('succesfully parsed category='.$category.'&alpha='.$alphabet.'&page='.$pagerange."\n");
                $time_elapsed_secs = microtime(true) - $start;

                // sleep so content has time to load and not too many requests are made too soon after eachother
                if($time_elapsed_secs < 5){
                    sleep(5-$time_elapsed_secs);
                }

                // debugging
                // echo nl2br($time_elapsed_secs."\n");
                // echo nl2br($objects['items']."\n");
                // echo nl2br(gettype($objects['items'])."\n");
                // echo nl2br(count($objects['items'])."\n");

                // loop over each item in the page and push it as key value pair to items array
                if(count($objects['items']) != '0'){
                    foreach ($objects['items'] as $object ) {
                        echo nl2br('added '.$object['name'].' with id '. $object['id']."\n");
                        $items[$object['name']] = $object['id'];
                    }
                    $pagerange++;
                } else {
                    $error = true;
                }

  
            } catch (Exception $e) {
                $error = true;
                
            }

        }
        

    }
}



// encode items array to json
$json = json_encode($items);

//write json to file
if (file_put_contents("items.json", $json)) {
    echo nl2br("JSON file created successfully...\n");
} else {
    echo nl2br("Oops! Error creating json file...\n");
}

// in case writing to file didnt work for some reason, echo items so you dont have to start over
echo $json


?>