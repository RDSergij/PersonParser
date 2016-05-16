<?php 

/**
 * Model for phones
 */
class Cards extends ActiveRecord\Model implements iIdentity, iMetaData
{
	/**
	 * Inherit
	 */
	public static function getPersonId( $card ) {
		$card = Utilities::parseInteger( $card );
		$item = self::first( array( 'card' => $card ) );
		if ( ! empty( $item->person_id ) ) {
			return $item->person_id;
		} else {
			return null;
		}
	}

	/**
	 * Add new card
	 * 
	 * @param integer $personId
	 * @param string $card
	 */
	public static function add( $personId, $card ) {
		$card = Utilities::parseInteger( $card );
		self::create( array( 'person_id' => $personId, 'card' => $card ) );
	}
}