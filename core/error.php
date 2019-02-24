<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORE</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bulma.min.css">
    <script defer src="<?= BASE_URL ?>/assets/js/all.js"></script>
  </head>
  <body>
  <section class="hero is-danger is-fullheight">
  <div class="hero-body">
    <div class="container">
      <h1 class="title">
        Error Page
      </h1>
      <p class="subtitle">
        <?php
        if(!empty($error_message)){
          echo $error_message;
        }
        if(!empty($debug_message) and $config['debug']){
          echo $debug_message;
        }
        ?>
      </p>
    </div>
  </div>
  <div class="hero-foot">
    <nav class="tabs">
      <div class="container">
        <ul>
          <li><a href="<?= BASE_URL ?>/index.php">Kembali</a></li>
        </ul>
      </div>
    </nav>
  </div>
</section>
</body>
</html>