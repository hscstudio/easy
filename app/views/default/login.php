<section class="hero is-primary is-fullheight">
  <div class="hero-body">
    <div class="container">
      <?= $controller->getMessage() ?>
      <form method="post">
      <div class="field">
        <p class="control has-icons-left has-icons-right">
          <input name="username" class="input" value="admin" type="text" placeholder="username">
          <span class="icon is-small is-left">
            <i class="fas fa-envelope"></i>
          </span>
          <span class="icon is-small is-right">
            <i class="fas fa-check"></i>
          </span>
        </p>
      </div>
      <div class="field">
        <p class="control has-icons-left">
          <input name="password" class="input" value="123456" type="password" placeholder="password">
          <span class="icon is-small is-left">
            <i class="fas fa-lock"></i>
          </span>
        </p>
      </div>
      <div class="field">
        <p class="control">
          <button type="submit" class="button is-inverted">
            Login
          </button>
        </p>
      </div>
      </form>
    </div>
  </div>
</section>