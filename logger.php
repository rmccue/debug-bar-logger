<?php

use \Psr\Log\AbstractLogger;

class Rotor_DebugBarLogger_Logger extends AbstractLogger {
	/**
	 * Store the logged messages
	 * @var array
	 */
	protected $messages = array();

	protected $max_age = 0;

	public function __construct() {
		$this->messages = get_option('rdbg_messages', array());
		$this->max_age = 2 * MINUTE_IN_SECONDS;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function log($level, $message, array $context = array()) {
		$item = new Rotor_DebugBarLogger_Logger_Item($level, $message, $context);
		$this->messages[] = $item;
	}

	public function get_messages() {
		return $this->messages;
	}

	public function save_messages() {
		delete_option('rdbg_messages');
		add_option('rdbg_messages', $this->messages, null, 'no');
	}

	public function clear_messages() {
		delete_option('rdbg_messages');
		$this->messages = array();
	}

	public function clear_old_messages() {
		foreach ($this->messages as $key => $item) {
			if ( $item->timestamp < (time() - $this->max_age) ) {
				unset($this->messages[$key]);
			}
		}
	}
}

class Rotor_DebugBarLogger_Logger_Item {
	public $level;
	public $message;
	public $context;

	public $timestamp;

	public function __construct($level, $message, $context = array()) {
		$this->level = $level;
		$this->message = $message;
		$this->context = $context;

		$this->timestamp = time();
	}
}