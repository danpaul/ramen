<?php

class View
{
	public static $template_called = FALSE;
	public static $template_callback = NULL;
	public static $data = array();

	public static function include_template($template, $callback_file)
	{
		self::$template_called = TRUE;
		self::$template_callback = $callback_file;
		require_once($GLOBALS['config']['views']. '/'. $template);
	}

	public static function make_alert($message, $type = NULL)
	{
		$type_class = 'alert-box';
		if( isset($type) ){ $type_class .= ' '. $type; }
		echo '<div data-alert class="'. $type_class. '">';
			echo $message;
			echo '<a href="#" class="close">&times;</a>';
		echo '</div>';
	}

	public static function display_categories($categories, &$product_categories = NULL, $top_level = TRUE)
	{
		echo '<ul>';
			foreach ($categories as $category_data) {
				if( ($top_level && $category_data['parent'] ===  NULL) || !$top_level )
				{
					$checked = '';

					if( isset($product_categories) && in_array($category_data['id'], $product_categories) )
					{
						$checked = 'checked="true"';
					}

					echo '<li>';
						echo '<input type="checkbox" name="categories['. $category_data['id']. ']" id="category_'. $category_data['id']. '"  value="'. $category_data['id']. '" '. $checked. '/>';
						echo '<label for="category_'. $category_data['id']. '">';
							echo $category_data['name'];
						echo '</label>';

						if( !empty($category_data['subcategories']) )
						{
							self::display_categories($category_data['subcategories'], $product_categories, FALSE);
						}
					echo '</li>';
				}
			}
		echo '</ul>';
	}

	public static function display_tags()
	{

		foreach (View::$data['tags'] as $type => $members)
		{
			echo '<h4>Type: '. $type. '</h4>';
			echo '<ul>';
				foreach ($members as $tag)
				{
					$checked = '';

					if( isset(View::$data['product_tags']) && in_array($tag['id'], View::$data['product_tags']) )
					{
						$checked = 'checked="TRUE"';
					}

					echo '<li>';
						echo '<input type="checkbox" '. $checked. 'name="tags['.$tag['id']. ']" id="'. 'tag_'. $tag['id']. '" value="'. $tag['id']. '"/>';
						echo '<label for="tag_'. $tag['id']. '">';
							echo $tag['name'];
						echo '</label>';
					echo '</li>';

				}
			echo '</ul>';

		}

	}

}