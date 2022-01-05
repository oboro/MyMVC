<?php
#----------------------------------#
# アプリケーションに関わる定数定義  #
#----------------------------------#

/** @var define	VIEWテンプレート拡張子 */
define( 'EXTENSION', '.tpl' );

/** @var define	デフォルトで使用するレイアウト */
define( 'DEFAULT_LAYOUT', '../_views/layout/default' );

/** @var define	デフォルトで実行するコントローラ */
define( 'DEFAULT_CONTROLLER', 'default' );

/** @var define	デフォルトで実行するアクション */
define( 'DEFAULT_ACTION', 'index' );

/** @var define	エラー時に実行するコントローラ */
define( 'ERROR_CONTROLLER', 'error' );

/** @var define	エラー時に実行するアクション */
define( 'ERROR_ACTION', 'Err404' );


/** @var define	アプリケーションの設置ディレクトリ */
define( 'APP_DIR', '/' );

/** @var define	アプリケーションの設置ディレクトリ */
define( 'APP_DOMAIN', '' );

/** @var define	アプリケーションのルートアドレス */
define( 'APP_PATH', '' );

/** @var define	アプリケーションの内部エンコーディング */
define( 'APP_ENCODING', 'UTF-8' );

#------------------------#
# DB接続に関わる定数定義  #
#------------------------#

/** @var define	使用するDB名 */
define( 'DB_NAME', 'sample' );

/** @var define	使用するDBサーバ */
// 文字列入れるとDB接続エラーとなるため設定をしているが、要変更
define( 'DB_HOST', '192.168.19.101' );

/** @var define	MySQLアカウント */
define( 'DB_USER', '' );

/** @var define	MySQLパスワード */
define( 'DB_PASSWORD', '' );

#----------------------------#
# セッションに関わる定数定義  #
#----------------------------#

/** @var define	使用するセッション名 */
define( 'SESSION_NAME', 'test_session' );

/** @var define	セッションを保存する一時ディレクトリの場所 */
define( 'SESSION_PATH',  dirname(__FILE__) . APP_DIR . '/../../../_tmp/session'  );

/** @var define	クッキーを有効にするパス(ここ以下のアクセスに対してクッキーを送付します。) */
define( 'SESSION_COOKIE_PARAMS', '/' );

#----------------------------#
# メール送信に関わる定数定義  #
#----------------------------#

/** @var define	使用するMAILサーバ */
define( 'MAIL_HOST', 'smtp.licenseacademy.jp' );

/** @var define	使用するMAILポート */
define( 'MAIL_PORT', 465 );

/** @var define	使用する認証 */
define( 'MAIL_AUTH', true );

/** @var define	メールアカウント */
define( 'MAIL_USER', 'xxxxxxyy@licenseacademy.jp' );

/** @var define	メールパスワード */
define( 'MAIL_PASSWORD', 'xxxxxx' );

/** @var define	メールフロム */
define( 'MAIL_FROM', "LA LOCAL" );

/** @var define	メール from address */
define( 'MAIL_FROM_ADDRESS', 'no_reply@licenseacademy.jp' );

/** */
define( 'ADMIN_MAIL', 'xxxx@licenseacademy.jp');




#------------------------------------------------#
# アプリケーション・業務ロジックに関わる定数定義 #
#------------------------------------------------#

/** @var define	404エラーメッセージ : MODE <= Enum::SYSTEM()->DEVELOPMENT の際に表示されます。 */
define( 'ERROR_MESSAGE', 'ページが見つかりませんでした。' );

/** @var define	SITE名(テンプレートに埋め込まれます。) */
define( 'SITE_TITLE', 'MYMVC 2.0' );

/** @var class エラーメール定数定義クラス */
class ERROR_NOTICE
{
    /** @var const エラー表示	On:表示 Off:非表示 **/
    const DISPLAY_ERRORS = 'On';
    /** @var 送信元 	**/
    const SENDER = 'no_reply@licenseacademy.jp';
    /** @var const 件名 	**/
    const FROM = 'WEBADMIN';
    /** @var const 件名 	**/
    const SUBJECT = "【".SITE_TITLE."】 500 Error ";
    /** @var const 受信者 	**/
    const RECEIVER = 'yyyyyyyy@licenseacademy.jp' ;
}

/** @var class PC　 */
class PC_VIEW
{
    /** @var array スマホブラウザでもPC版を見せるコントローラ名の配列 */
    static $CONTROLLERS = array(
    );
}
?>