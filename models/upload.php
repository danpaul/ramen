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

// echo($this->get_sized_image($_FILES['file']['name'], 75));
// die();

		$file_path = $GLOBALS['config']['upload_path']. '/full/'. $_FILES['file']['name'];
		move_uploaded_file($_FILES['file']['tmp_name'], $file_path);
		// $this->resize_images($file_path, $_FILES['file']['name']);
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

	// protected function resize_images($file_path, $file_name)
	// {
	// 	$resizer = new resize($file_path);
	// 	foreach($GLOBALS['config']['image_widths'] as $width)
	// 	{
	// 		if( $width !== 'full' )
	// 		{
	// 			$path = $GLOBALS['config']['upload_path']
	// 						. '/'. $width. '/'. $file_name;
	// 			$resizer->resizeImage($width, 0, 'landscape');
	// 			$resizer->saveImage($path, 100);
	// 		}
	// 	}
	// }

	// protected function make_upload_paths()
	// {
	// 	foreach($GLOBALS['config']['image_widths'] as $width)
	// 	{
	// 		$path = $GLOBALS['config']['upload_path']. '/'. $width;
	// 		if( !file_exists($path) )
	// 		{
	// 			mkdir($path, 0774, TRUE);
	// 		}
	// 	}
	// }

	// protected function make_upload_path($folder)
	// {
	// 	$path = $GLOBALS['config']['upload_path']. '/'. (string)$width;

	// }

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