<?php
/**
 * トークン生成
 * @access public
 * @author
 * @version 1.00
 * @since 2016/05/19
 */
class Token
{
	/** @var object		self instance. */
	private static $token = null;


	/**
	 * コンストラクタ
	 * @access private
	 * @param  void
	 * @return object DB Connection
	 */
	public function __construct()
	{
		session_start();
	}

	// トークンをセット
	public function setToken(){
		$this->token = sha1(uniqid(mt_rand(), true));
		$_SESSION['token'] = $this->token;
	}

	// トークンを取得
	public function getToken(){
		return $this->token;
	}

	//トークンのチェック
	function checkToken( $post_data, $redirect_url = "/admin/index.php?error" ){

		if(empty($_SESSION['token']) || ($_SESSION['token'] != $post_data)){
	        header( "location:" . $redirect_url );
	        exit;
	    }else{
	    	return true;
	    }
	}

	//エスケープ
	function h($s){
	    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
	}


}
?>
