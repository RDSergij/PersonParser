<?php
/**
 * Config file
 */

require_once dirname( __FILE__ ) .'/activerecord/ActiveRecord.php';

ActiveRecord\Config::initialize( function( $cfg ) {
    $cfg->set_model_directory( dirname( __FILE__ ) . '/models' );
    $cfg->set_connections( array( 'development' => 'mysql://USER:PASS@localhost/DB_name?charset=utf8' ) );
});