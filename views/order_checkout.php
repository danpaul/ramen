<?php
	if( !View::$template_called )
	{
		View::include_template('__main.php', __FILE__);
		return;
	}
?>

<div class="small-12 medium-8 large-6 small-centered columns" id="small-page-wrap">
		<ul>
			<?php foreach( View::$data['cart_data']['items'] as $product_id => $product_details ) { ?>
				<li>
					<label >Price: $<?php echo $product_details['price'] ?></label>
					<label >Quantity: $<?php echo $product_details['quantity'] ?></label>
					<label >Total price: $<?php echo $product_details['total_price'] ?></label>
				</li>
			<?php } ?>
		</ul>
		<a href="<?php echo $GLOBALS['config']['site_root_url']. '/cart'; ?>"><input class="button small radius" type="submit" value="Edit cart"></a>
</div>

<?php //find prim ?>

<form action="<?php echo $GLOBALS['config']['site_root_url']. '/user/add-address'; ?>" method="post">
	<div class="small-12 columns">
		<h2>Add new address: </h2>
	</div>

	<div class="small-12 medium-8 large-9 columns">
		<label>First name</label><input type="text" name="address[first_name]" required><br>
		<label>Last name</label><input type="text" name="address[last_name]" required><br>
		<label>Address</label><input type="text" name="address[address]" required><br>
		<label>Apartment/suite</label><input type="text" name="address[apartment]"><br>
		<label>City</label><input type="text" name="address[city]" required><br>
		<label>State</label><input type="text" name="address[state]" required><br>
		<label>Zipcode</label><input type="text" name="product[zipcode]"><br>
		<input class="button small radius" type="submit" value="Submit">
	</div>

</form>