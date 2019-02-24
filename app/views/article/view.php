<section class="hero is-info is-fullheight">
  <div class="hero-body">
    <div class="container">
      <?= $controller->getMessage() ?>
      <h1 class="title">
        <?= $article->title ?>
      </h1>
      <p><?= $article->body ?></p>

    </div>
  </div>
  <div class="hero-foot">
    <nav class="tabs">
      <div class="container">
        <ul>
          <li class="is-active"><a href="<?= BASE_URL ?>/article/index">Back</a></li>
          <li><a href="<?= BASE_URL ?>/site/logout">Logout</a></li>
        </ul>
      </div>
    </nav>
  </div>
</section>