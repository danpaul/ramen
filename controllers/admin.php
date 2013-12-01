<?php

require_once($config['controllers']. '/base.php');

class Admin_controller extends Base_controller
{
	const PRODUCT_CATEGORY_TYPE = 'products';
	const ERROR_RECORD_SAVE = 'Sorry, there was an error saving your record.';
	const SUCCESS_RECORD_SAVE = 'Your product has been added/updated.';

	protected $tag_types = array('healthy', 'spice');

	public function __construct()
	{
//check credentials
		require_once($GLOBALS['config']['models']. '/admin.php');
		// $this->admin = new Admin_model();
		parent::__construct();
	}

/*******************************************************************************

					PRODUCTS

*******************************************************************************/

	public function get_add_product()
	{
		require_once($GLOBALS['config']['views']. '/admin_product_add.php');
	}

	public function post_add_products()
	{
		require_once $GLOBALS['config']['models']. '/product.php';
		$product = new Product_model();

		if($product->add_product($_POST)){
			$_SESSION['flash_message'] = array(self::SUCCESS_RECORD_SAVE);
			header('Location: '. $GLOBALS['config']['site_root_url']. '/admin/products');
		}else{
			$_SESSION['flash_message'] = array(self::ERROR_RECORD_SAVE);
			header('Location: '. $GLOBALS['config']['error_page']);
		}
	}

	public function get_edit_product($id)
	{
		require_once $GLOBALS['config']['models']. '/product.php';
		$product = new Product_model();
		$_product = $product->get_product($id);
		require_once($GLOBALS['config']['views']. '/admin_product_edit.php');
	}


	public function post_edit_product($id)
	{
		require_once $GLOBALS['config']['models']. '/product.php';
		$product = new Product_model();
		if($product->edit($id, $_POST)){
			$_SESSION['flash_message'] = array(self::SUCCESS_RECORD_SAVE);
			header('Location: '. $GLOBALS['config']['site_root_url']. '/admin/products');
		}else{
			$_SESSION['flash_message'] = array(self::ERROR_RECORD_SAVE);
			header('Location: '. $GLOBALS['config']['error_page']);
		}
	}

	public function get_products()
	{
		require_once $GLOBALS['config']['models']. '/product.php';
		$product = new Product_model();
		$_products = $product->get_products();
		require_once($GLOBALS['config']['views']. '/admin_product_all.php');
	}

/*******************************************************************************

					TAXONOMIES

*******************************************************************************/

	public function get_taxonomies()
	{
		require_once $GLOBALS['config']['models']. '/taxonomy.php';
		$taxonomy = new Taxonomy_model();
		$_categories = $taxonomy->get_categories(self::PRODUCT_CATEGORY_TYPE);
		$_category_list = $taxonomy->get_category_list(self::PRODUCT_CATEGORY_TYPE);
		$_tags = $taxonomy->get_tags(self::PRODUCT_CATEGORY_TYPE);
		//Add empty tag categories
		foreach ($this->tag_types as $tag_type)
		{
			if( !isset($_tags[$tag_type]) )
			{
				$_tags[$tag_type] = array();
			}
		}
		require_once($GLOBALS['config']['views']. '/admin_taxonomies.php');
	}

/*******************************************************************************

					CATEGORIES

*******************************************************************************/

	public function add_category()
	{
		require_once $GLOBALS['config']['models']. '/taxonomy.php';
		$taxonomy = new Taxonomy_model();

		if( isset($_POST['name']) && $_POST['name'] !== '' )
		{
			$parent_id = NULL;

			if( isset($_POST['parent_id']) && $_POST['parent_id'] !== '' )
			{
				$parent_id = $_POST['parent_id'];
			}
			$taxonomy->add_category($_POST['name'], 
				self::PRODUCT_CATEGORY_TYPE, $parent_id);
			header('Location: '. $GLOBALS['config']['site_root_url']. '/admin/taxonomies');
		}
	}

	public function rename_category()
	{
		if( isset($_POST['confirmed']) && $_POST['confirmed'] === 'TRUE' )
		{
			require_once $GLOBALS['config']['models']. '/taxonomy.php';
			$taxonomy = new Taxonomy_model();
			$taxonomy->rename_category($_POST['id'], $_POST['new_name']);
			header('Location: '. $GLOBALS['config']['site_root_url']. '/admin/taxonomies');
		}else{
			require_once($GLOBALS['config']['views']. '/admin_taxonomies_category_rename.php');
		}
	}

	public function delete_category()
	{
		if( isset($_POST['confirmed']) && $_POST['confirmed'] === 'TRUE' )
		{
			require_once $GLOBALS['config']['models']. '/taxonomy.php';
			$taxonomy = new Taxonomy_model();
			$taxonomy->delete_category(self::PRODUCT_CATEGORY_TYPE, $_POST['id']);
			header('Location: '. $GLOBALS['config']['site_root_url']. '/admin/taxonomies');
		}else{
			require_once($GLOBALS['config']['views']. '/admin_taxonomies_category_delete.php');
		}		
	}

	public function move_category()
	{
		require_once $GLOBALS['config']['models']. '/taxonomy.php';
		$new_parent_id = NULL;
		if( $_POST['new_parent_id'] !== '' )
		{
			$new_parent_id = $_POST['new_parent_id'];
		}
		$taxonomy = new Taxonomy_model;
		if( !$taxonomy->move_category($_POST['id'], $new_parent_id) )
		{
			echo self::ERROR_RECORD_SAVE;
		}else{
			header('Location: '. $GLOBALS['config']['site_root_url']. '/admin/taxonomies');
		}
	}

/*******************************************************************************

					TAGS

*******************************************************************************/

	public function add_tag()
	{
		require_once $GLOBALS['config']['models']. '/taxonomy.php';
		$taxonomy = new Taxonomy_model();
		$taxonomy->add_tag(self::PRODUCT_CATEGORY_TYPE,
						   $_POST['type'], 
						   $_POST['name']);
	}

	public function delete_tag()
	{
		if( isset($_POST['confirmed']) && $_POST['confirmed'] === 'TRUE' )
		{
			require_once $GLOBALS['config']['models']. '/taxonomy.php';
			$taxonomy = new Taxonomy_model();
			$taxonomy->delete_tag(self::PRODUCT_CATEGORY_TYPE, 
								  $_POST['type'],
								  $_POST['name']);

		}else{
			require_once($GLOBALS['config']['views']. '/admin_taxonomies_tag_delete.php');
		}
	}

	public function rename_tag()
	{
		if( isset($_POST['confirmed']) && $_POST['confirmed'] === 'TRUE')
		{
			require_once $GLOBALS['config']['models']. '/taxonomy.php';
			$taxonomy = new Taxonomy_model();
			$taxonomy->rename_tag(self::PRODUCT_CATEGORY_TYPE, 
								  $_POST['type'],
								  $_POST['name'],
								  $_POST['new_name']);

		}else{
			require_once($GLOBALS['config']['views']. '/admin_taxonomies_tag_rename.php');
		}
	}


}