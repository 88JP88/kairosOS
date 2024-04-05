<?php

require 'flight/Flight.php';
require_once 'database/db_users.php';
require_once 'model/users/postModel.php';
require_once 'model/users/getModel.php';
require_once 'model/users/responses.php';
require 'model/modelSecurity/authModule.php';
require_once 'env/domain.php';

require_once 'kronos/postLog.php';


Flight::route('POST /pp', function () {
echo "hello";

});


Flight::start();
