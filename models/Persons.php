<?php 

/*
 * Model of persons
 */
class Persons extends ActiveRecord\Model
{
	/**
	 * Add new person
	 * 
	 * @return integer
	 */
	public static function addPerson() {
		$person = Persons::create( array( 'id' => null ) );
		return $person->id;
	}

	/**
	 * Update Person data
	 * 
	 * @param integer $personId
	 * @param string $key
	 * @param string $value
	 */
	public static function addPersonData( $personId, $key, $value ) {
		if ( 'birthdate' == $key ) {
			$value =self::regenDate( $value );
		}
		if ( 'sex' == $key ) {
			$value = self::getSexIdByString( $value );
		}
		$person = Persons::find( $personId );
		$person->$key = $value;
		$person->save();
	}

	/**
	 * Get date by format
	 * 
	 * @param string $date
	 * @return string
	 */
	public static function regenDate( $date ) {
		$time = strtotime( $date );
		return date( 'Y-m-d', $time );
	}

	/**
	 * Get sex Id by sex string
	 * 
	 * @param string $string
	 * @return int
	 */
	public static function getSexIdByString( $string ) { // lol
		$string = trim( strtolower( $string ) );
		switch( $string ) {
			case 'male': return 1;
			case 'female': return 2;
			default: return 0;
		}
	}
}