<?php

/**
 * TaskModel
 * @author k1k9
 */

namespace app\models;

class TaskModel extends AbstractModel
{
    public function getTasks() {
        $mysqli = $this->connectMysql();
        if ($mysqli) {
            $stmt = $mysqli->prepare("SELECT * FROM Tasks ORDER BY points ASC");
            $stmt->execute();
            $result = $stmt->get_result();
            $tasks = array();
    
            while ($row = $result->fetch_assoc()) {
                $tasks[] = $row;
            }
    
            $stmt->close();
            $mysqli->close();
    
            return $tasks;
        }
    
        return array();
    }

    public function getTask(int $id) {
        $mysqli = $this->connectMysql();
        if ($mysqli) {
            $stmt = $mysqli->prepare("SELECT * FROM Tasks WHERE id = ?;");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $result = $result->fetch_assoc();
            } else { $result = false; }
    
            $stmt->close();
            $mysqli->close();
    
            return $result;
        }
    
        return array();
    }


    public function addTask($title, $description, $flag, $points, $level, $category, $author, $file = '') {
        $mysqli = $this->connectMysql();
        if ($mysqli) {
            $stmt = $mysqli->prepare("INSERT INTO Tasks (title, description, file, flag, category, author, points, level) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ssssiiis', $title, $description, $file, $flag, $category, $author, $points, $level);
            $stmt->execute();
            $stmt->close();
            $mysqli->close();
            return true;
        }
        return false;
    }


    public function getSolves($id) {
        $mysqli = $this->connectMysql();
        if ($mysqli) {
            $stmt = $mysqli->prepare("SELECT * FROM Solves WHERE task_id = ? AND user_id NOT IN (SELECT id FROM Users WHERE permissions = 2);");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
    
            $stmt->close();
            $mysqli->close();
    
            return $result;
        }
    
        return 0;
    }


    public function deleteTask($id) {
        $mysqli = $this->connectMysql();
        if ($mysqli) {
            // Remove solves
            $stmt = $mysqli->prepare("DELETE FROM Solves WHERE task_id = ?;");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
    
            // Remove task
            $stmt = $mysqli->prepare("DELETE FROM Tasks WHERE id = ?;");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
            $mysqli->close();
            return true;
        }
        return false;
    }    
}