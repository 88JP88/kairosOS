<?php

require 'flight/Flight.php';
require_once 'database/db_users.php';
require_once 'model/users/postModel.php';
require_once 'model/users/getModel.php';
require_once 'model/users/responses.php';
require 'model/modelSecurity/authModule.php';
require_once 'env/domain.php';

require_once 'kronos/postLog.php';






Flight::route('POST /postClientOrder/@apk/@xapk', function ($apk,$xapk) {
        
                                                    header("Access-Control-Allow-Origin: *");
                                                    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
                                                    if (!empty($apk) && !empty($xapk)) {    
                                                        // Leer los datos de la solicitud
                                                    

                                                        
                                    $response11=modelAuth::authModel($apk,$xapk);//AUTH MODULE

                                                        $dta = array(
                                    
                                                            'clientId' =>Flight::request()->data->clientId,
                                                            'cart' => Flight::request()->data->cart,
                                                            'userId' => Flight::request()->data->userId,
                                                            'fromIp' => Flight::request()->data->fromIp,
                                                            'fromBrowser' => Flight::request()->data->fromBrowser,
                                                            'customerId' => Flight::request()->data->customerId,
                                                            'paymentMethod' => Flight::request()->data->paymentMethod,
                                                        
                                                        
                                                            'paymentType' => Flight::request()->data->paymentType,
                                                            'payWith' => Flight::request()->data->payWith,
                                                            'bankEntity' => Flight::request()->data->bankEntity,
                                                        );
                                                        $dt=json_encode($dta);
                                                        if ($response11 == 'true' ) {

                            $cart= Flight::request()->data->cart;

                            $clientId= Flight::request()->data->clientId;
                            $customerId= Flight::request()->data->customerId;
                            $paymentMethod= Flight::request()->data->paymentMethod;
                            $paymentType= Flight::request()->data->paymentType;
                            $payWith= Flight::request()->data->payWith;
                            $bankEntity= Flight::request()->data->bankEntity;
                            $fromBrowser= Flight::request()->data->fromBrowser;
                            $fromIp= Flight::request()->data->fromIp;
                            $userId= Flight::request()->data->userId;
                            //$query= modelPost::postOrder($dta);
                                                        
                                                        

                            //DATA EXTRACTION ARRAY - JSON CONVERT


                                                            require_once '../../apiClient/v1/model/modelSecurity/uuid/uuidd.php';
                                                        
                                                        
                                                            $gen_uuid = new generateUuid();
                                                            $myuuid = $gen_uuid->guidv4();
                                                            $myuuid1 = $gen_uuid->guidv4();
                                                        

                                                            $cartId = substr($myuuid, 0, 8);
                                                            $orderId = substr($myuuid1, 0, 8);

                                                            $conectar=conn();
                                                        
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
                                                        // Verifica si la consulta se ejecutó correctamente y maneja los errores si es necesario
                                                        if (!$query) {
                                                            echo "Error al insertar datos: " . mysqli_error($conectar);
                                                        }
                                                    } else {
                                                        if ($query) {
                                                $ar=json_encode($arrayData,true);
                                                        $fTotal=  $_SESSION['fTotal'];
                                                            $fsTotal=$_SESSION['fsTotal'];
                                                            $fSaver=$_SESSION['fSaver'];
                                                            $npro=$_SESSION['nProducts'];
                                                            $npa=$_SESSION['nPacks'];
                                                            $query3 = mysqli_query($conectar, "SELECT MAX(incId) as coId from generalOrders");

                                                            // Verificar si la consulta fue exitosa
                                                            
                                                                // Obtener la primera fila como un arreglo asociativo
                                                                $fila = $query3->fetch_assoc();
                                                            
                                                                // Verificar si la fila tiene datos
                                                                if ($fila) {
                                                                    // Obtener el valor de la columna 'coId'
                                                                    $valor = $fila['coId']+1;
                                                                // echo "El valor máximo de incId es: " . $valor;
                                                                } else {
                                                                //  echo "No se encontraron datos.";
                                                                }
                                                            
                                                            
                                                        
                                                            // Mostrar o utilizar el valor
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
                                                                
                                                                // Uso de la función para calcular puntos
                                                            // $monto = 75000; // Por ejemplo, monto de la compra
                                                                $puntosObtenidos = round(calcularPuntos($fTotal,$clientId)+$cPoints,2);
                                                                $puntosObtenidos2 = round(calcularPuntos($fTotal,$clientId),2);

                                                                $query5 = mysqli_query($conectar, "UPDATE generalCustomers SET customerPoints='$puntosObtenidos' WHERE customerId='$customerId'");
                                                    //VALIDA EL TIPO DE PAGO TRANSACCIONAL
                                                if($paymentType=="transfer" || $paymentType=="card"){
                                                                        //VALIDA EL MÉTODO DE PAGO
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
                                                                    $respuesta="true_method";
                                                                            }else{
                                                                                $respuesta="false";
                                                                            }
                                                    
                                                    }
                                                //VALIDA TIPO DE PAGO EN EFECTIVO
                                                    else if($paymentType=="cash"){
                                                        $query1 = mysqli_query($conectar, "INSERT INTO generalOrders (orderId,carId, clientId, userId, shopperId, storeType, storeId, totalAmount, subtotalAmount, orderProgress, saver, fromIp, fromStore, fromBrowser, orderPayload, paymentMethod, returnCash, transactionStatus,numberProducts,numberPacks,inDate,inTime,incId,customerPoints,paymentReference,isCash,payWith,isPayed) VALUES ('$orderId','$cartId','$clientId','$userId','$customerId','POS','$storeId',$fTotal,$fsTotal,'DONE',$fSaver,'$fromIp','$storeId','$fromBrowser','$ar','cash',$payWith-$fTotal,'DONE',$npro,$npa,'$fechaBogota','$hora_actual_bogota',$valor,'$puntosObtenidos2','cash',1,'$payWith',1)");
                                                        $respuesta="true_cash";
                                                    }

                                                    //VALIDA TIPO DE PAGO EN PUNTOS
                                                else if($paymentType=="points"){

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
                                                                            $respuesta="true_point_bank";
                                                                        }
                                                                        if($paymentMethod=="dc"){
                                                                            $parameter="isDebit";
                                                                            $pm="points_isDebit_".$bankEntity;
                                                                            $puntosObtenidos=0;
                                                                            $query10 = mysqli_query($conectar, "UPDATE generalCustomers SET customerPoints=0,backupPoints='$cPoints' WHERE customerId='$customerId'");

                                                                            $query1 = mysqli_query($conectar, "INSERT INTO generalOrders (orderId,carId, clientId, userId, shopperId, storeType, storeId, totalAmount, subtotalAmount, orderProgress, saver, fromIp, fromStore, fromBrowser, orderPayload, paymentMethod, returnCash, transactionStatus,numberProducts,numberPacks,inDate,inTime,incId,customerPoints,paymentReference,isDebit,payWith,isPayed,bankEntity) VALUES ('$orderId','$cartId','$clientId','$userId','$customerId','POS','$storeId',$fTotal,$fsTotal,'WAITING_PAYMENT',$fSaver,'$fromIp','$storeId','$fromBrowser','$ar','$pm',0,'PENDING',$npro,$npa,'$fechaBogota','$hora_actual_bogota',$valor,'$puntosObtenidos2','POINTS_$parameter',1,0,1,'$bankEntity')");
                                                                            $respuesta="true_point_bank";
                                                                        }
                                                                        if($paymentMethod=="cc"){
                                                                            $parameter="isCredit";
                                                                            $pm="points_isCredit_".$bankEntity;
                                                                            $puntosObtenidos=0;
                                                                            $query10 = mysqli_query($conectar, "UPDATE generalCustomers SET customerPoints=0,backupPoints='$cPoints' WHERE customerId='$customerId'");

                                                                            $query1 = mysqli_query($conectar, "INSERT INTO generalOrders (orderId,carId, clientId, userId, shopperId, storeType, storeId, totalAmount, subtotalAmount, orderProgress, saver, fromIp, fromStore, fromBrowser, orderPayload, paymentMethod, returnCash, transactionStatus,numberProducts,numberPacks,inDate,inTime,incId,customerPoints,paymentReference,isCredit,payWith,isPayed,bankEntity) VALUES ('$orderId','$cartId','$clientId','$userId','$customerId','POS','$storeId',$fTotal,$fsTotal,'WAITING_PAYMENT',$fSaver,'$fromIp','$storeId','$fromBrowser','$ar','$pm',0,'PENDING',$npro,$npa,'$fechaBogota','$hora_actual_bogota',$valor,'$puntosObtenidos2','POINTS_$parameter',1,0,1,'$bankEntity')");
                                                                            $respuesta="true_point_bank";
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
                                                                                $respuesta="true";
                                                                            }else{
                                                                                $respuesta="false_point";
                                                                            }
                                                                        }
                                                                    }

                                                                }else{

                                                                    $validationPay=$fTotal/$cPointValue;
                                                                    $puntosObtenidos=$puntosObtenidos-$validationPay;
                                                                    $query10 = mysqli_query($conectar, "UPDATE generalCustomers SET customerPoints='$puntosObtenidos' WHERE customerId='$customerId'");

                                                                    $query1 = mysqli_query($conectar, "INSERT INTO generalOrders (orderId,carId, clientId, userId, shopperId, storeType, storeId, totalAmount, subtotalAmount, orderProgress, saver, fromIp, fromStore, fromBrowser, orderPayload, paymentMethod, returnCash, transactionStatus,numberProducts,numberPacks,inDate,inTime,incId,customerPoints,paymentReference,isCash,payWith,isPayed) VALUES ('$orderId','$cartId','$clientId','$userId','$customerId','POS','$storeId',$fTotal,$fsTotal,'DONE',$fSaver,'$fromIp','$storeId','$fromBrowser','$ar','POINTS','0','DONE',$npro,$npa,'$fechaBogota','$hora_actual_bogota',$valor,'$puntosObtenidos2','POINTS',1,'$payWith',1)");
                                                                    $respuesta="true";

                                                                }
                                                            }else{
                                                                $respuesta="false_point_lack";
                                                            }
                                                                    
                                                                
                                                }
                                                        


                                                    //valida respuesta para api de salida
                                                if($respuesta=="true_cash"){
                                                                    if($query1){

                                                                    echo "true|¡Orden creada con éxito!|".$valor."|".$orderId."|".$fTotal."|".$fsTotal."|".$fSaver."|".$paymentMethod."|cash";
                                                                } else {
                                                                    // Si hay un error, imprime el mensaje de error
                                                                    
                                                    $response12="false|" . mysqli_error($conectar);

                                                    //inicio de log
                                                    require_once 'kronos/postLog.php';
                                            
                                                    $backtrace = debug_backtrace();
                                                    $info['Función'] = $backtrace[1]['function']; // 1 para obtener la función actual, 2 para la anterior, etc.
                                                    $currentFile = __FILE__; // Obtiene la ruta completa y el nombre del archivo actual
                                                $justFileName = basename($currentFile);
                                                $rutaCompleta = __DIR__;
                                                $status = http_response_code();
                                                $cid=Flight::request()->data->clientId;
                                                
                                                //$response1 = trim($response1); // Eliminar espacios en blanco alrededor de la respuesta
                                                $array = explode("|", $response12);
                                                $response12=$array[0];
                                                $message=$array[1];
                                                kronos($response12,$message,$message, $info['Función'],$justFileName,$rutaCompleta,$cid,$dt,$url,$status,'true');
                                                //final de log
                                                                    echo "false|" . mysqli_error($conectar);
                                                                }
                                                }
                                                if($respuesta=="true_method"){
                                                    if($query1){

                                                    echo "true|¡Orden creada con éxito, VALIDE CÓDIGO DE TRANSACCIÓN PARA SEGUIMIENTO INTERNO!|".$valor."|".$orderId."|".$fTotal."|".$fsTotal."|".$fSaver."|".$paymentMethod."|".$paymentType;
                                                } else {
                                                    // Si hay un error, imprime el mensaje de error
                                                    
                                                    $response12="false|" . mysqli_error($conectar);

                                                    //inicio de log
                                                    require_once 'kronos/postLog.php';
                                            
                                                    $backtrace = debug_backtrace();
                                                    $info['Función'] = $backtrace[1]['function']; // 1 para obtener la función actual, 2 para la anterior, etc.
                                                    $currentFile = __FILE__; // Obtiene la ruta completa y el nombre del archivo actual
                                                $justFileName = basename($currentFile);
                                                $rutaCompleta = __DIR__;
                                                $status = http_response_code();
                                                $cid=Flight::request()->data->clientId;
                                                
                                                //$response1 = trim($response1); // Eliminar espacios en blanco alrededor de la respuesta
                                                $array = explode("|", $response12);
                                                $response12=$array[0];
                                                $message=$array[1];
                                                kronos($response12,$message,$message, $info['Función'],$justFileName,$rutaCompleta,$cid,$dt,$url,$status,'true');
                                                //final de log
                                                    echo "false|" . mysqli_error($conectar);
                                                }
                                        }
                                                if($respuesta=="true_point_bank"){
                                                    if($query1){

                                                    echo "true|¡Orden creada con éxito, VALIDE CÓDIGO DE TRANSACCIÓN PARA SEGUIMIENTO INTERNO!|".$valor."|".$orderId."|".$fTotal."|".$fsTotal."|".$fSaver."|".$paymentMethod."|".$pm;
                                                } else {
                                                    // Si hay un error, imprime el mensaje de error
                                                    
                                                    $response12="false|" . mysqli_error($conectar);

                                                    //inicio de log
                                                    require_once 'kronos/postLog.php';
                                            
                                                    $backtrace = debug_backtrace();
                                                    $info['Función'] = $backtrace[1]['function']; // 1 para obtener la función actual, 2 para la anterior, etc.
                                                    $currentFile = __FILE__; // Obtiene la ruta completa y el nombre del archivo actual
                                                $justFileName = basename($currentFile);
                                                $rutaCompleta = __DIR__;
                                                $status = http_response_code();
                                                $cid=Flight::request()->data->clientId;
                                                
                                                //$response1 = trim($response1); // Eliminar espacios en blanco alrededor de la respuesta
                                                $array = explode("|", $response12);
                                                $response12=$array[0];
                                                $message=$array[1];
                                                kronos($response12,$message,$message, $info['Función'],$justFileName,$rutaCompleta,$cid,$dt,$url,$status,'true');
                                                //final de log
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
                                                        
                                                        } else {
                                                            // Si hay un error, imprime el mensaje de error
                                                            
                                                    $response12="false|" . mysqli_error($conectar);

                                                    //inicio de log
                                                    require_once 'kronos/postLog.php';
                                            
                                                    $backtrace = debug_backtrace();
                                                    $info['Función'] = $backtrace[1]['function']; // 1 para obtener la función actual, 2 para la anterior, etc.
                                                    $currentFile = __FILE__; // Obtiene la ruta completa y el nombre del archivo actual
                                                $justFileName = basename($currentFile);
                                                $rutaCompleta = __DIR__;
                                                $status = http_response_code();
                                                $cid=Flight::request()->data->clientId;
                                                
                                                //$response1 = trim($response1); // Eliminar espacios en blanco alrededor de la respuesta
                                                $array = explode("|", $response12);
                                                $response12=$array[0];
                                                $message=$array[1];
                                                kronos($response12,$message,$message, $info['Función'],$justFileName,$rutaCompleta,$cid,$dt,$url,$status,'true');
                                                //final de log
                                                            echo "false|" . mysqli_error($conectar);
                                                        }
                                                    }
                                                }

                                                        
                                                        
                                                            
                                                            
                                                        
                                                    

                                                    
                                                        
                                                        // echo json_encode($response1);
                                                        } else {
                                                            $response12='false|¡Autenticación fallida!'.$response11;
                                        
                                                            //inicio de log
                                                            require_once 'kronos/postLog.php';
                                                    
                                                            $backtrace = debug_backtrace();
                                                            $info['Función'] = $backtrace[1]['function']; // 1 para obtener la función actual, 2 para la anterior, etc.
                                                            $currentFile = __FILE__; // Obtiene la ruta completa y el nombre del archivo actual
                                                        $justFileName = basename($currentFile);
                                                        $rutaCompleta = __DIR__;
                                                        $status = http_response_code();
                                                        $cid=Flight::request()->data->clientId;
                                                        
                                                        //$response1 = trim($response1); // Eliminar espacios en blanco alrededor de la respuesta
                                                        $array = explode("|", $response12);
                                                        $response12=$array[0];
                                                        $message=$array[1];
                                                        kronos($response12,$message,$message, $info['Función'],$justFileName,$rutaCompleta,$cid,$dt,$url,$status,'true');
                                                        //final de log
                                                            echo 'false|¡Autenticación fallida!'.$response11;
                                                        // echo json_encode($data);
                                                        }
                                                    } else {
                                        
                                                        $response12='false|¡Encabezados faltantes!';
                                        
                                                        //inicio de log
                                                        require_once 'kronos/postLog.php';
                                                
                                                        $backtrace = debug_backtrace();
                                                        $info['Función'] = $backtrace[1]['function']; // 1 para obtener la función actual, 2 para la anterior, etc.
                                                        $currentFile = __FILE__; // Obtiene la ruta completa y el nombre del archivo actual
                                                    $justFileName = basename($currentFile);
                                                    $rutaCompleta = __DIR__;
                                                    $status = http_response_code();
                                                    $cid=Flight::request()->data->clientId;
                                                    
                                                    //$response1 = trim($response1); // Eliminar espacios en blanco alrededor de la respuesta
                                                    $array = explode("|", $response12);
                                                    $response12=$array[0];
                                                    $message=$array[1];
                                                    kronos($response12,$message,$message, $info['Función'],$justFileName,$rutaCompleta,$cid,$dt,$url,$status,'true');
                                                    //final de log
                                                        echo 'false|¡Encabezados faltantes!';
                                                    }
});


