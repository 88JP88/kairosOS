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


public static function postDelivery($dta) {
    
           


        // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
    
        // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
        $conectar = conn();

        // Verifica si la conexión se realizó correctamente
        if (!$conectar) {
            return "Error de conexión a la base de datos";
        }

        
            
        $gen_uuid = new generateUuid();
        $myuuid = $gen_uuid->guidv4();
        $deliveryId = substr($myuuid, 0, 8);

        // Escapa los valores para prevenir inyección SQL
        $clientId = mysqli_real_escape_string($conectar, $dta['clientId']);
        $deliveryName = mysqli_real_escape_string($conectar, $dta['deliveryName']);
        $deliveryLastName = mysqli_real_escape_string($conectar, $dta['deliveryLastName']);
        $deliveryMail = mysqli_real_escape_string($conectar, $dta['deliveryMail']);
        $deliveryContact = mysqli_real_escape_string($conectar, $dta['deliveryContact']);
        

        $query = mysqli_query($conectar, "INSERT INTO generalDelivery 
        (deliveryId, clientId, deliveryName, deliveryLastName,deliveryMail,deliveryContact) 
        VALUES
        ('$deliveryId', '$clientId', '$deliveryName', '$deliveryLastName', '$deliveryMail','$deliveryContact')");

        if($query){
            $filasAfectadas = mysqli_affected_rows($conectar);
            if ($filasAfectadas > 0) {
                // Éxito: La actualización se realizó correctamente
            $response="true";
            $message="Creación exitosa. Filas afectadas: $filasAfectadas";
            $apiMessage="¡Repartidor creado con éxito!";
                $status="201";
            } else {
                $response="false";
            $message="Creación no exitosa. Filas afectadas: $filasAfectadas";
                $status="500";
                $apiMessage="¡Repartidor no credo con éxito!";
            }
        //  return "true";
        //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
        }else{
            $response="true";
            $message="Error en la actualización: " . mysqli_error($conectar);
            $status="404";
            $apiMessage="¡Repartidor no creado con éxito!";
        
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

    public static function sendValidationEcmCode($dta) {
            
                
        // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
        
            // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
            $conectar = conn();
    
            // Verifica si la conexión se realizó correctamente
            if (!$conectar) {
                return "Error de conexión a la base de datos";
            }
    
            
                
            $gen_uuid = new generateUuid();
            $myuuid = $gen_uuid->guidv4();
          

            // Escapa los valores para prevenir inyección SQL
            $clientId = mysqli_real_escape_string($conectar, $dta['clientId']);
            $customerMail = mysqli_real_escape_string($conectar, $dta['customerMail']);
           //$dato_encriptado = $keyword;
            
    
         $query = mysqli_query($conectar, "SELECT customerId FROM generalCustomers WHERE customerMail='$customerMail' and clientId='$clientId'");

            $num_rows = mysqli_num_rows($query);
            if($query){
                      //  $filasAfectadas = mysqli_affected_rows($conectar);
                            if ($num_rows > 0) 
                                {

                                    $valCode = substr($myuuid, 0, 8);
                                    sendMail::sendConfirmationOrderCodeMail('confirmation@lugma.tech',$customerMail,'Código de confirmación para compra','Tu Código de confirmación es: <strong>' . $valCode.'</strong>');
                                    // Éxito: La actualización se realizó correctamente
                                    $query = mysqli_query($conectar, "UPDATE generalCustomers SET ecmCode='$valCode' WHERE clientId='$clientId' AND customerMail='$customerMail'");

                                   
                                    $response="true";
                                    $message="Envío exitoso.";
                                    $apiMessage="¡Código enviado con éxito al correo $customerMail!|validMail";
                                    $status="201";
                                } 
                                else {
                                    $response="false";
                                    $message="Envío no exitoso.";
                                    $status="500";
                                    $apiMessage="¡Debes registrarte como cliente!|invalidMail";
                                    }
                    //  return "true";
                    //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
            }
            else{
                    $response="true";
                    $message="Error en la actualización: " . mysqli_error($conectar);
                    $status="404";
                    $apiMessage="¡Envío no exitoso!";
                
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
    public static function validationEcmCode($dta) {
            
                
        // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
        
            // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
            $conectar = conn();
    
            // Verifica si la conexión se realizó correctamente
            if (!$conectar) {
                return "Error de conexión a la base de datos";
            }
    
            
                
            $gen_uuid = new generateUuid();
            $myuuid = $gen_uuid->guidv4();
          

            // Escapa los valores para prevenir inyección SQL
            $clientId = mysqli_real_escape_string($conectar, $dta['clientId']);
            $customerMail = mysqli_real_escape_string($conectar, $dta['customerMail']);
            $valCode = mysqli_real_escape_string($conectar, $dta['valCode']);
           //$dato_encriptado = $keyword;
            
    
           $query = mysqli_query($conectar, "SELECT customerId,codeAttemps from generalCustomers WHERE customerMail='$customerMail' AND clientId='$clientId' and ecmCode='$valCode'");
            
           // Verificar si la consulta fue exitosa
           $fila = $query->fetch_assoc();

               // Obtener la primera fila como un arreglo asociativo
               $num_rows = mysqli_num_rows($query);
            if($query){
                      //  $filasAfectadas = mysqli_affected_rows($conectar);
                            if ($num_rows > 0) 
                                {
                                    $attemps=$fila['codeAttemps'];
                                    if($attemps<=3){
                                        $query = mysqli_query($conectar, "UPDATE generalCustomers SET ecmCode='0',codeAttemps=0 where clientId='$clientId' and customerId IN (SELECT customerId WHERE customerMail='$customerMail' and clientId='$clientId')");

                                       
                                        sendMail::sendConfirmationOrderCodeMail('confirmation@lugma.tech',$customerMail,'Código de confirmación para compra','<strong>Tu compra ha sido validada.</strong>');
                                        $response="true";
                                        $message="Envío exitoso.";
                                        $apiMessage="¡Código validado con éxito!";
                                        $statusCode="validatedMail";
                                        $status="202";
                                    
                                    }else{

                                        $query = mysqli_query($conectar, "UPDATE generalCustomers SET codeAttemps=0 where clientId='$clientId' and customerMail='$customerMail'");
                   
                                        sendMail::sendConfirmationOrderCodeMail('confirmation@lugma.tech',$customerMail,'Código de confirmación para compra','Genera un nuevo código, exediste el número máximo de intentos.');

                                        $response="false";
                                        $message="Envío exitoso.";
                                        $apiMessage="¡Exediste el número de intentos máximos!";
                                        $statusCode="codeAttemps";
                                        $status="501";
                                    }

                                  
                                   
                                } 
                                else {


                                    $query = mysqli_query($conectar, "UPDATE generalCustomers SET codeAttemps=(SELECT codeAttemps FROM generalCustomers WHERE customerMail='$customerMail' AND clientId='$clientId')+1 where clientId='$clientId' and customerMail='$customerMail'");
                                    $query3 = mysqli_query($conectar, "SELECT customerId,codeAttemps from generalCustomers WHERE customerMail='$customerMail' AND clientId='$clientId'");
                            
                            // Verificar si la consulta fue exitosa
                            $fila = $query3->fetch_assoc();
                            $attemps=$fila['codeAttemps'];
                            if($attemps>=3){

                                $query = mysqli_query($conectar, "UPDATE generalCustomers SET codeAttemps=0 where clientId='$clientId' and customerMail='$customerMail'");
                                sendMail::sendConfirmationOrderCodeMail('confirmation@lugma.tech',$customerMail,'Código de confirmación para compra','Genera un nuevo código, exediste el número máximo de intentos.');

                                $response="false";
                                $message="Envío no exitoso.";
                                $status="501";
                                $apiMessage="¡Exediste el número de intentos máximos!";
                                $statusCode="codeAttemps";
                            }else{
                                sendMail::sendConfirmationOrderCodeMail('confirmation@lugma.tech',$customerMail,'Código de confirmación para compra','Código incorrecto.');

                                    $response="false";
                                    $message="Envío no exitoso.";
                                    $status="501";
                                    $apiMessage="¡Código o correo incorrecto!";
                                    $statusCode="invalidMailCode";
                            }

                                    
                                    }
                    //  return "true";
                    //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
            }
            else{
                    $response="true";
                    $message="Error en la actualización: " . mysqli_error($conectar);
                    $status="404";
                    $apiMessage="¡Envío no exitoso!";
                
                }

                $values=[];

                $value=[
                    'response' => $response,
                    'message' => $message,
                    'apiMessage' => $apiMessage,
                    'status' => $status,
                    'statusCode'=>$statusCode
                    
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
            $query = mysqli_query($conectar, "DELETE FROM generalPlaces where clientId='$clientId' and deliveryId='$deliveryId'");
            $apiMessage="¡Repartidor removido con éxito!";
        }  if($param!="del"){
            $query = mysqli_query($conectar, "UPDATE generalPlaces 
                                  SET infoPlace = JSON_SET(infoPlace, '$[0].info.$param', '$value') 
                                  WHERE clientId = '$clientId' AND placeId = '$placeId'");

            $apiMessage="¡Ubicación actualizado con éxito!";
        }

       // $query = mysqli_query($conectar, "UPDATE generalDelivery SET $param='$value' where clientId='$clientId' and deliveryId='$deliveryId'");
    
        if($query){
            $filasAfectadas = mysqli_affected_rows($conectar);
            if ($filasAfectadas > 0) {
                // Éxito: La actualización se realizó correctamente
            $response="true";
            $message="Actualización exitosa. Filas afectadas: $filasAfectadas";
            $apiMessage="¡Repartidor actualizado con éxito!";
     
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
            public static function putCustomer($dta) {
            
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
                $customerId = mysqli_real_escape_string($conectar, $dta['customerId']);
            
                //$dato_encriptado = $keyword;
                
        
                $query = mysqli_query($conectar, "UPDATE generalCustomers SET $param='$value' where clientId='$clientId' and customerId='$customerId'");

                
                if($query){
                    $filasAfectadas = mysqli_affected_rows($conectar);
                    if ($filasAfectadas > 0) {
                        // Éxito: La actualización se realizó correctamente
                    $response="true";
                    $message="Actualización exitosa. Filas afectadas: $filasAfectadas";
                    $apiMessage="¡Cliente actualizado con éxito!";
                        $status="201";
                    } else {
                        $response="false";
                    $message="Actualización no exitosa. Filas afectadas: $filasAfectadas";
                        $status="500";
                        $apiMessage="¡Cliente no actualizado con éxito!";
                    }
                //  return "true";
                //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
                }else{
                    $response="true";
                    $message="Error en la actualización: " . mysqli_error($conectar);
                    $status="404";
                    $apiMessage="¡Cliente67yhb no actualizado con éxito!";
                
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
                
        
                if($param=="isEcommerce" && $value=="1" || $param=="isPos" && $value=="1"){
                    $query = mysqli_query($conectar, "UPDATE generalProducts SET $param='$value' ,isStocked=0,isInternal=0 where clientId='$clientId' and productId='$productId'");

                }
                if($param=="isStocked" && $value=="1"){
                    $query = mysqli_query($conectar, "UPDATE generalProducts SET $param='$value' ,isEcommerce=0,isPos=0,isInternal=0 where clientId='$clientId' and productId='$productId'");

                }
                if($param=="isInternal" && $value=="1"){
                    $query = mysqli_query($conectar, "UPDATE generalProducts SET $param='$value' ,isEcommerce=0,isPos=0,isStocked=0 where clientId='$clientId' and productId='$productId'");

                }else{
                    $query = mysqli_query($conectar, "UPDATE generalProducts SET $param='$value' where clientId='$clientId' and productId='$productId'");

                }
                if($query){
                    $filasAfectadas = mysqli_affected_rows($conectar);
                    if ($filasAfectadas > 0) {
                        // Éxito: La actualización se realizó correctamente
                    $response="true";
                    $message="Actualización exitosa. Filas afectadas: $filasAfectadas";
                    $apiMessage="¡Producto actualizado con éxito!";
                        $status="201";
                    } else {
                        $response="false";
                    $message="Actualización no exitosa. Filas afectadas: $filasAfectadas";
                        $status="500";
                        $apiMessage="¡Producto no actualizado con éxito!";
                    }
                //  return "true";
                //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
                }else{
                    $response="true";
                    $message="Error en la actualización: " . mysqli_error($conectar);
                    $status="404";
                    $apiMessage="¡Producto no actualizado con éxito!";
                
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

                    public static function putOrderPaymentStatus($dta) {
            
                        // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
                    
                        // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
                        $conectar = conn();
                
                        // Verifica si la conexión se realizó correctamente
                        if (!$conectar) {
                            return "Error de conexión a la base de datos";
                        }
                
                        
                
                        // Escapa los valores para prevenir inyección SQL
                        $clientId = mysqli_real_escape_string($conectar, $dta['clientId']);
                        $orderId = mysqli_real_escape_string($conectar, $dta['orderId']);
                        $reference = mysqli_real_escape_string($conectar, $dta['reference']);
                    
                        //$dato_encriptado = $keyword;
                        
                        $query = mysqli_query($conectar, "UPDATE generalOrders SET transactionStatus='DONE',paymentReference='$reference',orderProgress='DONE' where clientId='$clientId' and orderId='$orderId'");

                        if($query){
                            $filasAfectadas = mysqli_affected_rows($conectar);
                            if ($filasAfectadas > 0) {
                                // Éxito: La actualización se realizó correctamente
                            $response="true";
                            $message="Actualización exitosa. Filas afectadas: $filasAfectadas";
                            $apiMessage="¡Pago actualizado con éxito!";
                                $status="201";
                            } else {
                                $response="false";
                            $message="Actualización no exitosa. Filas afectadas: $filasAfectadas";
                                $status="500";
                                $apiMessage="¡Pago no actualizado con éxito!";
                            }
                        //  return "true";
                        //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
                        }else{
                            $response="true";
                            $message="Error en la actualización: " . mysqli_error($conectar);
                            $status="404";
                            $apiMessage="¡Pago no actualizado con éxito!";
                        
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
                            public static function putOrderStatus($dta) {
            
                                // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
                            
                                // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
                                $conectar = conn();
                        
                                // Verifica si la conexión se realizó correctamente
                                if (!$conectar) {
                                    return "Error de conexión a la base de datos";
                                }
                        
                                
                        
                                // Escapa los valores para prevenir inyección SQL
                                $clientId = mysqli_real_escape_string($conectar, $dta['clientId']);
                                $orderId = mysqli_real_escape_string($conectar, $dta['orderId']);
                                $param = mysqli_real_escape_string($conectar, $dta['param']);
                                $value = mysqli_real_escape_string($conectar, $dta['value']);
                            
                                //$dato_encriptado = $keyword;
                                
                                                                
                                        if($param=="deliveryStatus"){

                                            $query = mysqli_query($conectar, "UPDATE generalOrders SET deliveryStatus='assigned' where clientId='$clientId' and orderId='$orderId'");

                                        }
                                        if($param=="deliveryPerson"){

                                            $query = mysqli_query($conectar, "UPDATE generalOrders SET $param='$value',deliveryStatus='undefined' where clientId='$clientId' and orderId='$orderId'");

                                        }
                                        if($param=="orderProgress"){

                                            $query = mysqli_query($conectar, "UPDATE generalOrders SET $param='$value',deliveryStatus='undefined' where clientId='$clientId' and orderId='$orderId'");

                                        }
                                if($query){
                                    $filasAfectadas = mysqli_affected_rows($conectar);
                                    if ($filasAfectadas > 0) {

                                    $query9 = mysqli_query($conectar, "SELECT gor.incId,gc.customerMail,gs.storeName,gor.deliveryAdd,gor.deliveryMethod,gc.customerName,gc.customerLastName from generalOrders gor JOIN generalCustomers gc ON gc.customerId=gor.shopperId JOIN generalStores gs ON gs.storeId=gor.storeId WHERE gor.orderId='$orderId' AND gor.clientId='$clientId'");

                                    // Verificar si la consulta fue exitosa
                                    
                                        // Obtener la primera fila como un arreglo asociativo
                                        $fila9 = $query9->fetch_assoc();
                                    
                                        // Verificar si la fila tiene datos
                                        if ($fila9) {
                                            // Obtener el valor de la columna 'coId'
                                            $orNumber = $fila9['incId'];
                                            $cusMail = $fila9['customerMail'];
                                            $stName = $fila9['storeName'];
                                            $delMeth = $fila9['deliveryMethod'];
                                            $delAdd = $fila9['deliveryAdd'];
                                            $cusname = $fila9['customerName'];
                                            $cuslname = $fila9['customerLastName'];
                                            $data = json_decode($delAdd, true);
                                            foreach ($data as $item) {
                                                $deliveryAdd = $item['deliveryAdd'];
                                            
                                                $startStreet = $deliveryAdd['startStreet'];
                                                $startAvenue = $deliveryAdd['startAvenue'];
                                                $context = $deliveryAdd['context'];
                                                $paramOne = $deliveryAdd['paramOne'];
                                                $paramSecond = $deliveryAdd['paramSecond'];
                                                $paramOneBis = $deliveryAdd['paramOneBis'];
                                                $paramSecondBis = $deliveryAdd['paramSecondBis'];
                                                $paramOneLet = $deliveryAdd['paramOneLet'];
                                                $paramSecondLet = $deliveryAdd['paramSecondLet'];
                                                $paramDescription = $deliveryAdd['paramDescription'];
                                            
                                               $delAdd= $paramOne. " ". $startStreet."".$paramOneLet. " ".$paramOneBis."<br>".$paramSecond." # ".$startAvenue."".$paramSecondLet." ".$paramSecondBis."<br>Casa ".$context;
                                              
                                            
                                                // ... y así sucesivamente con los demás valores
                                            }
                                           
                        
                                        // echo "El valor máximo de incId es: " . $valor;
                                        } else {
                                        //  echo "No se encontraron datos.";
                                        }
                                                                if($value=="in_progress"){
                                                                            $stateorder="EN PROGRESO";
                                                                            $colorstate="orange";
                                                                }
                                                                if($value=="packing"){
                                                                    $stateorder="SELECCIONANDO PRODUCTOS";
                                                                    $colorstate="DarkSalmon";
                                                        }
                                                        if($value=="ready"){
                                                            $stateorder="LISTA";
                                                            $colorstate="green";
                                                        }
                                                        if($value=="on_way"){
                                                            $stateorder="EN CAMINO";
                                                            $colorstate="blue";
                                                        }
                                                        if($value=="delivered"){
                                                            $stateorder="ENTREGADA";
                                                            $colorstate="#a3e4d7";
                                                        }
                                                        if($value=="done"){
                                                            $stateorder="FINALIZADA";
                                                            $colorstate="#d4e6f1";
                                                        }
                                                        if($value=="canceled"){
                                                            $stateorder="CANCELADA";
                                                            $colorstate="#cd6155";
                                                        }
                                                        
                        
                                   
                        
                                     if($param=="deliveryStatus"){
                        
                        
                                          $query99 = mysqli_query($conectar, "SELECT deliveryName,deliveryLastName,deliveryMail,deliveryContact FROM generalDelivery WHERE clientId='$clientId' and deliveryId='$value'");
                        
                                    // Verificar si la consulta fue exitosa
                                    
                                        // Obtener la primera fila como un arreglo asociativo
                                        $fila99 = $query99->fetch_assoc();
                                    
                                        // Verificar si la fila tiene datos
                                        if ($fila99) {
                                            // Obtener el valor de la columna 'coId'
                                            $delname = $fila99['deliveryName'];
                                            $dellname = $fila99['deliveryLastName'];
                                            $delmail = $fila99['deliveryMail'];
                                            $delcontact = $fila99['deliveryContact'];
                        
                                           
                        
                                        // echo "El valor máximo de incId es: " . $valor;
                                        } else {
                                        //  echo "No se encontraron datos.";
                                        }
                                        $finishedMsg = "Validación de estado de orden con ID <strong>$orderId</strong> con número consecutivo <strong>$orNumber</strong>. <br/><br>Estado de orden: <h3 style='color: green;'>ASIGNADA A DOMICILIARIO $delname $dellname</h3><br/>Tienda: $stName <br>Método de entrega: $delMeth <br>Dirección de entrega: $delAdd";
                                        
                                        sendMail::sendConfirmationOrderMail("confirmation@lugma.tech",$cusMail,"Confirmación de orden #",$finishedMsg,$orderId);
                        
                                        $finishedMsg = "Asignación de orden con ID <strong>$orderId</strong> con número consecutivo <strong>$orNumber</strong>. <br/><br>Estado de orden: <h3 style='color: green;'>ASIGNADA para el cliente $cusname $cuslname</h3><br/>Tienda: $stName <br>Método de entrega: $delMeth <br>Dirección de entrega: $delAdd";
                                        
                                        sendMail::sendConfirmationOrderMail("confirmation@lugma.tech",$cusMail,"Confirmación de orden #",$finishedMsg,$orderId);
                                     }
                        
                        
                                     if($param=="orderProgress"){
                                        $finishedMsg = "Validación de estado de orden con ID <strong>$orderId</strong> con número consecutivo <strong>$orNumber</strong>. <br/><br>Estado de orden: <h3 style='color: $colorstate;'>$stateorder</h3><br/>Tienda: $stName <br>Método de entrega: $delMeth <br>Dirección de entrega: $delAdd";
                                        
                                        sendMail::sendConfirmationOrderMail("confirmation@lugma.tech",$cusMail,"Confirmación de orden #",$finishedMsg,$orderId);
                               
                                     }


                                   
                                        // Éxito: La actualización se realizó correctamente
                                    $response="true";
                                    $message="Actualización exitosa. Filas afectadas: $filasAfectadas";
                                    $apiMessage="¡Estado de orden actualizado con éxito!";
                                        $status="201";
                                    } else {
                                        $response="false";
                                    $message="Actualización no exitosa. Filas afectadas: $filasAfectadas";
                                        $status="500";
                                        $apiMessage="¡Estado de orden no actualizado con éxito!";
                                    }
                                //  return "true";
                                //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
                                }else{
                                    $response="true";
                                    $message="Error en la actualización: " . mysqli_error($conectar);
                                    $status="404";
                                    $apiMessage="¡Estado de orden no actualizado con éxito!";
                                
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