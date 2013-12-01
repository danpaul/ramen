<?php

require_once($GLOBALS['config']['models']. '/base.php');

class Product_model extends Base_model
{
	const STATEMENT_GET_PRODUCT = 'SELECT * FROM Products WHERE id=:id LIMIT 1';
	const STATEMENT_GET_PRODUCTS = 'SELECT * FROM Products';
	
	const STATEMENT_INSERT_PRODUCT = 'INSERT INTO Products(name, description, price, inventory) VALUES (:name, :description, :price, :inventory)';
	const STATEMENT_UPDATE_PRODUCT = 'UPDATE Products SET name=:name, description=:description, price=:price, inventory=:inventory WHERE id=:id';

	const STATEMENT_INSERT_PRODUCT_TAG = 'INSERT INTO ProductTags(product_id, tag_id) VALUES (:product_id, :tag_id)';
	const STATEMENT_INSERT_PRODUCT_CATEGORY = 'INSERT INTO ProductCategories(product_id, tag_id) VALUES (:product_id, :tag_id)';

	public function __construct()
	{
		parent::__construct();
	}

	public function add_product($product_details)
	{
		$product_details = $this->type_set_params($product_details);

		$product = $this->type_set_params($product_details['product']);

		$statement = $this->db->prepare(self::STATEMENT_INSERT_PRODUCT);
		if( !$statement->execute($product) ){ return FALSE; }

		$product_id = $this->db->lastInsertId();

		if( !empty($product_details['tags']) )
		{
			$statement = $this->db->prepare(self::STATEMENT_INSERT_PRODUCT_TAG);
			foreach ($product_details['tags'] as $tag_id)
			{
				if( !$statement->execute(array('product_id' => $product_id, 'tag_id' => $tag_id)) )
				{
					return FAlSE;
				}
			}
		}

		if( !empty($product_details['categories']))
		{

		}
		return TRUE;
	}

	public function edit($id, $params)
	{
		$params = $this->type_set_params($params);
		$params['id'] = $id;
		$statement = $this->db->prepare(self::STATEMENT_UPDATE_PRODUCT);
		return($statement->execute($params));
	}

	public function get_product($id, $type_unset = TRUE)
	{
		$statement = $this->db->prepare(self::STATEMENT_GET_PRODUCT);
		$statement->execute(array('id' => $id));
		if($type_unset){
			return $this->type_unset_params($statement->fetch(PDO::FETCH_ASSOC));
		}else{
			return $statement->fetch(PDO::FETCH_ASSOC);
		}
	}

	public function get_products($type_unset = TRUE)
	{
		$statement = $this->db->prepare(self::STATEMENT_GET_PRODUCTS);
		$statement->execute();
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
		if($type_unset){
			return $this->type_unset_products($results);
		}else{
			return $results;
		}
	}

	private function type_unset_products($products)
	{
		$return_array = array();
		foreach ($products as $product) {
			array_push($return_array, $this->type_unset_params($product));
		}
		return $return_array;
	}

	private function type_unset_params($params)
	{
		$return_array = array();
		foreach ($params as $key => $value) {
			if($key === 'price'){
				$return_array[$key] = number_format((float)(0.01 * $value), 2);
			}else{
				$return_array[$key] = $value;
			}
		}
		return $return_array;
	}

	private function type_set_params($params)
	{
		$return_array = array();
		foreach ($params as $key => $value) {
			if($key === 'price'){
				$return_array[$key] = (int)($value * 100);
			}elseif ($key === 'inventory') {
				$return_array[$key] = (int)$value;
			}else{
				$return_array[$key] = $value;
			}
		}
		return $return_array;
	}
}