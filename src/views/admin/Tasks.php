<section class="adminTasks">
    <div class="header">
        <h3 class="orange anim-flip">Tasks</h3>
        <p>(<?= count($data) ?>)</p>
        <a href="/t/add">AddTask</a>
    </div>
    <ul class="table">
        <li class="table-header">
            <span class="title">LVL</span>
            <span class="level">Title</span>
            <span class="actions">ACTIONS</span> 
        </li>
        <?php for ($i = 0; $i < count($data); $i++) { ?>
            <li class="table-element">
                <span class="level">
                    <?= $data[$i]['level'] ?>
                </span>

                <span class="title">
                    <?= $data[$i]['title'] ?>
                </span>

                <span class="actions">
                    <a href="/t/delete?id=<?= $data[$i]['id'] ?>" class="delete">delete</a>
                </span>
            </li>
        <?php } ?>
    </ul>
</section>

