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
        if($param=="placeIdCar"){
            $query = mysqli_query($conectar, "SELECT s.siteId, s.clientId, s.infoSite, s.placeId,JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS name FROM generalSites s JOIN generalPlaces p ON p.placeId = s.placeId WHERE s.clientId = '$clientId' AND s.placeId = '$value'");

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




public static function getCategories($dta) {
            
                


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

    
    
            $query= mysqli_query($conectar,"SELECT 
            c.categoryId, 
            c.clientId, 
            c.infoCategory,
            c.parentId,
            JSON_UNQUOTE(JSON_EXTRACT(c.infoCategory, '$[0].info.name')) AS catName, 
            (SELECT JSON_UNQUOTE(JSON_EXTRACT(gc.infoCategory, '$[0].info.name')) 
             FROM generalCategories gc 
             WHERE gc.categoryId =c.parentId
             LIMIT 1
            ) AS parentName,
            (SELECT gc.infoCategory
             FROM generalCategories gc 
             WHERE gc.categoryId =c.parentId
             LIMIT 1
            ) AS parentInfo
        FROM 
            generalCategories c 
        WHERE 
            c.clientId = '$clientId'");
        }
        
    
if($filter=="filter"){

        
        
    $query= mysqli_query($conectar,"SELECT 
    c.categoryId, 
    c.clientId, 
    c.infoCategory,
    c.parentId,
    JSON_UNQUOTE(JSON_EXTRACT(c.infoCategory, '$[0].info.name')) AS catName, 
    (SELECT JSON_UNQUOTE(JSON_EXTRACT(gc.infoCategory, '$[0].info.name')) 
     FROM generalCategories gc 
     WHERE gc.categoryId =c.parentId
     LIMIT 1
    ) AS parentName,
    (SELECT gc.infoCategory
     FROM generalCategories gc 
     WHERE gc.categoryId =c.parentId
     LIMIT 1
    ) AS parentInfo
FROM 
    generalCategories c 
WHERE 
    c.clientId = '$clientId' AND JSON_EXTRACT(c.infoCategory, '$[0].info.$param') LIKE '%$value%'");
          

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
                    'categoryId' => $row['categoryId'],
                    'clientId' => $row['clientId'],
                    'parentId' => $row['parentId'],
                    'parentName' => $row['parentName'],
                    'parentInfo' => json_decode($row['parentInfo'],true)[0],
                  
                    'infoCategory' => json_decode($row['infoCategory'], true)[0]
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
                'categories' => $values
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
                'categories' => $values
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
                'categories' => $values
            ];
            array_push($values,$value);
            

    //echo json_encode($students) ;
    return json_encode($responseData);
                            }

                            
        
}
       




public static function getCatalogs($dta) {
            
                


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

    
    
            $query = mysqli_query($conectar, "SELECT c.catalogId,c.clientId,c.productId,c.placeId,c.categoryId, c.infoCatalog,p.infoPlace AS infoPlace,pr.infoProdserv AS productInfo,ct.infoCategory AS categoryInfo FROM generalCatalogs c JOIN  generalPlaces p ON c.placeId=p.placeId JOIN generalProdServ pr ON c.productId=pr.prodservId JOIN generalCategories ct ON c.categoryId=ct.categoryId WHERE c.clientId = '$clientId'");
        }
        
    
if($filter=="catalogs"){

    $query = mysqli_query($conectar, "SELECT c.catalogId,c.clientId,c.productId,c.placeId,c.categoryId, c.infoCatalog,p.infoPlace AS infoPlace,pr.infoProdserv AS productInfo,ct.infoCategory AS categoryInfo FROM generalCatalogs c JOIN  generalPlaces p ON c.placeId=p.placeId JOIN generalProdServ pr ON c.productId=pr.prodservId JOIN generalCategories ct ON c.categoryId=ct.categoryId WHERE c.clientId = '$clientId' AND JSON_EXTRACT(c.infoCatalog, '$[0].info.$param') LIKE '%$value%'");

          

}
 
if($filter=="products"){

    $query = mysqli_query($conectar, "SELECT c.catalogId,c.clientId,c.productId,c.placeId,c.categoryId, c.infoCatalog,p.infoPlace AS infoPlace,pr.infoProdserv AS productInfo,ct.infoCategory AS categoryInfo FROM generalCatalogs c JOIN  generalPlaces p ON c.placeId=p.placeId JOIN generalProdServ pr ON c.productId=pr.prodservId JOIN generalCategories ct ON c.categoryId=ct.categoryId WHERE c.clientId = '$clientId' AND JSON_EXTRACT(pr.infoProdserv, '$[0].info.$param') LIKE '%$value%'");

          

}
if($filter=="categories"){

    $query = mysqli_query($conectar, "SELECT c.catalogId,c.clientId,c.productId,c.placeId,c.categoryId, c.infoCatalog,p.infoPlace AS infoPlace,pr.infoProdserv AS productInfo,ct.infoCategory AS categoryInfo FROM generalCatalogs c JOIN  generalPlaces p ON c.placeId=p.placeId JOIN generalProdServ pr ON c.productId=pr.prodservId JOIN generalCategories ct ON c.categoryId=ct.categoryId WHERE c.clientId = '$clientId' AND JSON_EXTRACT(ct.infoCategory, '$[0].info.$param') LIKE '%$value%'");

          

}
if($filter=="places"){

    $query = mysqli_query($conectar, "SELECT c.catalogId,c.clientId,c.productId,c.placeId,c.categoryId, c.infoCatalog,p.infoPlace AS infoPlace,pr.infoProdserv AS productInfo,ct.infoCategory AS categoryInfo FROM generalCatalogs c JOIN  generalPlaces p ON c.placeId=p.placeId JOIN generalProdServ pr ON c.productId=pr.prodservId JOIN generalCategories ct ON c.categoryId=ct.categoryId WHERE c.clientId = '$clientId' AND JSON_EXTRACT(p.infoPlace, '$[0].info.$param') LIKE '%$value%'");

          

}
if($filter=="placesOS"){

    $query = mysqli_query($conectar, "SELECT c.catalogId,c.clientId,c.productId,c.placeId,c.categoryId, c.infoCatalog,p.infoPlace AS infoPlace,pr.infoProdserv AS productInfo,ct.infoCategory AS categoryInfo FROM generalCatalogs c JOIN  generalPlaces p ON c.placeId=p.placeId JOIN generalProdServ pr ON c.productId=pr.prodservId JOIN generalCategories ct ON c.categoryId=ct.categoryId WHERE c.clientId = '$clientId' AND c.placeId= '$value'");

          

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
                    'catalogId' => $row['catalogId'],
                    'clientId' => $row['clientId'],
                    'productId' => $row['productId'],
                    'placeId' => $row['placeId'],
                    'categoryId' => $row['categoryId'],
                    'infoPlace' => json_decode($row['infoPlace'], true)[0],
                    'infoProduct' => json_decode($row['productInfo'], true)[0],
                    'infoCategory' => json_decode($row['categoryInfo'], true)[0],
                    'infoCatalog' => json_decode($row['infoCatalog'], true)[0]
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
                'catalogs' => $values
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
                'catalogs' => $values
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
                'catalogs' => $values
            ];
            array_push($values,$value);
            

    //echo json_encode($students) ;
    return json_encode($responseData);
                            }

                            
        
}



