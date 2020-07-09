<?php
/**
 * Self-Hosted Google Fonts
 *
 * @package           Sphere\SGF
 *
 * Plugin Name:       Self-Hosted Google Fonts - Forked by Guido De Gobbis
 * Description:       Automatically self-host your Google Fonts - works with any theme or plugin.
 * Version:           2.0.1
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

// Upload folder and URL
$dir = trailingslashit(WP_CONTENT_DIR) . 'uploads';
$url = trailingslashit(WP_CONTENT_URL) . 'uploads';

if (defined('UPLOADS'))
{
	$dir = trailingslashit(ABSPATH) . UPLOADS;
	$url = trailingslashit(get_option( 'siteurl' )) . UPLOADS;
}

define('SGF_FONTS_UPLOAD_DIR', $dir);
define('SGF_FONTS_UPLOAD_URL', $url);

/**
 * Register activation and deactivation hooks
 */
global $sgf_is_done;
$sgf_is_done = array();

register_activation_hook(__FILE__, function () {
	global $sgf_is_done;

	$sgf_options = unserialize('a:5:{s:7:"enabled";s:1:"1";s:16:"process_enqueues";s:2:"on";s:17:"process_css_files";s:2:"on";s:18:"process_css_inline";s:2:"on";s:17:"protocol_relative";s:2:"on";}');

	if (is_multisite())
	{
		if (empty($sgf_is_done))
		{
			$sgf_is_done = true;
			activate_plugins(plugin_basename(__FILE__), '', true);
		}

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

		return;
	}

	if (get_option('sgf_options'))
	{
		return;
	}

	update_option('sgf_options', $sgf_options);

	return;
});

register_deactivation_hook(__FILE__, function () {
	return;
});

require_once plugin_dir_path(__FILE__) . 'bootstrap.php';
