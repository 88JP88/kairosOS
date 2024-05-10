<?php
session_start();
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
                    $isPoint = mysqli_real_escape_string($conectar, $dta['isPoint']);
                    $points = mysqli_real_escape_string($conectar, $dta['points']);
                    $pointsValue = mysqli_real_escape_string($conectar, $dta['pointsValue']);
                    $pointsOut = mysqli_real_escape_string($conectar, $dta['pointsOut']);
                    $pointsAutoDiscount = mysqli_real_escape_string($conectar, $dta['pointsAutoDiscount']);
                    $poinsDiscountTotal = mysqli_real_escape_string($conectar, $dta['pointsDiscountTotal']);
                    $pointPrice = mysqli_real_escape_string($conectar, $dta['pointsPrice']);

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
                                "isActive" => true,
                                "status" => true,
                                "isPoint" => filter_var($isPoint, FILTER_VALIDATE_BOOLEAN),//aplica puntos
                                "points" => floatval($points),//cantidad de puntos para aplicar
                                "pointsValue" => floatval($pointsValue),//por la compra de VALUE se le dan x puntos
                                "pointsOut" => floatval($pointsOut),// cantidad minima de puntos para poder redimir
                                "pointsAutoDiscount" => filter_var($pointsAutoDiscount, FILTER_VALIDATE_BOOLEAN),// auto descontar puntos al momento de pagar
                                "poinsDiscountTotal" => filter_var($poinsDiscountTotal, FILTER_VALIDATE_BOOLEAN),//descontar el total de puntos al momento de pagar si es true descuenta todo si es false descuenta solo la cantidad de puntos minima pára redimir
                                "pointPrice" => floatval($pointPrice)// valor de cada punto

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
                                "isActive" => true,
                                "status" => true,
                                "isBussy" => false,
                                "isOrder" => false,
                                "isOutService" => false
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

            
            public static function postCatalog($dta) {
            
                
                // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
                
                    // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
                    $conectar = conn();
            
                    // Verifica si la conexión se realizó correctamente
                    if (!$conectar) {
                        return "Error de conexión a la base de datos";
                    }
            
                    
                        
                    $gen_uuid = new generateUuid();
                    $myuuid = $gen_uuid->guidv4();
                    $catalogId = substr($myuuid, 0, 8);

                    // Escapa los valores para prevenir inyección SQL
                    $clientId = mysqli_real_escape_string($conectar, $dta['clientId']);
                    $productId = mysqli_real_escape_string($conectar, $dta['productId']);
                   
                   
                    $stock = mysqli_real_escape_string($conectar, $dta['stock']);
                    $catalogComments = mysqli_real_escape_string($conectar, $dta['catalogComments']);
                    $categoryId = mysqli_real_escape_string($conectar, $dta['categoryId']);
                    $secStock = mysqli_real_escape_string($conectar, $dta['secStock']);
                    $minQty = mysqli_real_escape_string($conectar, $dta['minQty']);
                    $maxQty = mysqli_real_escape_string($conectar, $dta['maxQty']);
                    $secStock = mysqli_real_escape_string($conectar, $dta['secStock']);
                    $placeId = mysqli_real_escape_string($conectar, $dta['placeId']);
                    $isDiscount = mysqli_real_escape_string($conectar, $dta['isDiscount']);
                    $discount = mysqli_real_escape_string($conectar, $dta['discount']);
                    $isPromo = mysqli_real_escape_string($conectar, $dta['isPromo']);
                    $promo = mysqli_real_escape_string($conectar, $dta['promo']);
                    $isStocked = mysqli_real_escape_string($conectar, $dta['isStocked']);
                    $isInternal = mysqli_real_escape_string($conectar, $dta['isInternal']);
                    $price = mysqli_real_escape_string($conectar, $dta['price']);

                    $keywords=$catalogComments." ".$catalogId;
                    //$dato_encriptado = $keyword;



                    

                    $infoCatalog = [
                        [
                            "info" => [
                                
                               
                                "comments" => $catalogComments,
                                "maxQty"=>floatval($maxQty),
                                "minQty"=>floatval($minQty),
                                "price"=>floatval($price),
                                "stock"=>floatval($stock),
                                "securityStock"=>floatval($secStock),
                                "isDiscount"=>filter_var($isDiscount, FILTER_VALIDATE_BOOLEAN),
                                "discount"=>floatval($discount),
                                "isPromo"=>filter_var($isPromo, FILTER_VALIDATE_BOOLEAN),
                                "promo"=>$promo,
                                "isStocked"=>filter_var($isStocked, FILTER_VALIDATE_BOOLEAN),
                                "isInternal"=>filter_var($isInternal, FILTER_VALIDATE_BOOLEAN),
                                
                                "keyWords" => $keywords
                            ],
                            "params" => [
                                "isActive" => true,
                                "status" => true
                            ]
                        ]
                    ];
                    
                    $jsonInfoCatalog = json_encode($infoCatalog);
                   // echo $jsonInfoSite;
                    
                    $query = mysqli_query($conectar, "INSERT INTO generalCatalogs 
                    (catalogId, clientId, infoCatalog,productId,categoryId,placeId) 
                    VALUES
                    ('$catalogId', '$clientId','$jsonInfoCatalog','$productId','$categoryId','$placeId')");

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

            public static function postOrder($dta) {
            
                
                // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
                
                    // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
                    $conectar = conn();
            
                    // Verifica si la conexión se realizó correctamente
                    if (!$conectar) {
                        return "Error de conexión a la base de datos";
                    }
            
                    
                        
                    $gen_uuid = new generateUuid();
                    $myuuid = $gen_uuid->guidv4();
                    $orderId = substr($myuuid, 0, 8);

                    // Escapa los valores para prevenir inyección SQL
                    $clientId = mysqli_real_escape_string($conectar, $dta['clientId']);
                    $siteId = mysqli_real_escape_string($conectar, $dta['siteId']);
                    $infoProducts = mysqli_real_escape_string($conectar, $dta['products']);
                    $infoPayload = mysqli_real_escape_string($conectar, $dta['payload']);
                    $infoOrders = mysqli_real_escape_string($conectar, $dta['order']);
                    //$dato_encriptado = $keyword;
                    $infopaymentArray = json_decode(stripslashes($infoPayload), true);

                    $infoProductsArray = json_decode(stripslashes($infoProducts), true);
                    $totalCatalogPrice = 0;
                    $infoPayloadArray = json_decode(stripslashes($infoPayload), true);

                    // Iterar sobre cada elemento del array
                    foreach ($infoProductsArray as $item) {
                        // Verificar si el elemento tiene la estructura esperada
                        if (isset($item['product']['catalogPrice'])) {
                            // Sumar el valor de catalogPrice al total
                            $totalCatalogPrice += $item['product']['catalogPrice']*$item['product']['qty'];
                            $infoPayloadArray['infoPayment']['backTotal'] = "h";     
                        $subtotalCatalogPrice=$totalCatalogPrice- $infopaymentArray['infoPayment']['saver'];
                        }
                    }
                    $infototal = json_decode(stripslashes($infoPayload), true);
            
if($infototal['infoPayment']['total']==$subtotalCatalogPrice ){
$isEqTotal=true;
}else{
    $isEqTotal=false;
}
if($infototal['infoPayment']['subTotal']==$totalCatalogPrice ){
    $isEqSubTotal=true;
    }else{
        $isEqSubTotal=false;
    }
    $saver1=$infototal['infoPayment']['subTotal']-$infototal['infoPayment']['total'];
    if($infototal['infoPayment']['saver']==$saver1 ){
        $isEqSaver=true;
        }else{
            $isEqSaver=false;
        }
                    $backPayload = [
                        "infoPayment"=>[
                        "total" => $subtotalCatalogPrice,
                        "subTotal" => $totalCatalogPrice,
                        "saver" => $saver1,
                        "isEqTotalToFront"=>$isEqTotal,
                        "isEqSubTotalToFront"=>$isEqSubTotal,
                        "isEqSaverToFront"=>$isEqSaver]
                    ];
                    $accouuntStatus = [
                        "accountStatus"=>[
                        "paymentType" => "",
                        "paymentMethod" => "",
                        "paymentCompany" => "",
                        "transactionCode"=>""]
                    ];

                    $infoPlaces1 = [
                        "placeStatus"=>[
                            "placeId" => ""]
                    ];
                    
                    date_default_timezone_set('America/Bogota');

                    // Obtener la fecha y hora actual
                    $fecha_actual = date('Y-m-d H:i:s');
               
                    $infoOrder = [
                        [
                            "info" => [
                                "timeStamp"=>$fecha_actual,
                                "infoProducts" => json_decode(stripslashes($infoProducts), true),
                                "infoPayload" => json_decode(stripslashes($infoPayload), true),
                                "backPayload"=>$backPayload,
                                "infoAccount"=>$accouuntStatus,
                                "infoPlace"=>$infoPlaces1,
                                "infoOrder" => json_decode(stripslashes($infoOrders), true)
                            ],
                            "params" => [
                                "isActive" => true,
                                "status" => true
                            ]
                        ]
                    ];
                    $jsonInfoOrder = json_encode($infoOrder);
                   // echo $jsonInfoSite;
                    
                    $query = mysqli_query($conectar, "INSERT INTO generalOrders 
                    (orderId, clientId,siteId, infoOrder) 
                    VALUES
                    ('$orderId', '$clientId','$siteId', '$jsonInfoOrder')");

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


            
        public static function postEmployee($dta) {
            
                
            // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
            
                // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
                $conectar = conn();
        
                // Verifica si la conexión se realizó correctamente
                if (!$conectar) {
                    return "Error de conexión a la base de datos";
                }
        
                
                    
                $gen_uuid = new generateUuid();
                $myuuid = $gen_uuid->guidv4();
                $employeeId = substr($myuuid, 0, 8);

                // Escapa los valores para prevenir inyección SQL
                $clientId = mysqli_real_escape_string($conectar, $dta['clientId']);
                $employeeName = mysqli_real_escape_string($conectar, $dta['employeeName']);
                $employeeComments = mysqli_real_escape_string($conectar, $dta['employeeComments']);
                $employeeContact = mysqli_real_escape_string($conectar, $dta['employeeContact']);
                $employeeMail = mysqli_real_escape_string($conectar, $dta['employeeMail']);
                $employeePlace = mysqli_real_escape_string($conectar, $dta['employeePlace']);
               
                $employeeRol = mysqli_real_escape_string($conectar, $dta['employeeRol']);

                $employeeLevel = mysqli_real_escape_string($conectar, $dta['employeeLevel']);
                $employeeImg = mysqli_real_escape_string($conectar, $dta['employeeImg']);

                //$dato_encriptado = $keyword;
                
                $infoEmployee = [
                    [
                        "info" => [
                            "name" => $employeeName,
                            "rol" => $employeeRol,
                            "comments" => $employeeComments,
                            "contact" => $employeeContact,
                            "email" => $employeeMail,
                            "level" => $employeeLevel,
                            "img" => $employeeImg
                        ],
                        "params" => [
                            "isActive" => true,
                            "status" => true
                        ]
                    ]
                ];
                
                $jsonInfoEmployee = json_encode($infoEmployee);
               // echo $jsonInfoSite;
                
                $query = mysqli_query($conectar, "INSERT INTO generalEmployees 
                (employeeId, clientId, infoEmployee,placeId) 
                VALUES
                ('$employeeId', '$clientId', '$jsonInfoEmployee','$employeePlace')");

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

        public static function postCustomer($dta) {
            
                
            // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
            
                // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
                $conectar = conn();
        
                // Verifica si la conexión se realizó correctamente
                if (!$conectar) {
                    return "Error de conexión a la base de datos";
                }
        
                
                    
                $gen_uuid = new generateUuid();
                $myuuid = $gen_uuid->guidv4();
                $customerId = substr($myuuid, 0, 8);

                // Escapa los valores para prevenir inyección SQL
                $clientId = mysqli_real_escape_string($conectar, $dta['clientId']);
                $customerName = mysqli_real_escape_string($conectar, $dta['customerName']);
                $customerComments = mysqli_real_escape_string($conectar, $dta['customerComments']);
                $customerContact = mysqli_real_escape_string($conectar, $dta['customerContact']);
                $customerMail = mysqli_real_escape_string($conectar, $dta['customerMail']);
                $customerPlace = mysqli_real_escape_string($conectar, $dta['customerPlace']);
                $customerAddres = mysqli_real_escape_string($conectar, $dta['customerAddress']);

               $customerImg = mysqli_real_escape_string($conectar, $dta['customerImg']);

                //$dato_encriptado = $keyword;
                
                $infoCustomer = [
                    [
                        "info" => [
                            "name" => $customerName,
                           
                            "comments" => $customerComments,
                            "contact" => $customerContact,
                            "email" => $customerMail,
                           
                            "img" => $customerImg,
                            "points"=>0,
                            "address"=>$customerAddres
                        ],
                        "params" => [
                            "isActive" => true,
                            "status" => true
                        ]
                    ]
                ];
                
                $jsonInfoCustomer = json_encode($infoCustomer);
               // echo $jsonInfoSite;
                
                $query = mysqli_query($conectar, "INSERT INTO generalCustomers 
                (customerId, clientId, infoCustomer,placeId) 
                VALUES
                ('$customerId', '$clientId', '$jsonInfoCustomer','$customerPlace')");

                if($query){
                            $filasAfectadas = mysqli_affected_rows($conectar);
                                if ($filasAfectadas > 0) 
                                    {
                                        // Éxito: La actualización se realizó correctamente
                                        $response="true";
                                        $message="Creación exitosa. Filas afectadas: $filasAfectadas";
                                        $apiMessage="¡creado con éxito!";
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

            if($param=="isPoint" || $param=="points" || $param=="pointsValue" || $param=="pointsOut"|| $param=="pointsAutoDiscount" || $param=="poinsDiscountTotal" || $param=="pointPrice"){
                if($param=="isPoint" || $param=="pointsAutoDiscount" || $param=="poinsDiscountTotal" ){
                        $value=filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                        $value = (bool)$value;
                        if($value===false){
                            $query = mysqli_query($conectar, "UPDATE generalPlaces 
                            SET infoPlace = JSON_SET(infoPlace, '$[0].params.$param', false) 
                            WHERE clientId = '$clientId' AND placeId = '$placeId'");
            
                        }
                        if($value===true){
                            $query = mysqli_query($conectar, "UPDATE generalPlaces 
                            SET infoPlace = JSON_SET(infoPlace, '$[0].params.$param', true) 
                            WHERE clientId = '$clientId' AND placeId = '$placeId'");
            
                        }
                    }else{
                        $value=(float)$value;
                        $query = mysqli_query($conectar, "UPDATE generalPlaces 
                        SET infoPlace = JSON_SET(infoPlace, '$[0].params.$param', $value) 
                        WHERE clientId = '$clientId' AND placeId = '$placeId'");
        
                    }
                
            }else{
            $query = mysqli_query($conectar, "UPDATE generalPlaces 
                                  SET infoPlace = JSON_SET(infoPlace, '$[0].info.$param', '$value') 
                                  WHERE clientId = '$clientId' AND placeId = '$placeId'");
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
                    }if($param=="name" || $param=="comments"){
                    $query = mysqli_query($conectar, "UPDATE generalSites 
                                          SET infoSite = JSON_SET(infoSite, '$[0].info.$param', '$value') 
                                          WHERE clientId = '$clientId' AND siteId = '$siteId'");
                    }
                    else{
                        $value = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                        $value = (bool)$value;
                        if($value===false){
                            $query = mysqli_query($conectar, "UPDATE generalSites 
                            SET infoSite = JSON_SET(infoSite, '$[0].params.$param', false) 
                            WHERE clientId = '$clientId' AND siteId = '$siteId'");
 
                        }
                        if($value===true){
                            $query = mysqli_query($conectar, "UPDATE generalSites 
                                          SET infoSite = JSON_SET(infoSite, '$[0].params.$param', true) 
                                          WHERE clientId = '$clientId' AND siteId = '$siteId'");
               
                        }
                        $value= (bool)$value;
                      
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

                                    public static function putCategory($dta) {
            
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
                                        $categoryId = mysqli_real_escape_string($conectar, $dta['categoryId']);
                                    
                                        //$dato_encriptado = $keyword;
                                        if($param=="del"){
                                            //$query = mysqli_query($conectar, "DELETE FROM generalPlaces where clientId='$clientId' and deliveryId='$deliveryId'");
                                            $apiMessage="¡Repartidor removido con éxito!";
                                        }  if($param!="del"){

                                            if($param=="type"){
                                                if($value==$categoryId){
                                                    $type="main";
                                                }
                                                if($value!=$categoryId){
                                                    $type="secondary";
                                                }
                                                $query = mysqli_query($conectar, "UPDATE generalCategories 
                                                SET parentId='$value',infoCategory = JSON_SET(infoCategory, '$[0].info.type', '$type')
                                                WHERE clientId = '$clientId' AND categoryId = '$categoryId'");
              
                                            }else{

                                            $query = mysqli_query($conectar, "UPDATE generalCategories 
                                                                  SET infoCategory = JSON_SET(infoCategory, '$[0].info.$param', '$value') 
                                                                  WHERE clientId = '$clientId' AND categoryId = '$categoryId'");
                                            }
                                            $apiMessage="¡Actualizada con éxito!";
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



                                            
                            public static function putCatalog($dta) {
            
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
                                $catalogId = mysqli_real_escape_string($conectar, $dta['catalogId']);
                            
                                //$dato_encriptado = $keyword;
                               
                                if($param=="comments" || $param == "maxQty" || $param == "minQty" || $param == "price" || $param == "stock" || $param == "securityStock" || $param == "keyWords" || $param == "isDiscount" || $param == "discount" || $param == "isPromo" || $param == "promo" || $param == "isStocked" || $param == "isInternal"){
                                    if($param=="maxQty" || $param=="minQty" || $param=="price" || $param=="stock" || $param=="securityStock" || $param=="discount"){
                                        $value=floatval($value);
                                    }
                                    if($param=="isStocked" || $param=="isPromo" || $param=="isDiscount" || $param=="isInternal"){
                                        $value = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                                        $value = (bool)$value;
                                        if($value===false){
                                            $query = mysqli_query($conectar, "UPDATE generalCatalogs 
                                            SET infoCatalog = JSON_SET(infoCatalog, '$[0].info.$param', false) 
                                            WHERE clientId = '$clientId' AND catalogId = '$catalogId'");
                             
                                        }
                                        if($value===true){
                                            $query = mysqli_query($conectar, "UPDATE generalCatalogs 
                                            SET infoCatalog = JSON_SET(infoCatalog, '$[0].info.$param', true) 
                                            WHERE clientId = '$clientId' AND catalogId = '$catalogId'");
                             
                                        }
                                    }

                                    if (is_numeric($value) || is_float($value)) {
                                        $query = mysqli_query($conectar, "UPDATE generalCatalogs 
                                        SET infoCatalog = JSON_SET(infoCatalog, '$[0].info.$param', $value) 
                                        WHERE clientId = '$clientId' AND catalogId = '$catalogId'");
                        
                        
                                    }if(is_string($value)){
                                        $query = mysqli_query($conectar, "UPDATE generalCatalogs 
                                        SET infoCatalog = JSON_SET(infoCatalog, '$[0].info.$param', '$value') 
                                        WHERE clientId = '$clientId' AND catalogId = '$catalogId'");
       

                                    }
                                    
                                    $apiMessage="¡Catálogo actualizado con éxito!";
                                }
                                if($param=="categoryId"){

                                    $query = mysqli_query($conectar, "UPDATE generalCatalogs 
                                                          SET categoryId = '$value'
                                                          WHERE clientId = '$clientId' AND catalogId = '$catalogId'");
                        
                                    $apiMessage="¡Catálogo actualizado con éxito!";
                                }
                                if($param=="productId"){

                                    $query = mysqli_query($conectar, "UPDATE generalCatalogs 
                                                          SET productId = '$value'
                                                          WHERE clientId = '$clientId' AND catalogId = '$catalogId'");
                        
                                    $apiMessage="¡Catálogo actualizado con éxito!";
                                }
                                if($param=="placeId"){

                                    $query = mysqli_query($conectar, "UPDATE generalCatalogs 
                                                          SET placeId = '$value'
                                                          WHERE clientId = '$clientId' AND catalogId = '$catalogId'");
                        
                                    $apiMessage="¡Catálogo actualizado con éxito!";
                                }
                                if($param=="isActive"){

                                    $value = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                                    $value = (bool)$value;
                                    if($value===false){
                                        $query = mysqli_query($conectar, "UPDATE generalCatalogs 
                                        SET infoCatalog = JSON_SET(infoCatalog, '$[0].params.$param', false) 
                                        WHERE clientId = '$clientId' AND catalogId = '$catalogId'");
                         
                                    }
                                    if($value===true){
                                        $query = mysqli_query($conectar, "UPDATE generalCatalogs 
                                        SET infoCatalog = JSON_SET(infoCatalog, '$[0].params.$param', true) 
                                        WHERE clientId = '$clientId' AND catalogId = '$catalogId'");
                         
                                    }
                                    $apiMessage="¡Catálogo actualizado con éxito!";
                                }

                                if($param=="del"){

                                   
                                        $query = mysqli_query($conectar, "DELETE FROM generalCatalogs 
                                        WHERE clientId = '$clientId' AND catalogId = '$catalogId'");
                         
                                   
                                    $apiMessage="¡Catálogo removido con éxito!";
                                }

                                if($param=="delStatus"){

                                   
                                    $query = mysqli_query($conectar, "UPDATE generalCatalogs 
                                        SET infoCatalog = JSON_SET(infoCatalog, '$[0].params.status', false) 
                                        WHERE clientId = '$clientId' AND catalogId = '$catalogId'");
                         
                               
                                $apiMessage="¡Catálogo oculto con éxito!";
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


                                    public static function putEmployee($dta) {
            
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
                                        $employeeId = mysqli_real_escape_string($conectar, $dta['employeeId']);
                                    
                                        //$dato_encriptado = $keyword;
                                        if($param=="del"){
                                           // $query = mysqli_query($conectar, "DELETE FROM generalPlaces where clientId='$clientId' and deliveryId='$deliveryId'");
                                            $apiMessage="¡Repartidor removido con éxito!";
                                        }  if($param!="del"){
                                            if($param=="placeId"){
                                                $query = mysqli_query($conectar, "UPDATE generalEmployees 
                                                SET placeId = '$value'
                                                WHERE clientId = '$clientId' AND employeeId = '$employeeId'");
                                            }else{
                                            $query = mysqli_query($conectar, "UPDATE generalEmployees 
                                                                  SET infoEmployee = JSON_SET(infoEmployee, '$[0].info.$param', '$value') 
                                                                  WHERE clientId = '$clientId' AND employeeId = '$employeeId'");
                                            }
                                          
                                            $apiMessage="¡Actualizado con éxito!";
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


                                            
                                    public static function putOrder($dta) {
            
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
                                        $orderId = mysqli_real_escape_string($conectar, $dta['orderId']);
                                    
                                        //$dato_encriptado = $keyword;
                                        if($param=="del"){
                                           // $query = mysqli_query($conectar, "DELETE FROM generalPlaces where clientId='$clientId' and deliveryId='$deliveryId'");
                                            $apiMessage="¡Repartidor removido con éxito!";
                                        }  if($param!="del"){
                                            if($param=="orderStatus"){

                 switch ($value) {
                                          
                                                    case "ready":
                                               
                            $query = mysqli_query($conectar, "SELECT o.orderId, o.clientId, o.siteId, o.infoOrder FROM generalOrders o WHERE o.clientId = '$clientId' AND o.orderId = '$orderId'");
                            $row2 = $query->fetch_assoc();
                            $infostatus = json_decode($row2['infoOrder'], true)[0];
                            $infoStatusOrder = $infostatus['info']['infoOrder']['orderStatus']['status'];
                           
                           
                            if($infoStatusOrder=="inProgress"){
                                $query = mysqli_query($conectar, "SELECT o.orderId, o.clientId, o.siteId, o.infoOrder FROM generalOrders o WHERE o.clientId = '$clientId' AND o.orderId = '$orderId'");

                            while ($row = $query->fetch_assoc()) {
                                $infoOrders = json_decode($row['infoOrder'], true)[0];
                                $infoProducts = $infoOrders['info']['infoProducts'];
                            
                                // Iterar sobre cada producto
                                foreach ($infoProducts as $product) {
                                    // Acceder a los valores de cada producto
                                    $catalogId = $product['product']['catalogId'];
                                    $qty = $product['product']['qty'];
                            
                                    $query1 = mysqli_query($conectar, "UPDATE generalCatalogs 
                                        SET infoCatalog = JSON_SET(infoCatalog, '$[0].info.stock', 
                                            JSON_EXTRACT(infoCatalog, '$[0].info.stock') - $qty)
                                        WHERE clientId = '$clientId' AND catalogId = '$catalogId'");
                            
                                    // Aquí puedes realizar cualquier otra operación o acceso a los datos del producto
                                    // ...
                                }
                            }
                          

                        } 
                       
                            
                        break;

                        case "inProgress":

                            $query = mysqli_query($conectar, "SELECT o.orderId, o.clientId, o.siteId, o.infoOrder FROM generalOrders o WHERE o.clientId = '$clientId' AND o.orderId = '$orderId'");
                            $row2 = $query->fetch_assoc();
                            $infostatus = json_decode($row2['infoOrder'], true)[0];
                            $infoStatusOrder = $infostatus['info']['infoOrder']['orderStatus']['status'];
                           
                           
                            if($infoStatusOrder=="ready"){
                                $query = mysqli_query($conectar, "SELECT o.orderId, o.clientId, o.siteId, o.infoOrder FROM generalOrders o WHERE o.clientId = '$clientId' AND o.orderId = '$orderId'");

                            while ($row = $query->fetch_assoc()) {
                                $infoOrders = json_decode($row['infoOrder'], true)[0];
                                $infoProducts = $infoOrders['info']['infoProducts'];
                            
                                // Iterar sobre cada producto
                                foreach ($infoProducts as $product) {
                                    // Acceder a los valores de cada producto
                                    $catalogId = $product['product']['catalogId'];
                                    $qty = $product['product']['qty'];
                            
                                    $query1 = mysqli_query($conectar, "UPDATE generalCatalogs 
                                        SET infoCatalog = JSON_SET(infoCatalog, '$[0].info.stock', 
                                            JSON_EXTRACT(infoCatalog, '$[0].info.stock') + $qty)
                                        WHERE clientId = '$clientId' AND catalogId = '$catalogId'");
                            
                                    // Aquí puedes realizar cualquier otra operación o acceso a los datos del producto
                                    // ...
                                }
                            }
                        }
                        break;


                        case "delivered":

                            $query = mysqli_query($conectar, "SELECT o.orderId, o.clientId, o.siteId, o.infoOrder FROM generalOrders o WHERE o.clientId = '$clientId' AND o.orderId = '$orderId'");
                            $row2 = $query->fetch_assoc();
                            $infostatus = json_decode($row2['infoOrder'], true)[0];
                            $infoStatusOrder = $infostatus['info']['infoOrder']['orderStatus']['status'];
                           
                           
                            if($infoStatusOrder=="ready"){
                                
                                $query = mysqli_query($conectar, "SELECT o.orderId, o.clientId, o.siteId, o.infoOrder,p.placeId,p.infoPlace FROM generalOrders o JOIN generalSites s ON s.siteId=o.siteId JOIN generalPlaces p ON p.placeId=s.placeId WHERE o.clientId = '$clientId' AND o.orderId = '$orderId'");
                                $row2 = $query->fetch_assoc();
                                $infostatus = json_decode($row2['infoOrder'], true)[0];
                                $infoplace = json_decode($row2['infoPlace'], true)[0];
                                
                                (bool)$infoplaceispoint = $infoplace['params']['isPoint'];
                                $pointsTotal = $infoplace['params']['points'];

                                $pointsValue = $infoplace['params']['pointsValue'];
                                (bool)$pointsAutoDiscount = $infoplace['params']['pointsAutoDiscount'];
                                $pointsToOut = $infoplace['params']['pointsOut'];
                                $pointPrice = $infoplace['params']['pointPrice'];
                                (bool)$totalpointsAutoDiscount = $infoplace['params']['poinsDiscountTotal'];

                                $query5 = mysqli_query($conectar, "UPDATE generalOrders 
                                SET infoOrder = JSON_SET(infoOrder, '$[0].info.infoPlace.placeStatus.isPoint', $infoplaceispoint),
                                infoOrder = JSON_SET(infoOrder, '$[0].info.infoPlace.placeStatus.pointsValue', $pointsValue) ,
                                infoOrder = JSON_SET(infoOrder, '$[0].info.infoPlace.placeStatus.pointsAutoDiscount', $pointsAutoDiscount) ,
                                infoOrder = JSON_SET(infoOrder, '$[0].info.infoPlace.placeStatus.poinsDiscountTotal', $totalpointsAutoDiscount),
                                infoOrder = JSON_SET(infoOrder, '$[0].info.infoPlace.placeStatus.pointPrice', $pointPrice),
                                infoOrder = JSON_SET(infoOrder, '$[0].info.infoPlace.placeStatus.pointsOut', $pointsToOut),
                                infoOrder = JSON_SET(infoOrder, '$[0].info.infoPlace.placeStatus.points', $pointsTotal)
                                WHERE clientId = '$clientId' AND orderId = '$orderId'");
                            
                     



                                                            }
                        break;


                        case "cancelled":

                            $query = mysqli_query($conectar, "SELECT o.orderId, o.clientId, o.siteId, o.infoOrder FROM generalOrders o WHERE o.clientId = '$clientId' AND o.orderId = '$orderId'");
                            $row2 = $query->fetch_assoc();
                            $infostatus = json_decode($row2['infoOrder'], true)[0];
                            $infoStatusOrder = $infostatus['info']['infoOrder']['orderStatus']['status'];
                           
                           
                            if($infoStatusOrder=="ready" || $infoStatusOrder=="delivered"){
                                $query = mysqli_query($conectar, "SELECT o.orderId, o.clientId, o.siteId, o.infoOrder FROM generalOrders o WHERE o.clientId = '$clientId' AND o.orderId = '$orderId'");

                            while ($row = $query->fetch_assoc()) {
                                $infoOrders = json_decode($row['infoOrder'], true)[0];
                                $infoProducts = $infoOrders['info']['infoProducts'];
                            
                                // Iterar sobre cada producto
                                foreach ($infoProducts as $product) {
                                    // Acceder a los valores de cada producto
                                    $catalogId = $product['product']['catalogId'];
                                    $qty = $product['product']['qty'];
                            
                                    $query1 = mysqli_query($conectar, "UPDATE generalCatalogs 
                                        SET infoCatalog = JSON_SET(infoCatalog, '$[0].info.stock', 
                                            JSON_EXTRACT(infoCatalog, '$[0].info.stock') + $qty)
                                        WHERE clientId = '$clientId' AND catalogId = '$catalogId'");
                            
                                    // Aquí puedes realizar cualquier otra operación o acceso a los datos del producto
                                    // ...
                                }
                            }
                        }
                        break;


                        case "finished":
                                               
                                                $query = mysqli_query($conectar, "SELECT o.orderId, o.clientId, o.siteId, o.infoOrder FROM generalOrders o WHERE o.clientId = '$clientId' AND o.orderId = '$orderId'");
                                                $row2 = $query->fetch_assoc();
                                                $infostatus = json_decode($row2['infoOrder'], true)[0];
                                                $infoStatusOrder = $infostatus['info']['infoOrder']['orderStatus']['status'];
                                                $orderBackTotal = $infostatus['info']['backPayload']['infoPayment']['total'];
                                                $customerIdInfo = $infostatus['info']['infoOrder']['customerStatus']['customerId'];

                                            
                                 if($infoStatusOrder=="delivered"){
                                                    $query = mysqli_query($conectar, "SELECT p.infoPlace FROM generalOrders o JOIN generalSites s ON o.siteId=s.siteId JOIN generalPlaces p ON p.placeId=s.placeId WHERE o.clientId = '$clientId' AND o.orderId = '$orderId'");
                                                    $row3 = $query->fetch_assoc();
                                                    $placeInfo = json_decode($row3['infoPlace'], true)[0];

                                                    $isPoint = $placeInfo['params']['isPoint'];
                                            if ($isPoint===true) {

                                                        
                                                                $points = $placeInfo['params']['points'];
                                                                $pointsValue = $placeInfo['params']['pointsValue'];
                                                                $pointsAutoDiscount = $placeInfo['params']['pointsAutoDiscount'];
                                                                $pointsToOut = $placeInfo['params']['pointsOut'];
                                                                $pointPrice = $placeInfo['params']['pointPrice'];
                                                                if ($pointsAutoDiscount===true) {
                                                                                        
                                                                                                $totalpointsAutoDiscount = $placeInfo['params']['poinsDiscountTotal'];

                                                                                                if($totalpointsAutoDiscount===true){
                                                                                                    $query = mysqli_query($conectar, "SELECT c.infoCustomer FROM generalCustomers c WHERE c.clientId = '$clientId' AND c.customerId = '$customerIdInfo'");
                                                                                                    $row4 = $query->fetch_assoc();
                                                                                                    $customerInfo = json_decode($row4['infoCustomer'], true)[0];

                                                                                                    $qtyPoints = $customerInfo['info']['points'];
                                                              
                                                                                                                
                                       
                                                                                                                    $newTotalPoints= $qtyPoints*$pointPrice;
                                                                                                                    $newTotal= $orderBackTotal-$newTotalPoints;
                                                                                                                    if($newTotalPoints>$orderBackTotal){
                                                                                                                        $pointsResult=$newTotalPoints-$orderBackTotal;//precio menos puntos
                                                                                                                        $newTotalPoints=$pointsResult;// precio de puntos acumulados totales
                                                                                                                        $newCusPoints=$qtyPoints-($orderBackTotal/$pointPrice);


                                                                                                                        $newTotal=$newTotal+$pointsResult;
                                                                                                                        $query1 = mysqli_query($conectar, "UPDATE generalCustomers 
                                                                                                                        SET infoCustomer = JSON_SET(infoCustomer, '$[0].info.points',$newCusPoints)
                                                                                                                        WHERE clientId = '$clientId' AND customerId = '$customerIdInfo'");
    
                                                                                                                    }
                                                                                                                    if($newTotalPoints<=$orderBackTotal){
                                                                                                                        $query1 = mysqli_query($conectar, "UPDATE generalCustomers 
                                                                                                                        SET infoCustomer = JSON_SET(infoCustomer, '$[0].info.points',0)
                                                                                                                        WHERE clientId = '$clientId' AND customerId = '$customerIdInfo'");
    
                          
                                                                                                                    }

                                                                                                                    $query5 = mysqli_query($conectar, "UPDATE generalOrders 
                                                                                                                    SET infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.total', $newTotal),
                                                                                                                    infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.isAutoDiscount', true) ,
                                                                                                                    infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.isPointsDiscount', true) ,
                                                                                                                    infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.customerPoints', $qtyPoints),
                                                                                                                    infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.maxPointsToRedem', $pointsToOut),

                                                                                                                    infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.pointsValue', $newTotalPoints) , 
                                                                                                                    infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.prevTotal', $orderBackTotal),
                                                                                                                    infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.isTotalPointsDiscount', true)    
                                                                                                                    WHERE clientId = '$clientId' AND orderId = '$orderId'");
                                                                                                                
                                                                                            
                                                                                                    
                                                                                                    if($qtyPoints<$pointsToOut){
                                                                                                                $newTotalPoints= $qtyPoints*$pointPrice;
                                                                                                                $newTotal= $orderBackTotal-$newTotalPoints;

                                                                                                                $query5 = mysqli_query($conectar, "UPDATE generalOrders 
                                                                                                                SET infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.total', $orderBackTotal),
                                                                                                                infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.isAutoDiscount', true) ,
                                                                                                                infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.isPointsDiscount', false) ,
                                                                                                                infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.customerPoints', $qtyPoints),
                                                                                                                infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.maxPointsToRedem', $pointsToOut),

                                                                                                                infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.pointsValue', $newTotalPoints),  
                                                                                                                infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.withPointsTotal', $newTotal),
                                                                                                                infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.isTotalPointsDiscount', true)    
                                                                                                                WHERE clientId = '$clientId' AND orderId = '$orderId'");
                                                                                        
                                                                                                                $cusQtyPoints=($orderBackTotal/$pointsValue)*$points;
                                                                                                            $query1 = mysqli_query($conectar, "UPDATE generalCustomers 
                                                                                                            SET infoCustomer = JSON_SET(infoCustomer, '$[0].info.points',$qtyPoints+$cusQtyPoints)
                                                                                                            WHERE clientId = '$clientId' AND customerId = '$customerIdInfo'");
                                                                                                    }
                                                                            
                                                                                                }
                                                                                                
                                                                                                if($totalpointsAutoDiscount===false){
                                                                                            
                                                                                                    $newTotalPoints= $qtyPoints*$pointPrice;
                                                                                                    $newTotal= $orderBackTotal-$newTotalPoints;

                                                                                                    $query5 = mysqli_query($conectar, "UPDATE generalOrders 
                                                                                                    SET infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.total', $orderBackTotal),
                                                                                                    infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.isAutoDiscount', false) ,
                                                                                                    infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.isPointsDiscount', false) ,
                                                                                                    infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.customerPoints', $qtyPoints),
                                                                                                    infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.maxPointsToRedem', $pointsToOut),

                                                                                                    infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.pointsValue', $newTotalPoints),  
                                                                                                    infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.withPointsTotal', $newTotal),
                                                                                                    infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.isTotalPointsDiscount', false)    
                                                                                                    WHERE clientId = '$clientId' AND orderId = '$orderId'");
                                                                            
                                                                                                    $cusQtyPoints=($orderBackTotal/$pointsValue)*$points;
                                                                                                $query1 = mysqli_query($conectar, "UPDATE generalCustomers 
                                                                                                SET infoCustomer = JSON_SET(infoCustomer, '$[0].info.points',$qtyPoints+$cusQtyPoints)
                                                                                                WHERE clientId = '$clientId' AND customerId = '$customerIdInfo'");
                                                                                       


                                                                                                
                                                                                                }
                                                                             }
                                                                }
                                                                if ($isPoint===false) {
                                                                   $query5 = mysqli_query($conectar, "UPDATE generalOrders 
                                                                                                                SET infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.total', $orderBackTotal),
                                                                                                                infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.isPointsDiscount', false)
                                                                                                                WHERE clientId = '$clientId' AND orderId = '$orderId'");
                                                                                        
                                                                }
                                                             
                                                    
                                                }


                                                          
                                                    
                                                
                               
                                break;

                                case "finishedAll":
                                               
                                        

                                    $query = mysqli_query($conectar, "SELECT o.orderId, o.clientId, o.siteId, o.infoOrder FROM generalOrders o WHERE o.clientId = '$clientId' AND JSON_EXTRACT(o.infoOrder, '$[0].info.infoOrder.orderStatus.orderTrackId') = '$orderId'");

                                            if ($query) {
                                                $totalPaydView=0;
                                                // Iterar sobre los resultados de la consulta
                                                while ($row2 = mysqli_fetch_assoc($query)) {
                                                    $orderIds=$row2['orderId'];
                                                    $infostatus = json_decode($row2['infoOrder'], true)[0];
                                                    $infoStatusOrder = $infostatus['info']['infoOrder']['orderStatus']['status'];
                                                    $orderBackTotal = $infostatus['info']['backPayload']['infoPayment']['total'];
                                                    $customerIdInfo = $infostatus['info']['infoOrder']['customerStatus']['customerId'];
    
                                                
                                     if($infoStatusOrder=="delivered"){
   
                                                        $query = mysqli_query($conectar, "SELECT p.infoPlace FROM generalOrders o JOIN generalSites s ON o.siteId=s.siteId JOIN generalPlaces p ON p.placeId=s.placeId WHERE o.clientId = '$clientId' AND o.orderId = '$orderIds'");
                                                        $row3 = $query->fetch_assoc();
                                                        $placeInfo = json_decode($row3['infoPlace'], true)[0];
    
                                                        $isPoint = $placeInfo['params']['isPoint'];
                                                if ($isPoint===true) {
    
                                                            
                                                                    $points = $placeInfo['params']['points'];
                                                                    $pointsValue = $placeInfo['params']['pointsValue'];
                                                                    $pointsAutoDiscount = $placeInfo['params']['pointsAutoDiscount'];
                                                                    $pointsToOut = $placeInfo['params']['pointsOut'];
                                                                    $pointPrice = $placeInfo['params']['pointPrice'];
                                                                    if ($pointsAutoDiscount===true) {
                                                                                            
                                                                                                    $totalpointsAutoDiscount = $placeInfo['params']['poinsDiscountTotal'];
    
                                                                                                    if($totalpointsAutoDiscount===true){
                                                                                                        $query = mysqli_query($conectar, "SELECT c.infoCustomer FROM generalCustomers c WHERE c.clientId = '$clientId' AND c.customerId = '$customerIdInfo'");
                                                                                                        $row4 = $query->fetch_assoc();
                                                                                                        $customerInfo = json_decode($row4['infoCustomer'], true)[0];
    
                                                                                                        $qtyPoints = $customerInfo['info']['points'];
                                                                  
                                                                                                                    
                                           
                                                                                                                        $newTotalPoints= $qtyPoints*$pointPrice;
                                                                                                                        $newTotal= $orderBackTotal-$newTotalPoints;
                                                                                                                        if($newTotalPoints>$orderBackTotal){
                                                                                                                            $pointsResult=$newTotalPoints-$orderBackTotal;//precio menos puntos
                                                                                                                            $newTotalPoints=$pointsResult;// precio de puntos acumulados totales
                                                                                                                            $newCusPoints=$qtyPoints-($orderBackTotal/$pointPrice);
    
    
                                                                                                                            $newTotal=$newTotal+$pointsResult;
                                                                                                                            $query1 = mysqli_query($conectar, "UPDATE generalCustomers 
                                                                                                                            SET infoCustomer = JSON_SET(infoCustomer, '$[0].info.points',$newCusPoints)
                                                                                                                            WHERE clientId = '$clientId' AND customerId = '$customerIdInfo'");
        
                                                                                                                        }
                                                                                                                        if($newTotalPoints<=$orderBackTotal){
                                                                                                                            $query1 = mysqli_query($conectar, "UPDATE generalCustomers 
                                                                                                                            SET infoCustomer = JSON_SET(infoCustomer, '$[0].info.points',0)
                                                                                                                            WHERE clientId = '$clientId' AND customerId = '$customerIdInfo'");
        
                              
                                                                                                                        }
    
                                                                                                                        $query5 = mysqli_query($conectar, "UPDATE generalOrders 
                                                                                                                        SET infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.total', $newTotal),
                                                                                                                        infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.isAutoDiscount', true) ,
                                                                                                                        infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.isPointsDiscount', true) ,
                                                                                                                        infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.customerPoints', $qtyPoints),
                                                                                                                        infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.maxPointsToRedem', $pointsToOut),
    
                                                                                                                        infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.pointsValue', $newTotalPoints) , 
                                                                                                                        infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.prevTotal', $orderBackTotal),
                                                                                                                        infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.isTotalPointsDiscount', true)    
                                                                                                                        WHERE clientId = '$clientId' AND orderId = '$orderIds'");
                                                                                                                        $totalPaydView=$totalPaydView+$newTotal;
                                                                                                                    
                                                                                                
                                                                                                        
                                                                                                        if($qtyPoints<$pointsToOut){
                                                                                                                    $newTotalPoints= $qtyPoints*$pointPrice;
                                                                                                                    $newTotal= $orderBackTotal-$newTotalPoints;
    
                                                                                                                    $query5 = mysqli_query($conectar, "UPDATE generalOrders 
                                                                                                                    SET infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.total', $orderBackTotal),
                                                                                                                    infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.isAutoDiscount', true) ,
                                                                                                                    infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.isPointsDiscount', false) ,
                                                                                                                    infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.customerPoints', $qtyPoints),
                                                                                                                    infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.maxPointsToRedem', $pointsToOut),
    
                                                                                                                    infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.pointsValue', $newTotalPoints),  
                                                                                                                    infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.withPointsTotal', $newTotal),
                                                                                                                    infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.isTotalPointsDiscount', true)    
                                                                                                                    WHERE clientId = '$clientId' AND orderId = '$orderIds'");
                                                                                                                    $totalPaydView=$totalPaydView+$orderBackTotal;
                                                                                                                    $cusQtyPoints=($orderBackTotal/$pointsValue)*$points;
                                                                                                                $query1 = mysqli_query($conectar, "UPDATE generalCustomers 
                                                                                                                SET infoCustomer = JSON_SET(infoCustomer, '$[0].info.points',$qtyPoints+$cusQtyPoints)
                                                                                                                WHERE clientId = '$clientId' AND customerId = '$customerIdInfo'");
                                                                                                        }
                                                                                
                                                                                                    }
                                                                                                    
                                                                                                    if($totalpointsAutoDiscount===false){
                                                                                                
                                                                                                        $newTotalPoints= $qtyPoints*$pointPrice;
                                                                                                        $newTotal= $orderBackTotal-$newTotalPoints;
    
                                                                                                        $query5 = mysqli_query($conectar, "UPDATE generalOrders 
                                                                                                        SET infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.total', $orderBackTotal),
                                                                                                        infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.isAutoDiscount', false) ,
                                                                                                        infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.isPointsDiscount', false) ,
                                                                                                        infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.customerPoints', $qtyPoints),
                                                                                                        infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.maxPointsToRedem', $pointsToOut),
    
                                                                                                        infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.pointsValue', $newTotalPoints),  
                                                                                                        infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.withPointsTotal', $newTotal),
                                                                                                        infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.isTotalPointsDiscount', false)    
                                                                                                        WHERE clientId = '$clientId' AND orderId = '$orderIds'");
                                                                                $totalPaydView=$totalPaydView+$orderBackTotal;
                                                                                                        $cusQtyPoints=($orderBackTotal/$pointsValue)*$points;
                                                                                                    $query1 = mysqli_query($conectar, "UPDATE generalCustomers 
                                                                                                    SET infoCustomer = JSON_SET(infoCustomer, '$[0].info.points',$qtyPoints+$cusQtyPoints)
                                                                                                    WHERE clientId = '$clientId' AND customerId = '$customerIdInfo'");
                                                                                           
    
    
                                                                                                    
                                                                                                    }
                                                                                 }
                                                                    }
                                                                    if ($isPoint===false) {
                                                                       $query5 = mysqli_query($conectar, "UPDATE generalOrders 
                                                                                                                    SET infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.total', $orderBackTotal),
                                                                                                                    infoOrder = JSON_SET(infoOrder, '$[0].info.backPayload.infoPayment.isPointsDiscount', false)
                                                                                                                    WHERE clientId = '$clientId' AND orderId = '$orderIds'");
                                                                          $totalPaydView=$totalPaydView+$orderBackTotal;                  
                                                                    }
                                                                 
                                                        
                                                    }
                                                    
                                                }

                                                
                                                $query5 = mysqli_query($conectar, "UPDATE generalOrders 
                                                SET infoOrder = JSON_SET(infoOrder, '$[0].info.infoOrder.orderStatus.status', 'finished') 
                                                WHERE clientId = '$clientId' AND JSON_EXTRACT(infoOrder, '$[0].info.infoOrder.orderStatus.orderTrackId') = '$orderId'");
                                              
                                                
                                            } else {
                                                // Manejar el caso de error en la consulta
                                                echo "Error en la consulta SQL: " . mysqli_error($conectar);

                                                $generalMessage = mysqli_error($conectar);
                                            }

                                          
                                              
                                                 break;
                        
                                default:
                                                }
                                                $query5 = mysqli_query($conectar, "UPDATE generalOrders 
                                                SET infoOrder = JSON_SET(infoOrder, '$[0].info.infoOrder.orderStatus.status', '$value') 
                                                WHERE clientId = '$clientId' AND orderId = '$orderId'");
                                                                $generalMessage="Orden actualizada exitosamente";

          
                            } 
                       
                            if($param=="paymentType" || $param=="paymentMethod" || $param=="paymentCompany" || $param=="transactionCode" || $param=="otherMethod" || $param=="methodDetails" || $param=="cryptoName" || $param=="cryptoUser" || $param=="cryptoValue" || $param=="payWith" || $param=="change"){

                               
                                    $query5 = mysqli_query($conectar, "UPDATE generalOrders 
                                    SET infoOrder = JSON_SET(infoOrder, '$[0].info.infoAccount.accountStatus.$param', '$value')
                                    WHERE clientId = '$clientId' AND orderId = '$orderId'");
                                                    $generalMessage=$param." actualizado exitosamente";

                                    
                                   
                            }
                            if($param=="paymentStatus"){

                               
                                                    $query = mysqli_query($conectar, "SELECT o.orderId, o.clientId, o.siteId, o.infoOrder FROM generalOrders o WHERE o.clientId = '$clientId' AND o.orderId = '$orderId'");
                                                    $row2 = $query->fetch_assoc();
                                                    $infostatus = json_decode($row2['infoOrder'], true)[0];
                                                    $infoStatusOrder = $infostatus['info']['infoOrder']['orderStatus']['status'];
                                                    if($infoStatusOrder=="finished"){
                                                        $query5 = mysqli_query($conectar, "UPDATE generalOrders 
                                                        SET infoOrder = JSON_SET(infoOrder, '$[0].info.infoOrder.paymentStatus.status', '$value')
                                                        WHERE clientId = '$clientId' AND orderId = '$orderId'");
                                                            $generalMessage="Pago realizado exitosamente";
                                                    }
                                                    else{
                                                    
                                                            $generalMessage="No se ha finalizado la orden";
                                                    }

                                              
                                         }
                        

                        
                       
                    
                    }
                             
                        
                  
                                       // $query = mysqli_query($conectar, "UPDATE generalDelivery SET $param='$value' where clientId='$clientId' and deliveryId='$deliveryId'");
                                    
                                        if($query5){
                                            $filasAfectadas = mysqli_affected_rows($conectar);
                                            if ($filasAfectadas > 0) {
                                                // Éxito: La actualización se realizó correctamente
                                            $response="true";
                                            $message="Actualización exitosa. Filas afectadas: $filasAfectadas";
                                            $apiMessage=$generalMessage;
                                     
                                                $status="201";
                                            } else {
                                                $response="false";
                                            $message="Actualización no exitosa. Filas afectadas: $filasAfectadas";
                                                $status="500";
                                                $apiMessage=$generalMessage;
                                            }
                                        //  return "true";
                                        //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
                                        }else{
                                            $response="false";
                                            $message="Error en la actualización: " . mysqli_error($conectar);
                                            $status="404";
                                            $apiMessage="¡no actualizado con éxito!";
                                        
                                                            }
                                
                                                            $values=[];
                                
                                                            $value=[
                                                                'response' => $response,
                                                                'message' => $message,
                                                                'apiMessage' => $apiMessage,
                                                                'status' => $status,
                                                                'retrivePayment' => $totalPaydView
                                                                
                                                            ];
                                                            
                                                            array_push($values,$value);
                                                            
                                                
                                                    //echo json_encode($students) ;
                                                    return json_encode(['response'=>$values]);
                                
      }
                                        
                                        
    }
    
?>