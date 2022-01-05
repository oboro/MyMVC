<h2>SELECT DB is <?=DB_NAME?></h2>
<?php foreach( $results as $result ) { ?>
   <?=$result['Tables_in_mysql']; ?><BR>
<?php }?>