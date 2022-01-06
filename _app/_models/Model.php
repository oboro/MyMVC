<?php
/**
 * モデル スーパークラス
 * @access public
 * @author ISHIGURO Kei
 * @version 1.00
 * @since 2012/07/23
 */
class Model
{
	/** @var object	Db接続クラス */
	protected $db;

	/** @var array	バリデーション規則 */
	protected $validates = array();

	/** @var const	バリデーション規則 半角数字 */
	const REG_NUM = '/^[0-9]+$/';

	/** @var const	バリデーション規則 半角英数字(両方必須) */
	const REG_NUM_ALPHABET = '/^[a-zA-Z]+[0-9]+[a-zA-Z0-9]*$|^[0-9]+[a-zA-Z]+[a-zA-Z0-9]*$/u';

	/** @var const	バリデーション規則 半角英数字スペース */
	const REG_NUM_ALPHABET_SPACE = '/^[a-zA-Z0-9 ]+$/';

	/** @var const	バリデーション規則 EMAIL */
	const REG_EMAIL = '/^([a-zA-Z0-9\._-])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+(\.)([a-zA-Z0-9\._-]+)+$/';

	/** @var const	バリデーション規則 URL */
	const REG_URL = '/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/u';

	/** @var const	バリデーション規則 郵便番号 */
	const REG_POSTALCODE = '/^[0-9]{3}[\-][0-9]{4}$/';

	/** @var const	バリデーション規則 電話番号・FAX番号 */
	const REG_PHONE_NUM = '/^([0-9]+)+(-)+([0-9]+)+(-)+([0-9]+)/u';

	/**
	 * コンストラクタ
	 * @access public
	 * @param  void
	 * @return void
	 */
	public function __construct()
	{
		$this->db = Db::get();
	}

	/**
	 * バリデーション判定を行います。
	 * @access public
	 * @param  string				バリデート対象のform.name
	 * @param  array	I/O		HTTP::Requestの配列 エラーメッセージは array['invalid'][input.name]['message']に入ります。
	 * @return void
	 */
	public function validate( $form_name, & $inputs = array() )
	{

		# 入力エラーメッセージ
		$messages = array();
		# フォームごとのバリデーション規則
		$rules = array();

		# フォーム名のバリデーション規則が存在する場合
		if( array_key_exists( $form_name, $this->validates ) ) :

			# バリデーション規則の配列を取得します。
			$rules = $this->validates[ $form_name ];

		else :

			return;

		endif;

		foreach( $rules as $input_name => $indivisual_rules ) :

			# $_request['input_name']がバリデーション規則に設定されているか判定します。
			if( is_array( $rules[ $input_name ] ) ) :

				$input_value = $inputs[ $input_name ];

				# 規則が'confirmation'の場合だけ、別処理をします。
				if( isset( $rules[ $input_name ][ 'rules' ][ 'confirmation' ] ) ) :
					( $input_value == $inputs[ $rules[ $input_name ][ 'rules' ][ 'confirmation' ] ] ) ? "NOP" : $inputs[ 'invalid' ][ $input_name ] = $rules[ $input_name ][ 'message' ];
				# 他の規則のチェックを行います。
				elseif( $this->is_invalid( $input_value, $rules[ $input_name ][ 'rules' ] ) ) :
					$inputs[ 'invalid' ][ $input_name ] = $rules[ $input_name ][ 'message' ];

				endif;

			endif;

		endforeach;

// print_r($inputs[ 'invalid' ]);
	}

