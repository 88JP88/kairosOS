<?php
    require_once 'database/db_users.php';
class modelGet {
    public static function getDelivery($dta) {
            
                


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

        
        
                $query= mysqli_query($conectar,"SELECT deliveryId,deliveryName,deliveryLastName,clientId,isActive,distanceRules,deliveryMail,deliveryContact FROM generalDelivery where clientId='$clientId'");
            }
            
        
    if($filter=="filter"){
    
            
            
        $query= mysqli_query($conectar,"SELECT deliveryId,deliveryName,deliveryLastName,clientId,isActive,distanceRules,deliveryMail,deliveryContact FROM generalDelivery where clientId='$clientId' and $param LIKE '%$value%'");
              
    
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
                        'deliveryId' => $row['deliveryId'],
                        'clientId' => $row['clientId'],
                        'deliveryName' => $row['deliveryName'],
                        'deliveryLastName' => $row['deliveryLastName'],
                        'isActive' => $row['isActive'],
                        'distanceRules' => $row['distanceRules'],
                        'deliveryMail' => $row['deliveryMail'],
                        'deliveryContact' => $row['deliveryContact']
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
                    'delivery' => $values
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
                    'delivery' => $values
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
                    'delivery' => $values
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

                
                
            $query= mysqli_query($conectar,"SELECT gc.customerId,gc.clientId,gc.customerName,gc.customerLastName,gc.customerMail,gc.customerPoints,gc.customerPhone,gc.customerStars,gc.customerType,gc.isActive,gr.pointsEq,gr.pointsValue FROM generalCustomers gc JOIN generalRules gr ON gr.clientId=gc.clientId where gc.clientId='$clientId'");
        }
        
    
if($filter=="filter"){

        
        
    $query= mysqli_query($conectar,"SELECT gc.customerId,gc.clientId,gc.customerName,gc.customerLastName,gc.customerMail,gc.customerPoints,gc.customerPhone,gc.customerStars,gc.customerType,gc.isActive,gr.pointsEq,gr.pointsValue FROM generalCustomers gc JOIN generalRules gr ON gr.clientId=gc.clientId where gc.clientId='$clientId' and gc.$param LIKE '%$value%'");
   

}
        if($query){
            $numRows = mysqli_num_rows($query);

if ($numRows > 0) {
            $response="true";
            $message="Consulta exitosa";
            $status="202";
            $apiMessage="¡Clientes seleccionados ($numRows)!";
            $values=[];

            while ($row = $query->fetch_assoc()) {
                $value=[
                    'customerId' => $row['customerId'],
                    'clientId' => $row['clientId'],
                    'customerName' => $row['customerName'],
                    'customerLastName' => $row['customerLastName'],
                    'customerMail' => $row['customerMail'],
                    'customerPoints' => $row['customerPoints'],
                    'customerPhone' => $row['customerPhone'],
                    
                    'customerStars' => $row['customerStars'],
                    'customerType' => $row['customerType'],
                    'isActive' => $row['isActive'],
                    'pointsEq' => $row['pointsEq'],
                    'pointsValue' => $row['pointsValue']
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
            $apiMessage="¡Clientes no seleccionados con éxito!";
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