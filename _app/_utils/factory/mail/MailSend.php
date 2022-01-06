<?php
require_once( __DIR__ . '/../../vendor/autoload.php' );

/**
 * メール送信クラスの抽象クラス
 * @access public
 * @author
 * @version 2.00
 * @since 2018/06/21
 */
abstract class MailSend
{
	/** @var string 件名 */
	protected $title = "";

	/** @var string 本文 */
	protected $body = "";

	/** @var string 送信者 */
	protected $from = "";

	/** @var int メール送信件数 */
	protected $sent = 0;

	/** @var int サーバタイムアウト */
	protected $limit = 0;


	/**
	 * SMTPでメールを送信する。
	 * @access public
	 * @param  string	$to			あて先アドレス
	 * @param  string	$from		送信者名
	 * @param  string	$address	送信者アドレス <someaddress@somedomain>
	 * @param  string	$subject	件名
	 * @param  string	$mail_body	本文
	 * @return void
	 */
	public function send_email_by_smtp( $to, $from, $address, $subject, $mail_body )
	{
	    // 日本語を使う場合の事前処理　japanese.rst
        Swift::init(function () {
            Swift_DependencyContainer::getInstance()
                ->register('mime.qpheaderencoder')
                ->asAliasOf('mime.base64headerencoder');
	       Swift_Preferences::getInstance()->setCharset('iso-2022-jp');
        });

        //
        $transport = (new Swift_SmtpTransport( MAIL_HOST, MAIL_PORT, 'ssl' ) )
            ->setUsername( MAIL_USER )
            ->setPassword( MAIL_PASSWORD );

		$mailer = new Swift_Mailer($transport);

		$message = (new Swift_Message())
		    ->setSubject( $subject )
            ->setFrom([$address => $from])
            ->setTo([ $to => ''])
            ->setBody( $mail_body );

		// メールを送信する
		if ($mailer->send($message)) {
		    //echo 'メールを送信しました。';
		}

	}

}
?>
