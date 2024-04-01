<?php
    require_once 'database/db_users.php';
    require_once 'model/modelSecurity/uuid/uuidd.php';
    require_once 'model/users/sendMail.php';
class modelPost {
          
      
            
        public static function postPlace($dta) {
            
                
                // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
                
                    // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
                    $conectar = conn();
            
                    // Verifica si la conexión se realizó correctamente
                    if (!$conectar) {
                        return "Error de conexión a la base de datos";
                    }
            
                    
                        
                    $gen_uuid = new generateUuid();
                    $myuuid = $gen_uuid->guidv4();
                    $placeId = substr($myuuid, 0, 8);

                    // Escapa los valores para prevenir inyección SQL
                    $clientId = mysqli_real_escape_string($conectar, $dta['clientId']);
                    $placeName = mysqli_real_escape_string($conectar, $dta['placeName']);
                    $placeAddress = mysqli_real_escape_string($conectar, $dta['placeAddress']);
                    $placeComments = mysqli_real_escape_string($conectar, $dta['placeComments']);
                    $placeContact = mysqli_real_escape_string($conectar, $dta['placeContact']);
                    $placeMail = mysqli_real_escape_string($conectar, $dta['placeEmail']);
                    //$dato_encriptado = $keyword;
                    
                    $infoPlace = [
                        [
                            "info" => [
                                "name" => $placeName,
                                "address" => $placeAddress,
                                "comments" => $placeComments,
                                "contact" => $placeContact,
                                "email" => $placeMail
                            ],
                            "params" => [
                                "isActive" => "1",
                                "status" => "1"
                            ]
                        ]
                    ];
                    
                    $jsonInfoPlace = json_encode($infoPlace);
                   // echo $jsonInfoSite;
                    
                    $query = mysqli_query($conectar, "INSERT INTO generalPlaces 
                    (placeId, clientId, infoPlace) 
                    VALUES
                    ('$placeId', '$clientId', '$jsonInfoPlace')");

                    if($query){
                                $filasAfectadas = mysqli_affected_rows($conectar);
                                    if ($filasAfectadas > 0) 
                                        {
                                            // Éxito: La actualización se realizó correctamente
                                            $response="true";
                                            $message="Creación exitosa. Filas afectadas: $filasAfectadas";
                                            $apiMessage="¡ creado con éxito!";
                                            $status="201";
                                        } 
                                        else {
                                            $response="false";
                                            $message="Creación no exitosa. Filas afectadas: $filasAfectadas";
                                            $status="500";
                                            $apiMessage="¡Cliente no credo con éxito!";
                                            }
                            //  return "true";
                            //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
                    }
                    else{
                            $response="true";
                            $message="Error en la actualización: " . mysqli_error($conectar);
                            $status="404";
                            $apiMessage="¡Cliente no creado con éxito!";
                        
                        }

                        $values=[];

                        $value=[
                            'response' => $response,
                            'message' => $message,
                            'apiMessage' => $apiMessage,
                            'status' => $status
                            
                        ];
                        
                        array_push($values,$value);
                                            
                                
                                    //echo json_encode($students) ;
                                    return json_encode(['response'=>$values]);
                    
            }



            public static function postElement($dta) {
            
                
                // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
                
                    // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
                    $conectar = conn();
            
                    // Verifica si la conexión se realizó correctamente
                    if (!$conectar) {
                        return "Error de conexión a la base de datos";
                    }
            
                    
                        
                    $gen_uuid = new generateUuid();
                    $myuuid = $gen_uuid->guidv4();
                    $elementId = substr($myuuid, 0, 8);

                    // Escapa los valores para prevenir inyección SQL
                    $clientId = mysqli_real_escape_string($conectar, $dta['clientId']);
                    $elementName = mysqli_real_escape_string($conectar, $dta['elementName']);
                    $elementComments = mysqli_real_escape_string($conectar, $dta['elementComments']);
                    $elementType = mysqli_real_escape_string($conectar, $dta['elementType']);
                    $elementSite = mysqli_real_escape_string($conectar, $dta['elementSite']);
                    //$dato_encriptado = $keyword;
                    
                    $infoElement = [
                        [
                            "info" => [
                                "name" => $elementName,
                                "type" => $elementType,
                                "comments" => $elementComments
                            ],
                            "params" => [
                                "isActive" => "1",
                                "status" => "1"
                            ]
                        ]
                    ];
                    
                    $jsonInfoElement = json_encode($infoElement);
                   // echo $jsonInfoSite;
                    
                    $query = mysqli_query($conectar, "INSERT INTO generalElements 
                    (elementId, clientId,siteId, infoElement) 
                    VALUES
                    ('$elementId', '$clientId','$elementSite', '$jsonInfoElement')");

                    if($query){
                                $filasAfectadas = mysqli_affected_rows($conectar);
                                    if ($filasAfectadas > 0) 
                                        {
                                            // Éxito: La actualización se realizó correctamente
                                            $response="true";
                                            $message="Creación exitosa. Filas afectadas: $filasAfectadas";
                                            $apiMessage="¡ creado con éxito!";
                                            $status="201";
                                        } 
                                        else {
                                            $response="false";
                                            $message="Creación no exitosa. Filas afectadas: $filasAfectadas";
                                            $status="500";
                                            $apiMessage="¡Cliente no credo con éxito!";
                                            }
                            //  return "true";
                            //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
                    }
                    else{
                            $response="true";
                            $message="Error en la actualización: " . mysqli_error($conectar);
                            $status="404";
                            $apiMessage="¡Cliente no creado con éxito!";
                        
                        }

                        $values=[];

                        $value=[
                            'response' => $response,
                            'message' => $message,
                            'apiMessage' => $apiMessage,
                            'status' => $status
                            
                        ];
                        
                        array_push($values,$value);
                                            
                                
                                    //echo json_encode($students) ;
                                    return json_encode(['response'=>$values]);
                    
            }

            public static function postSite($dta) {
            
                
                // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
                
                    // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
                    $conectar = conn();
            
                    // Verifica si la conexión se realizó correctamente
                    if (!$conectar) {
                        return "Error de conexión a la base de datos";
                    }
            
                    
                        
                    $gen_uuid = new generateUuid();
                    $myuuid = $gen_uuid->guidv4();
                    $siteId = substr($myuuid, 0, 8);

                    // Escapa los valores para prevenir inyección SQL
                    $clientId = mysqli_real_escape_string($conectar, $dta['clientId']);
                    $siteName = mysqli_real_escape_string($conectar, $dta['siteName']);
                    $siteComments = mysqli_real_escape_string($conectar, $dta['siteComments']);
                    $sitePlace = mysqli_real_escape_string($conectar, $dta['sitePlace']);
                    //$dato_encriptado = $keyword;
                    
                    $infoSite = [
                        [
                            "info" => [
                                "name" => $siteName,
                                "comments" => $siteComments,
                            ],
                            "params" => [
                                "isActive" => "1",
                                "status" => "1"
                            ]
                        ]
                    ];
                    
                    $jsonInfoSite = json_encode($infoSite);
                   // echo $jsonInfoSite;
                    
                    $query = mysqli_query($conectar, "INSERT INTO generalSites 
                    (siteId, clientId, infoSite,placeId) 
                    VALUES
                    ('$siteId', '$clientId', '$jsonInfoSite','$sitePlace')");

                    if($query){
                                $filasAfectadas = mysqli_affected_rows($conectar);
                                    if ($filasAfectadas > 0) 
                                        {
                                            // Éxito: La actualización se realizó correctamente
                                            $response="true";
                                            $message="Creación exitosa. Filas afectadas: $filasAfectadas";
                                            $apiMessage="¡ creado con éxito!";
                                            $status="201";
                                        } 
                                        else {
                                            $response="false";
                                            $message="Creación no exitosa. Filas afectadas: $filasAfectadas";
                                            $status="500";
                                            $apiMessage="¡Cliente no credo con éxito!";
                                            }
                            //  return "true";
                            //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
                    }
                    else{
                            $response="true";
                            $message="Error en la actualización: " . mysqli_error($conectar);
                            $status="404";
                            $apiMessage="¡Cliente no creado con éxito!";
                        
                        }

                        $values=[];

                        $value=[
                            'response' => $response,
                            'message' => $message,
                            'apiMessage' => $apiMessage,
                            'status' => $status
                            
                        ];
                        
                        array_push($values,$value);
                                            
                                
                                    //echo json_encode($students) ;
                                    return json_encode(['response'=>$values]);
                    
            }




            public static function postProduct($dta) {
            
                
                // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
                
                    // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
                    $conectar = conn();
            
                    // Verifica si la conexión se realizó correctamente
                    if (!$conectar) {
                        return "Error de conexión a la base de datos";
                    }
            
                    
                        
                    $gen_uuid = new generateUuid();
                    $myuuid = $gen_uuid->guidv4();
                    $productId = substr($myuuid, 0, 8);

                    // Escapa los valores para prevenir inyección SQL
                    $clientId = mysqli_real_escape_string($conectar, $dta['clientId']);
                    $productName = mysqli_real_escape_string($conectar, $dta['productName']);
                    $productComments = mysqli_real_escape_string($conectar, $dta['productComments']);
                    $productCaracts = mysqli_real_escape_string($conectar, $dta['productCaracts']);
                    $productImg = mysqli_real_escape_string($conectar, $dta['productImg']);
                    $productType = mysqli_real_escape_string($conectar, $dta['productType']);
                    $productValue = mysqli_real_escape_string($conectar, $dta['productValue']);
                    $productUnit = mysqli_real_escape_string($conectar, $dta['productUnit']);
                    $productUnitCaracts = mysqli_real_escape_string($conectar, $dta['productUnitCaracts']);
                    $productByDiscount = mysqli_real_escape_string($conectar, $dta['productBydiscount']);
                    $productStockByUnit = mysqli_real_escape_string($conectar, $dta['productStockByUnit']);
                    $productSku = mysqli_real_escape_string($conectar, $dta['productSku']);
                    $productEan1 = mysqli_real_escape_string($conectar, $dta['productEan1']);
                    $productEan2 = mysqli_real_escape_string($conectar, $dta['productEan2']);
                    $keywords=$productName." ".$productComments." ".$productSku." ".$productCaracts;
                    //$dato_encriptado = $keyword;
                    
                    $infoProduct = [
                        [
                            "info" => [
                                "name" => $productName,
                                "type" => $productType,
                                "comments" => $productComments,
                                "caracts" => $productCaracts,
                                "imgProduct" => $productImg,
                                "value" => $productValue,
                                "unit" => $productUnit,
                                "unitCaracts" => $productUnitCaracts,
                                "byDiscount" => $productByDiscount,
                                "stockByUnit" => $productStockByUnit,
                                "sku" => $productSku,
                                "ean1" => $productEan1,
                                "ean2" => $productEan2,
                                "keyWords" => $keywords
                            ],
                            "params" => [
                                "isActive" => "1",
                                "status" => "1"
                            ]
                        ]
                    ];
                    
                    $jsonInfoProduct = json_encode($infoProduct);
                   // echo $jsonInfoSite;
                    
                    $query = mysqli_query($conectar, "INSERT INTO generalProdServ 
                    (prodservId, clientId, infoProdServ) 
                    VALUES
                    ('$productId', '$clientId','$jsonInfoProduct')");

                    if($query){
                                $filasAfectadas = mysqli_affected_rows($conectar);
                                    if ($filasAfectadas > 0) 
                                        {
                                            // Éxito: La actualización se realizó correctamente
                                            $response="true";
                                            $message="Creación exitosa. Filas afectadas: $filasAfectadas";
                                            $apiMessage="¡ creado con éxito!";
                                            $status="201";
                                        } 
                                        else {
                                            $response="false";
                                            $message="Creación no exitosa. Filas afectadas: $filasAfectadas";
                                            $status="500";
                                            $apiMessage="¡Cliente no credo con éxito!";
                                            }
                            //  return "true";
                            //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
                    }
                    else{
                            $response="true";
                            $message="Error en la actualización: " . mysqli_error($conectar);
                            $status="404";
                            $apiMessage="¡Cliente no creado con éxito!";
                        
                        }

                        $values=[];

                        $value=[
                            'response' => $response,
                            'message' => $message,
                            'apiMessage' => $apiMessage,
                            'status' => $status
                            
                        ];
                        
                        array_push($values,$value);
                                            
                                
                                    //echo json_encode($students) ;
                                    return json_encode(['response'=>$values]);
                    
            }



            public static function postCategory($dta) {
            
                
                // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
                
                    // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
                    $conectar = conn();
            
                    // Verifica si la conexión se realizó correctamente
                    if (!$conectar) {
                        return "Error de conexión a la base de datos";
                    }
            
                    
                        
                    $gen_uuid = new generateUuid();
                    $myuuid = $gen_uuid->guidv4();
                    $categoryId = substr($myuuid, 0, 8);

                    // Escapa los valores para prevenir inyección SQL
                    $clientId = mysqli_real_escape_string($conectar, $dta['clientId']);
                    $categoryName = mysqli_real_escape_string($conectar, $dta['categoryName']);
                    $categoryComments = mysqli_real_escape_string($conectar, $dta['categoryComments']);
                   
                    $categoryImg = mysqli_real_escape_string($conectar, $dta['categoryImg']);
                    $categoryParent = mysqli_real_escape_string($conectar, $dta['categoryParent']);
                    $keywords=$categoryName." ".$categoryComments." ".$categoryParent;
                    //$dato_encriptado = $keyword;
                    if($categoryParent=="main"){
                        $categoryType="main";
                        $parentId=$categoryId;
                    }
                    if($categoryParent!="main"){
                        $categoryType="secondary";
                        $parentId=$categoryParent;
                    }

                    $infoCategory = [
                        [
                            "info" => [
                                "name" => $categoryName,
                                "type" => $categoryType,
                                "comments" => $categoryComments,
                                
                                "imgCategory" => $categoryImg,
                                
                                "keyWords" => $keywords
                            ],
                            "params" => [
                                "isActive" => "1",
                                "status" => "1"
                            ]
                        ]
                    ];
                    
                    $jsonInfoCategory = json_encode($infoCategory);
                   // echo $jsonInfoSite;
                    
                    $query = mysqli_query($conectar, "INSERT INTO generalCategories 
                    (categoryId, clientId, infoCategory,parentId) 
                    VALUES
                    ('$categoryId', '$clientId','$jsonInfoCategory','$parentId')");

                    if($query){
                                $filasAfectadas = mysqli_affected_rows($conectar);
                                    if ($filasAfectadas > 0) 
                                        {
                                            // Éxito: La actualización se realizó correctamente
                                            $response="true";
                                            $message="Creación exitosa. Filas afectadas: $filasAfectadas";
                                            $apiMessage="¡ creado con éxito!";
                                            $status="201";
                                        } 
                                        else {
                                            $response="false";
                                            $message="Creación no exitosa. Filas afectadas: $filasAfectadas";
                                            $status="500";
                                            $apiMessage="¡Cliente no credo con éxito!";
                                            }
                            //  return "true";
                            //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
                    }
                    else{
                            $response="true";
                            $message="Error en la actualización: " . mysqli_error($conectar);
                            $status="404";
                            $apiMessage="¡Cliente no creado con éxito!";
                        
                        }

                        $values=[];

                        $value=[
                            'response' => $response,
                            'message' => $message,
                            'apiMessage' => $apiMessage,
                            'status' => $status
                            
                        ];
                        
                        array_push($values,$value);
                                            
                                
                                    //echo json_encode($students) ;
                                    return json_encode(['response'=>$values]);
                    
            }


    }


class modelPut{

        public static function putPlace($dta) {
            
        // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
    
        // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
        $conectar = conn();

        // Verifica si la conexión se realizó correctamente
        if (!$conectar) {
            return "Error de conexión a la base de datos";
        }

        

        // Escapa los valores para prevenir inyección SQL
        $clientId = mysqli_real_escape_string($conectar, $dta['clientId']);
        $param = mysqli_real_escape_string($conectar, $dta['param']);
        $value = mysqli_real_escape_string($conectar, $dta['value']);
        $placeId = mysqli_real_escape_string($conectar, $dta['placeId']);
    
        //$dato_encriptado = $keyword;
        if($param=="del"){
           // $query = mysqli_query($conectar, "DELETE FROM generalPlaces where clientId='$clientId' and deliveryId='$deliveryId'");
            $apiMessage="¡Repartidor removido con éxito!";
        }  if($param!="del"){
            $query = mysqli_query($conectar, "UPDATE generalPlaces 
                                  SET infoPlace = JSON_SET(infoPlace, '$[0].info.$param', '$value') 
                                  WHERE clientId = '$clientId' AND placeId = '$placeId'");

            $apiMessage="¡Ubicación actualizada con éxito!";
        }

       // $query = mysqli_query($conectar, "UPDATE generalDelivery SET $param='$value' where clientId='$clientId' and deliveryId='$deliveryId'");
    
        if($query){
            $filasAfectadas = mysqli_affected_rows($conectar);
            if ($filasAfectadas > 0) {
                // Éxito: La actualización se realizó correctamente
            $response="true";
            $message="Actualización exitosa. Filas afectadas: $filasAfectadas";
            $apiMessage="¡Ubicación actualizada con éxito!";
     
                $status="201";
            } else {
                $response="false";
            $message="Actualización no exitosa. Filas afectadas: $filasAfectadas";
                $status="500";
                $apiMessage="¡Repartidor no actualizado con éxito!";
            }
        //  return "true";
        //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
        }else{
            $response="false";
            $message="Error en la actualización: " . mysqli_error($conectar);
            $status="404";
            $apiMessage="¡Repartidor no actualizado con éxito!";
        
                            }

                            $values=[];

                            $value=[
                                'response' => $response,
                                'message' => $message,
                                'apiMessage' => $apiMessage,
                                'status' => $status
                                
                            ];
                            
                            array_push($values,$value);
                            
                
                    //echo json_encode($students) ;
                    return json_encode(['response'=>$values]);

            }
            public static function putSite($dta) {
            
                // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
            
                // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
                $conectar = conn();
        
                // Verifica si la conexión se realizó correctamente
                if (!$conectar) {
                    return "Error de conexión a la base de datos";
                }
        
                
        
                // Escapa los valores para prevenir inyección SQL
                $clientId = mysqli_real_escape_string($conectar, $dta['clientId']);
                $param = mysqli_real_escape_string($conectar, $dta['param']);
                $value = mysqli_real_escape_string($conectar, $dta['value']);
                $siteId = mysqli_real_escape_string($conectar, $dta['siteId']);
            
                //$dato_encriptado = $keyword;
                if($param=="del"){
                   // $query = mysqli_query($conectar, "DELETE FROM generalPlaces where clientId='$clientId' and deliveryId='$deliveryId'");
                    $apiMessage="¡Repartidor removido con éxito!";
                }  if($param!="del"){
                    if($param=="placeId"){
                        $query = mysqli_query($conectar, "UPDATE generalSites 
                        SET placeId = '$value'
                        WHERE clientId = '$clientId' AND siteId = '$siteId'");
                    }else{
                    $query = mysqli_query($conectar, "UPDATE generalSites 
                                          SET infoSite = JSON_SET(infoSite, '$[0].info.$param', '$value') 
                                          WHERE clientId = '$clientId' AND siteId = '$siteId'");
                    }
                    $apiMessage="¡Ubicación actualizada con éxito!";
                }
        
               // $query = mysqli_query($conectar, "UPDATE generalDelivery SET $param='$value' where clientId='$clientId' and deliveryId='$deliveryId'");
            
                if($query){
                    $filasAfectadas = mysqli_affected_rows($conectar);
                    if ($filasAfectadas > 0) {
                        // Éxito: La actualización se realizó correctamente
                    $response="true";
                    $message="Actualización exitosa. Filas afectadas: $filasAfectadas";
                    $apiMessage="¡Ubicación actualizada con éxito!";
             
                        $status="201";
                    } else {
                        $response="false";
                    $message="Actualización no exitosa. Filas afectadas: $filasAfectadas";
                        $status="500";
                        $apiMessage="¡Repartidor no actualizado con éxito!";
                    }
                //  return "true";
                //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
                }else{
                    $response="false";
                    $message="Error en la actualización: " . mysqli_error($conectar);
                    $status="404";
                    $apiMessage="¡Repartidor no actualizado con éxito!";
                
                                    }
        
                                    $values=[];
        
                                    $value=[
                                        'response' => $response,
                                        'message' => $message,
                                        'apiMessage' => $apiMessage,
                                        'status' => $status
                                        
                                    ];
                                    
                                    array_push($values,$value);
                                    
                        
                            //echo json_encode($students) ;
                            return json_encode(['response'=>$values]);
        
                    }


                    public static function putElement($dta) {
            
                        // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
                    
                        // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
                        $conectar = conn();
                
                        // Verifica si la conexión se realizó correctamente
                        if (!$conectar) {
                            return "Error de conexión a la base de datos";
                        }
                
                        
                
                        // Escapa los valores para prevenir inyección SQL
                        $clientId = mysqli_real_escape_string($conectar, $dta['clientId']);
                        $param = mysqli_real_escape_string($conectar, $dta['param']);
                        $value = mysqli_real_escape_string($conectar, $dta['value']);
                        $elementId = mysqli_real_escape_string($conectar, $dta['elementId']);
                    
                        //$dato_encriptado = $keyword;
                        if($param=="del"){
                          //  $query = mysqli_query($conectar, "DELETE FROM generalPlaces where clientId='$clientId' and deliveryId='$deliveryId'");
                            $apiMessage="¡Repartidor removido con éxito!";
                        }  if($param!="del"){
                            if($param=="siteId"){
                                $query = mysqli_query($conectar, "UPDATE generalElements 
                                SET siteId = '$value'
                                WHERE clientId = '$clientId' AND elementId = '$elementId'");
                            }else{
                            $query = mysqli_query($conectar, "UPDATE generalElements 
                                                  SET infoElement = JSON_SET(infoElement, '$[0].info.$param', '$value') 
                                                  WHERE clientId = '$clientId' AND elementId = '$elementId'");
                            }
                            $apiMessage="¡Ubicación actualizada con éxito!";
                        }
                
                       // $query = mysqli_query($conectar, "UPDATE generalDelivery SET $param='$value' where clientId='$clientId' and deliveryId='$deliveryId'");
                    
                        if($query){
                            $filasAfectadas = mysqli_affected_rows($conectar);
                            if ($filasAfectadas > 0) {
                                // Éxito: La actualización se realizó correctamente
                            $response="true";
                            $message="Actualización exitosa. Filas afectadas: $filasAfectadas";
                            $apiMessage="¡Ubicación actualizada con éxito!";
                     
                                $status="201";
                            } else {
                                $response="false";
                            $message="Actualización no exitosa. Filas afectadas: $filasAfectadas";
                                $status="500";
                                $apiMessage="¡Repartidor no actualizado con éxito!";
                            }
                        //  return "true";
                        //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
                        }else{
                            $response="false";
                            $message="Error en la actualización: " . mysqli_error($conectar);
                            $status="404";
                            $apiMessage="¡Repartidor no actualizado con éxito!";
                        
                                            }
                
                                            $values=[];
                
                                            $value=[
                                                'response' => $response,
                                                'message' => $message,
                                                'apiMessage' => $apiMessage,
                                                'status' => $status
                                                
                                            ];
                                            
                                            array_push($values,$value);
                                            
                                
                                    //echo json_encode($students) ;
                                    return json_encode(['response'=>$values]);
                
                            }


                            public static function putProduct($dta) {
            
                                // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
                            
                                // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
                                $conectar = conn();
                        
                                // Verifica si la conexión se realizó correctamente
                                if (!$conectar) {
                                    return "Error de conexión a la base de datos";
                                }
                        
                                
                        
                                // Escapa los valores para prevenir inyección SQL
                                $clientId = mysqli_real_escape_string($conectar, $dta['clientId']);
                                $param = mysqli_real_escape_string($conectar, $dta['param']);
                                $value = mysqli_real_escape_string($conectar, $dta['value']);
                                $productId = mysqli_real_escape_string($conectar, $dta['productId']);
                            
                                //$dato_encriptado = $keyword;
                                if($param=="del"){
                                    //$query = mysqli_query($conectar, "DELETE FROM generalPlaces where clientId='$clientId' and deliveryId='$deliveryId'");
                                    $apiMessage="¡Repartidor removido con éxito!";
                                }  if($param!="del"){
                                    $query = mysqli_query($conectar, "UPDATE generalProdServ 
                                                          SET infoProdServ = JSON_SET(infoProdServ, '$[0].info.$param', '$value') 
                                                          WHERE clientId = '$clientId' AND prodservId = '$productId'");
                        
                                    $apiMessage="¡Ubicación actualizada con éxito!";
                                }
                        
                               // $query = mysqli_query($conectar, "UPDATE generalDelivery SET $param='$value' where clientId='$clientId' and deliveryId='$deliveryId'");
                            
                                if($query){
                                    $filasAfectadas = mysqli_affected_rows($conectar);
                                    if ($filasAfectadas > 0) {
                                        // Éxito: La actualización se realizó correctamente
                                    $response="true";
                                    $message="Actualización exitosa. Filas afectadas: $filasAfectadas";
                                    $apiMessage="¡Ubicación actualizada con éxito!";
                             
                                        $status="201";
                                    } else {
                                        $response="false";
                                    $message="Actualización no exitosa. Filas afectadas: $filasAfectadas";
                                        $status="500";
                                        $apiMessage="¡Repartidor no actualizado con éxito!";
                                    }
                                //  return "true";
                                //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
                                }else{
                                    $response="false";
                                    $message="Error en la actualización: " . mysqli_error($conectar);
                                    $status="404";
                                    $apiMessage="¡Repartidor no actualizado con éxito!";
                                
                                                    }
                        
                                                    $values=[];
                        
                                                    $value=[
                                                        'response' => $response,
                                                        'message' => $message,
                                                        'apiMessage' => $apiMessage,
                                                        'status' => $status
                                                        
                                                    ];
                                                    
                                                    array_push($values,$value);
                                                    
                                        
                                            //echo json_encode($students) ;
                                            return json_encode(['response'=>$values]);
                        
                                    }
    }
    
?>