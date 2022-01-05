<?php
error_reporting( E_ERROR | E_WARNING );

# システム実行環境を指定します。
require_once( '../Init.php' );

# Init.phpで指定した環境変数を読み込みます
if( MODE == Enum::SYSTEM()->LOCAL ) :
	require_once( '../config/ConfigLocal.php' );
elseif( MODE == Enum::SYSTEM()->DEVELOPMENT ) :
	require_once( '../config/ConfigDevelopment.php' );
elseif( MODE == Enum::SYSTEM()->PRODUCTION ) :
require_once( '../config/ConfigProduction.php' );
endif;


# システム共通クラスを読み込みます。
require_once( '../_utils/Bootstrap.php' );
require_once( '../_controllers/Controller.php' );
require_once( '../_models/Model.php' );

# アプリケーション共通クラス・関数を読み込みます。
require_once( '../_utils/Route.php' );
require_once( '../_utils/Db.php' );
require_once( '../_utils/UserAgent.php' );
require_once( '../_utils/MyFunction.php' );
require_once( '../_utils/Css.php' );
require_once( '../_utils/Description.php' );

# エラーハンドラーを設定します。
ini_set( 'display_errors' , ERROR_NOTICE::DISPLAY_ERRORS );

register_shutdown_function( 'my_shutdown_handler' );

# タイムゾーンを設定します。
date_default_timezone_set( 'Asia/Tokyo' );

# HTTP::Requestを処理します。
$bootstrap = new Bootstrap();
$bootstrap->dispatch();
?>