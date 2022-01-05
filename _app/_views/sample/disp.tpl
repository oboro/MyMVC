<section class="contentArea disp">
  
  <form name="form1" action="post" >
  	
    <input type="hidden" id="form_id" name="form_id" value="<?=h( $form_id )?>" />
  
    <ul id="disp_base"></ul>

  </form>

</section>

<script type="text/javascript">

/**
 * 部分名称入力をもとに選択肢を絞って表示するクラス
 * @param	identifier Form部品のid
 * @param	table 選択肢の表示元のテーブル名 
 * @return	void
 */
var FormPartial = function( identifier, table )
{
	/** @var private 自身への参照 */
	var self = this;
	/** @var private Form部品のid */
	self.identifier = identifier;
	/** @var private 選択肢を表示する元のテーブル名 */
	self.table = table;

 /**
   * 部分名称入力をもとに選択肢を絞り込みます。
   */
	self.partialSearch = function()
	{
		$.post(
			'/sample/partial_search',															
			{ partial_string: $( '#partial_'+self.identifier ).val(), 
			  table: self.table, 
			  dataType: 'json', },		 													
			function( response )															
			{
				try{
					//console.log( response );
					// セレクトボックスのoptionを一旦全削除します。
					//console.log( self.identifier );
					$( '#'+self.identifier+' > option' ).remove();
	                for( var i in response )
	                {
	                    $( '#'+self.identifier ).append( $( '<option>' ).html( response[i].name ).val( response[i].id ) );
	                }
				}catch( e ){ console.log( e ); }
			}
		);
	}

 /**
   * 選択肢 再検索のイベントトリガーを登録します。
   */
	self.init = function()
	{
		$( '#partial_'+self.identifier ).blur( self.partialSearch );
	}
}

/**
 * フォーム部品を作成して表示するクラス
 * @param	void 
 * @return	void
 */
var FormDisp = function()
{
	/** @var private 自身への参照 */
	var self = this;
	/** @var private FormPartialの配列 */
	self.parts = [];

 /**
   * フォーム部品を生成します。
   */
	self.makeParts = function( response )
	{
		var ii = 0;
		var options = '';
		var input = '';

		// form部品のidとnameを設定します。
		var identifier = response.table_name+response.id;
		var q = response.question;

		// 部分検索パーツを生成します。
		if( response.type == 'partial' ){
			$( '#disp_base' ).append( '<li><h3>'+q+'</h3>'+
												'<input type="text" id="partial_'+identifier+'" name="partial_'+identifier+'" />'+
												'<select id="'+identifier+'" name="'+identifier+'">'+
												'<option>選択してください</option>'+
												'</select></li>'
											);
			// 絞り込みクラスを生成し、初期化します。
			self.parts.push( new FormPartial( response.table_name+response.id, response.table_name ) );
			self.parts[ self.parts.length - 1 ].init();
			
		// テキスト入力を生成します。
		}else if( response.type == 'text' ){
			$( '#disp_base' ).append( '<li><h3>'+q+'</h3>'+
												'<input type="text" id="'+identifier+'" name="'+identifier+'" />'+
												'</li>'
											);

		// ラジオボタンを生成します。
		}else if( response.type == 'radio' ){
			options = response.options.split( ',' );
			for( ii in options ){
				input += '<label><input type="radio" name="'+identifier+'" value="'+options[ii]+'"/>'+options[ii]+'</label>';
			}
			$( '#disp_base' ).append( '<li><h3>'+q+'</h3>'+input+'</li>' );
			
		// チェックボックスを生成します。
		}else if( response.type == 'checkbox' ){
			options = response.options.split( ',' );
			for( ii in options ){
				input += '<label><input type="checkbox" name="'+identifier+'[]" value="'+options[ii]+'"/>'+options[ii]+'</label> ';
			}
			$( '#disp_base' ).append( '<li><h3>'+q+'</h3>'+input+'</li>' );

		// セレクトボックスを生成します。
		}else if( response.type == 'select' ){
			options = response.options.split( ',' );
			input = '<option>選択してください</option>'
			for( ii in options ){
				input += '<option value="'+options[ii]+'">'+options[ii]+'</option> ';
			}
			$( '#disp_base' ).append( '<li><h3>'+q+'</h3><select name="'+identifier+'">'+input+'</select></li>' );

		// テキストエリアを生成します。
		}else if( response.type == 'textarea' ){
			$( '#disp_base' ).append( '<li><h3>'+q+'</h3><textarea name="'+identifier+'"></textarea></li>' );
		}
	}

 /**
   * フォーム部品をすべて削除します。
   */
	self.clearAllParts = function()
	{
		$( '#disp_base > li' ).remove();
	}
}

//----------------------//
// 画面を初期化します。 //
//----------------------//
$.post(
	'/sample/disp_init',			
	{ form_id: $( '#form_id' ).val(),  
	  dataType: 'json', },		 													
	function( response )
	{
		try{
			console.log( response );
			formDisp = new FormDisp();
			for( var i in response )
			{
				formDisp.makeParts( response[i] );
			}
		}catch( e ){ console.log( e ); }
	}
);

</script>