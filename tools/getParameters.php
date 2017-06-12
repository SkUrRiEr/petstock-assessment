<?php

/*
 * Retrieve database parameters from Symfony's configuration
 */

$filename = dirname(__DIR__) . "/app/config/parameters.yml";

$data = yaml_parse_file($filename);

$parameters = $data["parameters"];

$port = $data["parameters"]["database_port"];
$name = $data["parameters"]["database_name"];
$user = $data["parameters"]["database_user"];
$password = $data["parameters"]["database_password"];

$switches = array();

if (isset($parameters["database_host"]) && $parameters["database_host"] != "") {
    $switches[] = "--host=" . $parameters["database_host"];
}

if (isset($parameters["database_port"]) && $parameters["database_port"] != "") {
    $switches[] = "--port=" . $parameters["database_port"];
}

if (isset($parameters["database_user"]) && $parameters["database_user"] != "") {
    $switches[] = "--user=" . $parameters["database_user"];
}

if (isset($parameters["database_password"]) && $parameters["database_password"] != "") {
    $switches[] = "--password=" . $parameters["database_password"];
}

if (isset($parameters["database_name"]) && $parameters["database_name"] != "") {
    $switches[] = $parameters["database_name"];
}

echo implode(" ", $switches);
