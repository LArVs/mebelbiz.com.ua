<?php
// get filters from $_REQUEST

$debug = true;
//$debug = false;

if( !class_exists( 'prepareFilterForm' ) ) {

	class prepareFilterForm {
		protected $_debug	 = false;
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
			'products'	   => array(
				'table'		=> 'products',
				'alias'		=> 'p',
			),
			'category'	   => array(
				'table'		=> 'product_category',
				'alias'		=> 'c',
			),
			'manufacturer'	   => array(
				'table'		=> 'product_manufacturer',
				'alias'		=> 'm',
			),
		);
		public $table_alias = array(
			'products'	   => 'p',
			'category'	   => 'c',
			'manufacturer' => 'm',
		);
		public $category	 = array();
		public $manufacturer = array();

		// default: text filter
		public $default = array(
			'object'   => 'products',
			'operator' => '=',
			'type'	   => '%u',
			'type_php' => 'int'
		);
		//public $text_operator_prefix = '%';
		//public $text_operator_suffix = '%';

		public $filter_optoins = array(
			'cost_from' => array(
				'object'   => 'products',
				'field'    => 'cost',
				'type'	   => '%.2f',
				'operator' => '>=',
				'type_php' => 'float'
			),
			'cost_to' => array(
				'object'   => 'products',
				'field'    => 'cost',
				'type'	   => '%.2f',
				'operator' => '<=',
				'type_php' => 'float'
			),
			//'article'		 => array(),
			//'size'		 => array(),
			//'color'		 => array(),
			//'module'		 => array(),
			'category'	   => array(
				'field'    => 'name',
				'type'	   => '%s',
				'type_php' => 'select'
			),
			'manufacturer' => array(
				'field'    => 'name',
				'type'	   => '%s',
				'type_php' => 'select'
			),
			'sort' => array(
				'type_php' => 'select_sort'
			),
			//'name'		 => array(),
		);

		public $misc_options = array(
			'sort' => array(
				'name_asc'  => 'по названию от А-Я',
				'name_desc' => 'по названию от Я-А',
				'cost_asc'  => 'по возростанию цены',
				'cost_desc' => 'по убыванию цены',
			),
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
				$this->_log[] = "[$level] prepareFilterForm: $message";
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

		function getSelectOptions( $name, $value ) {
			$option = &$this->filter_optoins[ $name ];
			$value = (int)$value;
			$field = empty ( $option[ 'field' ] ) ? $name : $option[ 'field' ];
			$table_option = $this->table[ $name ];
			$table = $table_option[ 'table' ];
			$sql = <<<"EOS"
				SELECT * FROM `$table` ORDER BY `$field` ASC
EOS;
			//$this->log( modX::LOG_LEVEL_DEBUG, "sql: $sql" );
			$result = $this->modx->query( $sql );
			$options = array( '<option value="0">выберите...</option>' );
			$options_list = '';
			if( !empty( $result ) ) {
				while( $r = $result->fetch( PDO::FETCH_ASSOC ) ) {
					//$this->log( modX::LOG_LEVEL_DEBUG, "id: ".var_export( $r, true ) );
					$id   = $r[ 'id'   ];
					$name = $r[ 'name' ];
					$selected = '';
					if( $id == $value ) { $selected = " selected"; }
					$options[] = sprintf(
						'<option value="%u"%s>%s</option>'
						, $id
						, $selected
						, $name
					);
				}
				$options_list = implode( ' ', $options );
			}
			$this->log( modX::LOG_LEVEL_DEBUG, "select options: '$options_list'" );
			return( $options_list );
		}


		function getMiscSelectOptions( $name, $value ) {
			if( empty( $this->misc_options[ $name ] ) ) { return( '' ); }
			$misc_options = $this->misc_options[ $name ];
			$options = array( '<option value="0">выберите...</option>' );
			$options_list = '';
			foreach( $misc_options as $id => $name ) {
				$selected = '';
				if( $id === $value ) { $selected = " selected"; }
				$options[] = sprintf(
					'<option value="%s"%s>%s</option>'
					, $id
					, $selected
					, $name
				);
			}
			$options_list = implode( ' ', $options );
			$this->log( modX::LOG_LEVEL_DEBUG, "misc select options: '$options_list'" );
			return( $options_list );
		}

		function createPlaceholders() {
			if( empty( $_REQUEST ) ) { return; }
			$placeholders = array();
			foreach( $this->filter_optoins as $name => $option ) {
				$type_php = ( empty( $option[ 'type_php' ] ) ? $this->default[ 'type_php' ] : $option[ 'type_php' ] );
				$value = empty( $_REQUEST[ $name ] ) ? null : $_REQUEST[ $name ];
				switch( $type_php ) {
					case 'select':
						$value = $value === null ?  0 : (int)$value;
						$value = $this->getSelectOptions( $name, $value );
						break;
					case 'select_sort':
						$value = $value === null ?  0 : (string)$value;
						$value = $this->getMiscSelectOptions( $name, $value );
						break;
					case 'float':
						$value = $value === null ? '' : (float)$value;
						break;
					case 'int':
					default:
						$value = $value === null ? '' : (int)$value;
						break;
				}
				$placeholders[ $name ] = $value;
			}
			$this->modx->setPlaceholders( $placeholders, 'form_filter.' );
			return;
		}

	} // end of class

}
$processor = prepareFilterForm::Instance( $modx, array( 'debug' => $debug, 'clear_log' => true ) );

$filters = $processor->createPlaceholders();

$processor->SaveLog();

return( '' );
