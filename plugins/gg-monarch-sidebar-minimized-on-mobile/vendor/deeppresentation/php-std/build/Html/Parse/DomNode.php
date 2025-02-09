<?php namespace MSMoMDP\Std\Html\Parse;

define( 'HDOM_TYPE_ELEMENT', 1 );
define( 'HDOM_TYPE_COMMENT', 2 );
define( 'HDOM_TYPE_TEXT', 3 );
define( 'HDOM_TYPE_ENDTAG', 4 );
define( 'HDOM_TYPE_ROOT', 5 );
define( 'HDOM_TYPE_UNKNOWN', 6 );
define( 'HDOM_QUOTE_DOUBLE', 0 );
define( 'HDOM_QUOTE_SINGLE', 1 );
define( 'HDOM_QUOTE_NO', 3 );
define( 'HDOM_INFO_BEGIN', 0 );
define( 'HDOM_INFO_END', 1 );
define( 'HDOM_INFO_QUOTE', 2 );
define( 'HDOM_INFO_SPACE', 3 );
define( 'HDOM_INFO_TEXT', 4 );
define( 'HDOM_INFO_INNER', 5 );
define( 'HDOM_INFO_OUTER', 6 );
define( 'HDOM_INFO_ENDSPACE', 7 );

// simple html dom node
// -----------------------------------------------------------------------------
class DomNode {
	public $nodetype = HDOM_TYPE_TEXT;
	public $tag      = 'text';
	public $attr     = array();
	public $children = array();
	public $nodes    = array();
	public $parent   = null;
	public $_        = array();
	private $dom     = null;

	function __construct( $dom ) {
		$this->dom    = $dom;
		$dom->nodes[] = &$this;
	}

	function __destruct() {
		$this->clear();
	}

	function __toString() {
		return $this->outertext();
	}

	// clean up memory due to php5 circular references memory leak...
	function clear() {
		$this->dom      = null;
		$this->nodes    = null;
		$this->parent   = null;
		$this->children = null;
	}

	// returns the parent of node
	function parent() {
		return $this->parent;
	}

	// returns children of node
	function children( $idx = -1 ) {
		if ( $idx === -1 ) {
			return $this->children;
		}
		if ( isset( $this->children[ $idx ] ) ) {
			return $this->children[ $idx ];
		}
		return null;
	}

	// returns the first child of node
	function first_child() {
		if ( count( $this->children ) > 0 ) {
			return $this->children[0];
		}
		return null;
	}

	// returns the last child of node
	function last_child() {
		if ( ( $count = count( $this->children ) ) > 0 ) {
			return $this->children[ $count - 1 ];
		}
		return null;
	}

	// returns the next sibling of node
	function next_sibling() {
		if ( $this->parent === null ) {
			return null;
		}
		$idx   = 0;
		$count = count( $this->parent->children );
		while ( $idx < $count && $this !== $this->parent->children[ $idx ] ) {
			++$idx;
		}
		if ( ++$idx >= $count ) {
			return null;
		}
		return $this->parent->children[ $idx ];
	}

	// returns the previous sibling of node
	function prev_sibling() {
		if ( $this->parent === null ) {
			return null;
		}
		$idx   = 0;
		$count = count( $this->parent->children );
		while ( $idx < $count && $this !== $this->parent->children[ $idx ] ) {
			++$idx;
		}
		if ( --$idx < 0 ) {
			return null;
		}
		return $this->parent->children[ $idx ];
	}

	// get dom node's inner html
	function innertext() {
		if ( isset( $this->_[ HDOM_INFO_INNER ] ) ) {
			return $this->_[ HDOM_INFO_INNER ];
		}
		if ( isset( $this->_[ HDOM_INFO_TEXT ] ) ) {
			return $this->dom->restore_noise( $this->_[ HDOM_INFO_TEXT ] );
		}

		$ret = '';
		foreach ( $this->nodes as $n ) {
			$ret .= $n->outertext();
		}
		return $ret;
	}

