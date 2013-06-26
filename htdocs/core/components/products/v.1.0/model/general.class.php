<?php
/**
 * GeneralX main class
 *
 * @copyright Copyright (C) 2012, by LArV <larv.develop@gmail.com>
 * @author    LAtV <larv.develop@gmail.com>
 *
 * @package GeneralX
 * @subpackage class
 */
if( !class_exists( 'GeneralX' ) ) {
class GeneralX {
	/**
	 * constants
	 */
	const SNIPPET = 1;
	const PLUGINS = 2;
	/**
	 *
	 * usage snippet:
	 *
		return $modx->runSnippet( 'Script', array( 'scriptProperties' => $scriptProperties,
			'snippet' => 'test',
		));
	 *
	 * usage plugins:
	 *
		<?php
		return $modx->runSnippet( 'Script', array( 'scriptProperties' => $scriptProperties,
		   'plugins' => 'test',
		));
	 *
	 */

	/**
	 * class instance
	 *
	 * @access private
	 * @var    object    static reference
	 */
	private static $_instance;
	/**
	 * @access protected
	 * @var boolean     A debug class.
	 */
	protected $_debug    = false;
	/**
	 * @access protected
	 * @var string      A package name.
	 */
	protected $_package    = 'GeneralX';
	/**
	 * @access protected
	 * @var string      A subpackage name.
	 */
	protected $_subpackage = '';
	/**
	 * @access protected
	 * @var array       package version
	 */
	protected $_version = 'v.1.0';
	/**
	 * @access protected
	 * @var array       package lexicon
	 */
	protected $_lexicon = ':default';
	/**
	 * @access protected
	 * @var array       package namespace
	 * @sample: add modx namespace: GX-v.1.0 {core_path}components/products/v.1.0/ {assets_path}components/products/v.1.0/
	 */
	protected $_namespace = 'GX-v.1.0';
	/**
	 * @access protected
	 * @var array       A collection of preprocessed chunk values.
	 */
	protected $_chunk = array();
	/**
	 * @access protected
	 * @var array       A collection of preprocessed snippet values.
	 */
	protected $_snippet = array();

	/**
	 * @access public
	 * @var modX A reference to the modX object.
	 */
	public $modx = null;
	/**
	 * @access public
	 * @var array A collection of properties to adjust Quip behaviour.
	 */
	public $config = array();

	/**
	 * Create/get instance of class
	 *
	 * @access public static
	 * @param  void
	 * @return reference
	 */
	public static function Instance( modX &$modx = null, array $config = array() ) {
		if( !isset( self::$_instance ) ) {
			$c = __CLASS__;
			self::$_instance = new $c( $modx, $config );
		}
		return( self::$_instance );
	}

	/**
	 * The GeneralX Constructor.
	 *
	 * This method is used to create a new GeneralX object.
	 *
	 * @param  modX  &$modx    A reference to the modX object.
	 * @param  array $config   A collection of properties.
	 * @return GeneralX         A unique GeneralX instance.
	 */
	function __construct( modX &$modx = null, array $config = array() ) {
		$modx = ( empty( $modx ) ? $GLOBALS[ 'modx' ]: $modx );
		$this->modx = &$modx;
			$this->_debug = ( empty( $config[ 'debug' ] ) ? false : true );
			$this->config[ 'debug' ] = $this->_debug;
		// degug ?
		//		$debug = ( $this->modx->getOption( 'debug', null, false ) === 'true' ? true: false );
		//		$debug = ( $this->config[ 'debug' ] === true ? true: false );
		$this->modx->setDebug( $this->_debug );

		$this->_package     = ( empty( $config[ 'package' ] ) ?
			$this->_package
			:
			$config[ 'package' ]
		);
		$this->_subpackage  = ( empty( $config[ 'subpackage' ] ) ?
			$this->_subpackage
			:
			$config[ 'subpackage' ]
		);

		define( 'gxMODX_PATH_CORE', $this->modx->getOption( 'core_path', null, MODX_CORE_PATH ) );


		$this->_version  = ( empty( $config[ 'version' ] ) ? $this->_version: $config[ 'version' ] );

		$this->_namespace = ( empty( $config[ 'namespace' ] ) ? $this->_package: $config[ 'namespace' ] );

		$_ns = $this->modx->getObject( 'modNamespace', $this->_namespace );

		// get lexicon
		if( empty( $this->modx->lexicon ) || !( $this->modx->lexicon instanceof modLexicon ) ) {
			$this->modx->getService( 'lexicon', 'modLexicon' );
		}

		if( empty( $_ns ) ) {
			$this->modx->log( modX::LOG_LEVEL_WARN, "GeneralX: namespace not found: '{$this->_namespace}'" );
			$_core_path   = '{core_path}components/'   . $this->_package . '/' . $this->_version . '/';
			$_assets_path = '{assets_path}components/' . $this->_package . '/' . $this->_version . '/';
			$_ns = $this->modx->newObject( 'modNamespace' );
			$_ns->set( 'name',        $this->_namespace );
			$_ns->set( 'path',        $_core_path       );
			$_ns->set( 'assets_path', $_assets_path     );
			if( $_ns->save() == false ) {
				$this->modx->log( modX::LOG_LEVEL_ERROR, "GeneralX: namespace not registered: '{$this->_namespace}'" );
			} else {
				$this->modx->lexicon->load( 'workspace' );
				$this->modx->log( modX::LOG_LEVEL_WARN, $this->modx->lexicon(
					'namespace_registered',
					array( 'namespace' => $this->_namespace, ) )
				);
			}
		}
		define( 'gxPATH_CORE',
			$this->modx->getOption(
				'gx.core_path',
				null,
				file_exists( $_ns->get( 'path' ) ) ?
				$_ns->get( 'path' )
				:
				gxMODX_PATH_CORE . 'components/' . $this->_package . '/' . $this->_version . '/'
			)
		);

		//$this->_version  = ( empty( $config[ 'version' ] ) ?
		//$this->_version
		//:
		//$config[ 'version' ]
		//);

		define( 'gxPATH_ASSETS',
			$this->modx->getOption(
				'gx.assets_path',
				null,
				MODX_ASSETS_PATH . 'components/' . $this->_package . '/' . $this->_version . '/'
			)
		);
		define( 'gxURL_ASSETS',
			$this->modx->getOption(
				'gx.assets_url',
				null,
				MODX_ASSETS_URL . 'components/' . $this->_package . '/' . $this->_version . '/'
			)
		);

		// GeneralX core path
		define( 'gxPATH_CONTROLLER', gxPATH_CORE     . 'controller/' );
		define( 'gxPATH_PROCESSOR',  gxPATH_CORE     . 'processor/' );
		define( 'gxPATH_MODEL',      gxPATH_CORE     . 'model/' );
		define( 'gxPATH_ELEMENT',    gxPATH_CORE     . 'element/' );
		define( 'gxPATH_CHUNK',      gxPATH_ELEMENT  . 'chunk/' );
		define( 'gxPATH_SNIPPET',    gxPATH_ELEMENT  . 'snippet/' );
		define( 'gxPATH_PLUGINS',    gxPATH_ELEMENT  . 'plugins/' );
		// GeneralX assets path
		define( 'gxPATH_CONNECTOR',  gxPATH_ASSETS   . 'connector/' );
		define( 'gxPATH_CSS',        gxPATH_ASSETS   . 'css/' );
		define( 'gxPATH_JS',         gxPATH_ASSETS   . 'js/' );
		// GeneralX assets url
		//define( 'gxURL_CONNECTOR',   gxURL_ASSETS    . 'connector/' );
		define( 'gxURL_CONNECTOR',   gxURL_ASSETS    . 'connector/' );
		define( 'gxURL_CSS',         gxURL_ASSETS    . 'css/' );
		define( 'gxURL_JS',          gxURL_ASSETS    . 'js/' );

		$this->config = array_merge(
			array(
				// GeneralX misc
				'package'          => $this->_package,
				'subpackage'       => $this->_subpackage,
				'version'          => $this->_version,
				'auth'             => $this->modx->site_id,
				'path'    => array(
					// GeneralX core path
					'modx_path_core'   => gxMODX_PATH_CORE,
					'core'        => gxPATH_CORE,
					'controller'  => gxPATH_CONTROLLER,
					'processor'   => gxPATH_PROCESSOR,
					'model'       => gxPATH_MODEL,
					'element'     => gxPATH_ELEMENT,
					'chunk'       => gxPATH_CHUNK,
					'snippet'     => gxPATH_SNIPPET,
					'plugins'     => gxPATH_PLUGINS,
					// GeneralX assets path
					'assets'      => gxPATH_ASSETS,
					'connector'   => gxPATH_CONNECTOR,
					'css'         => gxPATH_CSS,
					'js'          => gxPATH_JS,
				),
				'url'     => array(
					// GeneralX assets url
					'assets'       => gxURL_ASSETS,
					'connector'    => gxURL_CONNECTOR,
					'css'          => gxURL_CSS,
					'js'           => gxURL_JS,
				),
			)
			,$config
		);

		// load lexicon
		$this->modx->lexicon->load( $this->_package . ':default' );

		// add package
		$f = MODX_CORE_PATH . $this->_package;
		if( file_exists( $f ) ) {
			$this->modx->addPackage( $this->_package, gxPATH_MODEL );
		} else {
			if( $this->_debug ) { $modx->log( modX::LOG_LEVEL_WARN, "GeneralX: Package not found: '$f'" );}
		}
	}

	/**
	 * Initializes GeneralX based on a specific context.
	 *
	 * @access public
	 * @param  string $ctx   The context to initialize in.
	 * @return string        The processed content.
	 */
	public function initialize( $ctx = 'mgr' ) {
		$output = '';
		switch( $ctx ) {
		case 'mgr':
			//				if (!$this->modx->loadClass('quip.request.QuipControllerRequest',$this->config['modelPath'],true,true)) {
			//					return 'Could not load controller request handler.';
			//}
			//				$this->request = new QuipControllerRequest($this);
			//				$output = $this->request->handleRequest();
			break;
		}
		return $output;
	}

	/**
	 * Gets a Snippet and caches it; also falls back to file-based snippet
	 * for easier debugging.
	 *
	 * Will always use the file-based snippet if $debug is set to true.
	 *
	 * @access public
	 * @param  string  $name         The name of the Snippet.
	 * @param  array   $properties   The properties for the Snippet.
	 * @return string                The processed content of the Snippet.
	 */
	public function getSnippet( $name, $properties = array(), $cacheable = true ) {
		return( $GX->getSnippet( $name, $properties, self::SNIPPET, $cacheable ) );
	}
	public function getScript( $name, $properties = array(), $type = self::SNIPPET, $cacheable = true ) {
		$snippet = null;
		//$prefix = '';
		//$name = $prefix . $name;
		// if not loaded snippet
		if( !isset( $this->_snippet[ $name ] ) ) {
			// if debug then use file-based snippet
			//if( !$this->_debug ) {
				//$snippet = $this->modx->getObject( 'modSnippet', array( 'name' => $name ) );
			//}
			if( empty( $snippet ) ) {
				$snippet = &$this->_loadSnippet( $name, $type, $cacheable );
				if( $snippet === false ) {
					$this->modx->log( modX::LOG_LEVEL_ERROR, "GeneralX: Could not load snippet: '$name'!" );
					return( $snippet );
				}
			}
			$this->_snippet[ $name ] = $snippet->getContent();
		}
		// if loaded snippet
		else {
			$o = $this->_snippet[ $name ];
			$snippet = $this->modx->newObject( 'modSnippet' );
			$snippet->setContent( $o );
		}
		$snippet->setCacheable( $cacheable );
		return( $snippet->process( $properties ) );
	}

	/**
	 * Returns a modSnippet object from a file.
	 *
	 * @access private
	 * @param string $name          The name of the Snippet. Will parse to snippet.name.php
	 * @param string $prefix        The prefix for snippet with
	 * @return modSnippet/boolean   Returns the modSnippet object if found, else false.
	 */
	private function &_loadSnippet( $name, $type = self::SNIPPET, $cacheable = true, $prefix = false ) {
		switch( $type ) {
			case self::PLUGINS :
				$path = 'plugins';
				break;
			case self::SNIPPET :
			default:
				$path = 'snippet';
				break;
		}
		if( empty( $prefix ) ) {
			$prefix = $path;
		}
		//		$prefix = $this->modx->getOption( 'prefix', $this->config, $prefix );
		//		$f = $this->config[ 'path' ][ 'snippet' ] . $prefix . strtolower( $name ) . '.php';
		$f = $this->config[ 'path' ][ $path ] . $prefix . '.' . $name . '.php';
		if( file_exists( $f ) ) {
			$o = file_get_contents( $f );
			//			$id = hexdec( (int)sprintf( '%u', crc32( $name ) ) );
			$id = (int)sprintf( '%u', crc32( $name ) );
			$snippet = $this->modx->newObject( 'modSnippet' );
			$snippet->set( 'name', $name );
			$snippet->set( 'id', $id );
			$snippet->setCacheable( $cacheable );
			$snippet->xpdo->cacheManager = $snippet->xpdo->getCacheManager('cache.xPDOMemCache');
			// clean cache
			if( !$cacheable ) {
				$cache_key = $snippet->getScriptCacheKey();
				$snippet->xpdo->cacheManager->delete( $cache_key, array( 'cache_ext' => '.cache.php' ) );
				$snippet->xpdo->cacheManager->delete( $cache_key, array( 'cache_ext' => '.include.cache.php' ) );
				$snippet->xpdo->cacheManager->refresh( array( 'scripts'   => array( 'elements' => array( 'modsnippet' => array( $id ) ) ) ) );
				//				$snippet->xpdo->cacheManager->refresh( array( 'includes'  => array( 'elements' => array( 'modsnippet' => array( $id ) ) ) ) );
				//				$snippet->xpdo->cacheManager->refresh( array( 'scripts' => array() ) );
				//				$snippet->xpdo->cacheManager->refresh( array( 'includes' => array() ) );
				//				$snippet->xpdo->cacheManager->refresh();
			}
			$snippet->setContent( $o );
			$this->modx->log( modX::LOG_LEVEL_DEBUG, "GeneralX: load $path: '$name' (file: $f)" );
		}
		else {
			$this->modx->log( modX::LOG_LEVEL_ERROR, "GeneralX: Could not load $path: '$name' (file: $f)!" );
		}
		return( $snippet );
	}
	/**
	 * Gets a Chunk and caches it; also falls back to file-based templates
	 * for easier debugging.
	 *
	 * Will always use the file-based chunk if $debug is set to true.
	 *
	 * @access public
	 * @param string $name The name of the Chunk
	 * @param array $properties The properties for the Chunk
	 * @return string The processed content of the Chunk
	 */
	public function getChunk( $name, $properties = array() ) {
		$chunk = null;
		if( !isset( $this->_chunk[ $name ] ) ) {
			if( !$this->modx->getOption( 'gx.debug', null, false ) ) {
				$chunk = $this->modx->getObject( 'modChunk', array( 'name' => $name ) );
			}
			if( empty($chunk) ) {
				$chunk = $this->_loadChunk( $name );
				if( $chunk == $name ) return $name;
			}
			$this->chunk[ $name ] = $chunk->getContent();
		}
		else {
			$o = $this->chunk[ $name ];
			$chunk = $this->modx->newObject( 'modChunk' );
			$chunk->setContent( $o );
		}
		$chunk->setCacheable( false );
		return( $chunk->process( $properties ) );
	}
	/**
	 * Returns a modChunk object from a template file.
	 *
	 * @access private
	 * @param string $name The name of the Chunk. Will parse to name.chunk.tpl
	 * @param string $suffix The suffix to postfix the chunk with
	 * @return modChunk/boolean Returns the modChunk object if found, otherwise
	 * false.
	 */
	private function _loadChunk( $name, $suffix = '.chunk.tpl' ) {
		$chunk = $name;
		$suffix = $this->modx->getOption( 'suffix', $this->config, $suffix );
		$f = $this->config[ 'path' ][ 'chunk' ] . strtolower( $name ) . $suffix;
		if( file_exists( $f ) ) {
			$o = file_get_contents( $f );
			$chunk = $this->modx->newObject( 'modChunk' );
			$chunk->set( 'name', $name );
			$chunk->setContent( $o );
		}
		return $chunk;
	}
	/**
	 * Register GeneralX CSS to be injected inside the HEAD tag of a resource.
	 *
	 * @param string $src    The CSS to be injected before the closing HEAD tag in an HTML response.
	 */
	public function regClientCSS( $src ) {
		$this->modx->regClientCSS( $this->config[ 'url' ][ 'css' ] . $src );
	}
	/**
	 * Register GeneralX JavaScript to be injected inside the HEAD tag of a resource.
	 *
	 * @param string  $src          The JavaScript to be injected before the closing HEAD tag of an HTML response.
	 * @param boolean $plaintext    Optional param to treat the $src as plaintext rather than assuming it is JavaScript.
	 */
	public function regClientStartupScriptConnector( $src, $plaintext = false ) {
		$this->modx->regClientStartupScript( $this->config[ 'url' ][ 'connector' ] . $src, $plaintext );
	}
	/**
	 * Register GeneralX JavaScript to be injected inside the HEAD tag of a resource.
	 *
	 * @param string  $src          The JavaScript to be injected before the closing HEAD tag of an HTML response.
	 * @param boolean $plaintext    Optional param to treat the $src as plaintext rather than assuming it is JavaScript.
	 */
	public function regClientStartupScript( $src, $plaintext = false ) {
		$this->modx->regClientStartupScript( $this->config[ 'url' ][ 'js' ] . $src, $plaintext );
	}
	/**
	 * Register GeneralX JavaScript to be injected before the closing BODY tag.
	 *
	 * @param string  $src          The JavaScript to be injected before the closing BODY tag in an HTML response.
	 * @param boolean $plaintext    Optional param to treat the $src as plaintext rather than assuming it is JavaScript.
	 */
	public function regClientScript($src, $plaintext= false) {
		$this->modx->regClientScript( $this->config[ 'url' ][ 'js' ] . $src, $plaintext );
	}
	/**
	 * Register GeneralX HTML to be injected before the closing HEAD tag.
	 *
	 * @param string $html          The HTML to be injected.
	 */
	public function regClientStartupHTMLBlock( $html ) {
		return( $this->modx->regClientStartupScript( $html, true ) );
	}
	/**
	 * Register HTML to be injected before the closing BODY tag.
	 *
	 * @param string $html The HTML to be injected.
	 */
	public function regClientHTMLBlock( $html ) {
		return( $this->modx->regClientScript( $html, true ) );
	}
}
}

