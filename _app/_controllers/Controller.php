<?php
/**
 * コントローラ スーパークラス
 * @access public
 * @author K funakubo
 * @copyright License Academy Co.Ltd
 * @version 1.00
 * @since 2018/06/20
 */
class Controller
{
	/** @var string	コントローラ名 */
	public $controller_name = "";

	/** @var string	アクション名 */
	public $action_name = "";

	/** @var string	使用するレイアウト */
	public $layout = null;

	/** @var string	レイアウトに埋め込むView */
	public $contents_for_layout = null;

	/** @var string	レイアウトに埋め込むView **/
	public $ssl_require = false;

	/** @var int	戻るボタン非表示 1:非表示 **/
	public $PREV_DISABLED = 0;

	/**
	 * コンストラクタ
	 * @access public
	 * @param  string コントローラ名
	 * @param  string アクション名
	 * @return void
	 */
	public function __construct( $controller_name = "", $action_name = "" )
	{

		# HTTPSチェック
 		$this->ssl_required();

		# セッションチェック
//		$this->check_session();

		# ログイン処理を行なった場合に戻るURLを設定します。
		$this->back2 = $this->get_back2_url();


		# ページごとに読み込むCSSを切り替えます。
		$this->css_pages = CSS::$DEFINITIONS;

		# ページごとに読み込むdescription、titleを切り替えます。
		$this->descriptions = DESCRIPTION::$DEFINITIONS;

		# 使用する基本レイアウトを切り替えます。
		$this->layout =  '../_views/layout/default' . EXTENSION;
		$this->controller_name = $controller_name;
		$this->action_name = $action_name;
		$this->contents_for_layout = '../_views/' . $controller_name . '/' . $action_name . EXTENSION;
	}

	/**
	 * 画面名称を出力します。
	 * @access public
	 * @param  void
	 * @return string	画面名称
	 */
	public function get_display_title()
	{
		$title = "";
		if( isset( $_SESSION['companyname'] ) ) :
			$title = $_SESSION['companyname'];
		endif;
		return $title;
	}

	/**
	 * セッションIDを再作成する。
	 * @access protected
	 * @param  void
	 * @return void
	 */
	protected function init_session()
	{
		session_save_path( SESSION_PATH );
		session_set_cookie_params( 10800, SESSION_COOKIE_PARAMS );
		session_name( SESSION_NAME );
		session_cache_limiter( 'nocache' );
		session_start();
		session_regenerate_id( TRUE );
	}



	/**
	 * エラー表示をします。
	 * @access public
	 * @param  string エラー文言
	 * @return void
	 */
	public function error( $error_body )
	{
		$this->PREV_DISABLED = 1;
		session_destroy();
	
		require_once($this->layout =  '../_views/layout/error' . EXTENSION);

	}

	/**
	 * エラーメッセージを表示します。
	 * @access public
	 * @param  void
	 * @return void
	 */
	public function disp_error_message()
	{
		$invalid_masseges = array_values( $this->_request['invalid'] );
		for( $i = 0 ; $i < count( $invalid_masseges ) ; $i++ ):
			print_r( "{$invalid_masseges[$i]}<br />" );
		endfor;
	}

	/**
	 * トップ画面に飛ばします。
	 * @access public
	 * @param  void
	 * @return void
	 */
	public function redirect_top()
	{
		# セッションを再初期化します。
		$this->init_session();

		# セッションを破棄します。
		session_destroy();

		header( 'Location: ' . APP_PATH . '/' . DEFAULT_CONTROLLER . '/' . DEFAULT_ACTION );
		exit;
	}

