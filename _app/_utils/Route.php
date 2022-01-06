<?php
/**
 * ルーティング設定クラス
 * @access public
 * @author
 * @version 1.00
 * @since 2012/07/24
 */
class Route
{
	/** @var object		self instance */
	private static $instance = null;

	/**
	 * コンストラクタ
	 * @access public
	 * @param  void
	 * @return void
	 */
	private function __construct(){
		$this->mappings = array();
		$this->prefixes = array();
	}

	/**
	 * Routeクラスのインスタンスを取得します。
	 * @access public
	 * @param  void
	 * @return object このクラスのインスタンス
	 */
	public static function get()
	{
		if( is_null( self::$instance ) ) :
			self::$instance = new self;
		endif;
		return self::$instance;
	}

	/**
	 * Routingを指定します。
	 * @access public
	 * @param  string	(e.g.,) /:controller/:action などのURLとController::action()のマッピング
	 * @param  array
	 * @return void
	 */
	public function map( $path, $params = array() )
	{
		$path = join($this->prefixes , '/' ) . $path;
		$this->mappings[ $path ] = $params;
	}

	/**
	 * Routingに名前空間を設定します。
	 * @access public
	 * @param string-(e.g.,) /namespace などのPATHのprefix
	 * @param function 無名関数 名前空間内でのマッピング
	 * @retunr void
	 */
	public function _namespace( $prefix, $itelation )
	{
		array_push( $this->prefixes, $prefix );
		$itelation( $this );
		array_shift( $this->prefixes );
	}

	/**
	 * URLからRoutingにあった形でパスを抜き出します。
	 * @access public
	 * @param  string	(e.g.,) /contoroller_action などのURLパス
	 * @return void
	 */
	public function match( $request_path )
	{
		$request_path=h( preg_replace( '/\s|　/', '',$request_path));

		foreach( $this->mappings as $path => $params) :
			$matches = array();
			if( preg_match( $this->path2regexp( $path, $params ), $request_path, $matches ) ) :
				$request_params = $params;
				foreach( $this->path2keys( $path ) as $i => $key ) :
					$request_params[$key] = $matches[$i+1];
				endforeach;

				return $request_params;
			endif;
		endforeach;
		throw new Exception( "No route match." );
	}

	/**
	 * /: /にあった形でRoutingを抜き出します。
	 * @access public
	 * @param  string	(e.g.,) /contoroller_action などのURLパス
	 * @param  array
	 * @return void
	 */
	public function path2regexp( $path, $params = array() )
	{
		foreach( $params as $key => $val ) :
			$path = preg_replace( "/:".$key."/", '('.$val.')', $path );
		endforeach;
		$path = preg_replace( "/:([^\/]+)/", "([^/]+)", $path );
		$path = str_replace( "/", "\/", $path );
		$path = "/^".$path."$/";
		return $path;
	}

	/**
	 * /: /にあった形でRoutingを抜き出します。
	 * @access public
	 * @param  string	(e.g.,) /contoroller_action などのURLパス
	 * @return string	正規表現にマッチした文字列
	 */
	public function path2keys( $path )
	{
		$matches = array();
		preg_match_all( "/:([^\/]+)/", $path, $matches );
		return $matches[1];
	}
}
?>
