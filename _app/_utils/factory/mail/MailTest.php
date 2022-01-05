<?php
/**
 * 
 * @access public
 * @author
 * @copyright License Academy Co.Ltd
 * @version 1.00
 * @since 2018/06/20
 */
class MailTest extends MailSend
{
	/** @var string インスタンス指定子 */
	static $ORDER = "Test";

 /**
	* コンストラクタ
	* @access public
	* @param  void
	* @return void
	*/
	public function __construct()
	{
		$this->title = "MyMVC TEST Mail";
	}


	/**
	 * メールを送信
	 * @see MailSend::send_emails()
	 */
	public function send_emails( $mailTo, $requests )
	{
		$this->send_email_by_smtp
		(
			$mailTo,
			MAIL_FROM,
			MAIL_FROM_ADDRESS,
			$this->title,
			$this->get_body( $requests )
		);
	}


	/**
	 * メール本文を取得
	 * @param array $requests
	 */
	private function get_body( $requests )
	{
		$body = <<< HERE
Hi, Dear {$requests['name_last']}{$requests['name_first']} 

Your Phone Number is {$requests['tel']}

HERE;
		return $body;
	}
}
?>
