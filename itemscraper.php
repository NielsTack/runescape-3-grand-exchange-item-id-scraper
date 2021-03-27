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
                echo ('parsing https://secure.runescape.com/m=itemdb_rs/api/catalogue/items.json?category='.$category.'&alpha='.$alphabet.'&page='.$pagerange."\n\n");
                $json = file_get_contents('https://secure.runescape.com/m=itemdb_rs/api/catalogue/items.json?category='.$category.'&alpha='.$alphabet.'&page='.$pagerange);
                $objects = json_decode($json, true);
                echo ('succesfully parsed category='.$category.'&alpha='.$alphabet.'&page='.$pagerange."\n\n");
    
                // loop over each item in the page and push it as key value pair to items array
                if($objects['items'][0]['name'] != NULL){
                    foreach ($objects['items'] as $object ) {
                        echo ('added '.$object['name'].' with id '. $object['id']."\n\n");
                        $items[$object['name']] = $object['id'];
                    }
                    $pagerange++;
                } else {
                    $error = true;
                }
  
            } catch (Exception $e) {
                $error = true;
                echo 'Caught exception: ',  $e->getMessage(), "\n\n";
                
            }
        }
        

    }
}

// encode items array to json
$json = json_encode($items);

//write json to file
if (file_put_contents("items.json", $json)) {
    echo "JSON file created successfully...\n\n";
} else {
    echo "Oops! Error creating json file...\n\n";
}

// in case writing to file didnt work for some reason, echo items so you dont have to start over
echo $json

?>