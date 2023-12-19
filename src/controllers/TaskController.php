<?php
/**
 * TaskController
 * Perform operations on ctf challenges
 * @author k1k9
 */
namespace app\controllers;

use app\models\TaskModel;
use app\models\UserModel;
use app\models\CategoryModel;

class TaskController extends AbstractController
{
    protected array $tasks;
    private TaskModel $task;
    private UserModel $user;
    private CategoryModel $category;

    public function __construct(){
        parent::__construct();
        $this->task = new TaskModel();
        $this->user = new UserModel();
        $this->category = new CategoryModel();
    }

    public function list()
    {
        /**
         * Render full list of challenges
         */
        $tasks = $this->task->getTasks();

        if ($tasks && isset($_SESSION['id'])) {
            for ($i = 0; $i < count($tasks); $i++) {
                if ($this->user->checkSolvedTask($_SESSION['id'], $tasks[$i]['id']))
                {
                    $tasks[$i]['isSolved'] = 'solved';
                } else {
                    $tasks[$i]['isSolved'] = '';
                }
            }
        }

        for ($i = 0; $i < count($tasks); $i++) {
            $tasks[$i]['category'] = $this->category->getCategory($tasks[$i]['category']);
         }
        $this->renderView('task/List', data: ["tasks" => $tasks], head: ['title' => 'All challenges', 'css' => '/css/task.css']);
    }

    public function details($id)
    {
        /**
         * Render single view
         */
        $id = intval($id);
        $id = htmlspecialchars($id, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $task = $this->task->getTask($id) ?? false;

        if ($task) {
            $task['category'] = $this->category->getCategory($task['category']);

            if (isset($_SESSION['id'])) {
                $task['isSolved'] = $this->user->checkSolvedTask($_SESSION['id'], $id) ?? false;
            } else {
                $task['isSolved'] = false;
            }
            $task['solves'] = $this->task->getSolves($id)->num_rows ?? 0;
            $this->renderView(
                view: 'task/Details',
                data: $task,
                head: ['title' => $task['title'], 'css' => '/css/task.css']
            );
        } else {
            $this->retrunErrorView();
        }
    }

    public function checkFlag($id) {
        /**
         * Check flag and add points to user
         */
        $id = intval($id);
        $id = htmlspecialchars($id, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $task = $this->task->getTask($id) ?? false;
        $flag = strtoupper($_POST['flag']) ?? false;
        $user = new UserController;

        // Check is task valid
        if (!$task) {
            $this->retrunErrorView();
            exit;
        }

        if (!$flag) {
            $this->redirect('/t/'.$id);
            exit;
        }

        // Check is user logged in
        if (!isset($_SESSION['id'])) {
            $this->redirect('/u/login');
            exit;
        }

        // Check is task already solved
        $solved = $user->checkSolvedTask($_SESSION['id'], $id);
        if ($solved) { 
            $this->redirect('/t/'.$id);
            exit;
        }

        // Check is flag valid
        if ($flag === $task['flag']) {
            $user->addPoints($_SESSION['id'], $task['points']);
            $user->addSolvedTask($_SESSION['id'], $id);
            $this->redirect('/t/'.$id);
            exit;
        } else {
            $this->redirect('/t/'.$id);
            exit;
        }        
    }

    public function addIndex() {
        /**
         * Render add challenge form
         */
        $this->renderView(
            view: 'task/Add',
            data: ['categories' => $this->category->getCategories()],
            head: ['title' => 'Add challenge', 'css' => '/css/task.css']
        );
    }

    public function add() {
        /**
         * Add challenge to database
         */
        $title = $_POST['title'] ?? false;
        $category = $_POST['category'] ?? false;
        $points = $_POST['points'] ?? false;
        $flag = strtoupper($_POST['flag']) ?? false;
        $description = $_POST['description'] ?? false;
        $filename = $_POST['filename'] ?? '';

        if ($title && $category && $points && $flag && $description) {

            $author = $_SESSION['id'];
            $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $category = intval($category);
            $points = intval($points);
            switch ($points) {
                case ($points > 0 && $points <= 30):
                    $level = 'easy';
                    break;
                case ($points > 30 && $points <= 60):
                    $level = 'medium';
                    break;
                case ($points > 60 && $points <= 100):
                    $level = 'hard';
                    break;
                default:
                    $level = '????';
                    break;
            }
            
            $flag = htmlspecialchars($flag, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $description = htmlspecialchars($description, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $filename = htmlspecialchars($filename, ENT_QUOTES | ENT_HTML5, 'UTF-8');

            $this->addTaskToJson($title, $description, $flag, $points, $level, $category, $author, $filename);

            $this->task->addTask(
                title: $title,
                description: $description,
                flag: $flag,
                points: $points,
                level: $level,
                category: $category,
                author: $author,
                file: $filename
            );
            $this->redirect('/t');
        } else {
            $this->retrunErrorView();
        }
    }


    function addTaskToJson($title, $description, $flag, $points, $level, $category, $author, $file){
        $filePath = ROOT . '/tasks.json';
    
        if (!file_exists($filePath)) {
            if (file_put_contents($filePath, json_encode([])) === false) {
                return false;
            }
        }
    
        // Read content
        $json = file_get_contents($filePath);
        if ($json === false) {
            return false;
        }
    
        // Decode JSON
        $data = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return false; 
        }
    
        // Add new task
        $newTask = array(
            'title' => $title,
            'description' => $description,
            'flag' => $flag,
            'points' => $points,
            'level' => $level,
            'category' => $category,
            'author' => $author,
            'file' => $file
        );
        $data[] = $newTask;
    
        // Encode to JSON
        $json = json_encode($data);
        if ($json === false) {
            return false; 
        }
    
        // Save file
        if (file_put_contents($filePath, $json) === false) {
            return false;
        }
        return true;
    }
        

    public function delete() {
        /**
         * Delete challenge from database
         */
        if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != true)
        {
            return $this->redirect('/t');
        }

        if (!isset($_GET['id'])){
            return $this->redirect('/t');
        }

        $id = intval($_GET['id']);
        $id = htmlspecialchars($id, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $this->task->deleteTask($id);
        return $this->redirect('/a/tasks');
    }
}