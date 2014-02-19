<?php

require_once($GLOBALS['config']['models']. '/base.php');
require_once($GLOBALS['config']['lib']. '/resize.php');

//new upload of same file name should clear all other sized images

/******************************************************************************/

class Upload_model extends Base_model
{
	const FULL_IMAGE_DIRECTORY = 'full';

	private $image_widths;

	public function __construct()
	{
		parent::__construct();
	}

	public function upload_file()
	{
		$file_path = $GLOBALS['config']['upload_path']. '/full/'. $_FILES['file']['name'];
		move_uploaded_file($_FILES['file']['tmp_name'], $file_path);
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

	public function get_sized_image($image_name, $width)
	{
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
}