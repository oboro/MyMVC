<h2>メール送信</h2>
<form action="/default/mail" method="post">
<?php print_r( $this->_request['invalid'])?>
<table class="basicForm">
  <tr>
    <th class="required">名前</th>
    <td>
    <?php echo $this->_request['name_last']?> <?php echo $this->_request['name_first']?>
    </td>
  </tr>
  <tr>
    <th class="required">電話番号</th>
    <td><?php echo $this->_request['tel']?></td>
  </tr>
  <tr>
    <th class="required">メールアドレス</th>
    <td><?php echo $this->_request['email']?></td>
  </tr>
</table>

<div class="btnBox"><button type="submit" name="button" value="return">修正する</button></div>
<div class="btnBox"><button type="submit" name="button" value="commit">メールを送信する</button></div>

<?php echo  $this->model->write_hidden_tags( 'default_form', $this->_request )?>
<input type="hidden" name="token" value="<?php echo $this->token->getToken()?>" >
</form>
