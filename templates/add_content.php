<div class="content">
  <section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>
    <nav class="main-navigation">
      <ul class="main-navigation__list">
        <?php if (isset($projects)) : ?>
          <?php foreach ($projects as $num => $projeсt) : ?>
            <li class="main-navigation__list-item">
              <a class="main-navigation__list-item-link" href="/index.php?tag=<?= $projeсt['id'] ?>"><?= $projeсt['project_name']; ?></a>
              <span class="main-navigation__list-item-count"> <?= counting($all_tasks, $projeсt['id']) ?></span>
            </li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </nav>
    <a class="button button--transparent button--plus content__side-button" href="projects.php" target="project_add">Добавить проект</a>
  </section>
  <main class="content__main">
    <h2 class="content__main-heading">Добавление задачи</h2>

    <form class="form" action="add.php" method="post" autocomplete="off" enctype="multipart/form-data">
      <div class="form__row">

        <label class="form__label" for="name">Название <sup>*</sup></label>
        <input class="form__input  <?php if (isset($arr_error['name'])) echo 'form__input--error'; ?>" type="text" name="name" id="name" placeholder="Введите название" value="<?php if (isset($_POST['name'])) echo $_POST['name'] ?>">
        <?php if ( isset($_POST['name']) == '' && isset($_POST['name'])) : ?>
          <p class="form__message"> <?= $arr_error['name'] ?> </p>
        <?php endif; ?>
      </div>

      <div class="form__row">
        <label class="form__label" for="project">Проект <sup>*</sup></label>
        <select class="form__input form__input--select <?php if (isset($arr_error['project'])) echo 'form__input--error'; ?> " name="project" id="project">
          <?php foreach ($projects as $projeсt) : ?>
            <option <?if (isset($_POST['project']) && $_POST['project']==$projeсt['id']) echo'selected'?> value="<?= $projeсt['id'] ?>"><?= $projeсt['project_name'] ?></option>
          <?php endforeach; ?>
        </select>
        <?php if (isset($_POST['project']) && $_POST['project']!= $projeсt['id']) : ?>
          <p class="form__message"> <?=  $arr_error['project'] ?> </p>
        <?php endif; ?>
      </div>

      <div class="form__row">
        <label class="form__label" for="date">Дата выполнения</label>

        <input class="form__input form__input--date <?php if (isset($arr_error['date'])) echo 'form__input--error'; ?>" type="text" name="date" id="date" value="<?php if (!isset($arr_error['date'])) echo isset($_POST['date']) ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">

        <?php if (isset($arr_error['date'])) : ?>
          <p class="form__message"> <?= $arr_error['date'] ?></p>
        <?php endif; ?>
      </div>

      <div class="form__row">
        <label class="form__label" for="file">Файл</label>

        <div class="form__input-file">
          <input class="visually-hidden" type="file" name="file" id="file" value="">

          <label class="button button--transparent" for="file">
            <span>Выберите файл</span>
          </label>
        </div>
      </div>

      <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Добавить">
      </div>
    </form>
  </main>
</div>