<?php
echo "<pre>";

ini_set("display_errors",1);
error_reporting(E_ALL);

$strBaseUrl = "/development/vmvc/public";//   Base url

define("CORE_DIR", "core/");
define("CONTROLLER_DIR", "app/controllers/");
define("CONTROLLER_SUFFIX","Controller");
define("METHOD_PREFIX","action_");

define("VIEW_PATH", "app/views");
define("VIEW_EXTENSION",".phtml");

include_once "Router.php";

include_once CORE_DIR . "models/Database.php";
include_once CORE_DIR . "models/BaseModel.php";
include_once CORE_DIR . "views/BaseView.php";
include_once CORE_DIR . "controllers/BaseController.php";

Router::addRoute(
    "/products/:id/:name/:foo", array(
        "controller" => "Products",
        "action"    => "viewProduct",
    )
);

Router::addRoute(
    "/products/:action/:id/:name/:foo", array(
        "controller" => "products"
    )
);

Router::setBaseUrl($strBaseUrl);
Router::setRequestUri($_SERVER['REQUEST_URI']);

Router::dispatch();