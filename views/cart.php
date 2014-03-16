<?php
	if( !View::$template_called )
	{
		View::include_template('__main.php', __FILE__);
		return;
	}
?>

<div class="small-12 medium-8 large-6 small-centered columns" id="small-page-wrap">
	<?php if( View::$data['has_cart'] ) { ?>
		<ul>
			<?php foreach( View::$data['cart_data']['items'] as $product_id => $product_details ) { ?>
				<li>
					<form action="<?php echo $GLOBALS['config']['site_root_url']. '/cart/update-item-quantity/'. $product_id  ?>" method="post">
						<label >Price: $<?php echo $product_details['price'] ?></label>
						<label >Total price: $<?php echo $product_details['total_price'] ?></label>
						<label for="price">Quantity: </label>
						<input type="text" name="quantity" value="<?php echo $product_details['quantity'] ?>">
						<input class="button small radius" type="submit" value="Update quantity">
					</form>
					<form action="<?php echo $GLOBALS['config']['site_root_url']. '/cart/delete-item/'. $product_id  ?>" method="post">
						<input class="button small radius" type="submit" value="Remove item">
					</form>
				</li>
			<?php } ?>
		</ul>
		<a href="<?php echo $GLOBALS['config']['site_root_url']. '/order/checkout'; ?>"><input class="button small radius" type="submit" value="Checkout"></a>
	<?php } else { View::make_alert('There are no items in your cart.'); } ?>
</div>