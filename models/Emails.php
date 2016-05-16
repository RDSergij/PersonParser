<?php 

/**
 * Model for phones
 */
class Emails extends ActiveRecord\Model implements iIdentity, iMetaData
{
	/**
	 * Inherit
	 */
	public static function getPersonId( $email ) {
		$item = self::first( array( 'email' => $email ) );
		if ( ! empty( $item->person_id ) ) {
			return $item->person_id;
		} else {
			return null;
		}
	}

	/**
	 * Add new email
	 * 
	 * @param integer $personId
	 * @param string $emails
	 */
	public static function add( $personId, $emails ) {
		$list = Utilities::parseEmails( $emails );
		if ( ! empty( $list ) ) {
			foreach ( $list as $item ) {
				self::create( array( 'person_id' => $personId, 'email' => $item ) );
			}
		}
	}
}