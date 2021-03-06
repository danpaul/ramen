<?php

require_once($GLOBALS['config']['models']. '/base.php');

class Product_model extends Base_model
{
	const STATEMENT_GET_PRODUCT = 'SELECT * FROM Products WHERE id=:id LIMIT 1';
	const STATEMENT_GET_PRODUCTS = 'SELECT * FROM Products';
	const STATEMENT_GET_PRODUCT_IMAGE_NAMES = 'SELECT * FROM ProductImages WHERE product_id=?';
	const STATEMENT_SELECT_WHERE_PART = 'SELECT * FROM ProductCategories JOIN Products ON ProductCategories.product_id = Products.id WHERE';

	const STATEMENT_DELETE_PRODUCT = 'DELETE FROM Products WHERE id=:id';
	const STATEMENT_DELETE_PRODUCT_CATEGORIES = 'DELETE FROM ProductCategories WHERE product_id=:product_id';
	const STATEMENT_DELETE_PRODUCT_TAGS = 'DELETE FROM ProductTags WHERE product_id=:product_id';
	const STATEMENT_DELETE_PRODUCT_IMAGE = 'DELETE FROM ProductImages WHERE id=:id';

	const STATEMENT_INSERT_PRODUCT = 'INSERT INTO Products(name, description, price, inventory) VALUES (:name, :description, :price, :inventory)';
	const STATEMENT_UPDATE_PRODUCT = 'UPDATE Products SET name=:name, description=:description, price=:price, inventory=:inventory WHERE id=:id';
	const STATEMENT_UPDATE_PRODUCT_IMAGE = 'UPDATE ProductImages SET product_id=:product_id WHERE id=:id';
	const STATEMENT_UPDATE_PRODUCT_FEATURED_IMAGE = 'UPDATE ProductImages SET featured=:featured WHERE product_id=:product_id';
	const STATEMENT_UPDATE_FEATURED_IMAGE = 'UPDATE ProductImages SET featured=:featured WHERE id=:id';

	const STATEMENT_INSERT_PRODUCT_TAG = 'INSERT INTO ProductTags(product_id, tag_id) VALUES (:product_id, :tag_id)';
	const STATEMENT_INSERT_PRODUCT_CATEGORY = 'INSERT INTO ProductCategories(product_id, category_id) VALUES (:product_id, :category_id)';
	
	const STATEMENT_SELECT_PRODUCT_TAGS = 'SELECT * FROM ProductTags WHERE product_id=:product_id';
	const STATEMENT_SELECT_PRODUCT_CATEGORIES = 'SELECT * FROM ProductCategories WHERE product_id=:product_id';
	const STATEMENT_SELECT_PRODUCT_CATEGORY_IDS = 'SELECT category_id FROM ProductCategories WHERE product_id=:product_id';
	const STATEMENT_SELECT_PRODUCT_TAG_IDS = 'SELECT tag_id FROM ProductTags WHERE product_id=:product_id';
	const STATEMENT_SELECT_PRODUCT_IDS_BY_TAG_ID = 'SELECT product_id FROM ProductTags WHERE tag_id=:tag_id';
	const STATEMENT_SELECT_PRODUCTS_BY_IDS = 'SELECT * FROM Products WHERE id IN (???)';
	const STATEMENT_SELECT_JOIN_TAG_PART = 'SELECT * FROM ProductTags JOIN Products ON ProductTags.product_id = Products.id WHERE ';
	const STATEMENT_SELECT_JOIN_TAGS = 'SELECT * FROM ProductTags JOIN Products ON ProductTags.product_id = Products.id WHERE tag_id IN (???)';
	
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

		if( isset($product_details['uploads']) )
		{
			$this->add_product_images($product_details['uploads'], $product_id);
		}

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

	public function add_product_images(&$upload_ids, $product_id)
	{
		$statement = $this->db->prepare(self::STATEMENT_UPDATE_PRODUCT_IMAGE);
		foreach($upload_ids as $id)
		{
			$statement->execute(array('product_id' => $product_id, 'id' => $id));
		}
	}

