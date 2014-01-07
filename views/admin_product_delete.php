<?php

if( !View::$template_called )
{
	View::include_template('__admin_main.php', __FILE__);
	return;
}

$warning_message = 'Are you sure you want to delete: '. View::$data['product']['name']. '?';

?>

<div class="small-12 medium-8 large-6 small-centered columns" id="small-page-wrap">

	<?php View::make_alert($warning_message, 'warning'); ?>

	<form action="<?php echo $GLOBALS['config']['site_root_url']. '/admin/delete-product'; ?>" method="post">
		<input type="hidden" name="id" value="<?php echo View::$data['product']['id']; ?>" />
		<input class="button small radius" type="submit" value="yes">
		<p><a href="<?php echo $GLOBALS['config']['site_root_url']. '/admin/products'; ?>">cancel</a></p>
	</form>
	

</div>