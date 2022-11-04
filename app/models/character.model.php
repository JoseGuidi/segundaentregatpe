<?php
class CharacterModel{
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
        $query = $this->db->prepare("SELECT * FROM personajes");
        $query->execute();
        return ($query->fetchAll(PDO::FETCH_OBJ));
    }
    function getByID($idCharacter){
        $query=$this->db->prepare("SELECT * FROM personajes WHERE id = ?");
        $query->execute([$idCharacter]);
        return $query->fetch(PDO::FETCH_OBJ);
    }
    function getAllByHouse($id){
        $query = $this->db->prepare ("SELECT * FROM personajes WHERE id_casa = ?");
        $query->execute([$id]);
        return $query->fetchAll((PDO::FETCH_OBJ));
    }
    function insert($name,$idHouse,$role,$core){
        $query = $this->db->prepare("INSERT INTO personajes (id_casa,nombre,rol,nucleo_varita) VALUES (?,?,?,?)");
        $query->execute([$idHouse,$name,$role,$core]);
        return $this->db->lastInsertId();
    }
    function delete($id){   
        $query = $this->db->prepare("DELETE FROM personajes WHERE id = ?");
        $query->execute([$id]);
    }
    function update($name,$idHouse,$role,$core,$idCharacter){
        $query=$this->db->prepare("UPDATE personajes SET id_casa=?,nombre=?,rol=?,nucleo_varita=? WHERE id = ?");
        $query->execute([$idHouse,$name,$role,$core,$idCharacter]);
    }
}