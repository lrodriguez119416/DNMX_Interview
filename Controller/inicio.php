<?php 
use Routes\App;
use Model\UsuarioModel;
class Inicio{
    public $users;
    public $model;
    public function __construct()
    {
        App::page('wrapper');
        $this->model = new UsuarioModel();
    }
    public function index()
    {
        $this->users = $this->model->getUsers();
        return $this;
    }

    public function getRoles(){
        App::page('json');
        $roles = $this->model->getRoles();
        echo json_encode($roles);
    }
}
?>