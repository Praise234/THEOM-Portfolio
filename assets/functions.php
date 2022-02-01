<?php
class dbopps{
    private $host, $dbname, $username, $password, $conn;
    private $table, $column, $where, $limit, $order, $data;
    private $message="";
    private $selectFlag=TRUE;
    private $sql, $stmt;

    public function __construct($host, $dbname, $username, $password){
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
        try{
            $this->conn = new PDO("mysql:host=$host; dbname=$dbname", $this->username, $this->password);
            //PDO error mode exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e){
            $this->message = "Connection Failed! Please check database login credentials";
        }
    }

    private function executor($action){
        switch ($action) {
            case 'Select':
                if($this->selectFlag){
                    try{
                        $this->stmt = $this->conn->prepare($this->sql);
                        $this->stmt->execute();
                        $this->data = $this->stmt->fetchAll();
                        return $this->data;
                    }catch(PDOException $e){
                        $this->message = "An error occured, please try again";
                    }
                }
                break;
            default:
                $this->message = "An";
                break;
        }
        return $this->getMessage();
    }

    public function select($table, $columns, $where="" , $inner=[], $limit="", $order="", $offset=""){
        if(empty($table)){
            $this->selectFlag = FALSE;
        }
        if(empty($columns)){
            $this->selectFlag = FALSE;
        }
        if($this->selectFlag){
            $this->sql = "SELECT $columns FROM $table";
            if(!empty($where)){
                $this->sql .= " WHERE $where";
            }
            if(!empty($inner)){
                $this->sql .= " INNER JOIN $inner[0] ON $inner[1] = $inner[2]";
            }
            if(!empty($inner[3])){
                $this->sql .= " WHERE $inner[3]";
            }
            if(!empty($order)){
                $this->sql .= " ORDER BY $order";
            }
            if(!empty($limit)){
                $this->sql .= " lIMIT $limit";
            }
            if(!empty($offset)){
                $this->sql .= " OFFSET $offset";
            }
           return $this->executor("Select");
        }else{
            $this->message = "Incorrect Parameters!";
            return $this->getMessage();
        }
    }

    public function getMessage(){
        return $this->message;
    }
}
$db = new dbopps("localhost", "mayor", "root", "");
?>