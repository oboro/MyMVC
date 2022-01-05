<?php
/**
 * Enumクラス
 * @access public
 * @author ISHIGURO Kei
 * @copyright License Academy Co.Ltd
 * @version 1.00
 * @since 2012/08/03
 */
class Enum
{
	/** @var array	Enumプロパティの配列 */
	protected $values = array();

	/** @var array	Enum要素の配列 */
	static private $enums = array();

/**
 * Enumオブジェクトを生成する。
 * @access public
 * @param  string	モデル名
 * @return void
 */
	static public function __callStatic( $name, $arguments )
	{
		if( is_array( $arguments[0] ) ) :
			# 重複チェック
			if( isset( self::$enums[$name] ) ) :
				trigger_error( print "{$name} is exists..", E_USER_ERROR );
			endif;
			self::$enums[$name] = new Enum( $arguments[0] );
		else :
			if( !isset( self::$enums[ $name ] ) ) :
				trigger_error( "Enum {$name} not found...", E_USER_ERROR );
			endif;
			return self::$enums[ $name ];
		endif;
	}

/**
 * 配列からEnumプロパティを設定する。
 * @access public
 * @param  array	$key=0,1,2,3･･･ 、$p=Enum指定子
 * @return void
 */
	protected function __construct( array $params )
	{
		# Enum値をプロパティへ
		foreach( $params as $key => $p ) :
			if( !is_int( $key ) ) :
				trigger_error( 'Key must be an int.', E_USER_ERROR );
			endif;
			$this->values[ $p ] = $key;
		endforeach;
	}

/**
 * Enum要素を取得する。
 * @access public
 * @param  string	Enum指定子
 * @return void
 */
	public function __get( $name )
	{
		if( !isset( $this->values[ $name ] ) ) :
			# 存在しない要素
			trigger_error( "[{$name}] isn't enum element", E_USER_ERROR );
		endif;
		return $this->values[ $name ];
	}

/**
 * Enum要素を設定する。
 * @access public
 * @param  string	Enum指定子
 * @param  string	値
 * @return void
 */
	public function __set( $name, $value )
	{
		trigger_error( "Enum can't set.", E_USER_ERROR );
	}
}
?>