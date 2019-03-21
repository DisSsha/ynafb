<?php

namespace App\Core;
use \PDO;
class Model
{
    public $db;
    public $primaryKey = 'id'; 

    private $host	= 'localhost';
    private $database	= 'ynafb';
    private $login	= 'ynafb';
    private $password	= 'ynafb';

    public function __construct(){
		try{

			$pdo = new PDO(
				'mysql:host='.$this->host.';dbname='.$this->database.';',
				$this->login,
				$this->password,
				array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
			);
			$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
			$this->db = $pdo; 
		}catch(PDOException $e){
            die('Database connection error.'); 
		}	 
    }

	/**
	* Permet de récupérer plusieurs enregistrements
	* @param $req Tableau contenant les éléments de la requête
	**/
	public function find($req = array()){
		$sql = 'SELECT ';

        // Fields selection
		if(isset($req['fields'])){
			if(is_array($req['fields'])){
				$sql .= implode(', ',$$req['fields']);
			}else{
				$sql .= $req['fields']; 
			}
		}else{
			$sql.='*';
		}

		$sql .= ' FROM '.$this->table.' as '.get_class($this).' ';

		// Jointure ?
		if(isset($req['join'])){
			foreach($req['join'] as $k=>$v){
				$sql .= 'LEFT JOIN '.$k.' ON '.$v.' '; 
			}
		}

		// Where close
		if(isset($req['conditions'])){
			$sql .= 'WHERE ';
			if(!is_array($req['conditions'])){
				$sql .= $req['conditions']; 
			}else{
				$cond = array(); 
				foreach($req['conditions'] as $k=>$v){
					if(!is_numeric($v)){
						$v = '"'.mysql_escape_string($v).'"'; 
					}
					
					$cond[] = "$k=$v";
				}
				$sql .= implode(' AND ',$cond);
			}

		}

		if(isset($req['order'])){
			$sql .= ' ORDER BY '.$req['order'];
		}


		if(isset($req['limit'])){
			$sql .= ' LIMIT '.$req['limit'];
		}

		$pre = $this->db->prepare($sql); 
		$pre->execute(); 
		return $pre->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
	* Alias permettant de retrouver le premier enregistrement
	**/
	public function findFirst($req){
		return current($this->find($req)); 
	}

	/**
	* Récupère le nombre d'enregistrement
	**/
	public function findCount($conditions){
		$res = $this->findFirst(array(
			'fields' => 'COUNT('.$this->primaryKey.') as count',
			'conditions' => $conditions
			));
		return $res->count;  
    }
    
    /**
	* Permet de supprimer un enregistrement
	* @param $id ID de l'enregistrement à supprimer
	**/	
	public function delete($id){
		$sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = $id";
		$this->db->query($sql); 
    }
    
    	/**
	* Permet de sauvegarder des données
	* @param $data Données à enregistrer
	**/
	public function save($data){
		$key = $this->primaryKey;
		$fields =  array();
		$d = array(); 
		foreach($data as $k=>$v){
			if($k!=$this->primaryKey){
				$fields[] = "$k=:$k";
				$d[":$k"] = $v; 
			}elseif(!empty($v)){
				$d[":$k"] = $v; 
			}
		}
		if(isset($data->$key) && !empty($data->$key)){
			$sql = 'UPDATE '.$this->table.' SET '.implode(',',$fields).' WHERE '.$key.'=:'.$key;
			$this->id = $data->$key; 
			$action = 'update';
		} else{
			$sql = 'INSERT INTO '.$this->table.' SET '.implode(',',$fields);
			$action = 'insert'; 
		}
		$pre = $this->db->prepare($sql); 
		$pre->execute($d);
		if($action == 'insert'){
			$this->id = $this->db->lastInsertId(); 
		}
	}
}


