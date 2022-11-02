<?php
require_once 'app/models/character.model.php';
require_once 'app/views/character-api.view.php';
require_once 'app/models/house.model.php';
Class CharacterApiController{
    private $model;
    private $view;
    private $houseModel;
    private $data;
    public function __construct(){
        $this->model = new CharacterModel();
        $this->view = new CharacterApiView();
        $this->houseModel = new HouseModel();
        // lee el body del request
        $this->data = file_get_contents("php://input");

    }
    private function getData(){
        return json_decode($this->data);
    }
    function get($params = null){
        if($params == null){
            $chars = $this->model->getAll();
            return $this->view->response($chars);
        }else{
            $id = $params[':ID'];
            $char = $this->model->getByID($id);
            if(!empty($char)){
                return $this->view->response($char);
            }else{
                return $this->view->response("El personaje con id $id no existe",404);
            }
        }
    }
    /* PREGUNTAR SI ESTA BIEN ESTO */
    function getAllByOrder($params = null){
        /* Analazio si el primer parametro de la url es uno de estos campos de la bbdd, 
        en caso de que sea ordeno por ese campo, y si no es un campo de la bbdd por defecto le pongo id*/
        if ($params[':col'] == 'nombre' || $params[':col'] == 'rol' || $params[':col'] == 'nucleo_varita'||$params[':col'] == 'id_casa' ){ 
            $col = $params[':col'];
        } else $col = 'id';
        /* Analizo el segundo parametro de la url, si es desc de descendiente le asigno
         la palabra SORT_DESC necesaria para ordenarlos descendentemente. Si es asc o esta vacia asigno SORT_ASC */
        if($params[':order'] == 'descendant'){
            $order = SORT_DESC; 
        }else if ($params[':order'] != 'ascendant' || empty($params[':order'])){
            $order = $params[':order'];
            return $this->view->response("La manera de orden $order es desconocida",400);
            }else{
                $order = SORT_ASC;
            }
        $aux= array();
        $characters = $this->model->getAll();
        foreach ($characters as $key => $char) {
            $aux[$key] = strtolower($char->$col);
        }
        if(empty($aux)){
            return $this->view->response("El campo $col es desconocido",400);
        }else {
            array_multisort($aux,$order,$characters);
            return $this->view->response($characters);
        }
    }
    function delete($params = null){
        $id = $params[':ID'];
        $charDeleted = $this->model->getByID($id);      
        if($charDeleted){
            $this->model->delete($id);
            return $this->view->response($charDeleted);
        }else{
            return $this->view->response("El personaje con id $id no existe",404);
        }
    }

    function add($params = null){
        $newChar = $this->getData();
        if($this->charIsEmpty($newChar)){
            $this->view->response("Complete todos los datos", 400);
        }else{
            if($this->houseExists($newChar->id_casa)){
                $idNewChar = $this->model->insert($newChar->nombre,$newChar->id_casa,$newChar->rol,$newChar->nucleo_varita);
                $newChar = $this->model->getByID($idNewChar);
                $this->view->response($newChar,201);  
            }else{
                $this->view->response("Casa con id $newChar->id_casa no existe", 400);
            }
        }
    }
    private function charIsEmpty($char){
        return empty($char->nombre)|| empty($char->id_casa) || empty($char->rol) || empty($char->nucleo_varita);
    }
    private function houseExists($idChar){
        return $this->houseModel->getByID($idChar) != null;
    }
    function edit($params = null){
        $id = $params[':ID'];
        $charToEdit = $this->model->getByID($id); 
        if($charToEdit == null){
            $this->view->response("El personaje con id $id no existe",400);
        }else{
            $newValues = $this->getData();
            if($this->charIsEmpty($newValues)){
                $this->view->response("Complete todos los datos", 400);
            }else{
                if($this->houseExists($newValues->id_casa)){
                    $this->model->update($newValues->nombre,$newValues->id_casa,$newValues->rol,$newValues->nucleo_varita,$id);
                    $this->view->response("Se actualizo el personaje con id $id",200);
                }else{
                    $this->view->response("Casa con id $newValues->id_casa no existe", 400);
                }
            }
        }
    }

}