<?php
/**
 * 学科紹介　コントローラ
 * @access public
 * @author
 * @version 1.00
 * @since 2018/08/13
 */
class ErrorController extends Controller
{
	/**
	 * コンストラクタ
	 * @access public
	 * @param  string コントローラ名
	 * @param  string アクション名
	 * @return void
	 */
	public function __construct( $controller_name = "", $action_name = "" )
	{
		parent::__construct( $controller_name, $action_name );
	}

	public function Err404()
	{
		require_once( $this->layout );
	}
}
?>
