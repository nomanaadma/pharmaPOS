<?php

error_reporting(E_ALL);
ini_set('display_error', 1);
ini_set("log_errors", true);
ini_set("error_log", "error.log"); //send error log to log file specified here. 

require_once('config/config.php');
require_once 'builder/startup.php';

launch();