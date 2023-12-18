<?php

/**
 * CategoryModel
 * @author k1k9
 */

namespace app\models;

class CategoryModel extends AbstractModel
{
    public function createCategory($name) {
        $mysqli = $this->connectMysql();
        if ($mysqli) {
            $stmt = $mysqli->prepare("INSERT INTO Categories (name) VALUES (?)");
            $stmt->bind_param('s', $name);
            $stmt->execute();
            $stmt->close();
            $mysqli->close();
        }
    }

    public function getCategory($id) {
        $mysqli = $this->connectMysql();
        if ($mysqli) {
            $stmt = $mysqli->prepare("SELECT name FROM Categories WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->bind_result($name);
            
            if ($stmt->fetch()) {
                return $name;
            }
        }
        return false;
    }


    public function getCategories() {
        $mysqli = $this->connectMysql();
        if ($mysqli) {
            $stmt = $mysqli->prepare("SELECT * FROM Categories;");
            $stmt->execute();
            $result = $stmt->get_result();
            $categories = array();
    
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
    
            $stmt->close();
            $mysqli->close();
    
            return $categories;
        }
        return array();
    }
}