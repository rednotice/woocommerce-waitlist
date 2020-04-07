<?php
/**
 * @package pixelbaseWaitlist
 * 
 * @since 1.0.0
 */

namespace PixelBase\Base;

/**
 * Contains the plugin paths.
 * 
 * @since 1.0.0
 */
class Paths
{
    /**
     * Plugin.
     * 
     * @since 1.0.0
     */
    public $plugin;

    /**
     * Plugin path.
     * 
     * @since 1.0.0
     */
    public $pluginPath;

    /**
     * Plugin url.
     * 
     * @since 1.0.0
     */
    public $pluginUrl;

    /**
     * Populate the class attributes.
     * 
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->plugin = plugin_basename(dirname(__FILE__, 3) . '/pxb-waitlist.php');
        $this->pluginPath = plugin_dir_path(dirname(__FILE__, 2));
        $this->pluginUrl = plugin_dir_url(dirname(__FILE__, 2));
    }
}