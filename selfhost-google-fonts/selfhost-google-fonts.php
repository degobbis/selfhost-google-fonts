<?php
/**
 * Self-Hosted Google Fonts
 *
 * @package           Sphere\SGF
 *
 * Plugin Name:       Self-Hosted Google Fonts - Forked by Guido De Gobbis
 * Description:       Automatically self-host your Google Fonts - works with any theme or plugin.
 * Version:           2.0.6
 * Author:            Guido De Gobbis
 * Author URI:        https://github.com/degobbis/selfhost-google-fonts/releases
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       selfhost-google-fonts
 * Domain Path:       /languages
 * Requires PHP:      5.4
 */

defined('WPINC') || exit;

// Not so easy. Setting this to true on free version will give FATAL errors as some
// files are only in pro version. No cheating.
define('SGF_IS_PRO', false);

if ( !defined('SGF_UPLOAD') )
{
// Upload folder and URL
	$dir = trailingslashit(WP_CONTENT_DIR) . 'uploads';
	$url = trailingslashit(WP_CONTENT_URL) . 'uploads';

	if (defined('UPLOADS'))
	{
		$dir = trailingslashit(ABSPATH) . UPLOADS;
		$url = trailingslashit(get_option('siteurl')) . UPLOADS;
	}

	$sgf_upload = array(
		'path'    => $dir,
		'url'     => $url,
		'subdir'  => '',
		'basedir' => $dir,
		'baseurl' => $url,
		'error'   => false,
	);

	define('SGF_UPLOAD', $sgf_upload);
}

require_once plugin_dir_path(__FILE__) . 'bootstrap.php';

/**
 * Register activation and deactivation hooks
 */
if (!function_exists('sgf_activation_hook'))
{
	function sgf_activation_hook()
	{
		global $wp_filesystem;

		if (empty($wp_filesystem))
		{
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
		}

		$sgf_options = unserialize('a:5:{s:7:"enabled";s:1:"1";s:16:"process_enqueues";s:2:"on";s:17:"process_css_files";s:2:"on";s:18:"process_css_inline";s:2:"on";s:13:"relative_path";s:2:"on";}');

		if (is_multisite())
		{
			activate_plugins(plugin_basename(__FILE__), '', true, true);

			$blogIds = get_sites(array('fields' => 'ids'));

			foreach ($blogIds as $blogId)
			{
				switch_to_blog($blogId);

				if (!get_option('sgf_options'))
				{
					update_option('sgf_options', $sgf_options);
				}

				restore_current_blog();
			}

			switch_to_blog(1);

			return;
		}

		if (get_option('sgf_options'))
		{
			return;
		}

		update_option('sgf_options', $sgf_options);

		return;
	}
}
register_activation_hook(__FILE__, 'sgf_activation_hook');

if (!function_exists('sgf_deactivation_hook'))
{
	function sgf_deactivation_hook() {
		if (is_multisite())
		{
			deactivate_plugins(plugin_basename(__FILE__), true, true);
			switch_to_blog(1);
		}

		return;
	}
}
register_deactivation_hook(__FILE__, 'sgf_deactivation_hook');

/**
 * Register uninstall hooks.
 */
if (!function_exists('sgf_uninstall'))
{
	function sgf_uninstall()
	{
		global $wp_filesystem;

		if (empty($wp_filesystem))
		{
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
		}

		if ($wp_filesystem->is_dir(SGF_UPLOAD['basedir'] . '/sgf-css/'))
		{
			$wp_filesystem->rmdir(SGF_UPLOAD['basedir'] . '/sgf-css/', true);
		}

		if (is_multisite())
		{
			$blogIds = get_sites(array('fields' => 'ids'));

			delete_site_transient('sgf_update');

			foreach ($blogIds as $blogId)
			{
				switch_to_blog($blogId);

				delete_option('sgf_options');
				delete_transient('sgf_processed_cache');
				delete_transient('sgf_preload_cache');

				restore_current_blog();
			}

			return;
		}

		delete_option('sgf_options');
		delete_transient('sgf_processed_cache');
		delete_transient('sgf_preload_cache');
		delete_transient('sgf_update');

		return;
	}
}

register_uninstall_hook(__FILE__, 'sgf_uninstall');
