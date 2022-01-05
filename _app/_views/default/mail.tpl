<h2>メール送信</h2>
<form action="/default/mail" method="post">
<?php print_r( $this->_request['invalid'])?>
<table class="basicForm">
  <tr>
    <th class="required">名前</th>
    <td>
    <input type="text" name="name_last" value="<?php echo $this->_request['name_last']?>" placeholder="例）佐々木">
    <input type="text" name="name_first" value="<?php echo $this->_request['name_first']?>" placeholder="例）花子">
    </td>
  </tr>
  <tr>
    <th class="required">電話番号</th>
    <td><input type="tel" name="tel" value="<?php echo $this->_request['tel']?>"></td>
  </tr>
  <tr>
    <th class="required">メールアドレス</th>
    <td><input type="email" name="email" value="<?php echo $this->_request['email']?>"></td>
  </tr>
</table>

<div class="btnBox"><button type="submit" name="button" value="confirm">内容の確認</button></div>
<input type="hidden" name="token" value="<?php echo $this->token->getToken()?>" >
</form>
