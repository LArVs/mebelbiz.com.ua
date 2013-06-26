<?php

$debug = true;
//$debug = false;

if( !class_exists( 'SaveProduct' ) ) {

	class SaveProduct {
		protected $_debug    = false;
		protected $_log_level = array(
				modX::LOG_LEVEL_FATAL => 'Fatal',
				modX::LOG_LEVEL_ERROR => 'Error',
				modX::LOG_LEVEL_WARN  => 'Warn',
				modX::LOG_LEVEL_INFO  => 'Info',
				modX::LOG_LEVEL_DEBUG => 'Debug',
			);
		protected $_log = array();

		public $modx = null;
		public $config = array();

		public $table = array(
			'products'     => 'products',
			'category'     => 'product_category',
			'manufacturer' => 'product_manufacturer',
		);
		public $category     = array();
		public $manufacturer = array();

		private static $_instance;
		public static function Instance( &$modx = null, $config = array() ) {
			if( !isset( self::$_instance ) ) {
				$c = __CLASS__;
				self::$_instance = new $c( $modx, $config );
			}
			if( isset( $config[ 'clear_log' ] ) && $config[ 'clear_log' ] ) { self::$_instance->clear_log(); }
			return( self::$_instance );
		}

		function __construct( &$modx = null, $config = array() ) {
			$modx = ( empty( $modx ) ? $GLOBALS[ 'modx' ]: $modx );
			$this->modx = &$modx;

			$this->_debug = ( empty( $config[ 'debug' ] ) ? false : true );
			$this->config[ 'debug' ] = $this->_debug;
		}

		function clear_log() {
			$this->_log = array();
		}

		function log( $log_level, $message ) {
			if( $this->_debug ) {
				$level = $this->_log_level[ $log_level ];
				$this->_log[] = "[$level] SaveProduct: $message";
				$this->modx->log( $log_level, $message );
			}
		}

		function SaveLog() {
			if( $this->_debug ) {
				$file = 'error.log';
				$path = gxPATH_CORE . DIRECTORY_SEPARATOR;
				$file_path = $path . $file;
				$data = "\n\n---\n\n" . implode( "\n", $this->_log );
				file_put_contents( $file_path, $data, FILE_APPEND );
				$this->modx->log( modX::LOG_LEVEL_DEBUG, "log file: $file_path" );
			}
		}

		function dbTableExists( $table ) {
			$sql = "SHOW TABLES LIKE '$table'";
			$result = $this->modx->query( $sql );
			$r_count = $result->rowCount();
			$table_exists = ( $r_count > 0 );
			return( $table_exists );
		}

		function dbCreateTableProduct( $table ) {
			$this->log( modX::LOG_LEVEL_DEBUG, "create table: $table" );
			// create table
			$sql_create_table = <<<"EOS"
				DROP `$table` IF EXISTS;
EOS;
			$this->modx->query( $sql_create_table );
			$sql_create_table = <<<"EOS"
				CREATE TABLE `$table` (
					`id`              int   unsigned NOT NULL,
					`cost`            float unsigned NOT NULL,
					`category_id`     int   unsigned NOT NULL,
					`manufacturer_id` int   unsigned NOT NULL,
					PRIMARY KEY (`id`),
							KEY `cost`         ( `cost`            ),
							KEY `category`     ( `category_id`     ),
							KEY `manufacturer` ( `manufacturer_id` )
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOS;
			$this->modx->query( $sql_create_table );
		}

		function dbCreateTableCategory( $table ) {
			$this->log( modX::LOG_LEVEL_DEBUG, "create table: $table" );
			// create table
			$sql_create_table = <<<"EOS"
				DROP `$table` IF EXISTS;
EOS;
			$this->modx->query( $sql_create_table );
			$sql_create_table = <<<"EOS"
				CREATE TABLE `$table` (
					`id`    INT UNSIGNED AUTO_INCREMENT,
					`name`  VARCHAR(255) NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `name` ( `name` )
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOS;
			$this->modx->query( $sql_create_table );
		}

		function dbCreateTableManufacturer( $table ) {
			$this->log( modX::LOG_LEVEL_DEBUG, "create table: $table" );
			// create table
			$sql_create_table = <<<"EOS"
				DROP `$table` IF EXISTS;
EOS;
			$this->modx->query( $sql_create_table );
			$sql_create_table = <<<"EOS"
				CREATE TABLE `$table` (
					`id`    INT UNSIGNED AUTO_INCREMENT,
					`name`  VARCHAR(255) NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `name` ( `name` )
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOS;
			$this->modx->query( $sql_create_table );
		}

		function dbCreateTables() {
			$table = &$this->table;
			if( !$this->dbTableExists( $table[ 'products'     ] ) ) { $this->dbCreateTableProduct(      $table[ 'products'     ] ); }
			if( !$this->dbTableExists( $table[ 'category'     ] ) ) { $this->dbCreateTableCategory(     $table[ 'category'     ] ); }
			if( !$this->dbTableExists( $table[ 'manufacturer' ] ) ) { $this->dbCreateTableManufacturer( $table[ 'manufacturer' ] ); }
		}

		function fetchObject( $object, $reload_force = false ) {
			if( empty( $object ) ) { return( false ); }
			switch( $object ) {
				case 'category':
					$o = &$this->category;
					$table = $this->table[ 'category' ];
				break;
				case 'manufacturer':
					$o = &$this->manufacturer;
					$table = $this->table[ 'manufacturer' ];
				break;
			}
			if( !isset( $o ) ) { return( false ); }
			if( $reload_force || empty( $o[ 'loaded' ] ) ) {
				$o = array( 'loaded' => false, 'by_id' => array(), 'new' => array(), );
				$this->log( modX::LOG_LEVEL_DEBUG, "Loading object: $object" );
			} else {
				$this->log( modX::LOG_LEVEL_DEBUG, "Object $object already loaded" );
				return( false );
			}
			$_sql = 'SELECT * FROM %s';
			$sql = sprintf( $_sql, $table );
			$result = $this->modx->query( $sql );
			while( $r = $result->fetch( PDO::FETCH_ASSOC ) ) {
				$o[ 'by_id' ][ $r[ 'id' ] ] = $r[ 'name' ];
			}
			$o[ 'by_name' ]= array_flip( $o[ 'by_id' ] );
			$o[ 'loaded' ] = true;
			$this->log( modX::LOG_LEVEL_DEBUG, "$object: " . var_export( $o, true ) );
			return( true );
		}

		function addObject( $object, $name ) {
			if( empty( $name   ) ) { return( 0     ); }
			if( empty( $object ) ) { return( false ); }
			switch( $object ) {
				case 'category':
					$o = &$this->category;
					$table = $this->table[ 'category' ];
				break;
				case 'manufacturer':
					$o = &$this->manufacturer;
					$table = $this->table[ 'manufacturer' ];
				break;
			}
			if( !isset( $o ) ) { return( false ); }
			$_sql = 'INSERT INTO %s ( name ) VALUES( "%s" )';
			$sql = sprintf( $_sql, $table, $name );
			$result = $this->modx->query( $sql );
			$id = $this->modx->lastInsertId();
			$o[ 'by_id' ][ $id ] = $name;
			$o[ 'by_name' ]= array_flip( $o[ 'by_id' ] );
			$this->log( modX::LOG_LEVEL_DEBUG, "Add object $object: $name ($id)" );
			$this->log( modX::LOG_LEVEL_DEBUG, "Add object $object: $sql" );
			return( $id );
		}

		function getObject( $object, $name ) {
			if( empty( $object ) ) { return( false ); }
			switch( $object ) {
				case 'category':     $o = &$this->category;     break;
				case 'manufacturer': $o = &$this->manufacturer; break;
			}
			if( !isset( $o ) ) { return( false ); }
			$this->fetchObject( $object );
			if( empty( $o[ 'by_name' ][ $name ] ) ) {
				$o_id = $this->addObject( $object, $name );
			} else {
				$o_id = $o[ 'by_name' ][ $name ];
			}
			return( $o_id );
		}

		function SaveProductItem( $data ) {
			$this->log( modX::LOG_LEVEL_DEBUG, 'save item: ' . var_export( $data, true ) );
			$category_id     = $this->getObject( 'category',     $data[ 'category'     ] );
			$manufacturer_id = $this->getObject( 'manufacturer', $data[ 'manufacturer' ] );
			$table = $this->table[ 'products' ];
			$_sql = 'REPLACE INTO %s ( id, cost, category_id, manufacturer_id ) VALUES( "%u", "%.2f", "%u", "%u" )';
			$sql = sprintf( $_sql, $table
				,$data[ 'id'           ]
				,$data[ 'cost'         ]
				,$category_id
				,$manufacturer_id
			);
			$this->log( modX::LOG_LEVEL_DEBUG, $sql );
			$this->modx->query( $sql );
		}

		function SaveProduct( $resource ) {
			$this->dbCreateTables();
			$id           = $resource->get(        'id'           );
			$cost         = $resource->getTVValue( 'cost'         );
			$category     = $resource->getTVValue( 'category'     );
			$manufacturer = $resource->getTVValue( 'manufacturer' );
			$this->SaveProductItem( array(
				'id'           => $id,
				'cost'         => $cost,
				'category'     => $category,
				'manufacturer' => $manufacturer,
			));
		}

	} // end of class

}

$save_product = SaveProduct::Instance( $modx, array( 'debug' => $debug, 'clear_log' => true ) );

$save_product->log( modX::LOG_LEVEL_DEBUG, 'event = ' . $event->name );

// get event properties
$event      = $save_product->modx->getOption( 'event',            $scriptProperties, false, true );
$parameters = $save_product->modx->getOption( 'event_parameters', $scriptProperties, false, true );

//get resource
$id       = $parameters[ 'id'       ];
$mode     = $parameters[ 'mode'     ];
$resource = $parameters[ 'resource' ];

if( empty( $event ) || empty( $parameters ) || empty( $resource ) ) {
	$save_product->log( modX::LOG_LEVEL_DEBUG, "Empty: event" );
	return;
}


switch( $event->name ) {
	case 'OnDocFormSave':
		$template = $resource->get( 'template' );
		if( $template == 2 ) {
			$save_product->SaveProduct( $resource );
		}
	break;
}

$save_product->SaveLog();

