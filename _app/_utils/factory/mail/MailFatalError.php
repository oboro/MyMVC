<?php
/**
 * FATAL ERROR発生時にエラーメールをサイト担当者に送付
 * @access public
 * @author
 * @copyright License Academy Co.Ltd
 * @version 1.00
 * @since 2018/06/20
 */
class MailFatalError extends MailSend
{
    /** @var string インスタンス指定子 */
    static $ORDER = "FatalError";
    
    /**
     * コンストラクタ
     * @access public
     * @param  void
     * @return void
     */
    public function __construct()
    {
        $this->title = ERROR_NOTICE::SUBJECT. '['. date('Y/m/d H:i:s') . ']';
        
    }
    
    
    /**
     * メールを送信
     * @see MailSend::send_emails()
     */
    public function send_emails( $errors )
    {
        
        $this->send_email_by_smtp
        (
            ERROR_NOTICE::RECEIVER,
            ERROR_NOTICE::FROM,
            ERROR_NOTICE::SENDER,
            $this->title,
            $this->get_body($errors)
            );
    }
    
    
    /**
     * メール本文を取得
     * @param array $requests
     */
    private function get_body( $errors )
    {
				$body = <<<HERE
ERROR TYPE : {$errors['type']}

ERROR MESSAGE : {$errors['message']}

ERROR FILE : {$errors['file']}

ERROR LINE : {$errors['line']}
HERE;
        return $body;
    }
}
?>