	/**
	 * SSL通信が指定されている場合はHTTPSにリダイレクトします。それ以外はHTTPにリダイレクトします。
	 * @access public
	 * @param  void
	 * @return void
	 */
	public function ssl_required()
	{
		if($this->ssl_require===true):
			if(empty($_SERVER['HTTPS'])):
				header("Location: https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
				exit;
			endif;
		else:
			if(isset($_SERVER['HTTPS'])):
				header("Location: http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
				exit;
			endif;
		endif;
	}

	/**
	 * ログイン処理をした後に戻るURLを設定します。
	 * @access public
	 * @param  void
	 * @return void
	 */
	public function get_back2_url()
	{
		$back2_url = "";
		if( empty($_SERVER['HTTPS']) ):
			$back2_url = urlencode( "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}" );
		else:
			$back2_url = urlencode( "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}" );
		endif;
		return $back2_url;
	}

	/**
	 * ページごとに適切なCSSを読み込みます(今のところPCのみ)。
	 * @access public
	 * @param  void
	 * @return void
	 */
	public function get_css()
	{
	    # ページごとCSS定義配列を [:controller][:action]で走査します。
		if( isset( $this->css_pages[ $this->controller_name ][ $this->action_name ] ) ):
			$css_definitions = $this->css_pages[ $this->controller_name ][ $this->action_name ];
			# 定義配列にあるCSSを読み込みます。
			foreach( $css_definitions as $key => $css_name　):
				echo '<link rel="stylesheet" href="'.APP_DIR . "css/page/{$css_definitions[$key]}".'">';
			endforeach;
		endif;
	}

	/**
	 * ページごとに適切なdescriptionを読み込みます(今のところPCのみ)。
	 * @access public
	 * @param  void
	 * @return string
	 */
	public function get_description()
	{
		$description = "";
		if( isset( $this->descriptions[ "{$this->controller_name}/{$this->action_name}" ][ $this->contents_for_layout ] ) ):
			$description .= '<meta name="description" content="';
			$description .= $this->descriptions[ "{$this->controller_name}/{$this->action_name}" ][ $this->contents_for_layout ]['description'];
			$description .= '">';
			echo $description;
		endif;
	}

	/**
	 * ページごとに適切なtitleを読み込みます(今のところPCのみ)。
	 * @access public
	 * @param  void
	 * @return string
	 */
	public function get_title()
	{
		$title = "";
		if( isset( $this->descriptions[ "{$this->controller_name}/{$this->action_name}" ][ $this->contents_for_layout ] ) ):
			$title .= '<title>';
			$title .= $this->descriptions[ "{$this->controller_name}/{$this->action_name}" ][ $this->contents_for_layout ]['title'];
			$title .= '</title>';
			echo $title;
		endif;
	}

	/**
	 * パンくずを表示します(今のところPCのみ)。
	 * @access public
	 * @param  void
	 * @return string
	 */
	public function get_pankz()
	{
		$pankz = $this->pankz;
		$definitions = array();

		# 先頭から/までを除去します。
		$tpl = preg_replace( '|^.*/|', '', $this->contents_for_layout );
		# .tplを除去します。
		$tpl = preg_replace( '|\.tpl|', '', $tpl );

		#----------------------#
		# パンくずの定義を取得します。　#
		#----------------------#

		# [ controller/action ] を指定してpankzを取得する場合
		if( isset( $pankz[ "{$this->controller_name}/{$this->action_name}" ] ) ):
			$definitions = $pankz[ "{$this->controller_name}/{$this->action_name}" ];

		# [ controller/action/tpl_name ] を指定してpankzを取得する場合
		elseif( isset( $pankz[ "{$this->controller_name}/{$this->action_name}/{$tpl}" ] ) ):
			$definitions = $pankz[ "{$this->controller_name}/{$this->action_name}/{$tpl}" ];

		endif;

		if( $definitions ):

			echo "<ul class=\"pnkz\">";

			foreach( $definitions as $key => $value ):
				if( $key ):
					echo "<li><a href=\"{$key}\">{$value}</a></li>";
				else:
					echo "<li>{$value}</li>";
				endif;
			endforeach;

			echo '</ul>';

		endif;
	}

	/**
	 * ユーザーへのメッセージを設定します。
	 * @access public
	 * @param  string 表示文言
	 * @return void
	 */
	public function set_flash( $string )
	{
		$_SESSION['flash'] = $string;
	}

	/**
	 * ユーザーへのメッセージを表示します。
	 * @access public
	 * @param  void
	 * @return string HTML 表示文言
	 */
	public function flash()
	{
		if( $_SESSION['flash'] ):
			echo "<div class=\"flash\">{$_SESSION['flash']}</div>";
			unset( $_SESSION['flash'] );
		endif;
	}
}
?>