<?php
   
    class modelResponse {
        
       public static function responsePost($responseApi,$messageApi,$statusApi,$messageSQL) {
    
        $values=[];

        $value=[
            'response' => $responseApi,
            'message' => $messageApi,
            'status' => $statusApi,
            'status' => $statusApi,
            'messageSQL'=>$messageSQL
            
        ];
        
        array_push($values,$value);
        
return json_encode(['response'=>$values]);
            
        }
          public static function responsePostCode($responseApi,$messageApi,$statusApi,$messageSQL,$statusCode) {
    
        $values=[];

        $value=[
            'response' => $responseApi,
            'message' => $messageApi,
            'status' => $statusApi,
            'statusCode' => $statusCode,
            'messageSQL'=>$messageSQL
            
        ];
        
        array_push($values,$value);
        
return json_encode(['response'=>$values]);
            
        }


    }
    
    
?>