public static function getOrders($dta) {
            
                


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

    
    
            $query = mysqli_query($conectar, "SELECT o.orderId,o.clientId,o.siteId,o.infoOrder,JSON_EXTRACT(s.infoSite, '$[0].info.name') AS siteName,JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS placeName FROM generalOrders o JOIN generalSites s ON o.siteId=s.siteId JOIN generalPlaces p ON p.placeId=s.placeId WHERE o.clientId = '$clientId'");
        }
        
    
if($filter=="orderStatus"){

    $query = mysqli_query($conectar, "SELECT o.orderId,o.clientId,o.siteId,o.infoOrder,JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS placeName,p.placeId,JSON_EXTRACT(s.infoSite, '$[0].info.name') AS siteName FROM generalOrders o JOIN  generalSites s ON o.siteId=s.siteId JOIN generalPlaces p ON p.placeId=s.placeId WHERE o.clientId = '$clientId' AND JSON_EXTRACT(o.infoOrder, '$[0].info.infoOrder.orderStatus.status') = '$value'");

          

}
if($filter=="orderStatusExcludeOne"){

    $query = mysqli_query($conectar, "SELECT o.orderId,o.clientId,o.siteId,o.infoOrder,JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS placeName,p.placeId,JSON_EXTRACT(s.infoSite, '$[0].info.name') AS siteName FROM generalOrders o JOIN  generalSites s ON o.siteId=s.siteId JOIN generalPlaces p ON p.placeId=s.placeId WHERE o.clientId = '$clientId' AND JSON_EXTRACT(o.infoOrder, '$[0].info.infoOrder.orderStatus.status') != '$value'");

          

}
if($filter=="bySite"){

    $query = mysqli_query($conectar, "SELECT o.orderId,o.clientId,o.siteId,o.infoOrder,JSON_EXTRACT(s.infoSite, '$[0].info.name') AS siteName,JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS placeName FROM generalOrders o JOIN generalSites s ON o.siteId=s.siteId JOIN generalPlaces p ON p.placeId=s.placeId WHERE o.clientId = '$clientId' and s.siteId='$value'");

          

}
if($filter=="bySiteOrderStatus"){

    $query = mysqli_query($conectar, "SELECT o.orderId,o.clientId,o.siteId,o.infoOrder,JSON_EXTRACT(s.infoSite, '$[0].info.name') AS siteName,JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS placeName FROM generalOrders o JOIN generalSites s ON o.siteId=s.siteId JOIN generalPlaces p ON p.placeId=s.placeId WHERE o.clientId = '$clientId' and s.siteId='$value' AND JSON_EXTRACT(o.infoOrder, '$[0].info.infoOrder.orderStatus.status') = '$param'");

}

