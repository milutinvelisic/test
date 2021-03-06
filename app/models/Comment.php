<?php


namespace app\models;


class Comment
{

    private $database;

    public function __construct(Database $database) {
        $this->database = $database;
    }

    public function getAllActiveComments() {
        return $this->database->executeGet("select * from comments where status = 1");
    }

    public function getAllComments(){
        return $this->database->executeGet("select * from comments");
    }

    public function insertComment($email, $title, $text){

        $params = [
            $title,
            $email,
            $text
        ];

        try {
            $insert = $this->database->conn->prepare("INSERT into comments values(null,?, ?, ?, 0)");
            $result = $insert->execute($params);
            if($result){
                return true;
            }
        }catch (\PDOException $ex){
            echo $ex->getMessage();
        }

    }

    public function activateComment($id){
        $prep = $this->database->conn->prepare("UPDATE comments set status = 1 where idComment = ?");
        $res = $prep->execute([$id]);
        if($res){
            return true;
        }
    }

    public function deactivateComment($id){
        $prep = $this->database->conn->prepare("UPDATE comments set status = 0 where idComment = ?");
        $res = $prep->execute([$id]);
        if($res){
            return true;
        }
    }

    public function deleteComment($id){
        return $this->database->deleteSomething("delete from comments where idComment = ?", $id);
    }

}