Flight::route('POST /putClientOrderPaymentStatus/@apk/@xapk', function ($apk,$xapk) {
                                        
                                    header("Access-Control-Allow-Origin: *");
                                    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
                                    if (!empty($apk) && !empty($xapk)) {    
                                    


                                        $response11=modelAuth::authModel($apk,$xapk);//AUTH MODULE


                             
                                //DATA EXTRACTION**
                                $postData = Flight::request()->data->getData();
                                $dt=json_encode($postData);

                                        if ($response11 == 'true' ) {

                                        $query= modelPut::putOrderPaymentStatus($postData);  //DATA MODAL

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


                                    // kronos($responseSQL,$apiMessageSQL,$apiMessageSQL,Flight::request()->data->clientId,$dt,Flight::request()->url,'RECEIVED',Flight::request()->data->trackId);  //LOG FUNCTION  

                                echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

});




Flight::route('POST /putClientOrderStatus/@apk/@xapk', function ($apk,$xapk) {
        
   
    header("Access-Control-Allow-Origin: *");
    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
    if (!empty($apk) && !empty($xapk)) {    
    


        $response11=modelAuth::authModel($apk,$xapk);//AUTH MODULE


//DATA EXTRACTION ARRAY - JSON CONVERT
$postData = Flight::request()->data->getData();
$dt=json_encode($postData);
//DATA EXTRACTION**


        if ($response11 == 'true' ) {

        $query= modelPut::putOrderStatus($postData);  //DATA MODAL

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


       // kronos($responseSQL,$apiMessageSQL,$apiMessageSQL,Flight::request()->data->clientId,$dt,Flight::request()->url,'RECEIVED',Flight::request()->data->trackId);  //LOG FUNCTION  

echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

});


Flight::route('POST /postCustomer/@apk/@xapk', function ($apk,$xapk) {
        
          
                   
           header("Access-Control-Allow-Origin: *");
           // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
           if (!empty($apk) && !empty($xapk)) {    
           


               $response11=modelAuth::authModel($apk,$xapk);//AUTH MODULE

               $postData = Flight::request()->data->getData();
               $dt=json_encode($postData);


               if ($response11 == 'true' ) {

               $query= modelPost::postCustomer($postData);  //DATA MODAL

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



Flight::route('POST /postDelivery/@apk/@xapk', function ($apk,$xapk) {
        

   header("Access-Control-Allow-Origin: *");
   // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
   if (!empty($apk) && !empty($xapk)) {    
   


       $response11=modelAuth::authModel($apk,$xapk);//AUTH MODULE
       $postData = Flight::request()->data->getData();
       $dt=json_encode($postData);
//DATA EXTRACTION**


       if ($response11 == 'true' ) {

       $query= modelPost::postDelivery($postData);  //DATA MODAL

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


     //  kronos($responseSQL,$apiMessageSQL,$apiMessageSQL,Flight::request()->data->clientId,$dt,Flight::request()->url,'RECEIVED',Flight::request()->data->trackId);  //LOG FUNCTION  

echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

});


Flight::route('GET /getCustomers/@apiData', function ($apiData) {
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
echo modelGet::getCustomers($postData);
           

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


Flight::route('POST /putCustomer/@apk/@xapk', function ($apk,$xapk) {
        
          
           header("Access-Control-Allow-Origin: *");
           // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
           if (!empty($apk) && !empty($xapk)) {    
           


               $response11=modelAuth::authModel($apk,$xapk);//AUTH MODULE

               $postData = Flight::request()->data->getData();
               $dt=json_encode($postData);
       //DATA EXTRACTION**


               if ($response11 == 'true' ) {

               $query= modelPut::putCustomer($postData);  //DATA MODAL

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

       
              // kronos($responseSQL,$apiMessageSQL,$apiMessageSQL,Flight::request()->data->clientId,$dt,Flight::request()->url,'RECEIVED',Flight::request()->data->trackId);  //LOG FUNCTION  
       
       echo modelResponse::responsePost($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL);//RESPONSE FUNCTION

});



Flight::route('POST /putDelivery/@apk/@xapk', function ($apk,$xapk) {
        
                header("Access-Control-Allow-Origin: *");
                // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
                if (!empty($apk) && !empty($xapk)) {    
                


                    $response11=modelAuth::authModel($apk,$xapk);//AUTH MODULE


            //DATA EXTRACTION ARRAY - JSON CONVERT
            $postData = Flight::request()->data->getData();
            $dt=json_encode($postData);


                    if ($response11 == 'true' ) {

                    $query= modelPut::putDelivery($postData);  //DATA MODAL

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





Flight::route('GET /getClientOrders/@apiData', function ($apiData) {
    header("Access-Control-Allow-Origin: *");
    // Leer los encabezados
    $decodedData = urldecode($apiData);
    
    $postData = json_decode($apiData, true);
    $postData = json_decode(html_entity_decode($apiData), true);
    $headers = getallheaders();
    
    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
    if (isset($headers['Api-Key']) ) {
        // Leer los datos de la solicitud
       
        // Acceder a los encabezados
        $apiKey = $headers['Api-Key'];
        $xApiKey = $headers['x-api-Key'];

        $response1=modelAuth::authModel($apiKey,$xApiKey);//AUTH MODULE

        if ($response1 == 'true' ) {
           
           
echo modelGet::getOrders($postData);
   // echo json_encode($decodedData);      

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






Flight::route('POST /sendEcmValCode/@apk/@xapk', function ($apk,$xapk) {
        
  
   header("Access-Control-Allow-Origin: *");
   // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
   if (!empty($apk) && !empty($xapk)) {    
   


       $response11=modelAuth::authModel($apk,$xapk);//AUTH MODULE

       $postData = Flight::request()->data->getData();
       $dt=json_encode($postData);


       if ($response11 == 'true' ) {

       $query= modelPost::sendValidationEcmCode($postData);  //DATA MODAL

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



Flight::route('POST /validateEcmValCode/@apk/@xapk', function ($apk,$xapk) {
        
   
    header("Access-Control-Allow-Origin: *");
    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
    if (!empty($apk) && !empty($xapk)) {    
    
 
 
        $response11=modelAuth::authModel($apk,$xapk);//AUTH MODULE
 
        $postData = Flight::request()->data->getData();
        $dt=json_encode($postData);
 //DATA EXTRACTION**
 
 
        if ($response11 == 'true' ) {
 
        $query= modelPost::validationEcmCode($postData);  //DATA MODAL
 
    //JSON DECODE RESPPNSE
        $data = json_decode($query, true);
        $responseSQL=$data['response'][0]['response'];
        $messageSQL=$data['response'][0]['message'];
        $apiMessageSQL=$data['response'][0]['apiMessage'];
        $apiStatusSQL=$data['response'][0]['status'];
        $apiStatusCode=$data['response'][0]['statusCode'];
        //JSON DECODE**
 
        } else {
            $responseSQL="false";
            $apiMessageSQL="¡Autenticación fallida!";
            $apiStatusSQL="401";
            $messageSQL="¡Autenticación fallida!";
            $apiStatusCode="undefined";
        }
    } else {
 
        $responseSQL="false";
        $apiMessageSQL="¡Encabezados faltantes!";
        $apiStatusSQL="403";
        $messageSQL="¡Encabezados faltantes!";
        $apiStatusCode="undefined";
    }
 
 
        kronos($responseSQL,$apiMessageSQL,$apiMessageSQL,Flight::request()->data->clientId,$dt,Flight::request()->url,'RECEIVED',Flight::request()->data->trackId);  //LOG FUNCTION  
 
 echo modelResponse::responsePostCode($responseSQL,$apiMessageSQL,$apiStatusSQL,$messageSQL,$apiStatusCode);//RESPONSE FUNCTION
 
});





Flight::route('GET /getClientPickupPoints/@clientId/@filter/@param/@value', function ($clientId,$filter,$param,$value) {
    header("Access-Control-Allow-Origin: *");
    // Leer los encabezados
    $headers = getallheaders();
    
    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
    if (isset($headers['Api-Key']) ) {
        // Leer los datos de la solicitud
    
        // Acceder a los encabezados
        $apiKey = $headers['Api-Key'];
        $xApiKey = $headers['x-api-Key'];
        
        $sub_domaincon=new model_domain();
        $sub_domain=$sub_domaincon->domKairos();
        $url = $sub_domain.'/kairosCore/apiAuth/v1/authApiKeyKairos/';
    
        $data = array(
        'apiKey' =>$apiKey, 
        'xApiKey' => $xApiKey
        
        );
    $curl = curl_init();
    
    // Configurar las opciones de la sesión cURL
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    
    // Ejecutar la solicitud y obtener la respuesta
    $response1 = curl_exec($curl);

    


    curl_close($curl);

    

        // Realizar acciones basadas en los valores de los encabezados


        if ($response1 == 'true' ) {
        



        
            $conectar=conn();
            
        if($filter=="all"){

        
        
            $query= mysqli_query($conectar,"SELECT pickupId,clientId,pointName,pointAdd,isActive,cityPoint,subscribedTo FROM pickupPoints where clientId='$clientId'");
        }
        
    
if($filter=="byParam"){

        
        
    $query= mysqli_query($conectar,"SELECT pickupId,clientId,pointName,pointAdd,isActive,cityPoint,subscribedTo FROM pickupPoints where clientId='$clientId' and $param='$value'");
       

}





                $values=[];
        
                while($row = $query->fetch_assoc())
                {
                
                        $value=[
                            'clientId' => $row['clientId'],
                            'pickupId' => $row['pickupId'],
                            'pointName' => $row['pointName'],
                            'pointAdd' => $row['pointAdd'],
                            'isActive' => $row['isActive'],
                            'cityPoint' => $row['cityPoint'],
                            'subscribedTo' => $row['subscribedTo']
                            
                        ];
                        
                        array_push($values,$value);
                        
                }
                $row=$query->fetch_assoc();
                //echo json_encode($students) ;
                echo json_encode(['pickupPoints'=>$values]);
        
                if ($query) {
                   
                   // echo "true|¡Producto actualizado con éxito!";
                } else {
                    // Si hay un error, imprime el mensaje de error
                    echo "false|" . mysqli_error($conectar);
                }
        

        } else {
            echo 'Error: Autenticación fallida';
            //echo json_encode($response1);
        }
    } else {
        echo 'Error: Encabezados faltantes';
    }
});




Flight::route('POST /postClientOrderEcm/@apk/@xapk', function ($apk,$xapk) {
        
    header("Access-Control-Allow-Origin: *");
    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
    if (!empty($apk) && !empty($xapk)) {    
        // Leer los datos de la solicitud
    




        




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

    

        // Realizar acciones basadas en los valores de los encabezados


        if ($response11 == 'true' ) {



            $clientId= Flight::request()->data->clientId;
            $cart= Flight::request()->data->cart;
            $userId= Flight::request()->data->userId;
            $fromIp= Flight::request()->data->fromIp;
            $fromBrowser= Flight::request()->data->fromBrowser;
            $customerId= Flight::request()->data->customerId;
            $paymentMethod= Flight::request()->data->paymentMethod;
            $paymentType= Flight::request()->data->paymentType;
            $payWith= Flight::request()->data->payWith;
            $bankEntity= Flight::request()->data->bankEntity;
            $deliveryMethod= Flight::request()->data->deliveryMethod;
            $deliveryAdd= Flight::request()->data->deliveryAdd;
        
$customerEmail=$customerId;
            require_once '../../apiClient/v1/model/modelSecurity/uuid/uuidd.php';
        
        
            $gen_uuid = new generateUuid();
            $myuuid = $gen_uuid->guidv4();
            $myuuid1 = $gen_uuid->guidv4();
        

            $cartId = substr($myuuid, 0, 8);
            $orderId = substr($myuuid1, 0, 8);

            $conectar=conn();
        //reemplaza el correo por el id
            $query10 = mysqli_query($conectar, "SELECT customerId from generalCustomers where clientId='$clientId' and customerMail='$customerId'");

            // Verificar si la consulta fue exitosa
            
                // Obtener la primera fila como un arreglo asociativo
                $fila10 = $query10->fetch_assoc();
            
                // Verificar si la fila tiene datos
                if ($fila10) {
                    // Obtener el valor de la columna 'coId'
                    $_SESSION['customerId'] = $fila10['customerId'];
                    $customerId=$_SESSION['customerId'];
                // echo "El valor máximo de incId es: " . $valor;
                } else {
                //  echo "No se encontraron datos.";
                }



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
$stringMessageProducts="";
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
      
        $query13 = mysqli_query($conectar, "SELECT productName from generalProducts WHERE productId='$productId' and clientId='$clientId'");

        // Verificar si la consulta fue exitosa
        
            // Obtener la primera fila como un arreglo asociativo
            $fila13 = $query13->fetch_assoc();
        
            // Verificar si la fila tiene datos
            if ($fila13) {
                // Obtener el valor de la columna 'coId'
                $valor13 = $fila13['productName'];
            // echo "El valor máximo de incId es: " . $valor;
            } else {
            //  echo "No se encontraron datos.";
            }
      
        $stringMessageProducts=$stringMessageProducts."- x".strval($productQty)." ".$valor13." $".strval($salePrice)."<br>";
      
      
      
        $_SESSION['fTotal']=$_SESSION['fTotal']+$item['item']['totalShopping'];
$_SESSION['fsTotal']=$_SESSION['fsTotal']+$item['item']['subTotalShopping'];
$_SESSION['fSaver']=$_SESSION['fSaver']+$saver;
$_SESSION['nPacks']=$_SESSION['nPacks']+1;
$_SESSION['nProducts']=$_SESSION['nProducts']+$productQty;
        // Verifica si la consulta se ejecutó correctamente y maneja los errores si es necesario
        if (!$query) {
            
            $response12="false|" . mysqli_error($conectar);

            //inicio de log
            require_once 'kronos/postLog.php';
       
            $backtrace = debug_backtrace();
            $info['Función'] = $backtrace[1]['function']; // 1 para obtener la función actual, 2 para la anterior, etc.
            $currentFile = __FILE__; // Obtiene la ruta completa y el nombre del archivo actual
           $justFileName = basename($currentFile);
           $rutaCompleta = __DIR__;
           $status = http_response_code();
           $cid=Flight::request()->data->clientId;
           
           //$response1 = trim($response1); // Eliminar espacios en blanco alrededor de la respuesta
           $array = explode("|", $response12);
           $response12=$array[0];
           $message=$array[1];
           kronos($response12,$message,$message, $info['Función'],$justFileName,$rutaCompleta,$cid,$dt,$url,$status,'true');
           //final de log
            echo "Error al insertar datos: " . mysqli_error($conectar);
        }
    } else {
        if ($query) {
$ar=json_encode($arrayData,true);
        $fTotal=  $_SESSION['fTotal'];
            $fsTotal=$_SESSION['fsTotal'];
            $fSaver=$_SESSION['fSaver'];
            $npro=$_SESSION['nProducts'];
            $npa=$_SESSION['nPacks'];
            $query3 = mysqli_query($conectar, "SELECT MAX(incId) as coId from generalOrders");

            // Verificar si la consulta fue exitosa
            
                // Obtener la primera fila como un arreglo asociativo
                $fila = $query3->fetch_assoc();
            
                // Verificar si la fila tiene datos
                if ($fila) {
                    // Obtener el valor de la columna 'coId'
                    $valor = $fila['coId']+1;
                // echo "El valor máximo de incId es: " . $valor;
                } else {
                //  echo "No se encontraron datos.";
                }
            
            
        
            // Mostrar o utilizar el valor
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
                
                // Uso de la función para calcular puntos
            // $monto = 75000; // Por ejemplo, monto de la compra
                $puntosObtenidos = round(calcularPuntos($fTotal,$clientId)+$cPoints,2);
                $puntosObtenidos2 = round(calcularPuntos($fTotal,$clientId),2);

             //   $query5 = mysqli_query($conectar, "UPDATE generalCustomers SET customerPoints='$puntosObtenidos' WHERE customerId='$customerId'");
    //VALIDA EL TIPO DE PAGO TRANSACCIONAL
if($paymentType=="transfer" || $paymentType=="card"){
                        //VALIDA EL MÉTODO DE PAGO
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
                                $query1 = mysqli_query($conectar, "INSERT INTO generalOrders (orderId,carId, clientId, userId, shopperId, storeType, storeId, totalAmount, subtotalAmount, orderProgress, saver, fromIp, fromStore, fromBrowser, orderPayload, paymentMethod, returnCash, transactionStatus,numberProducts,numberPacks,inDate,inTime,incId,customerPoints,$parameter,bankEntity,deliveryMethod,deliveryAdd,isPayed) VALUES ('$orderId','$cartId','$clientId','$userId','$customerId','ECM','$storeId',$fTotal,$fsTotal,'RECEIVED',$fSaver,'$fromIp','$storeId','$fromBrowser','$ar','$paymentMethod',0,'PENDING',$npro,$npa,'$fechaBogota','$hora_actual_bogota',$valor,'$puntosObtenidos2',1,'$bankEntity','$deliveryMethod','$deliveryAdd',0)");
                    $respuesta="true_method";
                            }else{
                                $respuesta="false";
                            }
    
    }
//VALIDA TIPO DE PAGO EN EFECTIVO
    else if($paymentType=="cash"){
        $query1 = mysqli_query($conectar, "INSERT INTO generalOrders (orderId,carId, clientId, userId, shopperId, storeType, storeId, totalAmount, subtotalAmount, orderProgress, saver, fromIp, fromStore, fromBrowser, orderPayload, paymentMethod, returnCash, transactionStatus,numberProducts,numberPacks,inDate,inTime,incId,customerPoints,paymentReference,isCash,payWith,isPayed,deliveryMethod,deliveryAdd) VALUES ('$orderId','$cartId','$clientId','$userId','$customerId','ECM','$storeId',$fTotal,$fsTotal,'RECEIVED',$fSaver,'$fromIp','$storeId','$fromBrowser','$ar','cash',$payWith-$fTotal,'PENDING',$npro,$npa,'$fechaBogota','$hora_actual_bogota',$valor,'$puntosObtenidos2','cash',1,'$payWith',0,'$deliveryMethod','$deliveryAdd')");
        $respuesta="true_cash";
    }

    //VALIDA TIPO DE PAGO EN PUNTOS
else if($paymentType=="points"){

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
                          //  $query10 = mysqli_query($conectar, "UPDATE generalCustomers SET customerPoints=0,backupPoints='$cPoints' WHERE customerId='$customerId'");

                            $query1 = mysqli_query($conectar, "INSERT INTO generalOrders (orderId,carId, clientId, userId, shopperId, storeType, storeId, totalAmount, subtotalAmount, orderProgress, saver, fromIp, fromStore, fromBrowser, orderPayload, paymentMethod, returnCash, transactionStatus,numberProducts,numberPacks,inDate,inTime,incId,customerPoints,paymentReference,isApp,payWith,isPayed,bankEntity,deliveryMethod,deliveryAdd) VALUES ('$orderId','$cartId','$clientId','$userId','$customerId','ECM','$storeId',$fTotal,$fsTotal,'RECEIVED',$fSaver,'$fromIp','$storeId','$fromBrowser','$ar','$pm',0,'PENDING',$npro,$npa,'$fechaBogota','$hora_actual_bogota',$valor,'$puntosObtenidos2','POINTS_$parameter',1,0,0,'$bankEntity','$deliveryMethod','$deliveryAdd')");
                            $respuesta="true_point_bank";
                        }
                        if($paymentMethod=="dc"){
                            $parameter="isDebit";
                            $pm="points_isDebit_".$bankEntity;
                            $puntosObtenidos=0;
                          //  $query10 = mysqli_query($conectar, "UPDATE generalCustomers SET customerPoints=0,backupPoints='$cPoints' WHERE customerId='$customerId'");

                            $query1 = mysqli_query($conectar, "INSERT INTO generalOrders (orderId,carId, clientId, userId, shopperId, storeType, storeId, totalAmount, subtotalAmount, orderProgress, saver, fromIp, fromStore, fromBrowser, orderPayload, paymentMethod, returnCash, transactionStatus,numberProducts,numberPacks,inDate,inTime,incId,customerPoints,paymentReference,isDebit,payWith,isPayed,bankEntity,deliveryMethod,deliveryAdd) VALUES ('$orderId','$cartId','$clientId','$userId','$customerId','ECM','$storeId',$fTotal,$fsTotal,'RECEIVED',$fSaver,'$fromIp','$storeId','$fromBrowser','$ar','$pm',0,'PENDING',$npro,$npa,'$fechaBogota','$hora_actual_bogota',$valor,'$puntosObtenidos2','POINTS_$parameter',1,0,0,'$bankEntity','$deliveryMethod','$deliveryAdd')");
                            $respuesta="true_point_bank";
                        }
                        if($paymentMethod=="cc"){
                            $parameter="isCredit";
                            $pm="points_isCredit_".$bankEntity;
                            $puntosObtenidos=0;
                           // $query10 = mysqli_query($conectar, "UPDATE generalCustomers SET customerPoints=0,backupPoints='$cPoints' WHERE customerId='$customerId'");

                            $query1 = mysqli_query($conectar, "INSERT INTO generalOrders (orderId,carId, clientId, userId, shopperId, storeType, storeId, totalAmount, subtotalAmount, orderProgress, saver, fromIp, fromStore, fromBrowser, orderPayload, paymentMethod, returnCash, transactionStatus,numberProducts,numberPacks,inDate,inTime,incId,customerPoints,paymentReference,isCredit,payWith,isPayed,bankEntity,deliveryMethod,deliveryAdd) VALUES ('$orderId','$cartId','$clientId','$userId','$customerId','ECM','$storeId',$fTotal,$fsTotal,'RECEIVED',$fSaver,'$fromIp','$storeId','$fromBrowser','$ar','$pm',0,'PENDING',$npro,$npa,'$fechaBogota','$hora_actual_bogota',$valor,'$puntosObtenidos2','POINTS_$parameter',1,0,0,'$bankEntity','$deliveryMethod','$deliveryAdd')");
                            $respuesta="true_point_bank";
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
                                
                            
                              //  $query10 = mysqli_query($conectar, "UPDATE generalCustomers SET customerPoints=0 WHERE customerId='$customerId'");

                                $query1 = mysqli_query($conectar, "INSERT INTO generalOrders (orderId,carId, clientId, userId, shopperId, storeType, storeId, totalAmount, subtotalAmount, orderProgress, saver, fromIp, fromStore, fromBrowser, orderPayload, paymentMethod, returnCash, transactionStatus,numberProducts,numberPacks,inDate,inTime,incId,customerPoints,paymentReference,isCash,payWith,isPayed,deliveryMethod,deliveryAdd) VALUES ('$orderId','$cartId','$clientId','$userId','$customerId','ECM','$storeId',$fTotal,$fsTotal,'RECEIVED',$fSaver,'$fromIp','$storeId','$fromBrowser','$ar','$pm',$returnedCash,'PENDING',$npro,$npa,'$fechaBogota','$hora_actual_bogota',$valor,'$puntosObtenidos2','POINTS_$parameter',1,'$payWith',0,'$deliveryMethod','$deliveryAdd')");
                                $respuesta="true";
                            }else{
                                $respuesta="false_point";
                            }
                        }
                    }

                }else{

                    $validationPay=$fTotal/$cPointValue;
                    $puntosObtenidos=$puntosObtenidos-$validationPay;
                //    $query10 = mysqli_query($conectar, "UPDATE generalCustomers SET customerPoints='$puntosObtenidos' WHERE customerId='$customerId'");

                    $query1 = mysqli_query($conectar, "INSERT INTO generalOrders (orderId,carId, clientId, userId, shopperId, storeType, storeId, totalAmount, subtotalAmount, orderProgress, saver, fromIp, fromStore, fromBrowser, orderPayload, paymentMethod, returnCash, transactionStatus,numberProducts,numberPacks,inDate,inTime,incId,customerPoints,paymentReference,isCash,payWith,isPayed,deliveryMethod,deliveryAdd) VALUES ('$orderId','$cartId','$clientId','$userId','$customerId','ECM','$storeId',$fTotal,$fsTotal,'RECEIVED',$fSaver,'$fromIp','$storeId','$fromBrowser','$ar','POINTS','0','PENDING',$npro,$npa,'$fechaBogota','$hora_actual_bogota',$valor,'$puntosObtenidos2','POINTS',1,'$payWith',0,'$deliveryMethod','$deliveryAdd')");
                    $respuesta="true";

                }
            }else{
                $respuesta="false_point_lack";
            }
                    
                
}
        
function sendingMail($customermMail, $perMessage, $totalMessage, $stotalMessage, $saverMessage, $orId, $orNumber,$pw,$pt,$pm,$ep,$deladd,$delmet) {
 
   $changer= $pw-$totalMessage;
   $finishedMsg = "Tu compra ha sido validada con ID de orden <strong>$orId</strong> con número consecutivo <strong>$orNumber</strong>. <br/><br>Estado de orden: <h3 style='color: blue;'>ABIERTA</h3><br/><strong>Productos:</strong><br>" . $perMessage . "<br/>Total: <strong>$" . $totalMessage . "</strong><br/>Sub-Total: <strong>$" . $stotalMessage . "</strong><br/>Ahorro: <strong>$" . $saverMessage . "</strong><br>Paga con: <strong>".$pw."</strong><br>Cambio: <strong>".$changer."</strong><br><br>Tipo de pago: ".$pt."<br>Método de pago: ".$pm."<br>Entidad de pago: ".$ep."<br><br>Dirección de entrega: $deladd<br>Tipo de entrega: $delmet<br><img src='https://ssl.gstatic.com/ui/v1/icons/mail/rfr/logo_gmail_lockup_dark_1x_r5.png' alt='img'>";
    $from = "confirmation@lugma.tech";
    $to = $customermMail;
    $subject = "Confirmación de orden #" . $orId;

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: " . $from;

    mail($to, $subject, $finishedMsg, $headers);
}

    //valida respuesta para api de salida
if($respuesta=="true_cash"){
                    if($query1){
                                sendingMail($customerEmail,$stringMessageProducts,$fTotal,$fsTotal,$fSaver,$orderId,$valor,$payWith,$paymentType,$paymentMethod,$bankEntity,$deliveryAdd,$deliveryMethod);
                    echo "true|¡Orden creada con éxito!|".$valor."|".$orderId."|".$fTotal."|".$fsTotal."|".$fSaver."|".$paymentMethod."|cash";
                } else {
                    // Si hay un error, imprime el mensaje de error

                    
                    $response12="false|" . mysqli_error($conectar);

                    //inicio de log
                    require_once 'kronos/postLog.php';
               
                    $backtrace = debug_backtrace();
                    $info['Función'] = $backtrace[1]['function']; // 1 para obtener la función actual, 2 para la anterior, etc.
                    $currentFile = __FILE__; // Obtiene la ruta completa y el nombre del archivo actual
                   $justFileName = basename($currentFile);
                   $rutaCompleta = __DIR__;
                   $status = http_response_code();
                   $cid=Flight::request()->data->clientId;
                   
                   //$response1 = trim($response1); // Eliminar espacios en blanco alrededor de la respuesta
                   $array = explode("|", $response12);
                   $response12=$array[0];
                   $message=$array[1];
                   kronos($response12,$message,$message, $info['Función'],$justFileName,$rutaCompleta,$cid,$dt,$url,$status,'true');
                   //final de log
                    echo "false|" . mysqli_error($conectar);
                }
}
if($respuesta=="true_method"){
    if($query1){
        sendingMail($customerEmail,$stringMessageProducts,$fTotal,$fsTotal,$fSaver,$orderId,$valor,$payWith,$paymentType,$paymentMethod,$bankEntity,$deliveryAdd,$deliveryMethod);  
          echo "true|¡Orden creada con éxito, VALIDE CÓDIGO DE TRANSACCIÓN AL MOMENTO DE RECIBIR LA ORDEN!|".$valor."|".$orderId."|".$fTotal."|".$fsTotal."|".$fSaver."|".$paymentMethod."|".$paymentType;
} else {
    // Si hay un error, imprime el mensaje de error

    
    $response12="false|" . mysqli_error($conectar);

    //inicio de log
    require_once 'kronos/postLog.php';

    $backtrace = debug_backtrace();
    $info['Función'] = $backtrace[1]['function']; // 1 para obtener la función actual, 2 para la anterior, etc.
    $currentFile = __FILE__; // Obtiene la ruta completa y el nombre del archivo actual
   $justFileName = basename($currentFile);
   $rutaCompleta = __DIR__;
   $status = http_response_code();
   $cid=Flight::request()->data->clientId;
   
   //$response1 = trim($response1); // Eliminar espacios en blanco alrededor de la respuesta
   $array = explode("|", $response12);
   $response12=$array[0];
   $message=$array[1];
   kronos($response12,$message,$message, $info['Función'],$justFileName,$rutaCompleta,$cid,$dt,$url,$status,'true');
   //final de log
    echo "false|" . mysqli_error($conectar);
}
}
if($respuesta=="true_point_bank"){
    if($query1){
        sendingMail($customerEmail,$stringMessageProducts,$fTotal,$fsTotal,$fSaver,$orderId,$valor,$payWith,$paymentType,$paymentMethod,$bankEntity,$deliveryAdd,$deliveryMethod);
            echo "true|¡Orden creada con éxito, VALIDE CÓDIGO DE TRANSACCIÓN AL MOMENTO DE RECIBIR LA ORDEN!|".$valor."|".$orderId."|".$fTotal."|".$fsTotal."|".$fSaver."|".$paymentMethod."|".$pm;
} else {
    // Si hay un error, imprime el mensaje de error

    
    $response12="false|" . mysqli_error($conectar);

    //inicio de log
    require_once 'kronos/postLog.php';

    $backtrace = debug_backtrace();
    $info['Función'] = $backtrace[1]['function']; // 1 para obtener la función actual, 2 para la anterior, etc.
    $currentFile = __FILE__; // Obtiene la ruta completa y el nombre del archivo actual
   $justFileName = basename($currentFile);
   $rutaCompleta = __DIR__;
   $status = http_response_code();
   $cid=Flight::request()->data->clientId;
   
   //$response1 = trim($response1); // Eliminar espacios en blanco alrededor de la respuesta
   $array = explode("|", $response12);
   $response12=$array[0];
   $message=$array[1];
   kronos($response12,$message,$message, $info['Función'],$justFileName,$rutaCompleta,$cid,$dt,$url,$status,'true');
   //final de log
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
        
        } else {
            // Si hay un error, imprime el mensaje de error

            
            $response12="false|" . mysqli_error($conectar);

            //inicio de log
            require_once 'kronos/postLog.php';
       
            $backtrace = debug_backtrace();
            $info['Función'] = $backtrace[1]['function']; // 1 para obtener la función actual, 2 para la anterior, etc.
            $currentFile = __FILE__; // Obtiene la ruta completa y el nombre del archivo actual
           $justFileName = basename($currentFile);
           $rutaCompleta = __DIR__;
           $status = http_response_code();
           $cid=Flight::request()->data->clientId;
           
           //$response1 = trim($response1); // Eliminar espacios en blanco alrededor de la respuesta
           $array = explode("|", $response12);
           $response12=$array[0];
           $message=$array[1];
           kronos($response12,$message,$message, $info['Función'],$justFileName,$rutaCompleta,$cid,$dt,$url,$status,'true');
           //final de log
            echo "false|" . mysqli_error($conectar);
        }
    }
}

        
        
            
            
        
    

    
        
        // echo json_encode($response1);
        } else {
            $response12='false|¡Autenticación fallida!'.$response11;

            //inicio de log
            require_once 'kronos/postLog.php';
       
            $backtrace = debug_backtrace();
            $info['Función'] = $backtrace[1]['function']; // 1 para obtener la función actual, 2 para la anterior, etc.
            $currentFile = __FILE__; // Obtiene la ruta completa y el nombre del archivo actual
           $justFileName = basename($currentFile);
           $rutaCompleta = __DIR__;
           $status = http_response_code();
           $cid=Flight::request()->data->clientId;
           
           //$response1 = trim($response1); // Eliminar espacios en blanco alrededor de la respuesta
           $array = explode("|", $response12);
           $response12=$array[0];
           $message=$array[1];
           kronos($response12,$message,$message, $info['Función'],$justFileName,$rutaCompleta,$cid,$dt,$url,$status,'true');
           //final de log
            echo 'false|¡Autenticación fallida!'.$response11;
        // echo json_encode($data);
        }
    } else {

        $response12='false|¡Encabezados faltantes!';

        //inicio de log
        require_once 'kronos/postLog.php';
   
        $backtrace = debug_backtrace();
        $info['Función'] = $backtrace[1]['function']; // 1 para obtener la función actual, 2 para la anterior, etc.
        $currentFile = __FILE__; // Obtiene la ruta completa y el nombre del archivo actual
       $justFileName = basename($currentFile);
       $rutaCompleta = __DIR__;
       $status = http_response_code();
       $cid=Flight::request()->data->clientId;
       
       //$response1 = trim($response1); // Eliminar espacios en blanco alrededor de la respuesta
       $array = explode("|", $response12);
       $response12=$array[0];
       $message=$array[1];
       kronos($response12,$message,$message, $info['Función'],$justFileName,$rutaCompleta,$cid,$dt,$url,$status,'true');
       //final de log
        echo 'false|¡Encabezados faltantes!';
    }
});




Flight::route('GET /getDelivery/@apiData', function ($apiData) {
    header("Access-Control-Allow-Origin: *");
    // Leer los encabezados
    $headers = getallheaders();
    //$decodedData = urldecode($apiData);
    $postData = json_decode($apiData, true);
    // Verificar si los encabezados 'Api-Key' y 'Secret-Key' existen
    if (isset($headers['Api-Key']) ) {
        // Leer los datos de la solicitud
       
        // Acceder a los encabezados
        $apiKey = $headers['Api-Key'];
        $xApiKey = $headers['x-api-Key'];

        $response1=modelAuth::authModel($apiKey,$xApiKey);//AUTH MODULE

        if ($response1 == 'true' ) {
           
         

echo modelGet::getDelivery($postData);
           

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
