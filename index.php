<?php
/**
 * App for parser Person data from csv file
 * 
 * @author Osadchyi Serhii
 * @package PersonParser
 */

// Include config file
require_once dirname( __FILE__ ) . '/config.php';

/*
 * Main Class
 */
class PersonsParser {

	/**
	 * Path to csv file
	 *
	 * @var string
	 */
	protected $filePath;

	/**
	 * File stream
	 *
	 * @var source
	 */
	protected $fileHandle;

	/**
	 * List of headers
	 *
	 * @var array 
	 */
	protected $headers = [];

	/**
	 * Current header
	 *
	 * @var array
	 */
	protected $currentHeader;

	/**
	 * Relations cols => models etc
	 *
	 * @var array
	 */
	protected $relations = [];

	/*
	 * Constructor
	 */
	public function __construct( $file, $headers, $relations ) {
		if ( ! empty( $file ) ) {
			$this->setFile( $file );
		}

		if ( ! empty( $headers ) ) {
			$this->setHeaders( $headers );
		}

		if ( ! empty( $relations ) ) {
			$this->setRelations( $relations );
		}
	}

	/**
	 * Set full path to csv file
	 * 
	 * @param string $fileName prefix name
	 * @return \PersonsParser
	 */
	public function setFile( $fileName ) {
		$this->filePath = __DIR__ . '/csv/' . preg_replace( '/[^A-Za-z0-9_\-]/', '_', $fileName ) . '.csv';
		return $this;
	}

	/**
	 * Set all headers for parse data
	 * 
	 * @param array $headers headers list
	 * @return \PersonsParser
	 */
	public function setHeaders( $headers ) {
		$this->headers = $headers;
		return $this;
	}

	/**
	 * Set relations
	 * 
	 * @param array $relations cols => models
	 * @return \PersonsParser
	 */
	public function setRelations( $relations ) {
		$this->relations = $relations;
		return $this;
	}

	/**
	 * Start parse file
	 */
	public function parseFile() {
		if ( $this->fileHandle = fopen( $this->filePath, 'r') ) {
			while ( false !== ( $data = fgetcsv( $this->fileHandle, 1000, "," ) ) ) {
				$previousHeader = $this->currentHeader;
				$this->checkHeaders( $data );
				if ( ! empty( $this->currentHeader ) && $previousHeader == $this->currentHeader ) {
					$this->parseRows( $data );
				}
			}
			fclose( $this->fileHandle );
		}
	}

	/**
	 * Parse rows
	 * 
	 * @param array $data
	 */
	protected function parseRows( $data ) {
		$personId = null;

		// Try get Person id by contact or card
		foreach ( $this->currentHeader as $key => $alias ) {
			if (	! empty( $this->relations[ $alias ][ 'model' ] )
					&& class_exists( $model = ucfirst( $this->relations[ $alias ][ 'model' ] ) )
					&& ( new $model ) instanceof iIdentity 
				) {
				$personId = $model::getPersonId( $data[ $key ] );
				if ( ! empty( $personId ) ) {
					$data[ $key ] = null;
				}
			}
		}

		// Add new person if not found person
		if ( empty( $personId ) ) {
			$personId = Persons::addPerson();
		}

		// Update Person data
		foreach ( $this->currentHeader as $key => $alias ) {
			if ( ! empty( $this->relations[ $alias ] ) && !empty( $data[ $key ] ) ) {
				$model = ucfirst( $this->relations[ $alias ][ 'model' ] );
				if ( ( new $model ) instanceof iMetaData) {
					$model::add( $personId, $data[ $key ] );
				} else {
					if ( ! empty( $this->relations[ $alias ][ 'field' ] ) ) {
						$field = $this->relations[ $alias ][ 'field' ];
					} else {
						$field = $alias;
					}
					$model::addPersonData( $personId, $field, $data[ $key ] );
				}
			}
		}
	}

	/**
	 * Check an set current header
	 * 
	 * @param array $header
	 */
	protected function checkHeaders( $header ) {
		foreach ( $header as $key => $row ) {
			if ( mb_strlen( $row, 'UTF-8' ) < 1 ) {
				unset( $header[ $key ] );
			}
		}
		if ( false !== ( $index = array_search( $header, $this->headers ) ) ) {
			$this->currentHeader = $this->headers[ $index ];
		}
	}

}

$relations = [
		'name'		=> [ 'model' => 'persons', ],
		'sex'		=> [ 'model' => 'persons', ],
		'birthdate'	=> [ 'model' => 'persons', ],
		'address'	=> [ 'model' => 'persons', ],
		'hobby'		=> [ 'model' => 'hobbies', ],
		'company'	=> [ 'model' => 'persons', ],
		'post'		=> [ 'model' => 'persons', 'field' => 'position', ],
		'position'	=> [ 'model' => 'persons', ],
		'site'		=> [ 'model' => 'persons', ],
		'phone'		=> [ 'model' => 'phones', ],
		'phone2'	=> [ 'model' => 'phones', ],
		'email'		=> [ 'model' => 'emails', ],
		'card'		=> [ 'model' => 'cards', ],
		'icard'		=> [ 'model' => 'cards', ],
];

$headers = [
	[ 'id', 'login', 'phone', 'age', 'hobby', 'category', 'position', 'address', 'date', 'site', 'company', 'card', 'email', 'name', 'color', ],
	[ 'id', 'login', 'phone', 'age', 'post', 'ra', 'hobby', 'position', 'address', 'date', 'site', 'company', 'card', 'email', 'name', 'color', ],
	[ 'name', 'email', 'birthdate', 'company', 'icard', 'orgnum', 'phone', 'phone2', 'address', 'city', 'zip', 'region', 'hobby', 'dep', 'sex', ],
];

$CSVFileName = 'test_data';

$parserObject = new PersonsParser( $CSVFileName, $headers, $relations );
$parserObject->parseFile();