/**
 * GeneralX utility class
 *
 * @copyright Copyright (C) 2010, by LArV <larv.develop@gmail.com>
 * @author    LAtV <larv.develop@gmail.com>
 *
 * @package GeneralX
 * @subpackage util
 */
if( !class_exists( 'gxUtil' ) ) {
class gxUtil {
	/**
	 * Strip whitespace from the beginning and end of string, array...
	 *
	 * @param mix $a          Mixed input string, array - that will be trimmed.
	 * @param mix $charlist   Simply list all characters that you want to be stripped.
	 */
	static protected function _trim( &$v, $k, $charlist = '' ) {
		return( gxUtil::trim( $v, $charlist ) );
	}
	/**
	 * Strip whitespace from the beginning and end of string, array...
	 *
	 * @param mix $a          Mixed input string, array - that will be trimmed.
	 * @param mix $charlist   Simply list all characters that you want to be stripped.
	 */
	static public function trim( &$a, $charlist = '' ) {
		$charlist = ( empty( $charlist ) ? " \t\n\r\0\0x0b": $charlist );
		if( is_array( $a ) ) {
			array_walk_recursive( $a, 'gxUtil::_trim', $charlist );
			return( true );
		}
		elseif( is_string( $a ) ) {
			$a = trim( $a, $charlist );
			return( true );
		}
		return( false );
	}
}
}
