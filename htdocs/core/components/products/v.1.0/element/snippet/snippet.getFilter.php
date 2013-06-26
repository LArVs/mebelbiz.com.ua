<?php
// get filters from $_REQUEST

$debug = true;
//$debug = false;

if( !class_exists( 'GetProducts' ) ) {

	class GetProducts {
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
			'products'     => array(
				'table'     => 'products',
				'alias'     => 'p',
			),
			'category'     => array(
				'table'     => 'product_category',
				'alias'     => 'c',
			),
			'manufacturer'     => array(
				'table'     => 'product_manufacturer',
				'alias'     => 'm',
			),
		);
		public $table_alias = array(
			'products'     => 'p',
			'category'     => 'c',
			'manufacturer' => 'm',
		);
		public $category     = array();
		public $manufacturer = array();

		// default: text filter
		public $default = array(
			'object'   => 'products',
			'operator' => '=',
			'type'     => '%u',
		);
		//public $text_operator_prefix = '%';
		//public $text_operator_suffix = '%';

		public $filter_optoins = array(
			'cost_from' => array(
				'object'   => 'products',
				'field'    => 'cost',
				'type'     => '%.2f',
				'operator' => '>=',
			),
			'cost_to' => array(
				'object'   => 'products',
				'field'    => 'cost',
				'type'     => '%.2f',
				'operator' => '<=',
			),
			//'article'      => array(),
			//'size'         => array(),
			//'color'        => array(),
			//'module'       => array(),
			'category'     => array(
				'field'    => 'id',
				//'field'    => 'name',
				//'type'     => '%s',
			),
			'manufacturer' => array(
				'field'    => 'id',
				//'field'    => 'name',
				//'type'     => '%s',
			),
			//'name'         => array(),
		);

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
				$this->_log[] = "[$level] GetProducts: $message";
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

		function getFilters() {
			$filter = array();
			$default = &$this->default;
			$table   = &$this->table;
			foreach( $this->filter_optoins as $name => $option ) {
				if( !empty( $_REQUEST[ $name ] ) ) {
					$value = $_REQUEST[ $name ];
					//$tv       = empty( $option[ 'tv'       ] ) ? $name:           $option[   'tv'    ];
					//$operator_prefix = ( ( $operator == '==' || $operator == '!=' ) && !empty( $option[ 'operator_prefix' ] )? $text_operator_prefix: '' );
					//$operator_suffix = ( ( $operator == '==' || $operator == '!=' ) && !empty( $option[ 'operator_suffix' ] )? $text_operator_suffix: '' );
					$field    = empty ( $option[ 'field'    ] ) ? $name                : $option[ 'field'    ];
					$object   = empty ( $option[ 'object'   ] ) ? $name                : $option[ 'object'   ];
						$alias = $table[ $object ][ 'alias' ] . '.';
					$type     = empty ( $option[ 'type'     ] ) ? $default['type']     : $option[ 'type'     ];
						if( $type == '%s' ) { $value = urldecode( $value ); }
					$operator = empty ( $option[ 'operator' ] ) ? $default['operator'] : $option[ 'operator' ];
					$filter[] = sprintf( "( $alias`%s` %s '$type' )"
						,$field
						,$operator
						,$value
					);
						//,urldecode( $filter )
						//,$operator_suffix
				}
			}
			if( !empty( $filter ) ) {
				$filters = implode( ' AND ', $filter );
				if( count( $filter ) > 1 ) {
					$filters = '(' . $filters . ')';
				}
			} else {
				$filters = '';
			}
			$this->log( modX::LOG_LEVEL_DEBUG, "filters: '$filters'" );
			return( $filters );
		}

		function getOrders() {
			$orders = '';
			$products_alias     = $this->table[ 'products'     ][ 'alias' ];
			$category_alias     = $this->table[ 'category'     ][ 'alias' ];
			$manufacturer_alias = $this->table[ 'manufacturer' ][ 'alias' ];
			if( !empty( $_REQUEST[ 'sort' ] ) ) {
				$sort = (array)$_REQUEST[ 'sort' ];
				$sort_by = array();
				foreach( $sort as $s ) {
					switch( $s ) {
						case 'cost_asc':
							$sort_by[ 'cost_asc' ] = "$products_alias.cost ASC";
							break;
						case 'cost_desc':
						default:
							$sort_by[ 'cost_desc' ] = "$products_alias.cost DESC";
							break;
					}
				}
				$orders = implode( ', ', $sort_by );
			} else { $orders = "$products_alias.cost DESC"; }
			$this->log( modX::LOG_LEVEL_DEBUG, "order: '$orders'" );
			return( $orders );
		}

		function fetchProductsIDs( $filters, $orders, $limit, $page ) {
			//if( empty( $filters ) && empty( $orders ) ) { return( '' ); }
			$sql_where = '';
			if( !empty( $filters ) ) {
				$sql_where = "WHERE $filters";
			}
			$sql_order = '';
			if( !empty( $orders ) ) {
				$sql_order = "ORDER BY $orders";
			}
			$products_table     = $this->table[ 'products'     ][ 'table' ];
			$products_alias     = $this->table[ 'products'     ][ 'alias' ];
			$category_table     = $this->table[ 'category'     ][ 'table' ];
			$category_alias     = $this->table[ 'category'     ][ 'alias' ];
			$manufacturer_table = $this->table[ 'manufacturer' ][ 'table' ];
			$manufacturer_alias = $this->table[ 'manufacturer' ][ 'alias' ];
			if( !empty( $_REQUEST[ 'page'  ] ) ) { $page  = (int)$_REQUEST[ 'page'  ]; }
			if( !empty( $_REQUEST[ 'limit' ] ) ) { $limit = (int)$_REQUEST[ 'limit' ]; }
			$page = ( empty( $page ) ? 1: $page );
			$limit = ( empty( $limit ) || $limit < 1 ? 10: $limit );
			$this->log( modX::LOG_LEVEL_DEBUG, "page: $page; limit: $limit" );
			$offset = ( $page - 1  ) * $limit;
			$sql = <<<"EOS"
				SELECT $products_alias.`id` as id FROM `$products_table` as $products_alias
				INNER JOIN `$manufacturer_table` as $manufacturer_alias on ($manufacturer_alias.id=$products_alias.manufacturer_id)
				INNER JOIN `$category_table`     as $category_alias     on ($category_alias.id=$products_alias.category_id)
				$sql_where
EOS;
				//$sql_order
				//LIMIT $offset, $limit
			$this->log( modX::LOG_LEVEL_DEBUG, "sql: $sql" );
			$result = $this->modx->query( $sql );
			$ids = array();
			$ids_list = '0';
			if( !empty( $result ) ) {
				while( $r = $result->fetch( PDO::FETCH_ASSOC ) ) {
					//$this->log( modX::LOG_LEVEL_DEBUG, "id: ".var_export( $r, true ) );
					$ids[] = $r[ 'id' ];
				}
				if( !empty( $ids ) ) {
					$ids_list = implode( ',', $ids );
				}
			}
			$this->log( modX::LOG_LEVEL_DEBUG, 'products ids: ' . $ids_list );
			$placeholders = array();
			$placeholders[ 'ids' ] = $ids_list;
			$sortby = 'cost';
			$sortdir = 'DESC';
			$sorttype = 'decimal';
			if( !empty( $_REQUEST[ 'sort' ] ) ) {
				$sort = $_REQUEST[ 'sort' ];
				switch( $sort ) {
					case 'cost_asc':
						$sortby = 'cost';
						$sortdir = 'ASC';
						$sorttype = 'decimal';
						break;
					case 'name_asc':
						$sortby = 'name';
						$sortdir = 'ASC';
						$sorttype = 'string';
						break;
					case 'name_desc':
						$sortby = 'name';
						$sortdir = 'DESC';
						$sorttype = 'string';
						break;
				}
			}
			$placeholders[ 'sortby'   ] = $sortby;
			$placeholders[ 'sortdir'  ] = $sortdir;
			$placeholders[ 'sorttype' ] = $sorttype;
			$this->modx->setPlaceholders( $placeholders, 'filters.' );
			$this->log( modX::LOG_LEVEL_DEBUG, 'placeholders: ' . var_export( $placeholders, true ) );
			return( '' );
			//return( $ids_list );
		}

	} // end of class

}

$processor = GetProducts::Instance( $modx, array( 'debug' => $debug, 'clear_log' => true ) );

$limit = $processor->modx->getOption( 'limit', $scriptProperties, 10, true );
$page  = $processor->modx->getOption( 'page',  $scriptProperties, 1,  true );

$filters = $processor->getFilters();
$orders  = $processor->getOrders();
$ids     = $processor->fetchProductsIDs( $filters, $orders,  $limit, $page );

$processor->SaveLog();

return( $ids );