	public function edit($id, $product_params, $categories, $tags)
	{
		$params = $this->type_set_params($product_params);
		$params['id'] = $id;
		$statement = $this->db->prepare(self::STATEMENT_UPDATE_PRODUCT);
		if( !$statement->execute($params) ){ return FALSE; }
		return $this->update_taxonomies($id, $categories, $tags);
	}

	public function get_product($id)
	{
		$statement = $this->db->prepare(self::STATEMENT_GET_PRODUCT);
		$statement->execute(array('id' => $id));
		return $statement->fetch(PDO::FETCH_ASSOC);
	}

	public function get_products_by_ids($id_array)
	{
		if( empty($id_array) ){ throw new Exception("Empty array passed to get_products_by_ids", 1);
		}
		$statement = str_replace('???',
								 $this->generate_question_marks(count($id_array)),
								 self::STATEMENT_SELECT_PRODUCTS_BY_IDS);
		$statement = $this->db->prepare($statement);
		$statement->execute($id_array);
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function get_product_images($id)
	{
		$statement = $this->db->prepare(self::STATEMENT_GET_PRODUCT_IMAGE_NAMES);
		$statement->execute(array($id));
		return $statement->fetchAll(PDO::FETCH_ASSOC);
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

	public function add_images_to_products(&$products_array)
	{
		foreach ($products_array as &$product)
		{
			$product['images'] = $this->get_product_images($product['id']);
			foreach ($product['images'] as $image)
			{
				if( $image['featured'] === '1' )
				{
					$product['featured_image'] = $image;
				}
			}
		}
	}

	public function get_products_in_type_by_tag($type, $tag, $with_images = TRUE)
	{
		require_once($GLOBALS['config']['models']. '/taxonomy.php');
		$taxonomy = new Taxonomy_model();
		$tag = $taxonomy->get_tag_by_name($type, $tag);

		$statement = $this->db->prepare(self::STATEMENT_SELECT_JOIN_TAG_PART. 'tag_id=:tag_id');
		if( !$statement->execute(array('tag_id' => $tag['id'])) ){ throw new Exception("Error getting products by tag", 1);
		 }
		$products =  $statement->fetchAll(PDO::FETCH_ASSOC);
		if( $with_images )
		{
			$this->add_images_to_products($products);
		}
		return $products;
	}

	public function get_products_by_types($types)
	{
		require_once($GLOBALS['config']['models']. '/taxonomy.php');
		$taxonomy = new Taxonomy_model;
		$tag_ids = $taxonomy->get_tags_by_names($types);
		if( !$tag_ids ){ return array(); }

		$statement = str_replace('???',
								 $this->generate_question_marks(count($tag_ids)),
								 self::STATEMENT_SELECT_JOIN_TAGS);
		$statement = $this->db->prepare($statement);
		$statement->execute($tag_ids);
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
		return $results;

	}

	public function get_products()
	{
		$statement = $this->db->prepare(self::STATEMENT_GET_PRODUCTS);
		$statement->execute();
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
		return $results;
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

	public function set_featured_image($product_id, $featured_image_id)
	{		

		$statement = $this->db->prepare(self::STATEMENT_UPDATE_PRODUCT_FEATURED_IMAGE);
		if( !$statement->execute(array('featured' => 0, 'product_id' => $product_id)) )
		{
			throw new Exception("Error updating featured image.");			
		};
		$statement = $this->db->prepare(self::STATEMENT_UPDATE_FEATURED_IMAGE);
		if( !$statement->execute(array('featured' => 1, 'id' => $featured_image_id)) )		
		{
			throw new Exception("Error updating featured image.", 1);			
		}
	}

	public function delete_images($product_id, $image_ids)
	{
		require_once($GLOBALS['config']['models']. '/upload.php');
		$upload = new Upload_model();
		foreach ($image_ids as $image_id)
		{
			$upload->delete_image($image_id);
			$statement = $this->db->prepare(self::STATEMENT_DELETE_PRODUCT_IMAGE);
			if( !$statement->execute(array('id' => $image_id)) )
			{
				throw new Exception("Unable to delete image.", 1);
			}
		}
	}

	private function type_set_params($params)
	{
		$return_array = array();
		foreach ($params as $key => $value) {
			if($key === 'price'){
				$return_array[$key] = (float)($value);
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