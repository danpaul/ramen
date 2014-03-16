<?php

require_once($GLOBALS['config']['models']. '/base.php');

class Cart_model extends Base_model
{
	const STATEMENT_INSERT_ITEM = 'INSERT INTO CartItems(status, quantity, product_id, user_id) VALUES (:status, :quantity, :product_id, :user_id)';
	const STATEMENT_SELECT_CART_ITEM = 'SELECT * FROM CartItems WHERE product_id=:product_id AND user_id=:user_id';
	const STATEMENT_SELECT_CART_ITEMS = 'SELECT * FROM CartItems WHERE user_id=:user_id AND status=:status';
	const STATEMENT_UPDATE_ITEM = 'UPDATE CartItems SET status=:status, quantity=:quantity  WHERE product_id=:product_id AND user_id=:user_id';
	const STATEMENT_UPDATE_ITEM_STATUS = 'UPDATE CartItems SET status=:status WHERE product_id=:product_id AND user_id=:user_id';

	const STATUS_DELETED = 0;
	const STATUS_ACTIVE = 1;

	public function get_cart_data()
	{
		
	}

	public function store_session_cart_items()
	{
		if( !empty($_SESSION['cart']) )
		{
			foreach( $_SESSION['cart'] as $product_id => $quantity)
			{
				$this->upsert($product_id, $quantity);
			}
		}
		$this->sync_cart();
	}

	protected function sync_cart()
	{
		$statement = $this->db->prepare(self::STATEMENT_SELECT_CART_ITEMS);
		$statement->execute(array('user_id' => $_SESSION['user']['id'], 'status' => self::STATUS_ACTIVE));
		$cart_items = $statement->fetchAll(PDO::FETCH_ASSOC);
		$_SESSION['cart'] = array();
		foreach( $cart_items as $item )
		{
			$_SESSION['cart'][$item['product_id']] = $item['quantity'];
		}
	}

	public function upsert($product_id, $quantity)
	{
		if( $this->user_is_logged_in() )
		{
			$user_id = $_SESSION['user']['id'];
			$item = $this->get_item($product_id, $user_id);
			if( empty($item) )
			{
				$this->add_item($product_id, $user_id, $quantity, self::STATUS_ACTIVE);
			}else{
				$this->update_item($product_id, $user_id, $quantity, self::STATUS_ACTIVE);
			}
		}else{
			$this->update_session_cart($product_id, $_POST['quantity']);
		}
	}

	protected function add_item($product_id, $user_id, $quantity, $status)
	{
		$statement = $this->db->prepare(self::STATEMENT_INSERT_ITEM);
		$statement->execute(array(
			'status' => $status,
			'quantity' => $quantity,
			'product_id' => $product_id,
			'user_id' => $user_id ));
		$this->update_session_cart($product_id, $quantity);
	}

	protected function update_item($product_id, $user_id, $quantity, $status)
	{
		$statement = $this->db->prepare(self::STATEMENT_UPDATE_ITEM);
		$statement->execute(array(
			'status' => $status,
			'quantity' => $quantity,
			'product_id' => $product_id,
			'user_id' => $user_id ));
		$this->update_session_cart($product_id, $quantity);
	}

	protected function update_session_cart($product_id, $quantity)
	{
		if( !isset($_SESSION['cart']) ){ $_SESSION['cart'] = array(); }
		$_SESSION['cart'][$product_id] = (int)$quantity;
	}

	public function get_item($product_id, $user_id)
	{
		$statement = $this->db->prepare(self::STATEMENT_SELECT_CART_ITEM);
		$params = array('product_id' => $product_id, 'user_id' => $user_id);
		$statement->execute($params);
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function delete_item($product_id, $user_id = NULL)
	{
		unset($_SESSION['cart'][$product_id]);
		if( $user_id )
		{	
			$statement = $this->db->prepare(self::STATEMENT_UPDATE_ITEM_STATUS);
			$params = array('product_id' => $product_id, 'user_id' => $user_id, 'status' => self::STATUS_DELETED);
			return $statement->execute($params);
		} else { return TRUE; }

	}
}