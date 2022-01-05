<?php
#----------------------------#
# ページごとdescription,title読込に関わる定数定義   #
#----------------------------#
/** @var class ページごとdescription,title定義クラス */
class DESCRIPTION
{
	/** @var array	$DEFINITIONS[ controller/action ][ index.tpl ] = [description][title] */
	static $DEFINITIONS = array(
		# 論理名称1
		'default/index' =>  array(
			'../_views/default/index.tpl' => array(
				'description' => 'For PHP 7 ',
				'title' 		=> 'Welcome MyMVC',
			),
		),
		# 論理名称2以下
		'default/mail' =>  array(
			'../_views/default/mail.tpl' => array(
				'description' => 'メールフォーム',
				'title' 			=> 'メールフォーム', ),
		    '../_views/default/mail_confirm.tpl' => array(
		        'description' => 'メールフォーム確認',
		        'title' 			=> 'メールフォーム確認', ),
		    '../_views/default/mail_complete.tpl' => array(
		        'description' => 'メール送信完了',
		        'title' 			=> 'メール送信完了', ),
		),
	);
}
?>
