<?php
#----------------------------#
# ページごとCSS読込に関わる定数定義   #
#----------------------------#
/** @var class ページごとCSS定義クラス */
class CSS
{
	/** @var array	$CSS_DEFINITIONS[ controller ][ action ] = array( cssファイル名  ) */
	static $DEFINITIONS = array(
			# 論理名称1
			'default' => array(
					'index' 	=> array( 'default/index.css'),
					'mail' 	=> array( 'default/mail.css','default/form.css', ),
					'db' 	=> array( 'default/db.css', ),
			),

	);
}
?>
