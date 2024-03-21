<?php
require_once 'env/domain.php';
class modelAuth {
        
    public static function authModel($apk,$xapk) {
 
          // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
      
         // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
        
   $sub_domaincon=new model_domain();
   $sub_domain=$sub_domaincon->domKairos();


        $url = $sub_domain.'/kairosCore/apiAuth/v1/authApiKey/';
    
        $data = array(
            'apiKey' =>$apk, 
            'xApiKey' => $xapk
        
        );
    $curl = curl_init();
    
    // Configurar las opciones de la sesión cURL
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    
    // Ejecutar la solicitud y obtener la respuesta
    $response11 = curl_exec($curl);

    curl_close($curl);

    if($response11=="true"){
        return "true";
    }
    if($response11!="true"){
        return "false";
    }
        
     }


 }
 

?>