if($filter=="bySiteOrderTrackIdStatusFinished"){

    $query = mysqli_query($conectar, "SELECT o.orderId,o.clientId,o.siteId,o.infoOrder,JSON_EXTRACT(s.infoSite, '$[0].info.name') AS siteName,JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS placeName FROM generalOrders o JOIN generalSites s ON o.siteId=s.siteId JOIN generalPlaces p ON p.placeId=s.placeId WHERE o.clientId = '$clientId' and s.siteId='$value' AND JSON_EXTRACT(o.infoOrder, '$[0].info.infoOrder.orderStatus.orderTrackId') = '$param' AND JSON_EXTRACT(o.infoOrder, '$[0].info.infoOrder.orderStatus.status') IN ('delivered','finished')");

}
if($filter=="bySiteOrderStatusExcludeOne"){

    $query = mysqli_query($conectar, "SELECT o.orderId,o.clientId,o.siteId,o.infoOrder,JSON_EXTRACT(s.infoSite, '$[0].info.name') AS siteName,JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS placeName FROM generalOrders o JOIN generalSites s ON o.siteId=s.siteId JOIN generalPlaces p ON p.placeId=s.placeId WHERE o.clientId = '$clientId' and s.siteId='$value' AND JSON_EXTRACT(o.infoOrder, '$[0].info.infoOrder.orderStatus.status') != '$param'");

}
if($filter=="byPlace"){

    $query = mysqli_query($conectar, "SELECT o.orderId,o.clientId,o.siteId,o.infoOrder,JSON_EXTRACT(s.infoSite, '$[0].info.name') AS siteName,JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS placeName FROM generalOrders o JOIN generalSites s ON o.siteId=s.siteId JOIN generalPlaces p ON p.placeId=s.placeId WHERE o.clientId = '$clientId' AND p.placeId= '$value'");

}
if($filter=="byPlaceOrderStatus"){

    $query = mysqli_query($conectar, "SELECT o.orderId,o.clientId,o.siteId,o.infoOrder,JSON_EXTRACT(s.infoSite, '$[0].info.name') AS siteName,JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS placeName FROM generalOrders o JOIN generalSites s ON o.siteId=s.siteId JOIN generalPlaces p ON p.placeId=s.placeId WHERE o.clientId = '$clientId' and s.placeId='$value' AND JSON_EXTRACT(o.infoOrder, '$[0].info.infoOrder.orderStatus.status') = '$param'");

}
if($filter=="byPlaceOrderStatusExcludeOne"){

    $query = mysqli_query($conectar, "SELECT o.orderId,o.clientId,o.siteId,o.infoOrder,JSON_EXTRACT(s.infoSite, '$[0].info.name') AS siteName,JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS placeName FROM generalOrders o JOIN generalSites s ON o.siteId=s.siteId JOIN generalPlaces p ON p.placeId=s.placeId WHERE o.clientId = '$clientId' and s.placeId='$value' AND JSON_EXTRACT(o.infoOrder, '$[0].info.infoOrder.orderStatus.status') != '$param'");

}

