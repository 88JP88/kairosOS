<?php

require 'flight/Flight.php';
require_once 'database/db_users.php';
require_once 'model/users/postModel.php';
require_once 'model/users/getModel.php';
require_once 'model/users/responses.php';
require 'model/modelSecurity/authModule.php';
require_once 'env/domain.php';

require_once 'kronos/postLog.php';


Flight::route('GET /pp', function () {
echo "hello";

});

Flight::route('POST /postPlace/@apk/@xapk', function ($apk,$xapk) {
        
          
                   
           header("Access-Control-Allow-Origin: *");
           // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
           if (!empty($apk) && !empty($xapk)) {    
           


               $response11=modelAuth::authModel($apk,$xapk);//AUTH MODULE

               $postData = Flight::request()->data->getData();
               $dt=json_encode($postData);


               if ($response11 == 'true' ) {

               $query= modelPost::postPlace($postData);  //DATA MODAL

           //JSON DECODE RESPPNSE
               $data = json_decode($query, true);
               $responseSQL=$data['response'][0]['response'];
               $messageSQL=$data['response'][0]['message'];
               $apiMessageSQL=$data['response'][0]['apiMessage'];
               $apiStatusSQL=$data['response'][0]['status'];
               //JSON DECODE**

               } else {
                   $responseSQL="false";
                   $apiMessageSQL="¡Autenticación fallida!";
                   $apiStatusSQL="401";
                   $messageSQL="¡Autenticación fallida!";

               }
           } else {

               $responseSQL="false";
               $apiMessageSQL="¡Encabezados faltantes!";
               $apiStatusSQL="403";
               $messageSQL="¡Encabezados faltantes!";
           }

       
               kronos($responseSQL,$apiMessageSQL,$apiMessageSQL,Flight::request()->data->clientId,$dt,Flight::request()->url,'RECEIVED',Flight::request()->data->trackId);  //LOG FUNCTION  
       
       echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

});


Flight::route('POST /putPlace/@apk/@xapk', function ($apk,$xapk) {
        
          
                   
    header("Access-Control-Allow-Origin: *");
    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
    if (!empty($apk) && !empty($xapk)) {    
    


        $response11=modelAuth::authModel($apk,$xapk);//AUTH MODULE

        $postData = Flight::request()->data->getData();
        $dt=json_encode($postData);


        if ($response11 == 'true' ) {

        $query= modelPut::putPlace($postData);  //DATA MODAL

    //JSON DECODE RESPPNSE
        $data = json_decode($query, true);
        $responseSQL=$data['response'][0]['response'];
        $messageSQL=$data['response'][0]['message'];
        $apiMessageSQL=$data['response'][0]['apiMessage'];
        $apiStatusSQL=$data['response'][0]['status'];
        //JSON DECODE**

        } else {
            $responseSQL="false";
            $apiMessageSQL="¡Autenticación fallida!";
            $apiStatusSQL="401";
            $messageSQL="¡Autenticación fallida!";

        }
    } else {

        $responseSQL="false";
        $apiMessageSQL="¡Encabezados faltantes!";
        $apiStatusSQL="403";
        $messageSQL="¡Encabezados faltantes!";
    }


        kronos($responseSQL,$apiMessageSQL,$apiMessageSQL,Flight::request()->data->clientId,$dt,Flight::request()->url,'RECEIVED',Flight::request()->data->trackId);  //LOG FUNCTION  

echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

});



