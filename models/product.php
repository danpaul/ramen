<?php

require_once($GLOBALS['config']['models']. '/base.php');

class Product_model extends Base_model
{
	const STATEMENT_GET_PRODUCT = 'SELECT * FROM Products WHERE id=:id LIMIT 1';
	const STATEMENT_GET_PRODUCTS = 'SELECT * FROM Products';
	const STATEMENT_SELECT_WHERE_PART = 'SELECT * FROM ProductCategories JOIN Products ON ProductCategories.product_id = Products.id WHERE';

	const STATEMENT_DELETE_PRODUCT = 'DELETE FROM Products WHERE id=:id';
	const STATEMENT_DELETE_PRODUCT_CATEGORIES = 'DELETE FROM ProductCategories WHERE product_id=:product_id';
	const STATEMENT_DELETE_PRODUCT_TAGS = 'DELETE FROM ProductTags WHERE product_id=:product_id';

	const STATEMENT_INSERT_PRODUCT = 'INSERT INTO Products(name, description, price, inventory) VALUES (:name, :description, :price, :inventory)';
	const STATEMENT_UPDATE_PRODUCT = 'UPDATE Products SET name=:name, description=:description, price=:price, inventory=:inventory WHERE id=:id';

	const STATEMENT_INSERT_PRODUCT_TAG = 'INSERT INTO ProductTags(product_id, tag_id) VALUES (:product_id, :tag_id)';
	const STATEMENT_INSERT_PRODUCT_CATEGORY = 'INSERT INTO ProductCategories(product_id, category_id) VALUES (:product_id, :category_id)';

	const STATEMENT_SELECT_PRODUCT_TAGS = 'SELECT * FROM ProductTags WHERE product_id=:product_id';
	const STATEMENT_SELECT_PRODUCT_CATEGORIES = 'SELECT * FROM ProductCategories WHERE product_id=:product_id';
	const STATEMENT_SELECT_PRODUCT_CATEGORY_IDS = 'SELECT category_id FROM ProductCategories WHERE product_id=:product_id';
	const STATEMENT_SELECT_PRODUCT_TAG_IDS = 'SELECT tag_id FROM ProductTags WHERE product_id=:product_id';

	const PRODUCT_CATEGORY_TYPE = 'products';

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
			$statement = $this->db->prepare(self::STATEMENT_INSERT_PRODUCT_CATEGORY);
			foreach ($product_details['categories'] as $category_id)
			{
				if( !$statement->execute(array('product_id' => $product_id, 'category_id' => $category_id)) )
				{
					return FAlSE;
				}
			}
		}
		return TRUE;
	}

	public function edit($id, $product_params, $categories, $tags)
	{
		$params = $this->type_set_params($product_params);
		$params['id'] = $id;
		$statement = $this->db->prepare(self::STATEMENT_UPDATE_PRODUCT);
		if( !$statement->execute($params) ){ return FALSE; }

		return $this->update_taxonomies($id, $categories, $tags);
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

	public function get_products_in_category($category_name)
	{
		require_once($GLOBALS['config']['models']. '/taxonomy.php');
		$taxonomy = new Taxonomy_model;
		$categories = $taxonomy->get_categories_by_name(self::PRODUCT_CATEGORY_TYPE, $category_name);
		if( !$categories ){ return array(); }
		return $this->get_products_by_category_ids($categories);
	}




	public function get_products_in_categories($category_names)
	{
		require_once($GLOBALS['config']['models']. '/taxonomy.php');
		$taxonomy = new Taxonomy_model;		
		$categories = $taxonomy->get_categories_by_names(self::PRODUCT_CATEGORY_TYPE, $category_names);
		if( !$categories ){ return array(); }
		return $this->get_products_by_category_ids($categories);
	}

	/*
		Takes an array of category ids and returns an array of product records

	*/
	private function get_products_by_category_ids($category_ids)
	{

		$statement = self::STATEMENT_SELECT_WHERE_PART. $this->or_statement_generate($category_ids, 'ProductCategories.category_id');
		$statement = $this->db->prepare($statement);
		if( !$statement->execute($category_ids) ){ return FALSE; }
		return $statement->fetchAll(PDO::FETCH_ASSOC);

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

	public function get_product_tags($product_id)
	{
		$tags = array();

		$statement = $this->db->prepare(self::STATEMENT_SELECT_PRODUCT_TAGS);
		$statement->execute(array('product_id' => $product_id));
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);

		foreach ($results as $product_tag)
		{
			array_push($tags, $product_tag['tag_id']);
		}

		return $tags;
	}

	public function get_product_categories($product_id)
	{
		$categories = array();

		$statement = $this->db->prepare(self::STATEMENT_SELECT_PRODUCT_CATEGORIES);
		$statement->execute(array('product_id' => $product_id));
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);

		foreach ($results as $product_category)
		{
			array_push($categories, $product_category['category_id']);
		}

		return $categories;
	}
	

	public function delete($id)
	{
		$statement = $this->db->prepare(self::STATEMENT_DELETE_PRODUCT);
		return $statement->execute(array('id' => $id));
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

	private function update_taxonomies($id, $categories, $tags)
	{

		$statement = $this->db->prepare(self::STATEMENT_SELECT_PRODUCT_CATEGORY_IDS);
		$statement->execute(array('product_id' => $id));
		$current_categories = $statement->fetchAll(PDO::FETCH_COLUMN);

		if( $current_categories !== array_values($categories) )
		{
			$statement = $this->db->prepare(self::STATEMENT_DELETE_PRODUCT_CATEGORIES);
			$statement->execute(array('product_id' => $id));

			$statement = $this->db->prepare(self::STATEMENT_INSERT_PRODUCT_CATEGORY);
			foreach ($categories as $category)
			{
				if( !$statement->execute(array('product_id' => $id, 'category_id' => $category)) )
				{
					return FALSE;
				}
			}
		}

		$statement = $this->db->prepare(self::STATEMENT_SELECT_PRODUCT_TAG_IDS);
		$statement->execute(array('product_id' => $id));
		$current_tags = $statement->fetchAll(PDO::FETCH_COLUMN);

		if( $current_tags !== array_values($tags) )
		{
			$statement = $this->db->prepare(self::STATEMENT_DELETE_PRODUCT_TAGS);
			$statement->execute(array('product_id' => $id));

			$statement = $this->db->prepare(self::STATEMENT_INSERT_PRODUCT_TAG);
			foreach ($tags as $tag)
			{
				if( !$statement->execute(array('product_id' => $id, 'tag_id' => $tag)) )
				{
					return FALSE;
				}
			}
		}

		return TRUE;

	}

}