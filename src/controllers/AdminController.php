<?php
/**
 * Admin Controller
 * @author k1k9
 */
namespace app\controllers;
class AdminController extends AbstractController
{
    private bool $isAdmin;
    private \app\models\UserModel $user;


    public function __construct()
    {
        parent::__construct();
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true)
        {
            $this->isAdmin = true;
        }
    }
    

    public function settingsIndex() {
        $this->renderView('admin/Settings', head: ['title' => 'Settings', 'css' => '/css/admin.css']);
   }


    public function tasksIndex() {
        if (!$this->isAdmin) { 
            return $this->redirect('/');
        }

        // Get all tasks
        $task = new \app\models\TaskModel();
        $tasks = $task->getTasks();

        $this->renderView('admin/Tasks', data: $tasks, head: ['title' => 'Tasks', 'css' => '/css/admin.css']);
    }


    public function categoriesIndex() {
        if (!$this->isAdmin) { 
            return $this->redirect('/');
        }

        $this->renderView('admin/CreateCategory', head: ['title' => 'Create category', 'css' => '/css/admin.css']);
    }


    public function listUsers() {
        $this->user = new \app\models\UserModel();
        if (isset($_GET['deleteId']) && $this->isAdmin) {
            $this->user->deleteUser($_GET['deleteId']);
            return $this->redirect('/a/users');
        } elseif (!$this->isAdmin) { return $this->redirect('/a/users'); }
        $users = $this->user->getUsersAndSortByPoints();
        $this->renderView('admin/Users', $users, ['title' => 'Users', 'css' => '/css/admin.css']);
    }


    public function addCategory() {
        if (!$this->isAdmin) { 
            return $this->redirect('/');
        }

        if (!isset($_POST['name']) || strlen($_POST['name']) < 1) {
            return $this->redirect('/a/categories');
        }

        $category = new \app\models\CategoryModel();
        $category->createCategory(strtoupper($_POST['name']));
        return $this->redirect('/a/categories');
    }


    public function settingsChange() {
        $configPath = ROOT . '/config.json';
        $config = json_decode(file_get_contents(ROOT . '/config.json'), true);

    
        if (isset($_POST['siteName']) && strlen($_POST['siteName']) > 0) {
            $config['siteName'] = $_POST['siteName'];
        }

        
        if (isset($_POST['flagPrefix']) && strlen($_POST['flagPrefix']) > 0) {
            $config['flagPrefix'] = $_POST['flagPrefix'];
        }


        if (isset($_POST['devmode']) && $_POST['devmode'] !== $config['devmode']) {
            $config['devmode'] = $_POST['devmode'];
        }
        

        if (isset($_POST['startDate']) && strlen($_POST['startDate']) > 0){
            $config['startDate'] = $_POST['startDate'];
        }


        if (isset($_POST['startTime']) && strlen($_POST['startTime']) > 0){
            $config['startTime'] = $_POST['startTime'];
        }

        file_put_contents($configPath, json_encode($config, JSON_PRETTY_PRINT));
    

        if (intval($config['devmode']) === 1) {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        }

        header('Location: /a/settings');
    }
}