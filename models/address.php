<?php

require_once($GLOBALS['config']['models']. '/base.php');

class Address_model extends Base_model
{
	const STATEMENT_INSERT_ADDRESS = 'INSERT INTO Addresses(first_name, last_name, address_1, address_2, city, state, zip, user_id) VALUES (:first_name, :last_name, :address_1, :address_2, :city, :state, :zip, :user_id)';

	public function add_address($address_array, $user_id)
	{
		$address_array['user_id'] = $user_id;
		if( !isset($address_array['address_2']) )
		{
			$address_array['address_2'] = '';
		} 
		$statement = $this->db->prepare(self::STATEMENT_INSERT_ADDRESS);
		return($statement->execute($address_array));
	}


}