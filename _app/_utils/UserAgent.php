<?php
/**
 * UserAgentを判別するクラス
 * @access public
 * @author ISHIGURO Kei
 * @version 1.00
 * @since 2012/09/21
 */
class UserAgent
{
	/** @var object		self instance. */
	private static $instance = null;

	/**
	 * コンストラクタ
	 * @access private
	 * @param  void
	 * @return object DB Connection
	 */
	private function __construct(){}

	/**
	 * UserAgentクラスのインスタンスを取得します。
	 * @access public
	 * @param  void
	 * @return object このクラスのインスタンス
	 */
	public static function get()
	{
		if( is_null( self::$instance ) )
		{
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * ブラウザがスマートフォン用かどうかを判定します。
	 * @access public
	 * @return bool true: スマートフォンブラウザ false: PCブラウザ
	 */
	public function is_smart_phone()
	{
		$ua = $_SERVER['HTTP_USER_AGENT'];
		
		if( (strpos($ua,'iPhone')==true) || (strpos($ua,'iPod')==true) || (strpos($ua,'Android')==true) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
?>