Flight::route('GET /getPlaces/@apiData', function ($apiData) {
    header("Access-Control-Allow-Origin: *");
    // Leer los encabezados
    $headers = getallheaders();
   
    $postData = json_decode($apiData, true);

    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
    if (isset($headers['Api-Key']) ) {
        // Leer los datos de la solicitud
       
        // Acceder a los encabezados
        $apiKey = $headers['Api-Key'];
        $xApiKey = $headers['x-api-Key'];

        $response1=modelAuth::authModel($apiKey,$xApiKey);//AUTH MODULE

        if ($response1 == 'true' ) {
           
          
//echo $apiData;
echo modelGet::getPlaces($postData);
           

}else { 
    
    $responseSQL="false";
    $apiMessageSQL="¡Autenticación fallida!";
    $apiStatusSQL="401";
    $messageSQL="¡Autenticación fallida!";
    echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

}
} else {

$responseSQL="false";
$apiMessageSQL="¡Encabezados faltantes!";
$apiStatusSQL="403";
$messageSQL="¡Encabezados faltantes!";
echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

}
});



Flight::route('GET /getCatalogs/@apiData', function ($apiData) {
    header("Access-Control-Allow-Origin: *");
    // Leer los encabezados
    $headers = getallheaders();
   
    $postData = json_decode($apiData, true);

    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
    if (isset($headers['Api-Key']) ) {
        // Leer los datos de la solicitud
       
        // Acceder a los encabezados
        $apiKey = $headers['Api-Key'];
        $xApiKey = $headers['x-api-Key'];

        $response1=modelAuth::authModel($apiKey,$xApiKey);//AUTH MODULE

        if ($response1 == 'true' ) {
           
          
//echo $apiData;
echo modelGet::getCatalogs($postData);
           

}else { 
    
    $responseSQL="false";
    $apiMessageSQL="¡Autenticación fallida!";
    $apiStatusSQL="401";
    $messageSQL="¡Autenticación fallida!";
    echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

}
} else {

$responseSQL="false";
$apiMessageSQL="¡Encabezados faltantes!";
$apiStatusSQL="403";
$messageSQL="¡Encabezados faltantes!";
echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

}
});



Flight::route('POST /postSite/@apk/@xapk', function ($apk,$xapk) {
        
          
                   
    header("Access-Control-Allow-Origin: *");
    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
    if (!empty($apk) && !empty($xapk)) {    
    


        $response11=modelAuth::authModel($apk,$xapk);//AUTH MODULE

        $postData = Flight::request()->data->getData();
        $dt=json_encode($postData);


        if ($response11 == 'true' ) {

        $query= modelPost::postSite($postData);  //DATA MODAL

    //JSON DECODE RESPPNSE
        $data = json_decode($query, true);
        $responseSQL=$data['response'][0]['response'];
        $messageSQL=$data['response'][0]['message'];
        $apiMessageSQL=$data['response'][0]['apiMessage'];
        $apiStatusSQL=$data['response'][0]['status'];
        //JSON DECODE**

        } else {
            $responseSQL="false";
            $apiMessageSQL="¡Autenticación fallida!";
            $apiStatusSQL="401";
            $messageSQL="¡Autenticación fallida!";

        }
    } else {

        $responseSQL="false";
        $apiMessageSQL="¡Encabezados faltantes!";
        $apiStatusSQL="403";
        $messageSQL="¡Encabezados faltantes!";
    }


        kronos($responseSQL,$apiMessageSQL,$apiMessageSQL,Flight::request()->data->clientId,$dt,Flight::request()->url,'RECEIVED',Flight::request()->data->trackId);  //LOG FUNCTION  

echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

});

Flight::route('GET /getSites/@apiData', function ($apiData) {
    header("Access-Control-Allow-Origin: *");
    // Leer los encabezados
    $headers = getallheaders();
   
    $postData = json_decode($apiData, true);

    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
    if (isset($headers['Api-Key']) ) {
        // Leer los datos de la solicitud
       
        // Acceder a los encabezados
        $apiKey = $headers['Api-Key'];
        $xApiKey = $headers['x-api-Key'];

        $response1=modelAuth::authModel($apiKey,$xApiKey);//AUTH MODULE

        if ($response1 == 'true' ) {
           
          
//echo $apiData;
echo modelGet::getSites($postData);
           

}else { 
    
    $responseSQL="false";
    $apiMessageSQL="¡Autenticación fallida!";
    $apiStatusSQL="401";
    $messageSQL="¡Autenticación fallida!";
    echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

}
} else {

$responseSQL="false";
$apiMessageSQL="¡Encabezados faltantes!";
$apiStatusSQL="403";
$messageSQL="¡Encabezados faltantes!";
echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

}
});


