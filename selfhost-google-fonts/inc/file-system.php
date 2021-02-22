<?php

namespace Sphere\SGF;

/**
 * Filesystem that mainly wraps the WP_File_System
 *
 * @author  asadkn
 * @since   1.0.0
 * @package Sphere\SGF
 */
class FileSystem {

	/**
	 * @var WP_Filesystem_Base
	 */
	public $filesystem;

	/**
	 * Setup file system
	 */
	public function __construct()
	{
		global $wp_filesystem;

		if (empty($wp_filesystem)) {

			require_once wp_normalize_path(ABSPATH . '/wp-admin/includes/file.php');

			$creds = request_filesystem_credentials('');

			if (!$creds) {
				$creds = arrays();
			}

			$filesystem = WP_Filesystem($creds);

			if (!$filesystem) {

				WP_Filesystem(false, SGF_UPLOAD['basedir'], true);
			}
		}

		$this->filesystem = $wp_filesystem;
	}

	/**
	 * Proxies to WP_Filesystem_Base
	 */
	public function __call($name, $arguments)
	{
		return call_user_func_array(array($this->filesystem, $name), $arguments);
	}

}
