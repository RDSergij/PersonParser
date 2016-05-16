<?php 

/**
 * Model for phones
 */
class Phones extends ActiveRecord\Model implements iIdentity, iMetaData
{

	/**
	 * Inherit
	 */
	public static function getPersonId( $phone ) {
		$phone = Utilities::parseInteger( $phone );
		$item = self::first( array( 'phone' => $phone ) );
		if ( ! empty( $item->person_id ) ) {
			return $item->person_id;
		} else {
			return null;
		}
	}

	/**
	 * Add new phone
	 * 
	 * @param integer $personId
	 * @param string $phone
	 */
	public static function add( $personId, $phone ) {
		$phone = Utilities::parseInteger( $phone );
		self::create( array( 'person_id' => $personId, 'phone' => $phone ) );
	}
}