<?php
require_once( 'mail/MailSend.php' );
require_once( 'mail/MailFatalError.php' );
require_once( 'mail/MailTest.php' );

/**
 * メール送信クラスのインスタンス生成を行なうクラス
 *
 * 用途に応じてメール送信クラスを生成します。
 *
 * @access public
 * @author
 * @copyright License Academy Co.Ltd
 * @version 1.00
 * @since 2018/06/20
 */
class MailFactory
{
	/** @var object 自分自身のインタンスを保持 */
	private static $instance = null;

	/**
	 * コンストラクタ
	 * @access private
	 * @param  void
	 * @return void
	 */
	private function __construct(){}

	/**
	 * BizMailFactoryクラスのインスタンスを取得する。
	 * @access public
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
	* 用途に応じたMailSendクラスを生成して渡す。
	* @access	public
	* @param	string	$order		送信種別
	* @return	object MailSendクラス
	*/
	public function create( $order = "" )
	{
		$mail_object = $this->create_mail_class( $order );
		return $mail_object;
	}

 /**
	* 用途に応じたMailSenderクラスを生成する。
	* @access	private
	* @param	string	$order			送信種別
	* @param	string	$title			件 名
	* @param	string	$body				メール本文
	* @param	string	$from				送信者
	* @param	int			$regist_id	Member.users.regist_user_agent_highschool_id
	* @return	object	$obj				MailSendクラス
	*/
	private function create_mail_class( $order )
	{
		$obj = "";
		if( $order == MailFatalError::$ORDER )
		{
		    $obj = new MailFatalError();
		}
		elseif( $order == MailTest::$ORDER )
		{
			$obj = new MailTest();
		}
		return $obj;
	}
}
?>