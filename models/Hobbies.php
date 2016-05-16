<?php 

/**
 * Model for phones
 */
class Hobbies extends ActiveRecord\Model implements iMetaData
{
	/**
	 * Check is set this hobby for the person
	 * 
	 * @param integer $personId
	 * @param string $hobby
	 * @return boolean/integer
	 */
	public static function checkPersonHobby(  $personId, $hobby ) {
		$item = self::first( array( 'person_id' => $personId, 'hobby' => $hobby ) );
		if ( ! empty( $item->id ) ) {
			return $item->id;
		} else {
			return false;
		}
	}

	/**
	 * Add new hobby
	 * 
	 * @param integer $personId
	 * @param string $hobby
	 */
	public static function add( $personId, $hobby ) {
		if ( self::checkPersonHobby( $personId, $hobby ) ) {
			return;
		}
		self::create( array( 'person_id' => $personId, 'hobby' => $hobby ) );
	}
}