if($filter=="byOwner"){

    $query = mysqli_query($conectar, "SELECT o.orderId,o.clientId,o.siteId,o.infoOrder,JSON_EXTRACT(s.infoSite, '$[0].info.name') AS siteName,JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS placeName FROM generalOrders o JOIN generalSites s ON o.siteId=s.siteId JOIN generalPlaces p ON p.placeId=s.placeId WHERE o.clientId = '$clientId' AND JSON_EXTRACT(o.infoOrder, '$[0].info.infoOrder.ownerStatus.ownerId') = '$value'");

}
if($filter=="byOwnerOrderStatus"){

    $query = mysqli_query($conectar, "SELECT o.orderId,o.clientId,o.siteId,o.infoOrder,JSON_EXTRACT(s.infoSite, '$[0].info.name') AS siteName,JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS placeName FROM generalOrders o JOIN generalSites s ON o.siteId=s.siteId JOIN generalPlaces p ON p.placeId=s.placeId WHERE o.clientId = '$clientId' and JSON_EXTRACT(o.infoOrder, '$[0].info.infoOrder.ownerStatus.ownerId') = '$value' AND JSON_EXTRACT(o.infoOrder, '$[0].info.infoOrder.orderStatus.status') = '$param'");

}
if($filter=="byOwnerOrderStatusExcludeOne"){

    $query = mysqli_query($conectar, "SELECT o.orderId,o.clientId,o.siteId,o.infoOrder,JSON_EXTRACT(s.infoSite, '$[0].info.name') AS siteName,JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS placeName FROM generalOrders o JOIN generalSites s ON o.siteId=s.siteId JOIN generalPlaces p ON p.placeId=s.placeId WHERE o.clientId = '$clientId' and JSON_EXTRACT(o.infoOrder, '$[0].info.infoOrder.ownerStatus.ownerId') = '$value' AND JSON_EXTRACT(o.infoOrder, '$[0].info.infoOrder.orderStatus.status') != '$param'");

}
if($filter=="paymentStatus"){

    $query = mysqli_query($conectar, "SELECT o.orderId,o.clientId,o.siteId,o.infoOrder,JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS placeName,p.placeId,JSON_EXTRACT(s.infoSite, '$[0].info.name') AS siteName FROM generalOrders o JOIN  generalSites s ON o.siteId=s.siteId JOIN generalPlaces p ON p.placeId=s.placeId WHERE o.clientId = '$clientId' AND JSON_EXTRACT(o.infoOrder, '$[0].info.infoOrder.paymentStatus.status') = '$value'");

          

}
if($filter=="paymentStatusExcludeOne"){

    $query = mysqli_query($conectar, "SELECT o.orderId,o.clientId,o.siteId,o.infoOrder,JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS placeName,p.placeId,JSON_EXTRACT(s.infoSite, '$[0].info.name') AS siteName FROM generalOrders o JOIN  generalSites s ON o.siteId=s.siteId JOIN generalPlaces p ON p.placeId=s.placeId WHERE o.clientId = '$clientId' AND JSON_EXTRACT(o.infoOrder, '$[0].info.infoOrder.paymentStatus.status') != '$value'");

          

}
if($filter=="accountStatus"){

    $query = mysqli_query($conectar, "SELECT o.orderId,o.clientId,o.siteId,o.infoOrder,JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS placeName,p.placeId,JSON_EXTRACT(s.infoSite, '$[0].info.name') AS siteName FROM generalOrders o JOIN  generalPlaces p ON p.placeId=s.placeId JOIN generalSites s ON s.siteId=o.sisteId WHERE o.clientId = '$clientId' AND JSON_EXTRACT(o.infoOrder, '$[0].info.infoAccount.accountStatus.$param') = '$value'");

          

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
                    'orderId' => $row['orderId'],
                    'siteId' => $row['siteId'],
                    'clientId' => $row['clientId'],
                    'siteName'=>json_decode($row['siteName']),
                    'placeName'=>json_decode($row['placeName']),
                    'infoOrder' => json_decode($row['infoOrder'], true)[0]
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
                'orders' => $values
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
                'orders' => $values
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
                'orders' => $values
            ];
            array_push($values,$value);
            

    //echo json_encode($students) ;
    return json_encode($responseData);
                            }

                            
        
}



public static function getEmployees($dta) {
            
                


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

    
    
            $query = mysqli_query($conectar, "SELECT e.employeeId, e.clientId, e.infoEmployee, e.placeId, JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS placeName FROM generalEmployees e JOIN  generalPlaces p ON e.placeId=p.placeId WHERE e.clientId = '$clientId'");
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
                    'employeeId' => $row['employeeId'],
                    'clientId' => $row['clientId'],
                    'placeId' => $row['placeId'],
                    'placeName' => json_decode($row['placeName']),
                    'infoEmployee' => json_decode($row['infoEmployee'], true)[0]
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
                'employees' => $values
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
                'employees' => $values
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
                'employees' => $values
            ];
            array_push($values,$value);
            

    //echo json_encode($students) ;
    return json_encode($responseData);
                            }

                            
        
}




public static function getCustomers($dta) {
            
                


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

    
    
            $query = mysqli_query($conectar, "SELECT c.customerId, c.clientId, c.infoCustomer, c.placeId, JSON_EXTRACT(p.infoPlace, '$[0].info.name') AS placeName FROM generalCustomers c JOIN  generalPlaces p ON c.placeId=p.placeId WHERE c.clientId = '$clientId'");
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
            $apiMessage="¡Filas seleccionados ($numRows)!";
            $values=[];

            while ($row = $query->fetch_assoc()) {
                $value=[
                    'customerId' => $row['customerId'],
                    'clientId' => $row['clientId'],
                    'placeId' => $row['placeId'],
                    'placeName' => json_decode($row['placeName']),
                    'infoCustomer' => json_decode($row['infoCustomer'], true)[0]
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
                'customers' => $values
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
                'customers' => $values
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
                'customers' => $values
            ];
            array_push($values,$value);
            

    //echo json_encode($students) ;
    return json_encode($responseData);
                            }

                            
        
}




    }


    
?>