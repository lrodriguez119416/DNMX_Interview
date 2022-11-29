<?php 

namespace Model;
use Libs\Model;

class UsuarioModel extends Model {

    public function getUsers(){
        $sql = "SELECT * 
            FROM users 
            AS u 
            INNER JOIN roles 
            AS r 
            ON u.id_rol = r.id";
        return $this->conn->query($sql)->fetchAll();       
    }

    public function getRoles(){
        $sql = "";
        $response = [];
        return $response;        
    }
}

?>