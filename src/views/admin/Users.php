<section class="users">
    <div class="header">
        <h3 class="orange anim-flip">Users</h3>
        <p>(<?= count($data) ?>)</p>
    </div>
    <ul class="table">
        <li class="table-header">
            <span class="username">USER</span>
            <span class="points">POINTS</span>
            <span class="visited">VISITED</span>
            <span class="actions">ACTIONS</span> 
        </li>
        <?php for ($i = 0; $i < count($data); $i++) { ?>
            <li class="table-element">
                <span class="username">
                    <?= $data[$i]['username'] ?>
                    <span class="id">
                       ID: <?= $data[$i]['id'] ?>
                    </span>
                </span>

                <span class="points">
                    <?= $data[$i]['points'] ?>
                </span>

                <span class="visited">
                    <?= $data[$i]['last_login'] ?>
                </span>

                <span class="actions">
                    <a href="/a/users?deleteId=<?= $data[$i]['id'] ?>" class="delete">delete</a>
                </span>
            </li>
        <?php } ?>
    </ul>
</section>

