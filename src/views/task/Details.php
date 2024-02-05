<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<section class="taskDetails">
<header>
    <h2 class="<?= $data['level'] ?>"><?= $data['title']; ?></h2>
    <div class="metadata">
        <p>Category: <?= $data['category']; ?></p>
        <p>Level: <?= ucfirst($data['level']) ?></p>
        <p>Points: <?= $data['points']; ?></p>
        <p>Solves: <?= $data['solves']; ?></p>
    </div>
</header>

<div class="content">
    <div class="content__text">
        <p><?= $data['description'] ?>
        <?php if (strlen($data['file']) > 0) { ?>
            <a href="/files/<?= $data['file'] ?>" download> Download file</a>
        <?php } ?></p>
    </div>

    <?php if (isset($_SESSION['id']) && !$data['isSolved']){ ?>
    <form action="/t/<?=$data['id']?>" method="POST">
        <input type="text" placeholder="Flag: <?= FLAG_PREFIX ?>{3x4mpl3}" name="flag">
        <input type="submit" value="Submit" data-umami-event="Submit flag button">
    </form>
    <?php } elseif ($data['isSolved']) { ?>
        <p class="solved">You solved this task!!</p>
    <?php } ?>

    <?php if (isset($_SESSION['is_admin'])) { if ($_SESSION['is_admin'] == true) { ?>
        <div class="topSolves">
            <p>Fastest users</p>
            <ol>
                <?php foreach ($data['topFirstSolves'] as $solve) { ?>
                    <li title="<?= $solve['created_at'] ?>">
                        <?= $solve['username'] ?>
                    </li>
                <?php } ?>
            </ol>
        </div>
    <?php } } ?>
</div>
</section>
