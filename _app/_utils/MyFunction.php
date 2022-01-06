<?php

/**
 * アプリケーション共通で使用するユーティリティ関数群を記述します。
 * @version 1.00
 */

/**
 * requireしていないクラスのインスタンス生成時に自動的に呼ばれます。
 * @access public
 * @param  string	モデル名
 * @return void
 */
function __autoload( $model_name )
{
	require_once( '../_models/' . ucwords( $model_name ) . '.php' );
}

/**
 * htmlspecialchars()のエイリアスです。
 * @access public
 * @param  string
 * @return string
 * @author ISHIGURO Kei
 * @since 2012/07/26
 */
function h( $value )
{
	return htmlspecialchars( $value );
}

/**
 * ログを書き出します。
 * @access public
 * @param string	$message
 * @return void
 * @author ISHIGURO Kei
 * @since 2012/08/18
 */
function mylog( $message = "" )
{
	$access_time = date( "Y-m-d H:i:s" );
	$safix = date( "Y-m-d" );
	error_log( "{$access_time}\t{$message}\n", 3, dirname(__FILE__) . APP_DIR . "./../../_tmp/log/mylog_{$safix}" );

}

/**
 * Date型をyyyy年mm月DD日に変換します。
 * @access public
 * @param  string	YYYY-mm-dd
 * @param  string	変換形式文字列
 * @return string
 * @author ISHIGURO Kei
 * @since 2012/08/03
 */
function fdate( $value, $format = '' )
{
	$formated = '';
	( $format ) ? $formated = date( $format, strtotime( $value ) ) : $formated = date( 'Y年 n月 j日', strtotime( $value ) ) ;
	return $formated;
}

/**
 * ブラウザがスマートフォン用かPC用かを判別します。
 * @access public
 * @param  void
 * @return string
 * @author ISHIGURO Kei
 * @since 2012/09/26
 */
function is_smart_phone()
{
	return UserAgent::get()->is_smart_phone();
}

/**
 * FATAL ERROR発生時にエラーメールをサイト担当者に送付します。
 * @access public
 * @param  void
 * @return void
 */
function my_shutdown_handler()
{
	$is_error = false;

	if( $error = error_get_last() ):
		switch( $error['type'] ):
			case E_ERROR:
			case E_USER_ERROR:
				require_once 'factory/MailFactory.php';
				MailFactory::get()->create( MailFatalError::$ORDER )->send_emails( $error );
			case E_PARSE:
			case E_CORE_ERROR:
			case E_CORE_WARNING:
			case E_COMPILE_ERROR:
			case E_COMPILE_WARNING:
				$is_error = true;
				break;
		endswitch;
	endif;
}
?>