Flight::route('GET /getProducts/@apiData', function ($apiData) {
    header("Access-Control-Allow-Origin: *");
    // Leer los encabezados
    $headers = getallheaders();
   
    $postData = json_decode($apiData, true);

    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
    if (isset($headers['Api-Key']) ) {
        // Leer los datos de la solicitud
       
        // Acceder a los encabezados
        $apiKey = $headers['Api-Key'];
        $xApiKey = $headers['x-api-Key'];

        $response1=modelAuth::authModel($apiKey,$xApiKey);//AUTH MODULE

        if ($response1 == 'true' ) {
           
          

echo modelGet::getProducts($postData);
           

}else { 
    
    $responseSQL="false";
    $apiMessageSQL="¡Autenticación fallida!";
    $apiStatusSQL="401";
    $messageSQL="¡Autenticación fallida!";
    echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

}
} else {

$responseSQL="false";
$apiMessageSQL="¡Encabezados faltantes!";
$apiStatusSQL="403";
$messageSQL="¡Encabezados faltantes!";
echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

}
});



Flight::route('POST /putSite/@apk/@xapk', function ($apk,$xapk) {
        
          
                   
    header("Access-Control-Allow-Origin: *");
    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
    if (!empty($apk) && !empty($xapk)) {    
    


        $response11=modelAuth::authModel($apk,$xapk);//AUTH MODULE

        $postData = Flight::request()->data->getData();
        $dt=json_encode($postData);


        if ($response11 == 'true' ) {

        $query= modelPut::putSite($postData);  //DATA MODAL

    //JSON DECODE RESPPNSE
        $data = json_decode($query, true);
        $responseSQL=$data['response'][0]['response'];
        $messageSQL=$data['response'][0]['message'];
        $apiMessageSQL=$data['response'][0]['apiMessage'];
        $apiStatusSQL=$data['response'][0]['status'];
        //JSON DECODE**

        } else {
            $responseSQL="false";
            $apiMessageSQL="¡Autenticación fallida!";
            $apiStatusSQL="401";
            $messageSQL="¡Autenticación fallida!";

        }
    } else {

        $responseSQL="false";
        $apiMessageSQL="¡Encabezados faltantes!";
        $apiStatusSQL="403";
        $messageSQL="¡Encabezados faltantes!";
    }


        kronos($responseSQL,$apiMessageSQL,$apiMessageSQL,Flight::request()->data->clientId,$dt,Flight::request()->url,'RECEIVED',Flight::request()->data->trackId);  //LOG FUNCTION  

echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

});



Flight::route('POST /putCatalog/@apk/@xapk', function ($apk,$xapk) {
        
          
                   
    header("Access-Control-Allow-Origin: *");
    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
    if (!empty($apk) && !empty($xapk)) {    
    


        $response11=modelAuth::authModel($apk,$xapk);//AUTH MODULE

        $postData = Flight::request()->data->getData();
        $dt=json_encode($postData);


        if ($response11 == 'true' ) {

        $query= modelPut::putCatalog($postData);  //DATA MODAL

    //JSON DECODE RESPPNSE
        $data = json_decode($query, true);
        $responseSQL=$data['response'][0]['response'];
        $messageSQL=$data['response'][0]['message'];
        $apiMessageSQL=$data['response'][0]['apiMessage'];
        $apiStatusSQL=$data['response'][0]['status'];
        //JSON DECODE**

        } else {
            $responseSQL="false";
            $apiMessageSQL="¡Autenticación fallida!";
            $apiStatusSQL="401";
            $messageSQL="¡Autenticación fallida!";

        }
    } else {

        $responseSQL="false";
        $apiMessageSQL="¡Encabezados faltantes!";
        $apiStatusSQL="403";
        $messageSQL="¡Encabezados faltantes!";
    }


        kronos($responseSQL,$apiMessageSQL,$apiMessageSQL,Flight::request()->data->clientId,$dt,Flight::request()->url,'RECEIVED',Flight::request()->data->trackId);  //LOG FUNCTION  

echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

});

