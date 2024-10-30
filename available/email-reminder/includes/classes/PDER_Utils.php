<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class PDER_Utils
{
	/**
	 * @param string $filename path to file
	 * @param mixed  $data     data to pass on to the view file
	 * @param string $parent   parent directory that $filename can be relative to.
	 *                         pass FALSE if you are passing an absolute path to $filename.
	 *
	 * @return string contents of the file
	 * @since    7.3
	 * @verified 2018.02.21
	 */
	public static function get_view(
		$filename,
		/** @noinspection PhpUnusedParameterInspection */
		$data = null,
		$parent = PDER_VIEWS
	) {
		if ( empty( $filename ) ) {
			return '';
		}


		if ( empty( $parent ) ) {
			//we will assume that $filename is absolute path
			$file = $filename;


		} else {
			//$filename is relative to $parent
			$file = trailingslashit( $parent ) . $filename;
		}


		//check if file exists
		if ( ! file_exists( $file ) ) {
			return '';
		}


		ob_start();
		include $file;
		$contents = ob_get_clean();


		return $contents;
	}
}
