<?php

/**
 * Interface for phone, card, email
 */
interface iIdentity {
	/**
	 * Get person_id by field
	 * 
	 * @param string $parameter field
	 */
	public static function getPersonId( $parameter );
}