Flight::route('POST /putElement/@apk/@xapk', function ($apk,$xapk) {
        
          
                   
    header("Access-Control-Allow-Origin: *");
    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
    if (!empty($apk) && !empty($xapk)) {    
    


        $response11=modelAuth::authModel($apk,$xapk);//AUTH MODULE

        $postData = Flight::request()->data->getData();
        $dt=json_encode($postData);


        if ($response11 == 'true' ) {

        $query= modelPut::putElement($postData);  //DATA MODAL

    //JSON DECODE RESPPNSE
        $data = json_decode($query, true);
        $responseSQL=$data['response'][0]['response'];
        $messageSQL=$data['response'][0]['message'];
        $apiMessageSQL=$data['response'][0]['apiMessage'];
        $apiStatusSQL=$data['response'][0]['status'];
        //JSON DECODE**

        } else {
            $responseSQL="false";
            $apiMessageSQL="¡Autenticación fallida!";
            $apiStatusSQL="401";
            $messageSQL="¡Autenticación fallida!";

        }
    } else {

        $responseSQL="false";
        $apiMessageSQL="¡Encabezados faltantes!";
        $apiStatusSQL="403";
        $messageSQL="¡Encabezados faltantes!";
    }


        kronos($responseSQL,$apiMessageSQL,$apiMessageSQL,Flight::request()->data->clientId,$dt,Flight::request()->url,'RECEIVED',Flight::request()->data->trackId);  //LOG FUNCTION  

echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

});



Flight::route('POST /putProduct/@apk/@xapk', function ($apk,$xapk) {
        
          
                   
    header("Access-Control-Allow-Origin: *");
    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
    if (!empty($apk) && !empty($xapk)) {    
    


        $response11=modelAuth::authModel($apk,$xapk);//AUTH MODULE

        $postData = Flight::request()->data->getData();
        $dt=json_encode($postData);


        if ($response11 == 'true' ) {

        $query= modelPut::putProduct($postData);  //DATA MODAL

    //JSON DECODE RESPPNSE
        $data = json_decode($query, true);
        $responseSQL=$data['response'][0]['response'];
        $messageSQL=$data['response'][0]['message'];
        $apiMessageSQL=$data['response'][0]['apiMessage'];
        $apiStatusSQL=$data['response'][0]['status'];
        //JSON DECODE**

        } else {
            $responseSQL="false";
            $apiMessageSQL="¡Autenticación fallida!";
            $apiStatusSQL="401";
            $messageSQL="¡Autenticación fallida!";

        }
    } else {

        $responseSQL="false";
        $apiMessageSQL="¡Encabezados faltantes!";
        $apiStatusSQL="403";
        $messageSQL="¡Encabezados faltantes!";
    }


        kronos($responseSQL,$apiMessageSQL,$apiMessageSQL,Flight::request()->data->clientId,$dt,Flight::request()->url,'RECEIVED',Flight::request()->data->trackId);  //LOG FUNCTION  

echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

});


