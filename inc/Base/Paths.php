<?php
/**
 * @package wpbitsWaitlist
 */

namespace Inc\Base;

class Paths
{
    public $plugin;

    public $pluginPath;

    public $pluginUrl;

    public function __construct()
    {
        $this->plugin = plugin_basename(dirname(__FILE__, 3) . '/wpbits-waitlist.php');
        $this->pluginPath = plugin_dir_path(dirname(__FILE__, 2));
        $this->pluginUrl = plugin_dir_url(dirname(__FILE__, 2));
    }
}