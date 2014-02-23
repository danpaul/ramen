<?php

require_once($GLOBALS['config']['models']. '/base.php');
require_once($GLOBALS['config']['lib']. '/resize.php');

/******************************************************************************/

class Upload_model extends Base_model
{
	const FULL_IMAGE_DIRECTORY = 'full';
	const STATEMENT_INSERT_PRODUCT_IMAGE = 'INSERT INTO ProductImages(product_id, file_name) VALUES (:product_id, :file_name)';
	const STATEMENT_SELECT_PRODUCT_IMAGE = 'SELECT * FROM ProductImages WHERE id=:id';

	private $image_widths;

	public function __construct()
	{
		parent::__construct();
	}

	public function upload_file()
	{
		if( $this->add_product_image($_FILES['file']['name']) )
		{
			$image_id = $this->db->lastInsertId();
			$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
			$file_path = $GLOBALS['config']['upload_path']. '/full/'. $image_id. '.'. $ext;

			move_uploaded_file($_FILES['file']['tmp_name'], $file_path);
			echo json_encode($image_id);
		}else{
			echo json_encode(FALSE);
		}
	}

	protected function add_product_image($file_name)
	{
		$statement = $this->db->prepare(self::STATEMENT_INSERT_PRODUCT_IMAGE);
		return($statement->execute(array('product_id' => NULL, 'file_name' => $file_name)));
	}

	protected function clear_old_images($file_name)
	{
		foreach(scandir($GLOBALS['config']['upload_path']) as $dir)
		{
			$full_path = $GLOBALS['config']['upload_path']. '/'. $dir;
			if( $dir !== '.' && $dir !== '..' && is_dir($full_path) )
			{
				$file_path = $full_path. '/'. $file_name;
				if( file_exists($file_path) )
				{
					if( !unlink($file_path) )
					{
						throw new Exception("Unable to delete file. You may need to set permissions.", 1);
						return FALSE;						
					}
				}
			}
		}
		return TRUE;
	}

	public function delete_image($image_id)
	{
		$statement = $this->db->prepare(self::STATEMENT_SELECT_PRODUCT_IMAGE);
		if( !$statement->execute(array('id' => $image_id)) )
		{
			throw new Exception("Unable to fetch image in upload.php", 1);			
		}
		$image = $statement->fetch(PDO::FETCH_ASSOC);
		$this->clear_old_images($this->get_internal_image_name($image));
	}

	public function get_sized_image($image_array, $width)
	{
		$image_name = $this->get_internal_image_name($image_array);
		$directory = $GLOBALS['config']['upload_path']. '/'. $width;
		$sized_image = $directory. '/'. $image_name;
		if( !file_exists($directory) )
		{
			mkdir($directory, 0774, TRUE);
		}

		if( !file_exists($sized_image) )
		{
			$full_size_image = $GLOBALS['config']['upload_path']. '/'
				. self::FULL_IMAGE_DIRECTORY. '/'. $image_name;

			if( !file_exists($full_size_image) )
			{
				return FALSE;
			}

			$resizer = new resize($full_size_image);
			$resizer->resizeImage($width, 0, 'landscape');
			$resizer->saveImage($sized_image, 100);
		}
		return $GLOBALS['config']['upload_url']. '/'. $width. '/'. $image_name;
	}

	private function get_internal_image_name($image_array)
	{ 
		return $image_array['id']. '.'. pathinfo($image_array['file_name'], PATHINFO_EXTENSION);
	}
}