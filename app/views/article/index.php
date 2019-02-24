<section class="hero is-info is-fullheight">
  <div class="hero-body">
    <div class="container">
      <?= $controller->getMessage() ?>
      <h1 class="title">
        List of articles
      </h1>
      <table class="table is-fullwidth is-bordered is-striped is-narrow is-hoverable">
      <tbody>
      <?php
      $idx=1;
      foreach($articles as $article){
        echo '<tr>
          <td>'.$article->id.'</td>
          <td>'.$article->title.'</td>
          <td><a class="button" href="'.BASE_URL.'/article/view?id='.$article->id.'">view</a></td>
        </tr>';
        $idx++;
      }
      ?>
      </tbody>
      </table>
    </div>
  </div>
  <div class="hero-foot">
    <nav class="tabs">
      <div class="container">
        <ul>
          <li><a href="<?= BASE_URL ?>">Home</a></li>
          <li class="is-active"><a href="<?= BASE_URL ?>/article">Article</a></li>
          <li><a href="<?= BASE_URL ?>/site/logout">Logout</a></li>
        </ul>
      </div>
    </nav>
  </div>
</section>