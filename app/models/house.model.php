<?php
class HouseModel{
    private $db;
    function __construct()
    {
        $this->db = new PDO(
            'mysql:host=localhost;'
                . 'dbname=primerentrega;charset=utf8',
            'root',
            ''
        );
    }
    function getAll(){
        $query = $this->db->prepare("SELECT * FROM casas");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    function getByID($idHouse){
        $query = $this->db->prepare("SELECT * FROM casas WHERE id = ?");
        $query->execute([$idHouse]);
        return $query->fetch(PDO::FETCH_OBJ);
    }
    
    function insert($name,$founder,$colors,$symbol,$image = null){
        
        if($image){
            $imagePath = $this->uploadImage($image);
        }else{
            $imagePath = 'images/houseShields/pordefecto.png';
        }
        $query = $this->db->prepare("INSERT INTO casas (nombre_casa,colores,simbolo,fundador,escudo) VALUES (?,?,?,?,?)");
        $query->execute([$name,$colors,$symbol,$founder,$imagePath]);
    }
    function uploadImage($image){
        $location = 'images/houseShields/'. uniqid() . '.' . strtolower(pathinfo($_FILES['shield']['name'], PATHINFO_EXTENSION)); 
        move_uploaded_file($image,$location); //se mueve de temporal a donde especifique arriba
        return $location; // lo devuelve para asi insertar la misma ruta en la bd
    }
    function delete($idHouse){
        $query = $this->db->prepare("DELETE FROM casas WHERE id = ?");
        $query->execute([$idHouse]);
    }
    function update($name,$founder,$colors,$symbol,$idHouse,$image = null){
        if($image){
            $imagePath = $this->uploadImage($image);
        }else{
            $imagePath = 'images/houseShields/pordefecto.png';
        }
        $query = $this->db->prepare("UPDATE `casas` SET `id`=?,`nombre_casa`= ?,`colores`=?,`simbolo`=?,`fundador`=?, `escudo`=? WHERE id = ?");
        $query->execute([$idHouse,$name,$colors,$symbol,$founder,$imagePath,$idHouse]);
    }
}