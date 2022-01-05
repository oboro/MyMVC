<?php
/**
 * コントローラ
 * @access public
 * @author K funakubo
 * @copyright License Academy Co.Ltd
 * @version 1.00
 * @since 2018/08/13
 */
class DefaultController extends Controller
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
		$this->model = new DefaultModel();
	}

	public function index()
	{
		require_once( $this->layout );
	}

	/**
	 * 設定されているDBのテーブル一覧を表示します。
	 * 
	 */
	public function db()
	{
	    
	    $results = $this->model->get_tables();
	    
	    require_once( $this->layout );
	}

	/**
	 * フォームに入力されたアドレスにメールを送信します。
	 */
	public function mail()
	{
	    require_once '../_utils/token.php';
	    $this->token = new Token();
	    
	    if( $this->_request['button'] == 'confirm' ){
	        $this->token->checkToken( $this->_request['token'], '/default/err400');
	        $this->model->validate( 'default_form', $this->_request );
	        
	        if( $this->model->invalid_count( $this->_request ))
	        {
	            
	        }else {
	            $this->contents_for_layout = "../_views/default/mail_confirm.tpl";
	        }
	    }elseif( $this->_request['button'] == 'commit' )
	    {
	        $this->token->checkToken( $this->_request['token'], '/default/err400');
	        $this->model->validate( 'default_form', $this->_request );
	        
	        if( $this->model->invalid_count( $this->_request ))
	        {
	            
	        }else {
	            
	            require_once '../_utils/factory/MailFactory.php';
	            MailFactory::get()->create( MailTest::$ORDER )->send_emails( $this->_request['email'], $this->_request );
	            
	            $this->contents_for_layout = "../_views/default/mail_complete.tpl";
	        }
	    }
	    $this->token->setToken();
	    require_once( $this->layout );
	}
	
	/**
	 * 画面遷移が不正な場合
	 */
	public function err400()
	{
	    require_once( $this->layout );
	    exit;
	}
}

?>