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
        $data = null;
        $code = 200;
        if(count($params) == 0){
            $data = $this->model->getAll();
            if(isset($_GET['sortby'])){
                
                $data = $this->getAllByOrder($data);
                if($data == null){
                    $col = $_GET['sortby'];
                    $data = "El campo $col es desconocido";
                    $code = 400;
                }
            }
            if(isset($_GET['rol'])){
                $data = $this->getSearch($_GET['rol'],$data);
               
                if(empty($data)){
                    $rol = $_GET['rol'];
                    $data = "El campo $rol no tiene coincidencias";
                    $code = 400;
                }
            }
            if(isset($_GET['page']) && isset($_GET['limit'])){
                $data = $this->getWithPagination($data,$_GET['page'],$_GET['limit']);
                if(empty($data)){
                    $data = "No hay mas resultados";
                    $code = 400;
                }else if (is_string($data)){
                    $code = 400;
                }
            }
        }else{
            $id = $params[':ID'];
            $char = $this->model->getByID($id);
            if(!empty($char)){
                $data = $char;
            }else{
               $data = "El personaje con id $id no existe";
               $code = 404;
            }
        }
        return $this->view->response($data,$code);
    }
    private function getWithPagination($characters,$page,$limit){
        if(is_numeric($page) && is_numeric($limit)){
            $start = $page*$limit ;
            $finish = $start + $limit; 
            $result = array();
            foreach($characters as $i=>$char){
                if($i >= $start && $i < $finish){
                    array_push($result,$char);
                }
            }
            return $result;
        }else{
            return  "Ingrese parametros validos";
        }
    }
    private function getSearch($filter,$charList){
        $aux = array();
        if(!is_string($charList)){
            foreach($charList as $char){
                if (strtolower($char->rol) == strtolower($filter)){
                    array_push($aux,$char);
                }
            }
        }
        return $aux;
    }
    private function getAllByOrder($charList){
        if(isset($_GET['order']) && $_GET['order'] == 'desc'){
                $order = SORT_DESC;
        }else{
            $order = SORT_ASC;
        }
        $col = $_GET['sortby'];
        if (($col== 'nombre' || $col== 'rol' || $col== 'nucleo_varita'|| $col== 'id'|| $col == 'id_casa' )){ 
            $aux= array();
            foreach ($charList as $key => $char) {
                $aux[$key] = strtolower($char->$col);// aux guarda todos los valores del campo dado de characters
            }
            array_multisort($aux,$order,$charList);//(arreglo con valores a comparar, orden asc o desc, arreglo a ordenar)
            return $charList;
        }else{
            return null;
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
                $this->view->response("Casa con id $newChar->id_casa no existe", 404);
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