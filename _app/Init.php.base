<?php require_once( '../_utils/Enum.php' ); ?>
<?php
/** @var array	システム実行環境モード */
Enum::SYSTEM
(
	array
	(
		'LOCAL',				# 開発環境
		'DEVELOPMENT',	# 評価環境
		'PRODUCTION',		# 本番環境
	)
);

# アプリケーション環境変数を実行環境に応じて設定します。
define( 'MODE', Enum::SYSTEM()->LOCAL );
//define( 'MODE', Enum::SYSTEM()->DEVELOPMENT );
//define( 'MODE', Enum::SYSTEM()->PRODUCTION );

# 2014/07/02 subversive 2.0 commit test 
# 2014/07/02 TortoiseSVN 1.8 commit test 
?>