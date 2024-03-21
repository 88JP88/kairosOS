<?php
    require_once 'database/db_users.php';
    require_once 'model/modelSecurity/uuid/uuidd.php';
    require_once 'model/users/sendMail.php';
class modelPost {
          
        public static function postOrder($dta) {
            
                


                                // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
                                
                                    // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
                                    $conectar = conn();
                            
                                    // Verifica si la conexión se realizó correctamente
                                    if (!$conectar) {
                                        return "Error de conexión a la base de datos";
                                    }
                            
                                   
                                        
                                                                      // Escapa los valores para prevenir inyección SQL


                                    // Escapa los valores para prevenir inyección SQL
                                    $clientId = mysqli_real_escape_string($conectar, $dta['clientId']);
                                    $cart = mysqli_real_escape_string($conectar, $dta['cart']);
                                    $userId = mysqli_real_escape_string($conectar, $dta['userId']);
                                    $fromIp = mysqli_real_escape_string($conectar, $dta['fromIp']);
                                    $fromBrowser = mysqli_real_escape_string($conectar, $dta['fromBrowser']);
                                    $customerId = mysqli_real_escape_string($conectar, $dta['customerId']);
                                    $paymentMethod = mysqli_real_escape_string($conectar, $dta['paymentMethod']);
                                    $paymentType = mysqli_real_escape_string($conectar, $dta['paymentType']);
                                    $payWith = mysqli_real_escape_string($conectar, $dta['payWith']);
                                    $bankEntity = mysqli_real_escape_string($conectar, $dta['bankEntity']);
                                    
                                    //$dato_encriptado = $keyword;
                                    
                                    $gen_uuid = new generateUuid();
                                    $myuuid = $gen_uuid->guidv4();
                                    $myuuid1 = $gen_uuid->guidv4();
                                
    
                                    $cartId = substr($myuuid, 0, 8);
                                    $orderId = substr($myuuid1, 0, 8);
    
                                    date_default_timezone_set('America/Bogota');
                                    $hora_actual_bogota = date('H:i:s');
                                    $fechaActual = gmdate('Y-m-d'); // Esto devuelve la fecha actual en formato 'YYYY-MM-DD'
                
                                    // Crea un objeto DateTime con la fecha actual en UTC
                                    $dateTimeUtc = new DateTime($fechaActual, new DateTimeZone('UTC'));
                
                                    // Establece la zona horaria a Bogotá
                                    $dateTimeUtc->setTimezone(new DateTimeZone('America/Bogota'));
                
                                    // Obtiene la fecha en la zona horaria de Bogotá
                                    $fechaBogota = $dateTimeUtc->format('Y-m-d'); // Esto devuelve la fecha actual en Bogotá
                                    $decodedData = urldecode($cart);
                                    $arrayData = json_decode($decodedData, true);
                                    $_SESSION['fTotal']=0;
                                    $_SESSION['fsTotal']=0;
                                    $_SESSION['fSaver']=0;
                                    $_SESSION['nProducts']=0;
                                    $_SESSION['nPacks']=0;
                                    $np=0;
                                    foreach ($arrayData as $item) {
                                        if (isset($item['item'])) {
                                            $uniqueId= $item['item']['uniqueId'];
                                            $productId= $item['item']['productId'];
                                            $catalogId= $item['item']['catalogId'];
                                            $outPrice= $item['item']['outPrice'];
                                            $productQty= $item['item']['productQty'];
                                            $discount= $item['item']['discount'];
                                            $promotion= $item['item']['promoId'];
                                            $salePrice= $item['item']['productPrice'];
                                            $storeId= $item['item']['storeId'];
                                            $categoryId= $item['item']['categoryId'];
                                            $storeName= $item['item']['storeName'];
                                            $categoryName= $item['item']['categoryName'];
                                            $saver= $item['item']['subTotalShopping']-$item['item']['totalShopping'];
                                            // Resto de tus variables aquí...
                
                                            // Tu consulta SQL aquí...
                                        // $query = mysqli_query($conectar, "INSERT INTO posCar (carId, clientId, uniqueId, productId, catalogId, outPrice, productQty, discount, promotion, salePrice, inDate, inTime, storeId, categoryId, storeName, categoryName, saver, userId, fromStore, fromIp, fromBrowser) VALUES ('$cartId', '$clientId', '$uniqueId', '$productId', '$catalogId', $salePrice, $productQty, $discount, '$promotion', $outPrice, '$fechaBogota', '$hora_actual_bogota', '$storeId', '$categoryId', '$storeName', '$categoryName', $saver, '$userId', '$storeName', '$fromIp', '$fromBrowser')");
                                            $query = mysqli_query($conectar, "UPDATE generalCatalogs SET stock= (SELECT stock FROM generalCatalogs where catalogId='$catalogId' and stock>=$productQty)-$productQty WHERE catalogId='$catalogId' and clientId='$clientId'");
                                            
                                            $_SESSION['fTotal']=$_SESSION['fTotal']+$item['item']['totalShopping'];
                                    $_SESSION['fsTotal']=$_SESSION['fsTotal']+$item['item']['subTotalShopping'];
                                    $_SESSION['fSaver']=$_SESSION['fSaver']+$saver;
                                    $_SESSION['nPacks']=$_SESSION['nPacks']+1;
                                    $_SESSION['nProducts']=$_SESSION['nProducts']+$productQty;
                                    $_SESSION['storeId']=$storeId;
                                            // Verifica si la consulta se ejecutó correctamente y maneja los errores si es necesario
                                            if (!$query) {
                                                echo "Error al insertar datos: " . mysqli_error($conectar);
                                            }
                                        }else{
                                            $ar=json_encode($arrayData,true);
                            $fTotal=  $_SESSION['fTotal'];
                                $fsTotal=$_SESSION['fsTotal'];
                                $fSaver=$_SESSION['fSaver'];
                                $npro=$_SESSION['nProducts'];
                                $npa=$_SESSION['nPacks'];
                                $query3 = mysqli_query($conectar, "SELECT MAX(incId) as coId from generalOrders");
                                $fila = $query3->fetch_assoc();
                                
                                // Verificar si la fila tiene datos
                                if ($fila) {
                                    // Obtener el valor de la columna 'coId'
                                    $valor = $fila['coId']+1;
                                // echo "El valor máximo de incId es: " . $valor;
                                } else {
                                //  echo "No se encontraron datos.";
                                } 
                                
                                $query4 = mysqli_query($conectar, "SELECT customerPoints,customerStars from generalCustomers WHERE customerId='$customerId' AND clientId='$clientId'");

                                // Verificar si la consulta fue exitosa
                                
                                    // Obtener la primera fila como un arreglo asociativo
                                    $fila4 = $query4->fetch_assoc();
                                
                                    // Verificar si la fila tiene datos
                                    if ($fila4) {
                                        // Obtener el valor de la columna 'coId'
                                        $cPoints = $fila4['customerPoints'];
                                        $cStars = $fila4['customerStars'];
                                    // echo "El valor máximo de incId es: " . $valor;
                                    } else {
                                    //  echo "No se encontraron datos.";
                                    }
                                    function calcularPuntos($montoCompra,$clientId) {
                                        $conectar=conn();
                                        // Definir el valor de puntos por cada 50.000 en compras
                                        $query8 = mysqli_query($conectar, "SELECT pointsEq from generalRules WHERE clientId='$clientId'");

                                // Verificar si la consulta fue exitosa
                                
                                    // Obtener la primera fila como un arreglo asociativo
                                    $fila8 = $query8->fetch_assoc();
                                
                                    // Verificar si la fila tiene datos
                                    if ($fila8) {
                                        // Obtener el valor de la columna 'coId'
                                        $cParam = $fila8['pointsEq'];
                                    // echo "El valor máximo de incId es: " . $valor;
                                    } else {
                                    //  echo "No se encontraron datos.";
                                    }
                                        $puntosPorCadaCincuentaMil = 1;
                                    
                                        // Calcular la cantidad de puntos
                                        if ($montoCompra >= $cParam) {
                                            $puntos = ($montoCompra / $cParam) * $puntosPorCadaCincuentaMil;
                                        } else {
                                            // Calcular la cantidad de puntos en función del monto
                                            // Por ejemplo, si el monto es 30.000, se le dará 0.6 puntos (30.000 / 50.000 * 1)
                                            $puntos = ($montoCompra / $cParam) * $puntosPorCadaCincuentaMil;
                                        }
                                    
                                        return $puntos;
                                    }
                                    $puntosObtenidos = round(calcularPuntos($fTotal,$clientId)+$cPoints,2);
                                    $puntosObtenidos2 = round(calcularPuntos($fTotal,$clientId),2);

                                    $query5 = mysqli_query($conectar, "UPDATE generalCustomers SET customerPoints='$puntosObtenidos' WHERE customerId='$customerId'");
                                  $storeId=$_SESSION['storeId'];
                                    switch ($paymentType) {
                                        case "transfer":
                                            if($paymentMethod=="app" || $paymentMethod=="dc" || $paymentMethod=="cc"){
                                                if($paymentMethod=="app"){
                                                    $parameter="isApp";
                                                }
                                                if($paymentMethod=="dc"){
                                                    $parameter="isDebit";
                                                }
                                                if($paymentMethod=="cc"){
                                                    $parameter="isCredit";
                                                }
                                        $query1 = mysqli_query($conectar, "INSERT INTO generalOrders (orderId,carId, clientId, userId, shopperId, storeType, storeId, totalAmount, subtotalAmount, orderProgress, saver, fromIp, fromStore, fromBrowser, orderPayload, paymentMethod, returnCash, transactionStatus,numberProducts,numberPacks,inDate,inTime,incId,customerPoints,$parameter,bankEntity) VALUES ('$orderId','$cartId','$clientId','$userId','$customerId','POS','$storeId',$fTotal,$fsTotal,'PENDING',$fSaver,'$fromIp','$storeId','$fromBrowser','$ar','$paymentMethod',0,'PENDING',$npro,$npa,'$fechaBogota','$hora_actual_bogota',$valor,'$puntosObtenidos2',1,'$bankEntity')");
                                         $_SESSION['respuesta']="true_method";
                                    }else{
                                        $_SESSION['respuesta']="false";
                                    }
                                            break;
                                        case "card":
                                            if($paymentMethod=="app" || $paymentMethod=="dc" || $paymentMethod=="cc"){
                                                if($paymentMethod=="app"){
                                                    $parameter="isApp";
                                                }
                                                if($paymentMethod=="dc"){
                                                    $parameter="isDebit";
                                                }
                                                if($paymentMethod=="cc"){
                                                    $parameter="isCredit";
                                                }
                                        $query1 = mysqli_query($conectar, "INSERT INTO generalOrders (orderId,carId, clientId, userId, shopperId, storeType, storeId, totalAmount, subtotalAmount, orderProgress, saver, fromIp, fromStore, fromBrowser, orderPayload, paymentMethod, returnCash, transactionStatus,numberProducts,numberPacks,inDate,inTime,incId,customerPoints,$parameter,bankEntity) VALUES ('$orderId','$cartId','$clientId','$userId','$customerId','POS','$storeId',$fTotal,$fsTotal,'PENDING',$fSaver,'$fromIp','$storeId','$fromBrowser','$ar','$paymentMethod',0,'PENDING',$npro,$npa,'$fechaBogota','$hora_actual_bogota',$valor,'$puntosObtenidos2',1,'$bankEntity')");
                                         $_SESSION['respuesta']="true_method";
                                        }else{
                                            $_SESSION['respuesta']="false";
                                        }
                                            break;
                                        case "cash":
                                            $query1 = mysqli_query($conectar, "INSERT INTO generalOrders (orderId,carId, clientId, userId, shopperId, storeType, storeId, totalAmount, subtotalAmount, orderProgress, saver, fromIp, fromStore, fromBrowser, orderPayload, paymentMethod, returnCash, transactionStatus,numberProducts,numberPacks,inDate,inTime,incId,customerPoints,paymentReference,isCash,payWith,isPayed) VALUES ('$orderId','$cartId','$clientId','$userId','$customerId','POS','$storeId',$fTotal,$fsTotal,'DONE',$fSaver,'$fromIp','$storeId','$fromBrowser','$ar','cash',$payWith-$fTotal,'DONE',$npro,$npa,'$fechaBogota','$hora_actual_bogota',$valor,'$puntosObtenidos2','cash',1,'$payWith',1)");
                                            $_SESSION['respuesta']="true_cash";
                                            break;
                                        case "points":
                                                

                            $query9 = mysqli_query($conectar, "SELECT gc.customerPoints,gr.pointsEq,gr.pointsValue,gr.minPoints from generalCustomers gc JOIN generalRules gr ON gr.clientId=gc.clientId WHERE gc.customerId='$customerId'");

                            // Verificar si la consulta fue exitosa
                            
                                // Obtener la primera fila como un arreglo asociativo
                                $fila9 = $query9->fetch_assoc();
                            
                                // Verificar si la fila tiene datos
                                if ($fila9) {
                                    // Obtener el valor de la columna 'coId'
                                    $cParam = $fila9['pointsEq'];
                                    $cPoint = $fila9['customerPoints'];
                                    $cPointValue = $fila9['pointsValue'];
                                    $cMinPoints = $fila9['minPoints'];

                                    $cTotal=$cPointValue*$cPoints; 

                                // echo "El valor máximo de incId es: " . $valor;
                                } else {
                                //  echo "No se encontraron datos.";
                                }
                                if($cPoints>=$cMinPoints){
                        if($fTotal>$cTotal){
                            if($paymentMethod=="app" || $paymentMethod=="dc" || $paymentMethod=="cc" || $paymentMethod=="cash"){
                                if($paymentMethod=="app"){
                                    $parameter="isApp";
                                    $pm="points_isApp_".$bankEntity;
                                    $puntosObtenidos=0;
                                    $query10 = mysqli_query($conectar, "UPDATE generalCustomers SET customerPoints=0,backupPoints='$cPoints' WHERE customerId='$customerId'");

                                    $query1 = mysqli_query($conectar, "INSERT INTO generalOrders (orderId,carId, clientId, userId, shopperId, storeType, storeId, totalAmount, subtotalAmount, orderProgress, saver, fromIp, fromStore, fromBrowser, orderPayload, paymentMethod, returnCash, transactionStatus,numberProducts,numberPacks,inDate,inTime,incId,customerPoints,paymentReference,isApp,payWith,isPayed,bankEntity) VALUES ('$orderId','$cartId','$clientId','$userId','$customerId','POS','$storeId',$fTotal,$fsTotal,'WAITING_PAYMENT',$fSaver,'$fromIp','$storeId','$fromBrowser','$ar','$pm',0,'PENDING',$npro,$npa,'$fechaBogota','$hora_actual_bogota',$valor,'$puntosObtenidos2','POINTS_$parameter',1,0,1,'$bankEntity')");
                                    $_SESSION['respuesta']="true_point_bank";
                                }
                                if($paymentMethod=="dc"){
                                    $parameter="isDebit";
                                    $pm="points_isDebit_".$bankEntity;
                                    $puntosObtenidos=0;
                                    $query10 = mysqli_query($conectar, "UPDATE generalCustomers SET customerPoints=0,backupPoints='$cPoints' WHERE customerId='$customerId'");

                                    $query1 = mysqli_query($conectar, "INSERT INTO generalOrders (orderId,carId, clientId, userId, shopperId, storeType, storeId, totalAmount, subtotalAmount, orderProgress, saver, fromIp, fromStore, fromBrowser, orderPayload, paymentMethod, returnCash, transactionStatus,numberProducts,numberPacks,inDate,inTime,incId,customerPoints,paymentReference,isDebit,payWith,isPayed,bankEntity) VALUES ('$orderId','$cartId','$clientId','$userId','$customerId','POS','$storeId',$fTotal,$fsTotal,'WAITING_PAYMENT',$fSaver,'$fromIp','$storeId','$fromBrowser','$ar','$pm',0,'PENDING',$npro,$npa,'$fechaBogota','$hora_actual_bogota',$valor,'$puntosObtenidos2','POINTS_$parameter',1,0,1,'$bankEntity')");
                                    $_SESSION['respuesta']="true_point_bank";
                                }
                                if($paymentMethod=="cc"){
                                    $parameter="isCredit";
                                    $pm="points_isCredit_".$bankEntity;
                                    $puntosObtenidos=0;
                                    $query10 = mysqli_query($conectar, "UPDATE generalCustomers SET customerPoints=0,backupPoints='$cPoints' WHERE customerId='$customerId'");

                                    $query1 = mysqli_query($conectar, "INSERT INTO generalOrders (orderId,carId, clientId, userId, shopperId, storeType, storeId, totalAmount, subtotalAmount, orderProgress, saver, fromIp, fromStore, fromBrowser, orderPayload, paymentMethod, returnCash, transactionStatus,numberProducts,numberPacks,inDate,inTime,incId,customerPoints,paymentReference,isCredit,payWith,isPayed,bankEntity) VALUES ('$orderId','$cartId','$clientId','$userId','$customerId','POS','$storeId',$fTotal,$fsTotal,'WAITING_PAYMENT',$fSaver,'$fromIp','$storeId','$fromBrowser','$ar','$pm',0,'PENDING',$npro,$npa,'$fechaBogota','$hora_actual_bogota',$valor,'$puntosObtenidos2','POINTS_$parameter',1,0,1,'$bankEntity')");
                                    $_SESSION['respuesta']="true_point_bank";
                                }
                                if($paymentMethod=="cash"){
                                    $parameter="isCash";
                                    $pm="points_isCash_".$bankEntity;
                                    $validationPay=$payWith+$cTotal;
                                    if($validationPay>=$fTotal){
                                        $puntosObtenidos=0;
                                        //valor en pesos de puntos
                                    
                                        $validarResultado=$fTotal-$cTotal;
                                        $returnedCash=($payWith-$validarResultado);
                                        
                                    
                                        $query10 = mysqli_query($conectar, "UPDATE generalCustomers SET customerPoints=0 WHERE customerId='$customerId'");

                                        $query1 = mysqli_query($conectar, "INSERT INTO generalOrders (orderId,carId, clientId, userId, shopperId, storeType, storeId, totalAmount, subtotalAmount, orderProgress, saver, fromIp, fromStore, fromBrowser, orderPayload, paymentMethod, returnCash, transactionStatus,numberProducts,numberPacks,inDate,inTime,incId,customerPoints,paymentReference,isCash,payWith,isPayed) VALUES ('$orderId','$cartId','$clientId','$userId','$customerId','POS','$storeId',$fTotal,$fsTotal,'DONE',$fSaver,'$fromIp','$storeId','$fromBrowser','$ar','$pm',$returnedCash,'DONE',$npro,$npa,'$fechaBogota','$hora_actual_bogota',$valor,'$puntosObtenidos2','POINTS_$parameter',1,'$payWith',1)");
                                        $_SESSION['respuesta']="true";
                                    }else{
                                        $_SESSION['respuesta']="false_point";
                                    }
                                }
                            }

                        }else{

                            $validationPay=$fTotal/$cPointValue;
                            $puntosObtenidos=$puntosObtenidos-$validationPay;
                            $query10 = mysqli_query($conectar, "UPDATE generalCustomers SET customerPoints='$puntosObtenidos' WHERE customerId='$customerId'");

                            $query1 = mysqli_query($conectar, "INSERT INTO generalOrders (orderId,carId, clientId, userId, shopperId, storeType, storeId, totalAmount, subtotalAmount, orderProgress, saver, fromIp, fromStore, fromBrowser, orderPayload, paymentMethod, returnCash, transactionStatus,numberProducts,numberPacks,inDate,inTime,incId,customerPoints,paymentReference,isCash,payWith,isPayed) VALUES ('$orderId','$cartId','$clientId','$userId','$customerId','POS','$storeId',$fTotal,$fsTotal,'DONE',$fSaver,'$fromIp','$storeId','$fromBrowser','$ar','POINTS','0','DONE',$npro,$npa,'$fechaBogota','$hora_actual_bogota',$valor,'$puntosObtenidos2','POINTS',1,'$payWith',1)");
                            $_SESSION['respuesta']="true";

                        }
                    }else{
                        $_SESSION['respuesta']="false_point_lack";
                    }
                                            break;
                                        default:
                                            echo "Opción no válida";
                                    }

                                        }
                                        $respuesta=$_SESSION['respuesta'];
                                        if($respuesta=="true_cash"){
                                            if($query1){
    
                                            echo "true|¡Orden creada con éxito!|".$valor."|".$orderId."|".$fTotal."|".$fsTotal."|".$fSaver."|".$paymentMethod."|cash";
                                        } else {
                                            // Si hay un error, imprime el mensaje de error
                                            
                            $response12="false|" . mysqli_error($conectar);
    
                            //inicio de log
                          
                                            echo "false|" . mysqli_error($conectar);
                                        }
                        }
                        if($respuesta=="true_method"){
                            if($query1){
    
                            echo "true|¡Orden creada con éxito, VALIDE CÓDIGO DE TRANSACCIÓN PARA SEGUIMIENTO INTERNO!|".$valor."|".$orderId."|".$fTotal."|".$fsTotal."|".$fSaver."|".$paymentMethod."|".$paymentType;
                        } else {
                            // Si hay un error, imprime el mensaje de error
                            
                           
                            echo "false|" . mysqli_error($conectar);
                        }
                }
                        if($respuesta=="true_point_bank"){
                            if($query1){
    
                            echo "true|¡Orden creada con éxito, VALIDE CÓDIGO DE TRANSACCIÓN PARA SEGUIMIENTO INTERNO!|".$valor."|".$orderId."|".$fTotal."|".$fsTotal."|".$fSaver."|".$paymentMethod."|".$pm;
                        } else {
                            // Si hay un error, imprime el mensaje de error
                          
                            echo "false|" . mysqli_error($conectar);
                        }
                }
                        if($respuesta=="false"){
                            echo "false|¡Orden no se pudo crear metodo y tipo de pago no concuerdan!|".$valor."|".$orderId."|".$fTotal."|".$fsTotal."|".$fSaver."|".$paymentMethod."|".$paymentType;
                        
                        }
                        if($respuesta=="false_point"){
                            echo "false|¡Orden no se pudo crear puntos, efectivo, credito insuficientes!|".$valor."|".$orderId."|".$fTotal."|".$fsTotal."|".$fSaver."|".$paymentMethod."|".$paymentType;
                        
                        }
                        if($respuesta=="false_point_lack"){
                            echo "false|¡Orden no se pudo crear puntos insuficientes, minimo de puntos acumulados deben ser ".$cMinPoints."!|".$valor."|".$orderId."|".$fTotal."|".$fsTotal."|".$fSaver."|".$paymentMethod."|".$paymentType;
                        
                        }
                        else{
                                    echo "false|¡Orden no se pudo crear metodo y tipo de pago no concuerdan!|".$valor."|".$orderId."|".$fTotal."|".$fsTotal."|".$fSaver."|".$paymentMethod."|".$paymentType;
                        
                        }
                                    
                                    }
                                    
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
                    $customerLastName = mysqli_real_escape_string($conectar, $dta['customerLastName']);
                    $customerMail = mysqli_real_escape_string($conectar, $dta['customerMail']);
                    $customerPhone = mysqli_real_escape_string($conectar, $dta['customerPhone']);
                    $customerType = mysqli_real_escape_string($conectar, $dta['customerType']);
                    //$dato_encriptado = $keyword;
                    
            
                    $query = mysqli_query($conectar, "INSERT INTO generalCustomers 
                    (customerId, clientId, customerName, customerLastName,customerMail,customerPhone,customerType) 
                    VALUES
                    ('$customerId', '$clientId', '$customerName', '$customerLastName', '$customerMail','$customerPhone','$customerType')");

                    if($query){
                                $filasAfectadas = mysqli_affected_rows($conectar);
                                    if ($filasAfectadas > 0) 
                                        {
                                            // Éxito: La actualización se realizó correctamente
                                            $response="true";
                                            $message="Creación exitosa. Filas afectadas: $filasAfectadas";
                                            $apiMessage="¡Cliente creado con éxito!";
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

        public static function putDelivery($dta) {
            
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
        $deliveryId = mysqli_real_escape_string($conectar, $dta['deliveryId']);
    
        //$dato_encriptado = $keyword;
        if($param=="del"){
            $query = mysqli_query($conectar, "DELETE FROM generalDelivery where clientId='$clientId' and deliveryId='$deliveryId'");
            $apiMessage="¡Repartidor removido con éxito!";
        }  if($param!="del"){
            $query = mysqli_query($conectar, "UPDATE generalDelivery SET $param='$value' where clientId='$clientId' and deliveryId='$deliveryId'");
            $apiMessage="¡Repartidor actualizado con éxito!";
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