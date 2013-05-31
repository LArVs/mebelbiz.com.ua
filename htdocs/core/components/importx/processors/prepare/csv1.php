<?php

require_once ('prepare.class.php');
class prepareCsv extends prepareImport {
	function process() {
		$lines = explode("\n",$this->data);
		if (count($lines) <= 1) {
			$this->importx->errors[] = $this->modx->lexicon('importx.err.notenoughdata');
			return false;
		}
		// cleanup lines
		$index_for_clear = array();
		foreach( $lines as $i => $l ) {
			$l = trim( $l );
			// empty line
			if( empty( $l ) ) { $lines[ $i ] = ''; continue; }
			// comment line
			if( strpos( $l, '#' ) === 0 ) { $lines[ $i ] = ''; continue; }
		}

		// find head line
		foreach( $lines as $i => $l ) {
			if( !empty( $l ) ) {
				$headingline = $l;
				$lines[ $i ] = '';
				break;
			}
		}
		if (substr($headingline,-strlen($this->importx->config['separator'])) == $this->importx->config['separator']) {
			$headingline = substr($headingline,0,-strlen($this->importx->config['separator']));
		}
		$headings = explode($this->importx->config['separator'],$headingline);
		$headings = array_map( function( $f ) { return( trim( $f ) ); }, $headings );
		$headingcount = count($headings);
		$tvs = false;

		// Validate the headers...
		$fields = array('id', 'type', 'contentType', 'pagetitle', 'longtitle',	'alias', 'description', 'link_attributes', 'published', 'pub_date', 'unpub_date', 'parent', 'isfolder', 'introtext', 'content', 'richtext', 'template', 'menuindex', 'searchable', 'cacheable', 'createdby', 'createdon', 'editedby', 'editedon', 'deleted', 'deletedon', 'deletedby', 'publishedon', 'publishedby', 'menutitle', 'donthit', 'haskeywords', 'hasmetatags', 'privateweb', 'privatemgr', 'content_dispo', 'hidemenu', 'class_key', 'context_key', 'content_type', 'uri', 'uri_override');
		foreach ($headings as $i => $h) {
			//$h = trim($h);
			if (!in_array($h,$fields)) {
				if (substr($h,0,2) != 'tv') {
					$this->importx->errors[] = $this->modx->lexicon('importx.err.invalidfield',array('field' => $h));
				}
				else {
					if (intval(substr($h,2)) <= 0) {
						$this->importx->errors[] = $this->modx->lexicon('importx.err.intexpected',array('field' => $h, 'int' => substr($h,2)));
					} else {
						$tvo = $this->modx->getObject('modTemplateVar',substr($h,2));
						if (!$tvo) {
							$this->importx->errors[] = $this->modx->lexicon('importx.err.tvdoesnotexist', array('field' => $h, 'id' => substr($h,2)));
						} else {
							$tvs = true;
						}
					}
				}
			//} else {
				//$headings[ $i ] = $h;
			}
		}

		if (count($this->importx->errors) > 0) {
			return false;
		}

		$this->importx->log('info', $this->modx->lexicon('importx.log.preimportclean'));
		//sleep(1);

		$err = array();
		$input_lines = $lines;
		$lines = array();
		foreach ($input_lines as $line => $lineval) {
			if( empty( $lineval ) ) { continue; }
			if (substr($lineval,-strlen($this->importx->config['separator'])) == $this->importx->config['separator']) {
				$lineval = substr($lineval,0,-strlen($this->importx->config['separator']));
			}

			$curline = explode($this->importx->config['separator'],$lineval);
			$curline = array_map( function( $f ) { return( trim( $f ) ); }, $curline );

			if ($headingcount != count($curline)) {
				$err[] = $line;
			} else {
				$line_res = array_combine($headings,$curline);
				if( !isset( $line_res[ 'context_key' ] )  ) { $line_res[ 'context_key' ] = $this->importx->defaults[ 'context_key' ] ; }
				if( !isset( $line_res[ 'parent'      ] )  ) { $line_res[ 'parent'      ] = $this->importx->defaults[ 'parent'      ] ; }
				if( !isset( $line_res[ 'published'   ] )  ) { $line_res[ 'published'   ] = $this->importx->defaults[ 'published'   ] ; }
				if( !isset( $line_res[ 'searchable'  ] )  ) { $line_res[ 'searchable'  ] = $this->importx->defaults[ 'searchable'  ] ; }
				if( !isset( $line_res[ 'hidemenu'    ] )  ) { $line_res[ 'hidemenu'    ] = $this->importx->defaults[ 'hidemenu'    ] ; }
				if( $tvs ) { $line_res[ 'tvs' ] = true; }
				$lines[ $line ] = $line_res;
			}
			//$this->importx->errors[] = var_export( $line_res,true );
			//return false;
		}
		if (count($err) > 0) {
			$this->importx->errors[] = $this->modx->lexicon('importx.err.elementmismatch',array('line' => implode(', ',$err)));
			return false;
		}
		return $lines;
	}
}

/* Return the class name */
return 'prepareCsv';