Flight::route('POST /postElement/@apk/@xapk', function ($apk,$xapk) {
        
          
                   
    header("Access-Control-Allow-Origin: *");
    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
    if (!empty($apk) && !empty($xapk)) {    
    


        $response11=modelAuth::authModel($apk,$xapk);//AUTH MODULE

        $postData = Flight::request()->data->getData();
        $dt=json_encode($postData);


        if ($response11 == 'true' ) {

        $query= modelPost::postElement($postData);  //DATA MODAL

    //JSON DECODE RESPPNSE
        $data = json_decode($query, true);
        $responseSQL=$data['response'][0]['response'];
        $messageSQL=$data['response'][0]['message'];
        $apiMessageSQL=$data['response'][0]['apiMessage'];
        $apiStatusSQL=$data['response'][0]['status'];
        //JSON DECODE**

        } else {
            $responseSQL="false";
            $apiMessageSQL="¡Autenticación fallida!";
            $apiStatusSQL="401";
            $messageSQL="¡Autenticación fallida!";

        }
    } else {

        $responseSQL="false";
        $apiMessageSQL="¡Encabezados faltantes!";
        $apiStatusSQL="403";
        $messageSQL="¡Encabezados faltantes!";
    }


        kronos($responseSQL,$apiMessageSQL,$apiMessageSQL,Flight::request()->data->clientId,$dt,Flight::request()->url,'RECEIVED',Flight::request()->data->trackId);  //LOG FUNCTION  

echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

});



Flight::route('GET /getElements/@apiData', function ($apiData) {
    header("Access-Control-Allow-Origin: *");
    // Leer los encabezados
    $headers = getallheaders();
   
    $postData = json_decode($apiData, true);

    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
    if (isset($headers['Api-Key']) ) {
        // Leer los datos de la solicitud
       
        // Acceder a los encabezados
        $apiKey = $headers['Api-Key'];
        $xApiKey = $headers['x-api-Key'];

        $response1=modelAuth::authModel($apiKey,$xApiKey);//AUTH MODULE

        if ($response1 == 'true' ) {
           
          
//echo $apiData;
echo modelGet::getElements($postData);
           

}else { 
    
    $responseSQL="false";
    $apiMessageSQL="¡Autenticación fallida!";
    $apiStatusSQL="401";
    $messageSQL="¡Autenticación fallida!";
    echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

}
} else {

$responseSQL="false";
$apiMessageSQL="¡Encabezados faltantes!";
$apiStatusSQL="403";
$messageSQL="¡Encabezados faltantes!";
echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

}
});




Flight::route('GET /getCategories/@apiData', function ($apiData) {
    header("Access-Control-Allow-Origin: *");
    // Leer los encabezados
    $headers = getallheaders();
   
    $postData = json_decode($apiData, true);

    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
    if (isset($headers['Api-Key']) ) {
        // Leer los datos de la solicitud
       
        // Acceder a los encabezados
        $apiKey = $headers['Api-Key'];
        $xApiKey = $headers['x-api-Key'];

        $response1=modelAuth::authModel($apiKey,$xApiKey);//AUTH MODULE

        if ($response1 == 'true' ) {
           
          
//echo $apiData;
echo modelGet::getCategories($postData);
           

}else { 
    
    $responseSQL="false";
    $apiMessageSQL="¡Autenticación fallida!";
    $apiStatusSQL="401";
    $messageSQL="¡Autenticación fallida!";
    echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

}
} else {

$responseSQL="false";
$apiMessageSQL="¡Encabezados faltantes!";
$apiStatusSQL="403";
$messageSQL="¡Encabezados faltantes!";
echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

}
});