	/**
	 * 個別のフォーム部品のバリデーション判定を行います。
	 * ※直接呼び出すことを考慮して、publicにしています。
	 * @access public
	 * @param  void		バリデート対象の値
	 * @param  array	バリデーション規則の配列
	 * @return bool		true : invalid(NG)、false : valid(OK)
	 */
	public function is_invalid( $value, $rules )
	{
		mb_language( "japanese" );
		mb_internal_encoding( "UTF-8" );

//print_r("判定する値:{$value}<br/>");
		$invalid = false;

		foreach( $rules as $order => $condition ) :

//print_r("命令:{$order}<br/>");
//print_r("条件:{$condition}<br/>");

			switch( $order ) :
				# 入力必須チェック
				case 'is' :
					# 入力必須の場合
					if( $condition == true ) :
						# '0'を入力された場合は、未入力としない。
						if( $value === '0' ) :
						# 値が空の場合は未入力とする。
						elseif( empty( $value ) ) :
							$invalid = true;
						endif;
					# 入力必須でない場合
					else :
						# 入力値があればチェックを続ける。
						if( $value ) :
							continue;
						else :
							return $invalid;
						endif;
					endif;
					break;
				# 文字列長チェック
				case 'str_length' :
					( $condition == mb_strlen( preg_replace( "/\r\n/", "\n", $value  ), APP_ENCODING ) ) ? "NOP" : $invalid = true ;
					break;
				# 文字数n以上
				case 'str_more_than' :
					( $condition <= mb_strlen( preg_replace( "/\r\n/", "\n", $value  ), APP_ENCODING ) ) ? "NOP" : $invalid = true ;
					break;
				# 文字数n以下
				case 'str_less_than' :

					# 指定文字数をオーバーしている場合はNGです。
					( $condition >= mb_strlen( preg_replace( "/\r\n/", "\n", $value  ), APP_ENCODING ) ) ? "NOP" : $invalid = true ;

					# 改行コード・半角スペース・全角スペースのみの場合(空文字のみは除く)はNGです。
					if( $value == '' ) :
					else :
						if( 0 == mb_strlen( preg_replace( '/[\s 　]/', '', $value ) ) ) :
							$invalid = true;
						endif;
					endif;

					break;

				# 数値n以上
				case 'more_than' :
					( (int)$condition <= (int)$value ) ? "NOP" : $invalid = true ;
					break;

				# 数値n以下
				case 'less_than' :
					( (int)$condition >= (int)$value ) ? "NOP" : $invalid = true ;
					break;

				# 全角　外注の checkingData.php -> is_zenkaku() を流用しました。
				case 'Em' :
					for($i = 0; $i < mb_strlen($value,"UTF-8");$i++){
						if(mb_strlen(mb_substr($value,$i,1,"UTF-8"),"UTF-8") == strlen(mb_substr($value,$i,1,"UTF-8"))){
							$invalid = true;
						}
					}

					if( $invalid ):
					else:
						$zen="ｱｲｳｴｵｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄﾅﾆﾇﾈﾉﾊﾋﾌﾍﾎﾏﾐﾑﾒﾓﾔﾕﾖﾗﾘﾙﾚﾛﾜｦﾝｧｨｩｪｫｯｬｭｮ-ﾞﾟ";
						$mes="";

						for ($i=0; $i< mb_strlen($value,"UTF-8"); $i++) {
							/* 文字列を１文字ずつ調べる */
							$tmp1=mb_substr($value,$i,1,"UTF-8");
							for ($j=0; $j<mb_strlen($zen,"UTF-8"); $j++) {
								$tmp2=mb_substr($zen,$j,1,"UTF-8");
								/* 含まれていた全角カナを格納 */
								if ($tmp1==$tmp2) {
									$mes.="「"+$tmp1+"」";
								}
							}
						}
						if($mes!=""){
							$invalid = true;
						}
					endif;

					break;

				# 全角カナ 外注の checkingData.php -> is_zenkana() を流用しました。
				case 'Emkana' :
					$zen="アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲンガギグゲゴザジズゼゾダヂヅデドバビブベボパピプペポァィゥェォッャュョーヴ　";
					$mes="";

					for ($i=0; $i< mb_strlen($value,"UTF-8"); $i++) {
						/* 文字列を１文字ずつ調べる */
						$tmp1=mb_substr($value,$i,1,"UTF-8");
						for ($j=0; $j<mb_strlen($zen,"UTF-8"); $j++) {
							$tmp2=mb_substr($zen,$j,1,"UTF-8");
							/* 含まれていた全角カナを格納 */
							if ($tmp1==$tmp2) {
								$mes.=$tmp1;
							}
						}
					}

					if($mes == $value): else:
						$invalid = true;
					endif;

					break;

				# アンケートチェックボックス 最大選択肢
				case 'checkbox_more_than' :
					( (int)$condition >= (int)count($value) ) ? "NOP" : $invalid = true ;
					break;

				# 正規表現チェック
				case 'with' :
					# 空の場合はチェックしない。
					if( $value == '' ):
						break;
					endif;
					( preg_match( $condition, $value ) ) ? "NOP" : $invalid = true ;
					break;

				case 'confirmation' :
					print $condition;
					break;
				default :
					throw new Exception( "unknown validation key : {$order}" );
			endswitch;

//print_r("結果:{$invalid}<br/>");

		endforeach;

		return $invalid;
	}

	/**
	 * バリデーションエラーの数を取得します。
	 * @access public
	 * @param  array	HTTP::Requestの配列
	 * @return int		バリデーションに引っかかったフォームの数
	 */
	public function invalid_count( $requests = array() )
	{
		if( is_array( $requests[ 'invalid' ] ) && array_key_exists( 'invalid', $requests ) ) :
			return count( $requests[ 'invalid' ] );
		else :
		 return 0;
		endif;
	}