	// get dom node's outer text (with tag)
	function outertext() {
		if ( $this->tag === 'root' ) {
			return $this->innertext();
		}

		// trigger callback
		if ( $this->dom->callback !== null ) {
			call_user_func_array( $this->dom->callback, array( $this ) );
		}

		if ( isset( $this->_[ HDOM_INFO_OUTER ] ) ) {
			return $this->_[ HDOM_INFO_OUTER ];
		}
		if ( isset( $this->_[ HDOM_INFO_TEXT ] ) ) {
			return $this->dom->restore_noise( $this->_[ HDOM_INFO_TEXT ] );
		}

		// render begin tag
		$ret = $this->dom->nodes[ $this->_[ HDOM_INFO_BEGIN ] ]->makeup();

		// render inner text
		if ( isset( $this->_[ HDOM_INFO_INNER ] ) ) {
			$ret .= $this->_[ HDOM_INFO_INNER ];
		} else {
			foreach ( $this->nodes as $n ) {
				$ret .= $n->outertext();
			}
		}

		// render end tag
		if ( isset( $this->_[ HDOM_INFO_END ] ) && $this->_[ HDOM_INFO_END ] != 0 ) {
			$ret .= '</' . $this->tag . '>';
		}
		return $ret;
	}

	// get dom node's plain text
	function plaintext() {
		if ( isset( $this->_[ HDOM_INFO_INNER ] ) ) {
			return $this->_[ HDOM_INFO_INNER ];
		}
		switch ( $this->nodetype ) {
			case HDOM_TYPE_TEXT:
				return $this->dom->restore_noise( $this->_[ HDOM_INFO_TEXT ] );
			case HDOM_TYPE_COMMENT:
				return '';
			case HDOM_TYPE_UNKNOWN:
				return '';
		}
		if ( strcasecmp( $this->tag, 'script' ) === 0 ) {
			return '';
		}
		if ( strcasecmp( $this->tag, 'style' ) === 0 ) {
			return '';
		}

		$ret = '';
		foreach ( $this->nodes as $n ) {
			$ret .= $n->plaintext();
		}
		return $ret;
	}

	// build node's text with tag
	function makeup() {
		// text, comment, unknown
		if ( isset( $this->_[ HDOM_INFO_TEXT ] ) ) {
			return $this->dom->restore_noise( $this->_[ HDOM_INFO_TEXT ] );
		}

		$ret = '<' . $this->tag;
		$i   = -1;

		foreach ( $this->attr as $key => $val ) {
			++$i;

			// skip removed attribute
			if ( $val === null || $val === false ) {
				continue;
			}

			$ret .= $this->_[ HDOM_INFO_SPACE ][ $i ][0];
			//no value attr: nowrap, checked selected...
			if ( $val === true ) {
				$ret .= $key;
			} else {
				switch ( $this->_[ HDOM_INFO_QUOTE ][ $i ] ) {
					case HDOM_QUOTE_DOUBLE:
						$quote = '"';
						break;
					case HDOM_QUOTE_SINGLE:
						$quote = '\'';
						break;
					default:
						$quote = '';
				}
				$ret .= $key . $this->_[ HDOM_INFO_SPACE ][ $i ][1] . '=' . $this->_[ HDOM_INFO_SPACE ][ $i ][2] . $quote . $val . $quote;
			}
		}
		$ret = $this->dom->restore_noise( $ret );
		return $ret . $this->_[ HDOM_INFO_ENDSPACE ] . '>';
	}

