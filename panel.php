<?php

class Rotor_DebugBarLogger_Panel extends Debug_Bar_Panel {
	/**
	 * Initializes the panel.
	 */
	function init() {
		$this->title('Logger');
	}

	function prerender() {}

	/**
	 * Renders the panel.
	 */
	function render() {
		$messages = rdbg_logger()->get_messages();
		rdbg_logger()->clear_old_messages();

		$start = $GLOBALS['timestart'];

?>
		<div id="debug-bar-logger">
			<h3><?php _e( 'Log Messages', 'rdbg' ) ?></h3>
<?php
		if ( ! empty( $messages ) ):
?>
			<p><?php _e( 'Displaying messages from the past 5 minutes', 'rdbg' ) ?></p>
			<ol>
<?php
			foreach ($messages as $item):
?>
				<li><strong><?php echo $item->level ?></strong>: <?php echo esc_html( $item->message ) ?> (<?php
					printf( __('%s ago', 'rdbg'), human_time_diff( $item->timestamp ) )
				?>)
<?php
				if (!empty($item->context)):
?>
					<pre><?php var_dump($item->context) ?></pre>
<?php
				endif;
?>
				</li>
<?php
			endforeach;
?>
			</ol>
<?php
		else:
?>
			<p><?php _e( 'No messages have been logged in the past 5 minutes', 'rdbg' ) ?></p>
<?php
		endif;
?>
		</div>
<?php
	}
}