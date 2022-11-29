<?php 
namespace Libs;

class Model {

    public $conn;
    public function __construct()
    {
        $this->connect();
    }

    public function connect(){
        try{
            $this->conn = new \PDO('mysql:host=localhost;dbname=users', 'root', '');
        }catch(\Exception $e){
            error_log("Error al conectar con la base de datos: ". $e);
        }
    }
}
?>