	// find elements by css selector
	function find( $selector, $idx = null ) {
		$selectors = $this->parse_selector( $selector );

		if ( ( $count = count( $selectors ) ) === 0 ) {
			return array();
		}
		$found_keys = array();

		// find each selector
		for ( $c = 0; $c < $count; ++$c ) {
			if ( ( $levle = count( $selectors[0] ) ) === 0 ) {
				return array();
			}
			if ( ! isset( $this->_[ HDOM_INFO_BEGIN ] ) ) {
				return array();
			}

			$head = array( $this->_[ HDOM_INFO_BEGIN ] => 1 );

			// handle descendant selectors, no recursive!
			for ( $l = 0; $l < $levle; ++$l ) {
				$ret = array();
				foreach ( $head as $k => $v ) {
					$n = ( $k === -1 ) ? $this->dom->root : $this->dom->nodes[ $k ];
					$n->seek( $selectors[ $c ][ $l ], $ret );
				}
				$head = $ret;
			}

			foreach ( $head as $k => $v ) {
				if ( ! isset( $found_keys[ $k ] ) ) {
					$found_keys[ $k ] = 1;
				}
			}
		}

		// sort keys
		ksort( $found_keys );

		$found = array();
		foreach ( $found_keys as $k => $v ) {
			$found[] = $this->dom->nodes[ $k ];
		}

		// return nth-element or array
		if ( is_null( $idx ) ) {
			return $found;
		} elseif ( $idx < 0 ) {
			$idx = count( $found ) + $idx;
		}
		return ( isset( $found[ $idx ] ) ) ? $found[ $idx ] : null;
	}

	// seek for given conditions
	protected function seek( $selector, &$ret ) {
		list($tag, $key, $val, $exp, $no_key) = $selector;

		$end = ( ! empty( $this->_[ HDOM_INFO_END ] ) ) ? $this->_[ HDOM_INFO_END ] : 0;
		if ( $end == 0 ) {
			$parent = $this->parent;
			while ( ! isset( $parent->_[ HDOM_INFO_END ] ) && $parent !== null ) {
				$end   -= 1;
				$parent = $parent->parent;
			}
			$end += $parent->_[ HDOM_INFO_END ];
		}

		for ( $i = $this->_[ HDOM_INFO_BEGIN ] + 1; $i < $end; ++$i ) {
			$node = $this->dom->nodes[ $i ];
			$pass = true;

			if ( $tag === '*' && ! $key ) {
				if ( in_array( $node, $this->children, true ) ) {
					$ret[ $i ] = 1;
				}
				continue;
			}

			// compare tag
			if ( $tag && $tag != $node->tag && $tag !== '*' ) {
				$pass = false;}
			// compare key
			if ( $pass && $key ) {
				if ( $no_key ) {
					if ( isset( $node->attr[ $key ] ) ) {
						$pass = false;
					}
				} elseif ( ! isset( $node->attr[ $key ] ) ) {
					$pass = false;
				}
			}
			// compare value
			if ( $pass && $key && $val && $val !== '*' ) {
				$check = $this->match( $exp, $val, $node->attr[ $key ] );
				// handle multiple class
				if ( ! $check && strcasecmp( $key, 'class' ) === 0 ) {
					foreach ( explode( ' ', $node->attr[ $key ] ) as $k ) {
						$check = $this->match( $exp, $val, $k );
						if ( $check ) {
							break;
						}
					}
				}
				if ( ! $check ) {
					$pass = false;
				}
			}
			if ( $pass ) {
				$ret[ $i ] = 1;
			}
			unset( $node );
		}
	}

	protected function match( $exp, $pattern, $value ) {
		$check = true;
		switch ( $exp ) {
			case '=':
				$check = ( $value === $pattern ) ? true : false;
				break;
			case '!=':
				$check = ( $value !== $pattern ) ? true : false;
				break;
			case '^=':
				$check = ( preg_match( '/^' . preg_quote( $pattern, '/' ) . '/', $value ) ) ? true : false;
				break;
			case '$=':
				$check = ( preg_match( '/' . preg_quote( $pattern, '/' ) . '$/', $value ) ) ? true : false;
				break;
			case '*=':
				$check = ( preg_match( '/' . preg_quote( $pattern, '/' ) . '/i', $value ) ) ? true : false;
				break;
		}
		return $check;
	}

