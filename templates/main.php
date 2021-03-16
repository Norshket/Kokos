<div class="content">
    <section class="content__side">
        <h2 class="content__side-heading">Проекты</h2>
        <nav class="main-navigation">
            <ul class="main-navigation__list">
                <?php if (isset($projects)) : ?>
                    <?php foreach ($projects as $num => $projeсt) : ?>
                        <li class="main-navigation__list-item <?php if ($projeсt['id'] == $_GET['tag']) echo 'main-navigation__list-item--active'; ?>">
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
        <h2 class="content__main-heading">Список задач</h2>

        <form class="search-form" action="index.php" method="GET" autocomplete="off">
            <input class="search-form__input" type="text" name="search" value="" placeholder="Поиск по задачам">

            <input class="search-form__submit" type="submit" name="" value="Искать">
        </form>

        <div class="tasks-controls">
            <nav class="tasks-switch">
                <a href="/index.php" class="tasks-switch__item <?php if ($_GET['date'] == '' || !$_GET['date']) : ?> tasks-switch__item--active <?php endif; ?>"> Все задачи</a>
                <a href="/index.php?date=tuday" class="tasks-switch__item <?php if ($_GET['date'] == 'tuday') : ?> tasks-switch__item--active <?php endif; ?>">Повестка дня</a>
                <a href="/index.php?date=tomorrow" class="tasks-switch__item   <?php if ($_GET['date'] == 'tomorrow') : ?> tasks-switch__item--active<?php endif; ?>">Завтра</a>
                <a href="/index.php?date=expired" class="tasks-switch__item  <?php if ($_GET['date'] == 'expired') : ?> tasks-switch__item--active <?php endif; ?>">Просроченные</a>
            </nav>
            <form method="POST">
                <input style="display:none" name="show_ninja" value="<?= $show_complete_tasks ?>" type="text">

                <label class="checkbox">
                    <input class="checkbox__input visually-hidden" onchange="this.form.submit();" <? if($show_complete_tasks==1):?> checked
                    <?endif;?> name="show_done" value="<?= $show_complete_tasks; ?>" type="checkbox">
                    <span class="checkbox__text">Показывать выполненные</span>
                </label>
            </form>
        </div>
        <table class="tasks">
            <?php if (isset($error_searh) && isset($_GET['search'])) echo $error_searh; ?>

            <?php if (isset($tasks) && $tasks != '') : ?>
                <?php foreach ($tasks as $value) : ?>

                    <tr class="tasks__item task 
                        <?php if ((!$value['task_done']) && !empty($value['task_deadline']) && (date_overdue($value['task_deadline']) <= 0)) echo 'task--important'; ?>
                        <?php if ($value['task_done'] == true && $show_complete_tasks) {
                            echo 'task--completed ';
                        } elseif ($value['task_done'] == true) {
                            echo '">';
                            continue;
                        } ?>">

                        <td class="task__select">

                            <form method="POST">
                                <input style="display:none" type="text" name="ninja_place" value="<?= $value['id'] ?>">
                                <label class="checkbox task__checkbox">
                                    <input class="checkbox__input visually-hidden task__checkbox" onchange="this.form.submit();" <?php if ($show_complete_tasks && $value['task_done']) : ?> checked <?php endif; ?> name="checkbox" type="checkbox" value="<?= $value['id'] ?>">
                                    <span class="checkbox__text"> <?php echo $value['task_name'] ?></span>

                                </label>
                            </form>

                        </td>
                        <td class="task__file">
                            <?php if (!empty($value['file_name'])) : ?>
                                <a class="download-link" href="<?php echo $value['new_file'] ?>"><?php echo $value['file_name'] ?> </a>
                            <?php endif; ?>
                        </td>

                        <td class="task__date">
                            <?php if (!empty($value['task_deadline'])) echo strftime("%d.%m.%Y", strtotime($value['task_deadline'])); ?>
                        </td>
                    </tr>

                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </main>
</div>