Flight::route('POST /postProduct/@apk/@xapk', function ($apk,$xapk) {
        
          
                   
    header("Access-Control-Allow-Origin: *");
    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
    if (!empty($apk) && !empty($xapk)) {    
    


        $response11=modelAuth::authModel($apk,$xapk);//AUTH MODULE

        $postData = Flight::request()->data->getData();
        $dt=json_encode($postData);


        if ($response11 == 'true' ) {

        $query= modelPost::postProduct($postData);  //DATA MODAL

    //JSON DECODE RESPPNSE
        $data = json_decode($query, true);
        $responseSQL=$data['response'][0]['response'];
        $messageSQL=$data['response'][0]['message'];
        $apiMessageSQL=$data['response'][0]['apiMessage'];
        $apiStatusSQL=$data['response'][0]['status'];
        //JSON DECODE**

        } else {
            $responseSQL="false";
            $apiMessageSQL="¡Autenticación fallida!";
            $apiStatusSQL="401";
            $messageSQL="¡Autenticación fallida!";

        }
    } else {

        $responseSQL="false";
        $apiMessageSQL="¡Encabezados faltantes!";
        $apiStatusSQL="403";
        $messageSQL="¡Encabezados faltantes!";
    }


        kronos($responseSQL,$apiMessageSQL,$apiMessageSQL,Flight::request()->data->clientId,$dt,Flight::request()->url,'RECEIVED',Flight::request()->data->trackId);  //LOG FUNCTION  

echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

});




Flight::route('POST /postCategory/@apk/@xapk', function ($apk,$xapk) {
        
          
                   
    header("Access-Control-Allow-Origin: *");
    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
    if (!empty($apk) && !empty($xapk)) {    
    


        $response11=modelAuth::authModel($apk,$xapk);//AUTH MODULE

        $postData = Flight::request()->data->getData();
        $dt=json_encode($postData);


        if ($response11 == 'true' ) {

        $query= modelPost::postCategory($postData);  //DATA MODAL

    //JSON DECODE RESPPNSE
        $data = json_decode($query, true);
        $responseSQL=$data['response'][0]['response'];
        $messageSQL=$data['response'][0]['message'];
        $apiMessageSQL=$data['response'][0]['apiMessage'];
        $apiStatusSQL=$data['response'][0]['status'];
        //JSON DECODE**

        } else {
            $responseSQL="false";
            $apiMessageSQL="¡Autenticación fallida!";
            $apiStatusSQL="401";
            $messageSQL="¡Autenticación fallida!";

        }
    } else {

        $responseSQL="false";
        $apiMessageSQL="¡Encabezados faltantes!";
        $apiStatusSQL="403";
        $messageSQL="¡Encabezados faltantes!";
    }


        kronos($responseSQL,$apiMessageSQL,$apiMessageSQL,Flight::request()->data->clientId,$dt,Flight::request()->url,'RECEIVED',Flight::request()->data->trackId);  //LOG FUNCTION  

echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

});


Flight::route('POST /postOrder/@apk/@xapk', function ($apk,$xapk) {
        
          
                   
    header("Access-Control-Allow-Origin: *");
    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
    if (!empty($apk) && !empty($xapk)) {    
    


        $response11=modelAuth::authModel($apk,$xapk);//AUTH MODULE

        $postData = Flight::request()->data->getData();
        $dt=json_encode($postData);


        if ($response11 == 'true' ) {

        $query= modelPost::postOrder($postData);  //DATA MODAL

    //JSON DECODE RESPPNSE
        $data = json_decode($query, true);
        $responseSQL=$data['response'][0]['response'];
        $messageSQL=$data['response'][0]['message'];
        $apiMessageSQL=$data['response'][0]['apiMessage'];
        $apiStatusSQL=$data['response'][0]['status'];
        //JSON DECODE**

        } else {
            $responseSQL="false";
            $apiMessageSQL="¡Autenticación fallida!";
            $apiStatusSQL="401";
            $messageSQL="¡Autenticación fallida!";

        }
    } else {

        $responseSQL="false";
        $apiMessageSQL="¡Encabezados faltantes!";
        $apiStatusSQL="403";
        $messageSQL="¡Encabezados faltantes!";
    }


        kronos($responseSQL,$apiMessageSQL,$apiMessageSQL,Flight::request()->data->clientId,$dt,Flight::request()->url,'RECEIVED',Flight::request()->data->trackId);  //LOG FUNCTION  

echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

});

