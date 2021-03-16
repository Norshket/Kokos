<div class="content">
    <section class="content__side">
        <h2 class="content__side-heading">Проекты</h2>
        <nav class="main-navigation">
            <ul class="main-navigation__list">
                <?php if (isset($projects)) : ?>
                    <?php foreach ($projects as $num => $projeсt) : ?>
                        <li class="main-navigation__list-item <?php if ($projeсt['id'] == $_GET['tag']) echo 'main-navigation__list-item--active';
                                                                else echo ''; ?>">
                            <a class="main-navigation__list-item-link" href="/index.php?tag=<?= $projeсt['id'] ?>"><?= $projeсt['project_name']; ?></a>
                            <span class="main-navigation__list-item-count"> <?= counting($all_tasks, $projeсt['id']) ?></span>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </nav>
    </section>

    <main class="content__main">
        <h2 class="content__main-heading">
            <--- туда ходи</h2>
                <h2 class="content__main-heading">Сюда не ходи 404</h2>
    </main>
</div>