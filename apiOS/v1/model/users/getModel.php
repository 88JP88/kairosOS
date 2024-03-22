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
                    'siteId' => $row['siteId'],
                    'clientId' => $row['clientId'],
                    'placeId' => $row['placeId'],
                    'placeName' => $row['name'],
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

        
                $query= mysqli_query($conectar,"SELECT gor.orderId,gor.carId,gor.clientId,gor.userId,gor.shopperId,gor.storeId,gor.totalAmount,gor.subTotalAmount,gor.orderProgress,gor.saver,gor.paymentMethod,gor.returnCash,gor.transactionStatus,gor.numberProducts,gor.numberPacks,gor.inDate,gor.inTime,gor.incId,gor.paymentReference,gor.payWith,gor.bankEntity,gc.customerName,gc.customerLastName,gs.storeName,gor.orderPayload,gor.deliveryMethod,gor.deliveryAdd,gor.deliveryPerson,gdel.deliveryName,gdel.deliveryLastName,gdel.distanceRules,gor.deliveryStatus FROM generalOrders gor JOIN generalCustomers gc ON gor.shopperId=gc.customerId JOIN generalStores gs ON gor.storeId=gs.storeId JOIN generalDelivery gdel ON gdel.deliveryId=gor.deliveryPerson where gor.clientId='$clientId' ORDER BY gor.incId DESC LIMIT 50");

               // $query= mysqli_query($conectar,"SELECT gc.customerId,gc.clientId,gc.customerName,gc.customerLastName,gc.customerMail,gc.customerPoints,gc.customerPhone,gc.customerStars,gc.customerType,gc.isActive,gr.pointsEq,gr.pointsValue FROM generalCustomers gc JOIN generalRules gr ON gr.clientId=gc.clientId where gc.clientId='$clientId'");
            }
            
        
    if($filter=="byStore"){
    
            
            
        $query= mysqli_query($conectar,"SELECT gor.orderId,gor.carId,gor.clientId,gor.userId,gor.shopperId,gor.storeId,gor.totalAmount,gor.subTotalAmount,gor.orderProgress,gor.saver,gor.paymentMethod,gor.returnCash,gor.transactionStatus,gor.numberProducts,gor.numberPacks,gor.inDate,gor.inTime,gor.incId,gor.paymentReference,gor.payWith,gor.bankEntity,gc.customerName,gc.customerLastName,gs.storeName,gor.orderPayload,gor.deliveryMethod,gor.deliveryAdd,gor.deliveryPerson,gdel.deliveryName,gdel.deliveryLastName,gdel.distanceRules,gor.deliveryStatus FROM generalOrders gor JOIN generalCustomers gc ON gor.shopperId=gc.customerId JOIN generalStores gs ON gor.storeId=gs.storeId JOIN generalDelivery gdel ON gdel.deliveryId=gor.deliveryPerson where gor.clientId='$clientId' and gor.$param='$value' ORDER BY gor.incId DESC LIMIT 50");
    
    
    }
            if($query){
                $numRows = mysqli_num_rows($query);

if ($numRows > 0) {
                $response="true";
                $message="Consulta exitosa";
                $status="202";
                $apiMessage="¡Ordenes seleccionadas ($numRows)!";
                $values=[];

                while ($row = $query->fetch_assoc()) {
                    $value=[
                        'orderId' => $row['orderId'],
                        'carId' => $row['carId'],
                        'clientId' => $row['clientId'],
                        'vendor' => $row['userId'],
                        'shopperId' => $row['shopperId'],
                        'storeId' => $row['storeId'],
                        'total' => $row['totalAmount'],
                        
                        'subTotal' => $row['subTotalAmount'],
                        'orderProgress' => $row['orderProgress'],
                        'saver' => $row['saver'],
                        'paymentMethod' => $row['paymentMethod'],
                        'exchange' => $row['returnCash'],
                        'paymentStatus' => $row['transactionStatus'],
                        'numberProducts' => $row['numberProducts'],
                        'numberPacks' => $row['numberPacks'],
                        'inDate' => $row['inDate'],
                        'inTime' => $row['inTime'],
                        'orderNumber' => $row['incId'],
                        'paymentReference' => $row['paymentReference'],
                        'payWith' => $row['payWith'],
                        'bankAccount' => $row['bankEntity'],
                        'customer' => $row['customerName'].' '.$row['customerLastName'],
                        'storeName' => $row['storeName'],
                        'orderPayload' => $row['orderPayload'],
                        'deliveryAdd' => $row['deliveryAdd'],
                        'deliveryMethod' => $row['deliveryMethod'],
                        'deliveryPerson' => $row['deliveryPerson'],
                        'deliveryName' => $row['deliveryName'],
                        'deliveryLastName' => $row['deliveryLastName'],
                        'distanceRules' => $row['distanceRules'],
                        'deliveryStatus' => $row['deliveryStatus']
                        
                    ];
                    
                    array_push($values,$value);
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
                $apiMessage="¡Ordenes no selleccionados con éxito!";
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
       
    }


    
?>