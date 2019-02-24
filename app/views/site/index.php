<section class="hero is-primary is-fullheight">
  <div class="hero-body">
    <div class="container">
      <?= $controller->getMessage() ?>
      <h1 class="title">
        Easy PHP Framework
      </h1>
      <p class="subtitle">
        Another simple PHP framework for education purpose!
      </p>
    </div>
  </div>
  <div class="hero-foot">
    <nav class="tabs">
      <div class="container">
        <ul>
          <li class="is-active"><a href="<?= BASE_URL ?>">Home</a></li>
          <li><a href="<?= BASE_URL ?>/article">Article</a></li>
        </ul>
      </div>
    </nav>
  </div>
</section>
<?php
$controller->js_header = "
  // your header js code here
";
?>