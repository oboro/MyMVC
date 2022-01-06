<?php
/**
 * アプリケーション起動クラス
 * @access public
 * @author ISHIGURO Kei
 * @version 1.00
 * @since 2012/07/23
 */
class Bootstrap
{
	/** @var string	コントローラ名 */
	private $controller_name;

	/** @var string	アクション名 */
	private $action_name;

	/** @var object	Rountクラス */
	private $route;

	/**
	 * コンストラクタ
	 * @access public
	 * @param  void
	 * @return void
	 */
	public function __construct()
	{
		$this->route = Route::get();
		# URLとcontoroller,action,idのマッピングを設定します。
		$this->route->map(':controller');
		$this->route->map(':controller/');
		$this->route->map(':controller/:action');
		$this->route->map(':controller/:action/');
		$this->route->map(':controller/:action/:id');
		$this->route->map(':controller/:action/:id/');
		$this->route->map(':controller/:action/:id/:id2');
		$this->route->map(':controller/:action/:id/:id2/');
		$this->route->map(':controller/:action/:id/:id2/:id3');
		$this->route->map(':controller/:action/:id/:id2/:id3/');
		$this->route->map(':controller/:action/:id/:id2/:id3/:id4');
		$this->route->map(':controller/:action/:id/:id2/:id3/:id4/');
	}

	/**
	 * HTTP::Requestに応じて、対応するコントローラ::メソッド()を呼び出して実行します。
	 * @access public
	 * @param  void
	 * @return void
	 */
	public function dispatch()
	{
		try
		{
			# URLを /controller/action/id の形で指定します。
			if( !isset( $_GET['mode'] ) ) :
				$controller_name = DEFAULT_CONTROLLER;
				$action_name = DEFAULT_ACTION;
			else :
				# URLのパラメータからcontrollerとactionを取得します。
				$params = $this->route->match( $_GET['mode'] );
				if( isset( $params['controller'] ) ) :
					$controller_name = $params['controller'];
				else :
					$controller_name = DEFAULT_CONTROLLER;
				endif;
				if( isset( $params['action'] ) ) :
					$action_name = $params['action'];
				else :
					$action_name = DEFAULT_ACTION;
				endif;

				# controller action 以外の配列を取得（書き方が・・・）
				$params_ = $params;
				array_shift($params_);
				array_shift($params_);
			endif;

			$controller_file = '../_controllers/' . ucwords( $controller_name ) . 'Controller.php';
			if( file_exists( $controller_file ) ) :
				# コントローラ名のファイルが存在する場合は、.phpファイルを読み込みます。
				require_once( $controller_file );
			else :
				$error_body = ucwords( $controller_name ) . 'Controller.php file is not found.';
				throw new Exception( $error_body );
			endif;

			# コントローラのインスタンスを生成します。
			$controller_class_name = $controller_name . 'Controller';
			$controllerInst = new $controller_class_name( $controller_name, $action_name  );

			# HTTP::Requestをコントローラに設定します。
			foreach( (array) $_GET as $key => $value ) :
				$controllerInst->_request[ $key ] = $value;
			endforeach;

			foreach( (array) $_POST as $key => $value ) :
				$controllerInst->_request[ $key ] = $value;
			endforeach;

			# DBへの接続をチェックします。
			$db = Db::get();
			if( !$db->get_is_connect() ) :
				$error_body = ' MySQL connect error. please set up Db.php ';
				throw new Exception( $error_body );
			endif;

			# コントローラのメソッドの有無をチェックします。
			if( method_exists( $controllerInst, $action_name ) && 0 !== strpos( $action_name, '_' ) ) :

				# コントローラのメソッドを実行します。
				// if( $controllerInst->$action_name() === false ) :
				# /:id /:id2パラメータを動的にController::action()に渡します。
				if( !is_array( $params_ ) ) $params_ = array();
				if( call_user_func_array( array( $controllerInst, $action_name ), $params_ ) === false ) :
					$error_body = ucwords( $controller_name ) . '::' . $action_name . '() return false.';
					throw new Exception( $error_body );
				endif;
			else :
				$error_body = ucwords( $controller_name ) . '::' . $action_name . '() method is not found.';
				throw new Exception( $error_body );
			endif;
		}
		catch( Exception $e )
		{
			if( MODE < Enum::SYSTEM()->DEVELOPMENT ) :
				$error_body = '<strong> php ERROR! </strong>' . $e->getMessage();
			else :
				$error_body = ERROR_MESSAGE;
			endif;

			$controller_name = ERROR_CONTROLLER;
			$errorcontroller_name = ERROR_CONTROLLER . 'Controller';
			$erroraction_name = ERROR_ACTION;

			require_once  '../_controllers/'  . ucwords( $errorcontroller_name ) . '.php';
			$errorControllerInst = new $errorcontroller_name();
 			$errorControllerInst->layout = DEFAULT_LAYOUT. EXTENSION;
 			$errorControllerInst->contents_for_layout = '../_views/' . $controller_name . '/' . $erroraction_name . EXTENSION;
			$errorControllerInst->$erroraction_name( $error_body );
		}
	}
}
?>
