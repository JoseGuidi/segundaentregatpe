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
    function get($params = null){ //preguntar si resolucion con if esta bien
        $allChars = $this->model->getAll();
      
        if(isset($_GET['sortby'])){ // si existe el parametro sortby en la url hay que ordenar
            return $this->getAllByOrder($allChars);
        }else if (isset($_GET['rol'])){// si existe el parametro search_rol en la url hay que buscar por rol
            return $this->getSearch($allChars,$_GET['rol']);
        }if(isset($_GET['page']) && isset($_GET['limit'])){
           // $this->getWithPagination($_GET['page'],$_GET['limit']);
        }else{
            if($params == null){
                return $this->view->response($allChars);
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
    }
    private function getSearch($allChars,$filter){
        $aux = array();
        foreach($allChars as $char){
            if (strtolower($char->rol) == strtolower($filter)){
                array_push($aux,$char);
            }
        }
        return $this->view->response($aux);
    }
    private function getAllByOrder($characters){
        if(isset($_GET['order'])){
            if($_GET['order'] == 'desc'){
                $order = SORT_DESC;
            }else{
                $order = SORT_ASC;
            }
        }
        else{
            $order = SORT_ASC;
        }
        $col = $_GET['sortby'];
        if (($col== 'nombre' || $col== 'rol' || $col== 'nucleo_varita'|| $col== 'id'|| $col == 'id_casa' )){ 
            $aux= array();
            foreach ($characters as $key => $char) {
                $aux[$key] = strtolower($char->$col);
            }
            if(!empty($aux)){
                array_multisort($aux,$order,$characters);
                return $this->view->response($characters);
            }
        }else{
            return $this->view->response("El campo $col es desconocido",400);
        }
        
    }
    /*private function getSearch($filter){
        $result = $this->model->getByRole($filter);
        if(empty($result)){
            return $this->view->response("El rol $filter es desconocido",400);
        }else {
            return $this->view->response($result);
        }
        
    }*/
    /*private function getWithPagination($page,$limit){
        if(is_numeric($page) || is_numeric($limit)){ //si son numeros
            $chars = $this->model->getAll();
            $aux = array();
            $start = intval($params[':page']) * intval($params[':limit']);
            foreach ($chars as $indice => $c) {
                if ($indice > $start && $indice < ($start + intval($params[':limit']))){
                    array_push($aux,$c);
                }
        }
        return $this->view->response($aux);
        }else return $this->view->response("Ingrese valores validos",400);
    }*/
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