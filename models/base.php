<?php


class Base_model
{

	protected $db;

	public function __construct()
	{
		try{
			$this->db = new PDO(
				'mysql:host='. $GLOBALS['config']['db']['host'].
				';dbname='. $GLOBALS['config']['db']['name'],
				$GLOBALS['config']['db']['user'],
				$GLOBALS['config']['db']['password']
			);
		}catch(PDOException $e){
			if($GLOBALS['config']['debug']){
				print "PDO error: " . $e->getMessage() . "<br/>";
				die();
			}else{
				return NULL;
			}
		}

		if($GLOBALS['config']['debug'])
		{
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		}else{
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
		}		
	}
}