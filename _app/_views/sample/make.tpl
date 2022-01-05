<section class="contentArea make">
<form id="make_form" action="/sample/make" method="post">
	
	<ul id="make_base">
		<li id="0">
			<ul>
				<li>
					<div>質問を入力してください。<b>必須</b></div>
					<input type="text" name="question[]" value="" />
				</li>
				<li>
					<div>フォーム部品の種類を選択してください。<b>必須</b></div>
					<select name="type[]">
						<? foreach( $parts_types as $ptype ): ?>
						  <option value="<?=h($ptype['type_name'])?>"><?=h($ptype['name'])?></option>
						<? endforeach; ?>
					</select>
				</li>
				<li>
					<div>表示する選択肢を選んでください。<br/>・絞り込みつきセレクトボックスの場合は<b>必須</b></div>
					<select name="table_name[]">
						<option value="">----</option>
						<? foreach( $master_types as $mtype ): ?>
							<option value="<?=h($mtype['table_name'])?>"><?=h($mtype['name'])?></option>
						<? endforeach; ?>
					</select>
				</li>
				<li>
					<div>選択肢を半角カンマ区切りで入力してください。<br/>・ラジオボタン、チェックボックス、セレクトボックスの場合は<b>必須</b></div>
					<input type="text" name="options[]" value="" />
				</li>
				<li>
					<input type ="button" value="この部品を削除する" onclick="clear_parts(this)"/>
				</li>
			</ul>
		</li>
	</ul>
	<ul id="new">
		<li><input type ="button"  value="新しく部品を追加する" onclick="make_parts()"/></li>
	</ul>
	<center><input type ="button" value="このフォームを表示する" onclick="disp()"/></center>
	<center id="submit" ><input type ="submit" name="make" value="このフォームを作成する"/></center>

</form>
</section>

<? # フォーム表示部分の画面を読み込みます。 ?>
<? require_once 'disp.tpl' ?>

<script type="text/javascript">

	var i = 0;
	var formDisp = new FormDisp();

 /**
   * この部品を削除します。
   */
	function clear_parts( obj )
	{
		console.log( $(obj).parent().parent().parent() );
		// 最初のフォーム部品は削除させません。
		if( $(obj).parent().parent().parent().attr('id') != 0 )
		{
			$(obj).parent().parent().parent().remove();
			formDisp.clearAllParts();
			
			// 作成するボタンを非表示にします。
			$( '#submit' ).css( 'visibility', 'hidden' );
		}
	}

 /**
   * 新しく部品を追加します。
   */
	function make_parts()
	{
		var parts  = $( '#0' ).clone().attr( 'id', i+1 ).appendTo( '#make_base' );
		i++;
		// 作成するボタンを非表示にします。
		$( '#submit' ).css( 'visibility', 'hidden' );
	}

/**
   * このフォームを表示します。
   */
	function disp()
	{
		// 表示中のフォーム部品をすべて削除します。
		formDisp.clearAllParts();

		// フォーム部品を描画します。
		$( '#make_base > li' ).each( function()
		{
//			console.log( this.id );
			var o = new Object();
			o.id = this.id;
			o.table_name = $( this ).find( '*[name=table_name\\[\\]]' ).val();
			o.question = $( this ).find( '*[name=question\\[\\]]' ).val();
			o.type = $( this ).find( '*[name=type\\[\\]]' ).val();
			o.options = $( this ).find( '*[name=options\\[\\]]' ).val();
			formDisp.makeParts( o );
		});
		
		// 作成するボタンを表示します。
		$( '#submit' ).css( 'visibility', 'visible' );
	}

</script>