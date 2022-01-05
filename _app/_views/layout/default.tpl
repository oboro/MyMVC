<!DOCTYPE html>
<html lang="ja"><head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <?php $this->get_description();?>
  <?php $this->get_title();?>
  <link rel="stylesheet" href="<?=APP_DIR?>css/module/base.css">
  <link rel="stylesheet" href="<?=APP_DIR?>css/module/common.css">
  <link rel="stylesheet" href="<?=APP_DIR?>css/module/layout.css">
  <?php $this->get_css();?>
  <!--[if lt IE 9]>
  <script src="<?=APP_DIR?>js/html5.js" type="text/javascript"></script>
  <script src="<?=APP_DIR?>js/selectivizr-min.js" type="text/javascript"></script>
  <![endif]-->
  <script type="text/javascript" src="<?=APP_DIR?>js/jquery.js"></script>
  <script type="text/javascript" src="<?=APP_DIR?>js/base.js"></script>
</head>
<body>
  <section>
  <header class="layout">
    <h1 class="logo"><a href="<?=APP_PATH?>/">MYMVC PHP7</a></h1>
    <div class="spMenuBtn">MENU</div>
    <nav class="globalSubNav">
      <ul>
        <li><a href="/default/db/">DB</a></li>
        <li><a href="/default/mail/">Mail</a></li>
      </ul>
    </nav>
  </header>

  <article class="layout">
  <?php require_once $this->contents_for_layout; ?>
  </article>

  <footer class="layout">
    <p class="copy">© 2018 LICENSE ACADEMY Inc.</p>
  </footer>
  
    
  </section>
</body>
</html>