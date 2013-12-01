<?php

require_once($GLOBALS['config']['views']. '/_head.php');


function display_categories($categories, $category_list, $top_level = TRUE){

	echo '<ul>';
		foreach ($categories as $category_data) {
			if( ($top_level && $category_data['parent'] ===  NULL) || !$top_level )
			{
				echo '<li>';
					echo $category_data['name'];
				?>

				<form action="<?php echo $GLOBALS['config']['site_root_url']. '/admin/taxonomy-rename-category'; ?>" method="post" class="inline">
					<input type="hidden" name="id" value="<?php echo $category_data['id'] ?>" />
					<input type="hidden" name="name" value="<?php echo $category_data['name'] ?>" />
					<input type="submit" value="rename" />
				</form>

				<form action="<?php echo $GLOBALS['config']['site_root_url']. '/admin/taxonomy-delete-category'; ?>" method="post" class="inline">
					<input type="hidden" name="id" value="<?php echo $category_data['id'] ?>" />
					<input type="hidden" name="name" value="<?php echo $category_data['name'] ?>" />
					<input type="submit" value="delete" />
				</form>

				<form class ="inline" action="<?php echo $GLOBALS['config']['site_root_url']. '/admin/taxonomy-move-category'; ?>" method="post">
					Move to parent:
						<select name="new_parent_id">
							<option value=""></option>
							<?php
								foreach ($category_list as $category) {
									if( $category_data['id'] !== $category['id'] )
									{
										echo '<option value="'. $category['id']. '">'. $category['name']. '</option>';
									}
								}
							?>
						</select>
						<input type="hidden" name="id" value="<?php echo $category_data['id'] ?>" />
					<input type="submit" value="move" />
				</form>

				<?php

					if( !empty($category_data['subcategories']) )
					{
						display_categories($category_data['subcategories'], $category_list, FALSE);
					}
				echo '</li>';
			}
		}
	echo '</ul>';
}

?>

<h2>categories:</h2>

<?php display_categories($_categories, $_category_list); ?>

<h2>add category</h2>
<form action="<?php echo $GLOBALS['config']['site_root_url']. '/admin/taxonomy-add-category'; ?>" method="post">
	Name: <input type="text" name="name">
	Parent:
		<select name="parent_id">
			<option value=""></option>
			<?php
				foreach ($_category_list as $category) {
					echo '<option value="'. $category['id']. '">'. $category['name']. '</option>';					
				}
			?>
		</select>
	<input type="submit" value="add" />
</form>

<h2>tags:</h2>

<?php foreach ($_tags as $type => $members) { ?>

	<h3>Type: <?php echo $type ?></h3>
	<ul>
		<?php foreach ($members as $tag) { ?>
			<li>
				<?php echo $tag['name']; ?>
				<form action="<?php echo $GLOBALS['config']['site_root_url']. '/admin/taxonomy-rename-tag'; ?>" method="post" class="inline">
					<input type="hidden" name="type" value="<?php echo $type ?>" />
					<input type="hidden" name="name" value="<?php echo $tag['name'] ?>" />
					<input type="submit" value="rename" />
				</form>
				<form action="<?php echo $GLOBALS['config']['site_root_url']. '/admin/taxonomy-delete-tag'; ?>" method="post" class="inline">
					<input type="hidden" name="type" value="<?php echo $type ?>" />
					<input type="hidden" name="name" value="<?php echo $tag['name'] ?>" />
					<input type="submit" value="delete" />
				</form>
			</li>
		<?php } ?>

	</ul>

	<form action="<?php echo $GLOBALS['config']['site_root_url']. '/admin/taxonomy-add-tag'; ?>" method="post">
		<input type="hidden" name="type" value="<?php echo $type ?>" />
		Name: <input type="text" name="name">
		<input type="submit" value="add" />
	</form>

<?php } ?>