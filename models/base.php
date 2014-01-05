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

	/*
		Takes an array and param name and constructs part of a prepared statemnt

	*/
	protected function or_statement_generate($params, $param_name)
	{
		$statement = '(';
		$first = TRUE;
		foreach ($params as $param)
		{
			if($first)
			{
				$first = FALSE;
				$statement .= $param_name. '=? ';
			}else{
				$statement .= 'OR '. $param_name. '=? ';
			}
		}
		$statement .= ')';
		return $statement;
	}

	/*
		Generates a number of question for parameter place holder in PDO prepared statement
	*/
	protected function generate_question_marks($number)
	{
		$questions = '';
		$first = TRUE;

		for($i = 0; $i < $number; $i++)
		{
			if($first)
			{
				$first = FALSE;
				$questions .= '?';
			}else{
				$questions .= ', ?';
			}
		}
		return $questions;
	}

}