Flight::route('POST /putCategory/@apk/@xapk', function ($apk,$xapk) {
        
          
                   
    header("Access-Control-Allow-Origin: *");
    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
    if (!empty($apk) && !empty($xapk)) {    
    


        $response11=modelAuth::authModel($apk,$xapk);//AUTH MODULE

        $postData = Flight::request()->data->getData();
        $dt=json_encode($postData);


        if ($response11 == 'true' ) {

        $query= modelPut::putCategory($postData);  //DATA MODAL

    //JSON DECODE RESPPNSE
        $data = json_decode($query, true);
        $responseSQL=$data['response'][0]['response'];
        $messageSQL=$data['response'][0]['message'];
        $apiMessageSQL=$data['response'][0]['apiMessage'];
        $apiStatusSQL=$data['response'][0]['status'];
        //JSON DECODE**

        } else {
            $responseSQL="false";
            $apiMessageSQL="¡Autenticación fallida!";
            $apiStatusSQL="401";
            $messageSQL="¡Autenticación fallida!";

        }
    } else {

        $responseSQL="false";
        $apiMessageSQL="¡Encabezados faltantes!";
        $apiStatusSQL="403";
        $messageSQL="¡Encabezados faltantes!";
    }


        kronos($responseSQL,$apiMessageSQL,$apiMessageSQL,Flight::request()->data->clientId,$dt,Flight::request()->url,'RECEIVED',Flight::request()->data->trackId);  //LOG FUNCTION  

echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

});


Flight::route('POST /postCatalog/@apk/@xapk', function ($apk,$xapk) {
        
          
                   
    header("Access-Control-Allow-Origin: *");
    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
    if (!empty($apk) && !empty($xapk)) {    
    


        $response11=modelAuth::authModel($apk,$xapk);//AUTH MODULE

        $postData = Flight::request()->data->getData();
        $dt=json_encode($postData);


        if ($response11 == 'true' ) {

        $query= modelPost::postCatalog($postData);  //DATA MODAL

    //JSON DECODE RESPPNSE
        $data = json_decode($query, true);
        $responseSQL=$data['response'][0]['response'];
        $messageSQL=$data['response'][0]['message'];
        $apiMessageSQL=$data['response'][0]['apiMessage'];
        $apiStatusSQL=$data['response'][0]['status'];
        //JSON DECODE**

        } else {
            $responseSQL="false";
            $apiMessageSQL="¡Autenticación fallida!";
            $apiStatusSQL="401";
            $messageSQL="¡Autenticación fallida!";

        }
    } else {

        $responseSQL="false";
        $apiMessageSQL="¡Encabezados faltantes!";
        $apiStatusSQL="403";
        $messageSQL="¡Encabezados faltantes!";
    }


        kronos($responseSQL,$apiMessageSQL,$apiMessageSQL,Flight::request()->data->clientId,$dt,Flight::request()->url,'RECEIVED',Flight::request()->data->trackId);  //LOG FUNCTION  

echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

});



Flight::route('GET /getOrders/@apiData', function ($apiData) {
    header("Access-Control-Allow-Origin: *");
    // Leer los encabezados
    $headers = getallheaders();
   
    $postData = json_decode($apiData, true);

    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
    if (isset($headers['Api-Key']) ) {
        // Leer los datos de la solicitud
       
        // Acceder a los encabezados
        $apiKey = $headers['Api-Key'];
        $xApiKey = $headers['x-api-Key'];

        $response1=modelAuth::authModel($apiKey,$xApiKey);//AUTH MODULE

        if ($response1 == 'true' ) {
           
          
//echo $apiData;
echo modelGet::getOrders($postData);
           

}else { 
    
    $responseSQL="false";
    $apiMessageSQL="¡Autenticación fallida!";
    $apiStatusSQL="401";
    $messageSQL="¡Autenticación fallida!";
    echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

}
} else {

$responseSQL="false";
$apiMessageSQL="¡Encabezados faltantes!";
$apiStatusSQL="403";
$messageSQL="¡Encabezados faltantes!";
echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

}
});

Flight::start();
