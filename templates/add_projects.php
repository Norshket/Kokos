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
    <h2 class="content__main-heading">Добавление проекта</h2>

    <form class="form" action="projects.php" method="post" autocomplete="off">
      <div class="form__row">
        <label class="form__label" for="project_name">Название <sup>*</sup></label>

        <input class="form__input" type="text" name="project" id="project_name" value="" placeholder="Введите название проекта">
        <?php if ($project_err) : ?>
          <p class="form__message"> <?= $project_err; ?></p>
        <?php endif; ?>
      </div>

      <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Добавить">
      </div>
    </form>
  </main>
</div>