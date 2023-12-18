<section class="taskList">
    <h3 class="blue anim-flip">All challenges</h3>

    <ul>
        <li class="header">
                <span class="level">LVL</span>
                <span class="category">Cat</span>
                <span class="title">Title</span>
                <span class="points">Points</span>
        </li>
        <?php foreach ($tasks as $task): ?>
            <li class="element">
                <a href="/t/<?= $task['id'] ?>" class="<?= $task['level'] ?>">
                <span class="level level-<?= $task['level'] ?>">
                        <?= ucfirst($task['level']) ?>
                    </span>
                    <span class="category">
                        <?= $task['category'] ?>
                    </span>
                    <span class="title">
                        <?= $task['title'] ?>
                    </span>
                    <span class="points"><?= $task['points'] ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>