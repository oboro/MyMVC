<?php
/**
 * データベース接続とSQLを実行するクラス
 * @access public
 * @author ISHIGURO Kei
 * @version 1.00
 * @since 2012/07/23
 */
class Db
{
	/** @var object		self instance. */
	private static $instance = null;

	/** @var object		database connection. */
	private $dbc = null;

	/** @var bool		flag if connection is alive or not. */
	private $is_connect = false;

	/**
	 * コンストラクタ
	 * @access private
	 * @param  void
	 * @return object DB Connection
	 */
	private function __construct()
	{
		$this->dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD );
		if( $this->dbc ) :
			$this->is_connect = true;
			mysqli_query( $this->dbc, "SET NAMES UTF8"  );
			mysqli_query( $this->dbc, "USE ".DB_NAME  );  
		endif;
	}

	/**
	 * Dbクラスのインスタンスを取得します。
	 * @access public
	 * @param  void
	 * @return object このクラスのインスタンス
	 */
	public static function get()
	{
		if( is_null( self::$instance ) ) :
			self::$instance = new self;
		endif;
		return self::$instance;
	}
	
	/**
	 * Db接続を取得します。
	 * @access public
	 * @param  void
	 * @return object DB接続
	 */
	public function get_connection()
	{
		return $this->dbc;
	}

	/**
	 * $tableにINSERTした直近のAUTO INCREMENT値を取得します。
	 * @access public
	 * @return int 直近のid
	 */
	public function get_last_insert_id( $table )
	{
		$query = "SELECT last_insert_id() FROM {$table}";
		$result = mysqli_query( $this->dbc, $query );
		$this->check_result( $result, $query );
		return $result;
	}

	/**
	 * トランザクションを開始します。
	 * @access public
	 * @return bool 成否
	 */
	public function do_transaction()
	{
		$query = "set autocommit = 0";
		$result = mysqli_query( $this->dbc, $query );
		$this->check_result( $result, $query );

		$query = "start transaction";
		$result = mysqli_query( $this->dbc, $query );
		$this->check_result( $result, $query );

		return true;
	}

	/**
	 * コミットします。
	 * @access public
	 * @return bool
	 */
	public function do_commit()
	{
		$query = "commit";
		$result = mysqli_query( $this->dbc, $query );
		$this->check_result( $result, $query );
		return true;
	}

	/**
	 * ロールバックします。
	 * @access public
	 * @return bool 成否
	 */
	public function do_rollback()
	{
		$query = "rollback";
		$result = mysqli_query( $this->dbc, $query );
		$this->check_result( $result, $query );
		return true;
	}

	/**
	 * SQLを実行します。
	 * @param		string	SQL
	 * @param		array		SQLに埋め込むパラメータ
	 * @access	public
	 * @return	resource
	 */
	public function do_query( $query, $hash_params = null )
	{
		if( MODE <= Enum::SYSTEM()->DEVELOPMENT  ) :
			$log_write = true;
		endif;

		try
		{
			# パラメータがある場合は、文字列をエスケープします。
			if( !empty( $hash_params ) ) :
				if( !is_array( $hash_params ) ) :
					throw new Exception(' hash_params is not array' );
				endif;
				foreach( $hash_params as $key => $value ) :
					if( is_string( $value ) ) :
						$value = "'{$this->escape( $value )}'";
					elseif( is_null( $value ) ) :
						$query = preg_replace( "/:({$key})\b/", 'NULL', $query );
						continue;
					endif;
					$query = preg_replace( "/:({$key})\b/", $value, $query );
				endforeach;
			endif;

			# SQLを実行します。
//print_r( $query );
			( $log_write ) ? mylog( $query ) : "" ;
			$result = mysqli_query( $this->dbc, $query );
			$this->check_result( $result, $query );
			return $result;
		}
		catch( Exception $e )
		{
			if( MODE <= Enum::SYSTEM()->DEVELOPMENT ) :
				echo '<pre>' . $e->getMessage() . "\n";
				var_dump( $hash_params );
				echo '</pre>';
			else :
				return false;
			endif;
		}
	}

	/**
	 * SQLのエスケープ処理をします。
	 * @access public
	 * @return object エスケープしたSQL
	 */
	public function escape( $string )
	{
	    return mysqli_real_escape_string( $this->dbc, $string );
	}

	/**
	 * SQL実行結果を配列で取得します。
	 * @access public
	 * @param  string SQL
	 * @return array SQL RESULT
	 */
	public function get_result( $result )
	{
		$data = array();
		while( $row = mysqli_fetch_assoc( $result ) ) :
			$data[] = $row;
		endwhile;
		return $data;
	}

	/**
	 * DB操作結果を検証します。
	 * @access public
	 * @param  object DB RESULT SET
	 * @param  string SQL
	 * @return object SQL
	 */
	private function check_result( $result, $query )
	{
		if( !$result ) :
			$message  = 'Invalid query: ' . mysql_error();
			$message .= 'Whole   query: ' . $query;
			trigger_error( $message , E_USER_ERROR );
		else :
			return $result;
		endif;
	}

	/**
	 * DBコネクション設定の有無を取得します。
	 * @access public
	 * @return bool  db connection alive
	 */
	public function get_is_connect()
	{
		return $this->is_connect;
	}

	/**
	 * コネクションクローズ
	 * @access public
	 * @return bool
	 */
	public function close()
	{
		if( !mysqli_close( $this->dbc ) ) :
			throw new Exception( " mysql connection close error" );
		endif;
		$this->is_connect = false;
		return true;
	}
}
?>
