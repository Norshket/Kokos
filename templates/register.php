<div class="content">
  <section class="content__side">
    <p class="content__side-info">Если у вас уже есть аккаунт, авторизуйтесь на сайте</p>

    <a class="button button--transparent content__side-button" href="autho.php">Войти</a>
  </section>

  <main class="content__main">
    <h2 class="content__main-heading">Регистрация аккаунта</h2>

    <form class="form" action="/register.php" method="post" autocomplete="off">
      <div class="form__row">
        <label class="form__label" for="email">E-mail <sup>*</sup></label>

        <input class="form__input <?php if (isset($reg_error['email']) && isset($_POST['email'])): ?>form__input--error<?php endif; ?>" type="text" name="email" id="email" value="<?php if (!isset($reg_error['email']) && isset($_POST['email'])) echo $_POST['email']; ?>" placeholder="Введите e-mail">

        <?php if (isset($reg_error['email'])) : ?>
          <p class="form__message"> <?= $reg_error['email']; ?></p>
        <?php endif; ?>

      </div>

      <div class="form__row">
        <label class="form__label" for="password">Пароль <sup>*</sup></label>

        <input class="form__input <?php if (isset($reg_error['password'])) : ?>form__input--error<?php endif; ?>" type="password" name="password" id="password" value="" placeholder="Введите пароль">

        <?php if (isset($reg_error['password'])) : ?>
          <p class="form__message"> <?= $reg_error['password']; ?></p>
        <?php endif; ?>
      </div>

      <div class="form__row">
        <label class="form__label" for="name">Имя <sup>*</sup></label>

        <input class="form__input <?php if (isset($reg_error['name']) && isset($_POST['name'])) : ?>form__input--error<?php endif; ?>" type="text" name="name" id="name" value="<?php if (!isset($reg_error['name']) && isset($_POST['name'])) echo $_POST['name']; ?>" placeholder="Введите имя">
        <?php if (isset($reg_error['name'])) : ?>
          <p class="form__message"> <?= $reg_error['name']; ?></p>
        <?php endif; ?>
      </div>
      <div class="form__row form__row--controls">
        <?php if (isset($reg_error['all'])) : ?>
          <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
        <?php endif; ?>
        <input class="button" type="submit" name="" value="Зарегистрироваться">
      </div>
    </form>
  </main>
</div>