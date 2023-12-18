<?php
/**
 * Class AuthenticatorMiddleware
 * Check is user logged in and what permissions to view have
 * @author k1k9
 */
namespace app\middlewares;
use app\controllers\UserController;
use app\controllers\HomeController;

class AuthenticatorMiddleware
{
    public function handle()
    {
        // Check session
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (RESTRICT && !isset($_SESSION['id'])) {
            if ($_SERVER['REQUEST_URI'] !== '/u/login')
            {
                $controller = new UserController();
                $controller->loginIndex();
                exit();
            }
        }

        // Check is user have admin permissions        
        if(preg_match('~^\/a\/.+~', $_SERVER['REQUEST_URI'], $match)) {
            if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true)
            {
                (new HomeController)->error();
            }
        }
    }
}