	/**
	 * $validatesに設定した名前のhiddenタグを書き出します。
	 * @access public
	 * @param  string エラー文言
	 * @return void
	 */
	public function write_hidden_tags( $form_name = '', $requests = array() )
	{
		foreach( $this->validates[ $form_name ] as $name => $others ) :
			if( array_key_exists( $name, $requests ) ) :
				if( is_array( $requests[ $name ] ) ):
					foreach( $requests[ $name ] as $key => $value ):
						echo "<input type=\"hidden\" name=\"{$name}[]\" value=\"" . h($value) . "\" />";
					endforeach;
				else:
					echo "<input type=\"hidden\" name=\"{$name}\" value=\"" . h($requests[ $name ]) . "\" />";
				endif;
			# エラーの場合は各アプリケーションで実装してください。
			else :
				//throw new Exception( "There is no keys '{$name}' in \$this->_request " );
				//echo "There is no keys '{$name}' in \$this->_request \n";
			endif;
		endforeach;
	}

	/**
	 * $validatesに設定した複数フォームの名前のhiddenタグを書き出します。
	 * @access public
	 * @param  array	書き出しを除外するフォーム名称の配列
	 * @param  array	HTTP::Request
	 * @return void
	 */
	public function write_multiple_hidden_tags( $omit_form_names = array(), $requests = array() )
	{
		foreach( $this->validates as $form_name => $input_name_rules ):
			if( in_array( $form_name, $omit_form_names ) ):
			else:
				$this->write_hidden_tags( $form_name, $requests );
			endif;
		endforeach;
	}

	/**
	 * SELECT文を構成してSQLを実行します。
	 * @access public
	 * @param  $options	SQL文の条件
	 * @param  $params 	WHERE句の条件
	 * @return void
	 */
	public function find( $options = array(), $params = array() )
	{
		# SELECT カラム指定がない場合は全カラム取得します。
		( $options['fields'] )? "NOP" : $options['fields']  = "*"; 
		
		foreach( $options as $option_key => $option_value ):
	
// 			print_r( $option_key );
// 			print_r( $option_value );
	
			# SELECTするカラムを設定します。
			# e.g.) $options['fields'] = "M.id,M.name";
			if( $option_key == 'fields' ):
				$$option_key = $option_value;
			
			# テーブル名を設定します。
			# e.g.) $options['table']= array( 'name' => $this->_request['table'], 'alias' => 'M' );
			elseif( $option_key == 'table' ):
				if( is_array( $option_value ) ):
					foreach( $option_value as $key => $value ):
						if( $key == 'name' ):
							$$option_key .= $value;
						elseif( $key == 'alias' ):
							$$option_key .= " AS {$value}";
						endif;
					endforeach;
				endif;
	
			# WHERE句を設定します。
			# e.g.) $options['conditions'] = array( 'M.name LIKE'=>"'%{$this->_request['partial_string']}%'" );
			elseif( $option_key == 'conditions' ):
				if( is_array( $option_value ) ):
					foreach( $option_value as $key => $value ):
						# $key が数字の場合は条件に含めません。
						( preg_match( '/[0-9]+/', $key ) )? $key = null : "NOP";
						$$option_key .= " AND {$key} {$value}";
					endforeach;
				endif;
	
			# LIMITがあれば設定します。
			# e.g.) $options['limit'] = '0,100';
			elseif( $option_key == 'limit' ):
				$$option_key = "LIMIT {$option_value}";
			endif;
	
		endforeach;
	
		$query = <<<HERE
			SELECT
				$fields
			FROM
				$table
			WHERE 1
				$conditions
				$limit
HERE;
// 		print_r( $query );
		$ret = array();
		$ret = $this->db->get_result( $this->db->do_query( $query, $params ) );
		return $ret;
	}
	
	/**
	 * REPLACE文を構成してSQLを実行します。
	 * @access public
	 * @param  $options	SQL文の条件
	 * @param  $params 	保存する値
	 * @return void
	 */
	public function save( $options = array(), $params = array() )
	{
		foreach( $options as $option_key => $option_value ):

			# 保存するカラムを設定します。
			if( $option_key == 'fields' ):
				$$option_key = $option_value;
				# VALUES() のカラムを設定します。
				$values = explode(  ',', $option_value );
				$values = implode( ', :', $values );
				$values = " :".$values;

			# テーブル名を設定します。
			# e.g.) $options['table']= array( 'name' => $this->_request['table'], 'alias' => 'M' );
			elseif( $option_key == 'table' ):
				$$option_key = $option_value['name'];
			endif;
		endforeach;

		$query = <<<HERE
			REPLACE INTO $table ( $fields ) 
				VALUES( $values )
HERE;
// 		print_r( $query );
		$this->db->do_query( $query, $params );
	}
}
?>
