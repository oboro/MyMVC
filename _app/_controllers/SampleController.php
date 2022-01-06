<?
/**
 * コントローラ サンプル機能実装ページ
 * @access public
 * @version 1.00
 * @author ISHIGURO Kei
 * 2019/03/20
 */
class SampleController extends Controller
{
	/**
	 * コンストラクタ
	 * @access public
	 * @param  void
	 * @return void
	 */
	public function __construct( $controller_name = "", $action_name = "" )
	{
		parent::__construct( $controller_name, $action_name );
		# スマートフォンとPCで、使用する基本レイアウトとviewを共通化します。
		$this->layout =  '../_views/layout/default' . EXTENSION;
		$this->contents_for_layout = '../_views/' . $controller_name . '/' . $action_name . EXTENSION;
		$this->css_pages = array( $controller_name => array( $action_name => array( 'sample.css' ) ) );
	}

	/**
	 * フォーム作成画面を表示します。
	 * @access public
	 * @param  void
	 * @return void
	 */
	public function make( $form_id = 0 )
	{
		$m = new Model();

		# フォーム作成時
		if( $this->_request['make'] ):

			# フォームの構成を保存します。
			if(  is_array( $this->_request['question'] ) && count( $this->_request['question'] ) ):

				$id = date( 'ymdhis' );
				$options = array();
				$options['table'] = array( 'name' => 'form_parts' );

				# フォームの個々の部品の属性を保存します。
				for( $i = 0 ; $i < count( $this->_request['question'] ) ; $i++ ):
					if( $this->_request['type'][$i] == 'partial'   ):
						$options['fields'] = "id,form_id,type,table_name,question,options";
					else:
						$options['fields'] = "id,form_id,type,question,options";
					endif;
					$m->save( $options, 
							array( 'id'=>'', 'form_id'=>$id, 'type'=>$this->_request['type'][$i],
										'table_name'=>$this->_request['table_name'][$i],
										'question'=>$this->_request['question'][$i],
										'options'=>$this->_request['options'][$i], ) );
				endfor;
				
				$this->contents_for_layout = '../_views/sample/make_complete.tpl';

			endif;

		# 初期表示
		else:
			# フォーム部品の種類を取得します。
			$parts_types = $m->find( array( 'table' => array( 'name' => 'sys_parts_types' ) ) );
			# マスターの種類を取得します。
			$master_types = $m->find( array( 'table' => array( 'name' => 'sys_master_types' ) ) );
	
		endif;

		require_once( $this->layout );
	}
	
	/**
	 * フォーム画面を表示します。
	 * @access public
	 * @param  void
	 * @return void
	 */
	public function disp( $form_id = 0 )
	{
 		require_once( $this->layout );
 	}
 	
 	/**
 	 * 画面を初期化します。
 	 * @access public
 	 * @param  void
 	 * @return void
 	 */
 	public function disp_init()
 	{
 		$m = new Model();
 		$ret = array();
 	
 		# マスタ情報を取得します。
 		$options = array();
 		$options['table'] = array( 'name' => 'form_parts', 'alias' => 'FP' );
 		$options['conditions'] = array( "FP.form_id = :form_id" );
 		$ret = $m->find( $options, array( 'form_id' => $this->_request['form_id'] ) );
//  		print_r($ret);

 		# JSON形式でResponseを返します。
		header( 'content-type: application/json; charset=utf-8' );
 		$data = json_encode( $ret );
 		if( json_last_error() == JSON_ERROR_NONE ):
 			echo $data;
 			exit;
 		else:
 			http_response_code( 500 );
 		endif;
 	}

 	/**
 	 * 入力文字列をもとに選択肢を絞りこみjson形式で返します。
 	 * @access public
 	 * @param  HTTP::Request
 	 * @return void
 	 */
	public function partial_search()
	{
		$m = new Model();
		$ret = array();
		
		# 入力文字列からマスタを絞り込みます。
		$options = array();
		$options['table'] = array( 'name' => $this->_request['table'], 'alias' => 'M' );
		$options['fields'] = "M.id,M.name";
		$options['conditions'] = array( 'M.name LIKE' => ':partial_string' );
		$options['limit'] = '0,100';
		$this->_request['partial_string'] = "%{$this->_request['partial_string']}%";
		$ret = $m->find( $options, $this->_request );

		# JSON形式でResponseを返します。
		header( 'content-type: application/json; charset=utf-8' );
		$data = json_encode( $ret );
 		if( json_last_error() == JSON_ERROR_NONE ):
			echo $data;
			exit;
		else:
			http_response_code( 500 );
		endif;
 	}
}
?>
