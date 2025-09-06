<?php
/**
 * TAD Dev Helpers
 *
 * Debugging + performance tools for local/dev environments.
 *
 * ✅ Features:
 * 		tad_debug($var, $label = '', $dump = false) → pretty inline dump.
 * 		tad_debug_die($var, $label = '', $dump = false) → dump + stop execution.
 * 		tad_debug_log($var, $label = '') → log to wp-content/debug.log.
 * 		tad_timer($key, $show = false, $label = '') → start/stop performance timers.
 *
 *  All auto-disabled unless WP_DEBUG is true and the current user is an admin.
 *
 */

if (!defined('TAD_DEBUG_MODE')) {
	// Only active when WP_DEBUG is true and current user is admin
	define('TAD_DEBUG_MODE', WP_DEBUG && current_user_can('manage_options'));
}

if (!function_exists('tad_debug')) {
	/**
	 * Nicely dump variables for debugging (inline).
	 *
	 *  ex: tad_debug($args, 'Template Args');
	 *
	 * @param mixed  $var   The variable to inspect
	 * @param string $label Optional label for clarity
	 * @param bool   $dump  If true, use var_dump instead of print_r
	 */
	function tad_debug($var, $label = '', $dump = false) {
		if (!TAD_DEBUG_MODE) return;

		echo '<pre style="background:#111;color:#0f0;padding:1em;border-radius:6px;overflow:auto;font-size:14px;line-height:1.4;">';
		if ($label) {
			echo "<strong style=\"color:#0ff;\">{$label}</strong>\n";
		}
		if ($dump) {
			var_dump($var);
		} else {
			print_r($var);
		}
		echo '</pre>';
	}
}

if (!function_exists('tad_debug_die')) {
	/**
	 * Dump variable(s) and halt execution.
	 *
	 *  ex: tad_debug_die($query, 'Broken WP_Query');
	 *
	 * @param mixed  $var   The variable to inspect
	 * @param string $label Optional label
	 * @param bool   $dump  If true, use var_dump instead of print_r
	 */
	function tad_debug_die($var, $label = '', $dump = false) {
		tad_debug($var, $label, $dump);
		wp_die('<p style="font-family:monospace;background:#111;color:#f55;padding:1em;border-radius:6px;">Execution stopped by tad_debug_die().</p>');
	}
}

if (!function_exists('tad_debug_log')) {
	/**
	 * Write debug info to wp-content/debug.log
	 *
	 *  ex: tad_debug_log($_POST, 'Form POST Data');
	 *
	 * @param mixed  $var
	 * @param string $label
	 */
	function tad_debug_log($var, $label = '') {
		if (!TAD_DEBUG_MODE) return;

		$output = $label ? strtoupper($label) . ': ' : '';
		$output .= print_r($var, true);
		error_log($output);
	}
}

if (!function_exists('tad_timer')) {
	/**
	 * Simple performance timer utility.
	 *
	 * Usage:
	 *   tad_timer('my_query'); // start
	 *   // do stuff...
	 *   tad_timer('my_query', true, 'After query'); // show elapsed
	 *
	 * @param string $key     Unique key for this timer
	 * @param bool   $show    Whether to immediately show elapsed time
	 * @param string $label   Optional label when showing
	 * @return float|null     Elapsed seconds (if $show = true), otherwise null
	 */
	function tad_timer($key, $show = false, $label = '') {
		static $timers = [];

		if (!TAD_DEBUG_MODE) return null;

		$now = microtime(true);

		// If no start exists, set it
		if (!isset($timers[$key])) {
			$timers[$key] = $now;
			return null;
		}

		// Otherwise calculate elapsed
		$elapsed = $now - $timers[$key];

		if ($show) {
			$label_out = $label ? " ({$label})" : '';
			tad_debug(
				sprintf('%s elapsed: %.4f sec', $key . $label_out, $elapsed),
				'Timer'
			);
		}

		return $elapsed;
	}
}
