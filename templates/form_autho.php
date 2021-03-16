<div class="content">

  <section class="content__side">
    <p class="content__side-info">Если у вас уже есть аккаунт, авторизуйтесь на сайте</p>

    <a class="button button--transparent content__side-button" href="autho.php">Войти</a>
  </section>

  <main class="content__main">
    <h2 class="content__main-heading">Вход на сайт</h2>

    <form class="form" action="autho.php" method="post" autocomplete="off">
      <div class="form__row">
        <label class="form__label" for="email">E-mail <sup>*</sup></label>

        <input class="form__input <?php if (isset($error_user['email']) && isset($_POST['email'])) : ?> form__input--error <?php endif; ?>" type="text" name="email" id="email" value="<?php if (!isset($error_user['email']) && isset($_POST['email'])) echo $_POST['email']; ?>" placeholder="Введите e-mail">

        <?php if (isset($error_user['email'])) : ?>
          <p class="form__message"><?= $error_user['email'] ?></p>
        <?php endif; ?>

      </div>

      <div class="form__row">
        <label class="form__label" for="password">Пароль <sup>*</sup></label>

        <input class="form__input <?php if (isset($error_user['password']) && isset($_POST['password'])) : ?> form__input--error <?php endif; ?>" type="password" name="password" id="password" value="<?php if (isset($error_user['password']) && isset($_POST['password'])) echo $_POST['password']; ?>" placeholder="Введите пароль">

        <?php if (isset($error_user['password'])) : ?>
          <p class="form__message"><?= $error_user['password'] ?></p>
        <?php endif; ?>
      </div>

      <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Войти">
      </div>
    </form>

  </main>

</div>