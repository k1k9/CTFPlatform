<?php
/**
 * SolvesModel
 * @author k1k9
 */
namespace app\models;
use Exception;
class SolvesModel extends AbstractModel
{
    public int $id;
    public int $user_id;
    public int $task_id;
    public string $created_at;

    public function getTopSolves(int $task, int $limit) {
        /**
         * Get $limit top solves
         */
        $mysqli = $this->connectMysql();
        if ($mysqli) {
            $stmt = $mysqli->prepare("SELECT Users.username, Users.permissions, Solves.created_at FROM Solves INNER JOIN Users ON Solves.user_id = Users.id WHERE Solves.task_id = ? ORDER BY Solves.created_at ASC");
            $stmt->bind_param('i', $task);
            $stmt->execute();
            $result = $stmt->get_result();
            $solves = array();
    
            while ($row = $result->fetch_assoc()) {
                if (count($solves) < $limit) {
                    if($row['permissions'] != 2) {
                        $solves[] = $row;
                    }
                } else {
                    break;
                }
            }

    
            $stmt->close();
            $mysqli->close();
    
            return $solves;
        }
    }
}