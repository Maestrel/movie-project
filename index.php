<?php
//import de de l'autoload
include 'vendor/autoload.php';

//récupération de l'url
$url = parse_url($_SERVER["REQUEST_URI"]);
$path = isset($url["path"]) ? $url["path"] : "/";

//Import des classes
use App\Controller\HomeController;

//Instance des controllers
$homeController = new HomeController();
//router
switch ($path) {
    case '/':
        $homeController->index();
        break;
    case '/login':
        echo "login";
        break;
    case '/logout':
        echo "deconnexion";
        break;
    case '/register':
        echo "inscription";
        break;
    default:
        echo "error 404";
        break;
}
