<?php
    require_once 'database/db_users.php';
class modelGet {
    public static function getPlaces($dta) {
            
                


        // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
        
            // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
            $conectar = conn();
    
            // Verifica si la conexión se realizó correctamente
            if (!$conectar) {
                return "Error de conexión a la base de datos";
            }
    
            
                

            // Escapa los valores para prevenir inyección SQL
            $clientId = mysqli_real_escape_string($conectar, $dta['clientId']);
            $filter = mysqli_real_escape_string($conectar, $dta['filter']);
            $param = mysqli_real_escape_string($conectar, $dta['param']);
            $value = mysqli_real_escape_string($conectar, $dta['value']);
           
    
            if($filter=="all"){

        
        
                $query= mysqli_query($conectar,"SELECT placeId,clientId,infoPlace FROM generalPlaces where clientId='$clientId'");
            }
            
        
    if($filter=="filter"){
    
            
            
        $query = mysqli_query($conectar, "SELECT placeId, clientId, infoPlace FROM generalPlaces WHERE clientId='$clientId' AND JSON_EXTRACT(infoPlace, '$[0].info.$param') LIKE '%$value%'");

              
    
    }
            if($query){
                $numRows = mysqli_num_rows($query);

if ($numRows > 0) {
                $response="true";
                $message="Consulta exitosa";
                $status="202";
                $apiMessage="¡Repartidores seleccionados ($numRows)!";
                $values=[];

                while ($row = $query->fetch_assoc()) {
                    $value=[
                        'placeId' => $row['placeId'],
                        'clientId' => $row['clientId'],
                        'infoPlace' => json_decode($row['infoPlace'], true)[0]
                    ];
                
                    array_push($values, $value);
                }
                
                $row = $query->fetch_assoc();
               // return json_encode(['products'=>$values]);
                
                // Crear un array separado para el objeto 'response'
                $responseData = [
                    'response' => [
                        'response' => $response,
                        'message' => $message,
                        'apiMessage' => $apiMessage,
                        'status' => $status,
                        'sentData'=>$dta
                    ],
                    'places' => $values
                ];
                
                return json_encode($responseData);
            }else {
                // La consulta no arrojó resultados
                $response="false";
                $message="Error en la consulta";
                $status="204";
                $apiMessage="¡La consulta no produjo resultados, filas seleccionadas ($numRows)!";
                $values=[];
                $value = [
                    
                ];
                $responseData = [
                    'response' => [
                        'response' => $response,
                        'message' => $message,
                        'apiMessage' => $apiMessage,
                        'status' => $status,
                        'sentData'=>$dta
                    ],
                    'places' => $values
                ];
                array_push($values,$value);
                
    
        //echo json_encode($students) ;
        return json_encode($responseData);
            }

            //  return "true";
            //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
            }else{
                $response="false";
                $message="Error en la consulta: " . mysqli_error($conectar);
                $status="404";
                $apiMessage="¡Repartidores no seleccionados con éxito!";
                $values=[];

                $value = [
                    
                ];
                $responseData = [
                    'response' => [
                        'response' => $response,
                        'message' => $message,
                        'apiMessage' => $apiMessage,
                        'status' => $status,
                        'sentData'=>$dta
                    ],
                    'places' => $values
                ];
                array_push($values,$value);
                
    
        //echo json_encode($students) ;
        return json_encode($responseData);
                                }

                                
            
}
public static function getSites($dta) {
            
                


    // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
    
        // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
        $conectar = conn();

        // Verifica si la conexión se realizó correctamente
        if (!$conectar) {
            return "Error de conexión a la base de datos";
        }

        
            

        // Escapa los valores para prevenir inyección SQL
        $clientId = mysqli_real_escape_string($conectar, $dta['clientId']);
        $filter = mysqli_real_escape_string($conectar, $dta['filter']);
        $param = mysqli_real_escape_string($conectar, $dta['param']);
        $value = mysqli_real_escape_string($conectar, $dta['value']);
       

        if($filter=="all"){

    
    
            $query = mysqli_query($conectar, "SELECT s.siteId, s.clientId, s.infoSite, s.placeId, JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS name FROM generalSites s JOIN  generalPlaces p ON p.placeId=s.placeId WHERE s.clientId = '$clientId'");
        }
        
    
if($filter=="filter"){

        if($param=="placeId"){
            $query = mysqli_query($conectar, "SELECT s.siteId, s.clientId, s.infoSite, s.placeId,JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS name FROM generalSites s JOIN generalPlaces p ON p.placeId = s.placeId WHERE s.clientId = '$clientId' AND s.placeId IN (SELECT placeId FROM generalPlaces WHERE clientId = '$clientId' AND JSON_EXTRACT(infoPlace, '$[0].info.name') LIKE '%$value%')");

        }
        else{
    $query = mysqli_query($conectar, "SELECT s.siteId, s.clientId, s.infoSite, s.placeId, JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS name FROM generalSites s JOIN  generalPlaces p ON p.placeId=s.placeId WHERE s.clientId = '$clientId' AND JSON_EXTRACT(s.infoSite, '$[0].info.$param') LIKE '%$value%'");
        }
          

}
        if($query){
            $numRows = mysqli_num_rows($query);

if ($numRows > 0) {
            $response="true";
            $message="Consulta exitosa";
            $status="202";
            $apiMessage="¡Repartidores seleccionados ($numRows)!";
            $values=[];

            while ($row = $query->fetch_assoc()) {
                $value=[
                    'siteId' => $row['siteId'],
                    'clientId' => $row['clientId'],
                    'placeId' => $row['placeId'],
                    'placeName' => json_decode($row['name']),
                    'infoSite' => json_decode($row['infoSite'], true)[0]
                ];
            
                array_push($values, $value);
            }
            
            $row = $query->fetch_assoc();
           // return json_encode(['products'=>$values]);
            
            // Crear un array separado para el objeto 'response'
            $responseData = [
                'response' => [
                    'response' => $response,
                    'message' => $message,
                    'apiMessage' => $apiMessage,
                    'status' => $status,
                    'sentData'=>$dta
                ],
                'sites' => $values
            ];
            
            return json_encode($responseData);
        }else {
            // La consulta no arrojó resultados
            $response="false";
            $message="Error en la consulta";
            $status="204";
            $apiMessage="¡La consulta no produjo resultados, filas seleccionadas ($numRows)!";
            $values=[];
            $value = [
                
            ];
            $responseData = [
                'response' => [
                    'response' => $response,
                    'message' => $message,
                    'apiMessage' => $apiMessage,
                    'status' => $status,
                    'sentData'=>$dta
                ],
                'sites' => $values
            ];
            array_push($values,$value);
            

    //echo json_encode($students) ;
    return json_encode($responseData);
        }

        //  return "true";
        //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
        }else{
            $response="false";
            $message="Error en la consulta: " . mysqli_error($conectar);
            $status="404";
            $apiMessage="¡Repartidores no seleccionados con éxito!";
            $values=[];

            $value = [
                
            ];
            $responseData = [
                'response' => [
                    'response' => $response,
                    'message' => $message,
                    'apiMessage' => $apiMessage,
                    'status' => $status,
                    'sentData'=>$dta
                ],
                'sites' => $values
            ];
            array_push($values,$value);
            

    //echo json_encode($students) ;
    return json_encode($responseData);
                            }

                            
        
}
   
public static function getElements($dta) {
            
                


    // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
    
        // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
        $conectar = conn();

        // Verifica si la conexión se realizó correctamente
        if (!$conectar) {
            return "Error de conexión a la base de datos";
        }

        
            

        // Escapa los valores para prevenir inyección SQL
        $clientId = mysqli_real_escape_string($conectar, $dta['clientId']);
        $filter = mysqli_real_escape_string($conectar, $dta['filter']);
        $param = mysqli_real_escape_string($conectar, $dta['param']);
        $value = mysqli_real_escape_string($conectar, $dta['value']);
       

        if($filter=="all"){

    
    
            $query = mysqli_query($conectar, "SELECT e.elementId, e.clientId, e.infoElement, e.siteId, JSON_EXTRACT(s.infoSite, '$[0].info.name') AS sname,JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS pname FROM generalElements e JOIN  generalSites s ON e.siteId=s.siteId JOIN generalPlaces p ON p.placeId=s.placeId WHERE e.clientId = '$clientId'");
        }
        
    
if($filter=="filter"){

        if($param=="placeId"){
            $query = mysqli_query($conectar, "SELECT e.elementId, e.clientId, e.infoElement, e.siteId, JSON_EXTRACT(s.infoSite, '$[0].info.name') AS sname,JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS pname FROM generalElements e JOIN  generalSites s ON e.siteId=s.siteId JOIN generalPlaces p ON p.placeId=s.placeId WHERE e.clientId = '$clientId' AND s.placeId IN (SELECT placeId FROM generalPlaces WHERE clientId = '$clientId' AND JSON_EXTRACT(infoPlace, '$[0].info.name') LIKE '%$value%')");

        }
        if($param=="siteId"){
            $query = mysqli_query($conectar, "SELECT e.elementId, e.clientId, e.infoElement, e.siteId, JSON_EXTRACT(s.infoSite, '$[0].info.name') AS sname,JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS pname FROM generalElements e JOIN  generalSites s ON e.siteId=s.siteId JOIN generalPlaces p ON p.placeId=s.placeId WHERE e.clientId = '$clientId' AND s.siteId  IN (SELECT siteId FROM generalSites WHERE clientId = '$clientId' AND JSON_EXTRACT(infoSite, '$[0].info.name') LIKE '%$value%')");

        }
        else{
            $query = mysqli_query($conectar, "SELECT e.elementId, e.clientId, e.infoElement, e.siteId, JSON_EXTRACT(s.infoSite, '$[0].info.name') AS sname,JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS pname FROM generalElements e JOIN  generalSites s ON e.siteId=s.siteId JOIN generalPlaces p ON p.placeId=s.placeId WHERE e.clientId = '$clientId' AND JSON_EXTRACT(e.infoElement, '$[0].info.$param') LIKE '%$value%'");
        }
          

}
        if($query){
            $numRows = mysqli_num_rows($query);

if ($numRows > 0) {
            $response="true";
            $message="Consulta exitosa";
            $status="202";
            $apiMessage="¡Repartidores seleccionados ($numRows)!";
            $values=[];

            while ($row = $query->fetch_assoc()) {
                $value=[
                    'elementId' => $row['elementId'],
                    'clientId' => $row['clientId'],
                    'siteId' => $row['siteId'],
                    'siteName' => json_decode($row['sname']),
                    'placeName' => json_decode($row['pname']),
                    'infoElement' => json_decode($row['infoElement'], true)[0]
                ];
            
                array_push($values, $value);
            }
            
            $row = $query->fetch_assoc();
           // return json_encode(['products'=>$values]);
            
            // Crear un array separado para el objeto 'response'
            $responseData = [
                'response' => [
                    'response' => $response,
                    'message' => $message,
                    'apiMessage' => $apiMessage,
                    'status' => $status,
                    'sentData'=>$dta
                ],
                'elements' => $values
            ];
            
            return json_encode($responseData);
        }else {
            // La consulta no arrojó resultados
            $response="false";
            $message="Error en la consulta";
            $status="204";
            $apiMessage="¡La consulta no produjo resultados, filas seleccionadas ($numRows)!";
            $values=[];
            $value = [
                
            ];
            $responseData = [
                'response' => [
                    'response' => $response,
                    'message' => $message,
                    'apiMessage' => $apiMessage,
                    'status' => $status,
                    'sentData'=>$dta
                ],
                'elements' => $values
            ];
            array_push($values,$value);
            

    //echo json_encode($students) ;
    return json_encode($responseData);
        }

        //  return "true";
        //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
        }else{
            $response="false";
            $message="Error en la consulta: " . mysqli_error($conectar);
            $status="404";
            $apiMessage="¡Repartidores no seleccionados con éxito!";
            $values=[];

            $value = [
                
            ];
            $responseData = [
                'response' => [
                    'response' => $response,
                    'message' => $message,
                    'apiMessage' => $apiMessage,
                    'status' => $status,
                    'sentData'=>$dta
                ],
                'elements' => $values
            ];
            array_push($values,$value);
            

    //echo json_encode($students) ;
    return json_encode($responseData);
                            }

                            
        
}

public static function getProducts($dta) {
            
                


    // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
    
        // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
        $conectar = conn();

        // Verifica si la conexión se realizó correctamente
        if (!$conectar) {
            return "Error de conexión a la base de datos";
        }

        
            

        // Escapa los valores para prevenir inyección SQL
        $clientId = mysqli_real_escape_string($conectar, $dta['clientId']);
        $filter = mysqli_real_escape_string($conectar, $dta['filter']);
        $param = mysqli_real_escape_string($conectar, $dta['param']);
        $value = mysqli_real_escape_string($conectar, $dta['value']);
       

        if($filter=="all"){

    
    
            $query= mysqli_query($conectar,"SELECT prodservId, clientId, infoProdServ FROM generalProdServ WHERE clientId='$clientId'");
        }
        
    
if($filter=="filter"){

        
        
    $query = mysqli_query($conectar, "SELECT prodservId, clientId, infoProdServ FROM generalProdServ WHERE clientId='$clientId' AND JSON_EXTRACT(infoProdServ, '$[0].info.$param') LIKE '%$value%'");

          

}
        if($query){
            $numRows = mysqli_num_rows($query);

if ($numRows > 0) {
            $response="true";
            $message="Consulta exitosa";
            $status="202";
            $apiMessage="¡Repartidores seleccionados ($numRows)!";
            $values=[];

            while ($row = $query->fetch_assoc()) {
                $value=[
                    'productId' => $row['prodservId'],
                    'clientId' => $row['clientId'],
                    'infoProduct' => json_decode($row['infoProdServ'], true)[0]
                ];
            
                array_push($values, $value);
            }
            
            $row = $query->fetch_assoc();
           // return json_encode(['products'=>$values]);
            
            // Crear un array separado para el objeto 'response'
            $responseData = [
                'response' => [
                    'response' => $response,
                    'message' => $message,
                    'apiMessage' => $apiMessage,
                    'status' => $status,
                    'sentData'=>$dta
                ],
                'products' => $values
            ];
            
            return json_encode($responseData);
        }else {
            // La consulta no arrojó resultados
            $response="false";
            $message="Error en la consulta";
            $status="204";
            $apiMessage="¡La consulta no produjo resultados, filas seleccionadas ($numRows)!";
            $values=[];
            $value = [
                
            ];
            $responseData = [
                'response' => [
                    'response' => $response,
                    'message' => $message,
                    'apiMessage' => $apiMessage,
                    'status' => $status,
                    'sentData'=>$dta
                ],
                'products' => $values
            ];
            array_push($values,$value);
            

    //echo json_encode($students) ;
    return json_encode($responseData);
        }

        //  return "true";
        //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
        }else{
            $response="false";
            $message="Error en la consulta: " . mysqli_error($conectar);
            $status="404";
            $apiMessage="¡Repartidores no seleccionados con éxito!";
            $values=[];

            $value = [
                
            ];
            $responseData = [
                'response' => [
                    'response' => $response,
                    'message' => $message,
                    'apiMessage' => $apiMessage,
                    'status' => $status,
                    'sentData'=>$dta
                ],
                'products' => $values
            ];
            array_push($values,$value);
            

    //echo json_encode($students) ;
    return json_encode($responseData);
                            }

                            
        
}
       
    }


    
?>