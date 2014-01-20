<?php

require_once($GLOBALS['config']['models']. '/base.php');
require_once($GLOBALS['config']['lib']. '/resize.php');

/******************************************************************************/

class Upload_model extends Base_model
{
	private $image_widths;

	public function __construct()
	{
		$this->image_widths = array(150, 350, 'full');
		$this->make_upload_paths();

		parent::__construct();
	}

	public function upload_file()
	{
		$file_path = $GLOBALS['config']['upload_path']
						. '/full/'. $_FILES['file']['name'];
		move_uploaded_file($_FILES['file']['tmp_name'], $file_path);
		$this->resize_images($file_path, $_FILES['file']['name']);

		echo 'success';
	}

	protected function resize_images($file_path, $file_name)
	{
		$resizer = new resize($file_path);
		foreach($this->image_widths as $width)
		{
			if( $width !== 'full' )
			{
				$path = $GLOBALS['config']['upload_path']
							. '/'. $width. '/'. $file_name;
				$resizer->resizeImage($width, 0, 'landscape');
				$resizer->saveImage($path, 100);
			}
		}
	}

	protected function make_upload_paths()
	{
		foreach($this->image_widths as $width)
		{
			$path = $GLOBALS['config']['upload_path']. '/'. (string)$width;
			if( !file_exists($path) )
			{
				mkdir($path, 0774, TRUE);
			}
		}
	}
}