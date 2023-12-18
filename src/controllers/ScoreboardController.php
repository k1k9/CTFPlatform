<?php
/**
 * Class ScoreboardController
 * Monitor the scoreboard
 * @author k1k9
 */
namespace app\controllers;
class ScoreboardController extends AbstractController
{
    private \app\models\UserModel $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = new \app\models\UserModel();
    }


    public function index() {
        $scoreboard = $this->user->getUsersAndSortByPoints();
        $scoreboard = array_filter($scoreboard, function ($user) {
            return $user['permissions'] != 2;
        });
        $scoreboard = array_values($scoreboard);
        $this->renderView('Scoreboard', $scoreboard, ['title' => 'Scoreboard', 'css' => 'css/scoreboard.css']);
    }
}