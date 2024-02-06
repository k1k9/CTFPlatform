<?php
/**
 * UserModel
 * @author k1k9
 */
namespace app\models;
use Exception;
class UserModel extends AbstractModel
{
    public string $username;
    private string $password;
    public int $permissions;
    public array $solves; // stores id to solved tasks
    public array $created;
    public string $created_at;
    public int $points;
    public array $last_solve;

    public function createUser(string $username, string $password) {
        /**
         * Create user in database
         */
        $mysqli = $this->connectMysql();
        if ($mysqli === false) return false;
        $stmt = $mysqli->prepare("INSERT INTO Users (username, password) VALUES (?,?)");
        $stmt->bind_param('ss', $username, $password);
        try {
            $result = $stmt->execute();
        } catch (Exception $e) {
            $result = false;
        }
        $stmt->close();
        $mysqli->close();

        return ($result) ? true : false;
    }


    public function checkIsUserExists(string $username)
    {
        /**
         * Check is user in database
         */
        $mysqli = $this->connectMysql();
        $stmt = $mysqli->prepare("SELECT id FROM Users WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        $result = ($stmt->num_rows > 0) ? true : false;
        $stmt->close();
        $mysqli->close();
        return $result;
    }


    public function checkUserCredentials(string $username, string $password) {
        /**
         * Check is user credentials is valid
         */
        $mysqli = $this->connectMysql();
        $stmt = $mysqli->prepare("SELECT id, username, password, permissions FROM Users WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $username, $password_hash, $permissions);
        $stmt->fetch();
        if ($password_hash === NULL){
            $password_hash = PASSWORD_DEFAULT;
        }
        $result = password_verify($password, $password_hash);
        if ($result) {
            $_SESSION['id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['permissions'] = $permissions;
            # Update last_login
            $stmt = $mysqli->prepare("UPDATE Users SET last_login = NOW() WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
        }
        $stmt->close();
        $mysqli->close();
        if ($result) return true;
        return false;
    }


    public function getUser(string $username = '', int $id = 0) {
        /**
         * Get user by username or id
         */
        $mysqli = $this->connectMysql();

        // Get by username
        if ($username > 3 && $mysqli) {
            $stmt = $mysqli->prepare("SELECT * FROM Users WHERE username = ?");
            $stmt->bind_param('s', $username);
        }

        // Get by ID
        if ($id > 0 && $mysqli) {
            $stmt = $mysqli->prepare("SELECT * FROM Users WHERE id = ?");
            $stmt->bind_param('i', $id);
        }


        if ($mysqli !== false) {
            try {
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $stmt->bind_result($id, $username, $password, $permissions, $created_at, $points, $last_solve, $last_login);
                    $stmt->fetch();
                    $result = [
                        'id' => $id,
                        'username' => $username,
                        'password' => $password,
                        'permissions' => $permissions,
                        'created_at' => $created_at,
                        'points' => $points,
                        'last_solve' => $last_solve,
                        'last_login' => $last_login,
                    ];
                } else {
                    $result = false;
                }
                $stmt->close();
            }
            catch (Exception $e) {
                $result = false;
            }
            $mysqli->close();
        }
        return $result ?? false;
    }


    public function getUsersAndSortByPoints($limit = null) {
        /**
         * Sort users by point and first solve
         */
        $mysqli = $this->connectMysql();
        if ($limit != null){
            $stmt = $mysqli->prepare("SELECT id, username, points, permissions, last_solve, last_login FROM Users ORDER BY points DESC, last_solve ASC LIMIT ?");
            $stmt->bind_param('i', $limit);
        } else {
            $stmt = $mysqli->prepare("SELECT id, username, points, permissions, last_solve, last_solve FROM Users ORDER BY points DESC, last_solve ASC");
        }
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $username, $points, $permissions, $last_solve, $last_login);
        $result = [];
        while ($stmt->fetch()) {
            $result[] = [
                'id' => $id,
                'username' => $username,
                'points' => $points,
                'permissions' => $permissions,
                'last_solve' => $last_solve ?? '',
                'last_login' => $last_login ?? ''
            ];
        }
        $stmt->close();
        $mysqli->close();
        return $result;
    }


    public function checkSolvedTasks(int $id) {
        /**
         * Get all solved tasks by user id
         */
        $mysqli = $this->connectMysql();
        $stmt = $mysqli->prepare("SELECT task_id FROM Solves WHERE user_id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($task_id);
        $result = [];
        while ($stmt->fetch()) {
            $result[] = $task_id;
        }
        $stmt->close();
        $mysqli->close();
        return $result;
    }


    public function checkSolvedTask(int $id, int $task_id) {
        /**
         * Check is user solved task
         */
        $mysqli = $this->connectMysql();
        $stmt = $mysqli->prepare("SELECT task_id FROM Solves WHERE user_id = ? AND task_id = ?");
        $stmt->bind_param('ii', $id, $task_id);
        $stmt->execute();
        $stmt->store_result();
        $result = ($stmt->num_rows > 0) ? true : false;
        $stmt->close();
        $mysqli->close();
        return $result;
    }


    public function addPoints(int $id, int $points) {
        /**
         * Add points to user
         */
        $mysqli = $this->connectMysql();
        $stmt = $mysqli->prepare("UPDATE Users SET points = points + ? WHERE id = ?");
        $stmt->bind_param('ii', $points, $id);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }


    public function addSolvedTask(int $user_id, int $task_id) {
        /**
         * Add solved task to user
         */
        $mysqli = $this->connectMysql();
        # Add solved task to user and timestamp
        $stmt = $mysqli->prepare("INSERT INTO Solves (user_id, task_id, created_at) VALUES (?,?,NOW()) ON DUPLICATE KEY UPDATE created_at = NOW()");
        $stmt->bind_param('ii', $user_id, $task_id);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }


    public function deleteUser(int $id) {
        /**
         * Delete user from database
         */
        $mysqli = $this->connectMysql();
        
        $stmt = $mysqli->prepare("DELETE FROM Solves WHERE user_id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $stmt = $mysqli->prepare("DELETE FROM Users WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }
}