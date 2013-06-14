<?php
/**
 * Plugin Name: Debug Bar Logger
 * Description: Provide a common logging interface for plugins, based on the PSR-3 logging standard.
 * Author: Ryan McCue
 * Author URI: http://ryanmccue.info/
 * Version: 1.0
 *
 * @internal This file must be parsable by PHP 5.2.
 */

Rotor_DebugBarLogger::bootstrap();

class Rotor_DebugBarLogger {
	/**
	 * Store our logger
	 * @var Rotor_DebugBarLogger_Logger
	 */
	protected static $logger = null;

	/**
	 * Setup our hooks as needed
	 */
	public static function bootstrap() {
		// Set up our hooks
		register_activation_hook( __FILE__, array( __CLASS__, 'activation' ) );
		add_filter('debug_bar_panels', array(__CLASS__, 'register_panel'));

		// Prepare the logger
		spl_autoload_register( array( __CLASS__, 'autoload' ) );

		require_once __DIR__ . '/logger.php';

		self::$logger = new Rotor_DebugBarLogger_Logger();
		add_filter('shutdown', array(self::$logger, 'save_messages'));
	}

	/**
	 * Activation callback, check our requirements
	 */
	public static function activation() {
		if (version_compare(PHP_VERSION, '5.3', '<')) {
			echo 'Debug Bar Logger requires PHP 5.3 or newer, please upgrade your system.';
			die();
		}
	}

	/**
	 * Register our Debug Bar panel
	 *
	 * @wp-filter debug_bar_panels
	 * @param array $panels Existing panels
	 * @return array
	 */
	public static function register_panel($panels) {
		require_once __DIR__ . '/panel.php';

		$panels[] = new Rotor_DebugBarLogger_Panel();
		return $panels;
	}

	/**
	 * Autoloader for the PSR-3 common interfaces
	 *
	 * @param string $class Class name
	 */
	public static function autoload($class) {
		if (strpos($class, 'Psr\\Log') !== 0)
			return;

		$filename = __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

		if (file_exists($filename))
			require $filename;
	}

	/**
	 * Get the primary logger
	 *
	 * @return Rotor_DebugBarLogger
	 */
	public static function get_logger() {
		return self::$logger;
	}
}

/**
 * Get the primary logger
 *
 * Synonymous with Rotor_DebugBarLogger::get_logger(), but much shorter.
 *
 * @return Rotor_DebugBarLogger
 */
function rdbg_logger() {
	return Rotor_DebugBarLogger::get_logger();
}

/**
 * Log to the Debug Bar Logger
 *
 * Synonymous with Rotor_DebugBarLogger::get_logger()->log(), but much shorter.
 */
function rdbg_log($level, $message, $context = array()) {
	Rotor_DebugBarLogger::get_logger()->log($level, $message, $context);
}