<?php

class versiones {

function ver_change() {
    
    $values=[];

    $value=[
        
        '1.0.0' => '2023-08-10- Sistema base'
        
    ];
    array_push($values,$value);

    return json_encode(['crystalCore-apiUsers'=>$values]);
}

}

?>