	protected function parse_selector( $selector_string ) {
		// pattern of CSS selectors, modified from mootools
		$pattern = "/([\w-:\*]*)(?:\#([\w-]+)|\.([\w-]+))?(?:\[(!?[\w-]+)(?:([!*^$]?=)[\"']?(.*?)[\"']?)?\])?([, ]+)/is";
		preg_match_all( $pattern, trim( $selector_string ) . ' ', $matches, PREG_SET_ORDER );
		$selectors = array();
		$result    = array();

		foreach ( $matches as $m ) {
			if ( trim( $m[0] ) === '' ) {
				continue;
			}

			list($tag, $key, $val, $exp, $no_key) = array( $m[1], null, null, '=', false );
			if ( ! empty( $m[2] ) ) {
				$key = 'id';
				$val = $m[2];}
			if ( ! empty( $m[3] ) ) {
				$key = 'class';
				$val = $m[3];}
			if ( ! empty( $m[4] ) ) {
				$key = $m[4];}
			if ( ! empty( $m[5] ) ) {
				$exp = $m[5];}
			if ( ! empty( $m[6] ) ) {
				$val = $m[6];}

			// convert to lowercase
			if ( $this->dom->lowercase ) {
				$tag = strtolower( $tag );
				$key = strtolower( $key );}
			//elements that do NOT have the specified attribute
			if ( isset( $key[0] ) && $key[0] === '!' ) {
				$key    = substr( $key, 1 );
				$no_key = true;}

			$result[] = array( $tag, $key, $val, $exp, $no_key );
			if ( trim( $m[7] ) === ',' ) {
				$selectors[] = $result;
				$result      = array();
			}
		}
		if ( count( $result ) > 0 ) {
			$selectors[] = $result;
		}

		return $selectors;
	}

	function __get( $name ) {
		if ( isset( $this->attr[ $name ] ) ) {
			return $this->attr[ $name ];
		}
		switch ( $name ) {
			case 'outertext':
				return $this->outertext();
			case 'innertext':
				return $this->innertext();
			case 'plaintext':
				return $this->plaintext();
			default:
				return array_key_exists( $name, $this->attr );
		}
	}

	function __set( $name, $value ) {
		switch ( $name ) {
			case 'outertext':
				return $this->_[ HDOM_INFO_OUTER ] = $value;
			case 'innertext':
				if ( isset( $this->_[ HDOM_INFO_TEXT ] ) ) {
					return $this->_[ HDOM_INFO_TEXT ] = $value;
				}
				return $this->_[ HDOM_INFO_INNER ] = $value;
		}
		if ( ! isset( $this->attr[ $name ] ) ) {
			$this->_[ HDOM_INFO_SPACE ][] = array( ' ', '', '' );
			$this->_[ HDOM_INFO_QUOTE ][] = HDOM_QUOTE_DOUBLE;
		}
		$this->attr[ $name ] = $value;
	}

	function __isset( $name ) {
		switch ( $name ) {
			case 'outertext':
				return true;
			case 'innertext':
				return true;
			case 'plaintext':
				return true;
		}
		//no value attr: nowrap, checked selected...
		return ( array_key_exists( $name, $this->attr ) ) ? true : isset( $this->attr[ $name ] );
	}

	function __unset( $name ) {
		if ( isset( $this->attr[ $name ] ) ) {
			unset( $this->attr[ $name ] );
		}
	}

	// camel naming conventions
	function getAllAttributes() {
		return $this->attr;}
	function getAttribute( $name ) {
		return $this->__get( $name );}
	function setAttribute( $name, $value ) {
		$this->__set( $name, $value );}
	function hasAttribute( $name ) {
		return $this->__isset( $name );}
	function removeAttribute( $name ) {
		$this->__set( $name, null );}
	function getElementById( $id ) {
		return $this->find( "#$id", 0 );}
	function getElementsById( $id, $idx = null ) {
		return $this->find( "#$id", $idx );}
	function getElementByTagName( $name ) {
		return $this->find( $name, 0 );}
	function getElementsByTagName( $name, $idx = null ) {
		return $this->find( $name, $idx );}
	function parentNode() {
		return $this->parent();}
	function childNodes( $idx = -1 ) {
		return $this->children( $idx );}
	function firstChild() {
		return $this->first_child();}
	function lastChild() {
		return $this->last_child();}
	function nextSibling() {
		return $this->next_sibling();}
	function previousSibling() {
		return $this->prev_sibling();}
}
