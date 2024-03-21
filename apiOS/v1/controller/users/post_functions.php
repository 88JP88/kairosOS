<?php
    
    class post_functions {
        function post_users($dta) {
            
            require_once '../../apiCore/v1/model/modelSecurity/uuid/uuidd.php';
            require_once '../../apiCore/v1/model/users/post_users.php';
           
   

            $gen_uuid = new generateUuid();
            $myuuid = $gen_uuid->guidv4();
            $profileId = substr($myuuid, 0, 8);
          
            
           

            $dta['profileId'] = $profileId;


             

           $model = new model_functions();
            return $model->model_user($dta);
        }


function post_internalusers($dta) {
            
            require_once '../../apiCore/v1/model/modelSecurity/uuid/uuidd.php';
            require_once '../../apiCore/v1/model/users/post_users.php';
           
   

            $gen_uuid = new generateUuid();
            $myuuid = $gen_uuid->guidv4();
            $profileId = substr($myuuid, 0, 8);
          
            
           

            $dta['profileId'] = $profileId;


             

           $model = new model_functions();
            return $model->model_internaluser($dta);
        }






        function post_usersAdmin($dta) {
            
            require_once '../../apiCore/v1/model/modelSecurity/uuid/uuidd.php';
            require_once '../../apiCore/v1/model/users/post_users.php';
           
    require_once '../../apiCore/v1/model/modelSecurity/crypt/cryptic.php';

            $gen_uuid = new generateUuid();
            $myuuid = $gen_uuid->guidv4();
            $myuuid2 = $gen_uuid->guidv4();
            $myuuid3 = $gen_uuid->guidv4();
            $primeros_ocho = substr($myuuid, 0, 8);
            $idusername = substr($myuuid, 0, 2);
            $primeros_ocho2 = substr($myuuid2, 0, 8);
            $primeros_ocho3 = substr($myuuid3, 0, 8);
            $names = $dta['name'];
            $namesString = (string) $names;
            $fullname = $namesString;

            $nombres = explode(" ", $fullname);
            $primerNombre = $nombres[0];
            $username= mb_strtolower($primerNombre, 'UTF-8').".".$idusername;
            $usernamekey2="CM.".$primerNombre.".".$idusername;
            
            $dato_encriptado = $encriptar($usernamekey2);

//CM.CAMBIO.6b
            $dta['primeros_ocho'] = $primeros_ocho;
            $dta['primeros_ocho2'] = $primeros_ocho2;
            $dta['primeros_ocho3'] = $primeros_ocho3;
            $dta['dato_encriptado'] = $dato_encriptado;
            $dta['username'] = $username;

            function generateApiTokenadmin() {
                $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                $token = '';
              
                $characterCount = strlen($characters);
                for ($i = 0; $i < 20; $i++) {
                  $randomIndex = rand(0, $characterCount - 1);
                  $token .= $characters[$randomIndex];
                }
              
                return $token;
              }
              
              $apiToken = generateApiTokenadmin();
             $dta['apiToken'] = $apiToken;
              

             function generateRandomCodeadmin() {
                $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                $token = '';
              
                $characterCount = strlen($characters);
                for ($i = 0; $i < 8; $i++) {
                  $randomIndex = rand(0, $characterCount - 1);
                  $token .= $characters[$randomIndex];
                }
              
                return $token;
              }
              
              $ranCode = generateRandomCodeadmin();
             $dta['ranCode'] = $ranCode;
            
           $model = new model_functions();
            return $model->model_user($dta);
        }


        
    }
    
?>