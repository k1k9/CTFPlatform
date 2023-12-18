<?php
/**
 * UserController
 * @author k1k9
 */
namespace app\controllers;
use app\models\UserModel;
use ErrorException;
class UserController extends AbstractController
{
    private UserModel $user;

    public function __construct(){
        parent::__construct();
        $this->user = new UserModel();
    }


    public function loginIndex(){
        if (isset($_SESSION['id'])) {
            header('Location: /');
        }
        $this->renderView('user/Login', 
        head:['title' => 'Login', 'css' => '/css/login.css']);
    }


    public function registerIndex(){
        if (isset($_SESSION['id'])) {
            header('Location: /');
        }
        $this->renderView('user/Register',
        head:['title' => 'Register', 'css' => '/css/login.css']);
    }
       
    
    public function register(){
        /**
         * Create user in database
         */
        if (!isset($_POST['username']) || !isset($_POST['password']) || strlen($_POST['username']) > 20 || strlen($_POST['password']) <= 3)
        {
            return $this->redirect('/u/register');
        }

        try{
            $username = htmlspecialchars($_POST['username'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $password = htmlspecialchars($_POST['password'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $password = password_hash($password, PASSWORD_DEFAULT);
            $created = $this->user->createUser($username, $password);
            if ($created) {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }   
                $this->login();
                $_SESSION['id'] = $this->user->getUser(username: $username)['id'];
                return $this->redirect('/');
            }
        } catch (ErrorException $e) {
            if (DEV_MODE) {
                $this->retrunErrorView(400);
            }
        }
        return $this->redirect('/u/register');
    }


    public function login() {
        /**
         * Check is user credentials is valid
         */
        if (!isset($_POST['username']) || !isset($_POST['password']))
        {
            return $this->redirect('/u/login');
        }
        $username = htmlspecialchars($_POST['username'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $password = htmlspecialchars($_POST['password'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $exists = $this->user->checkUserCredentials($username, $password);
        if ($exists) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }            
            $_SESSION['id'] = $this->user->getUser(username: $username)['id'];
            $_SESSION['is_admin'] = $this->checkIsAdmin($_SESSION['id']);
            return $this->redirect('/');
        }
        return $this->redirect('/u/login');
    }


    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }        
        session_destroy();
        $this->redirect('/');
    }


    public function checkIsAdmin(int $id) {
        $permissions = $this->user->getUser(id: $id)['permissions'];
        return ($permissions === 2) ? true : false;
    }


    public function checkSolvedTask(int $id, int $task_id) {
        $solved = $this->user->checkSolvedTask($id, $task_id);
        return ($solved) ? true : false;
    }


    public function addPoints(int $id, int $points) {
        $this->user->addPoints($id, $points);
    }


    public function addSolvedTask(int $id, int $task_id) {
        $this->user->addSolvedTask($id, $task_id);
    }


    public function deleteUser(int $id) {
        $this->user->deleteUser($id);
    }
}