<?php

/**
 * Class-helper
 */
class Utilities {

	/**
	 * Parse integer from string
	 * 
	 * @param string $string
	 * @return integer
	 */
	public static function parseInteger( $string ) {
		return preg_replace( '/[^0-9]/i', '', $string );
	}

	/**
	 * Parse emails from string
	 * 
	 * @param type $string
	 * @return null/array
	 */
	public static function parseEmails( $string ) {
		$email_regex = "/[^0-9< ][A-z0-9_]+([.][A-z0-9_]+)*@[A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}/";
		preg_match_all( $email_regex, $string, $matches );
		$emails = $matches[0];
		if ( ! empty( $emails ) && is_array( $emails ) && 0 < count( $emails ) ) {
			return $emails;
		} else {
			return null;
		}
	}

}