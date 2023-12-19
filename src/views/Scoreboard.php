<?php
function ordinalSuffix($number) {
    if (!in_array(($number % 100), [11, 12, 13])) {
        switch ($number % 10) {
            // Handle 1st, 2nd, 3rd
            case 1:  return $number . 'st';
            case 2:  return $number . 'nd';
            case 3:  return $number . 'rd';
            default: return $number . 'th';
        }
    } else {
        return $number . 'th';
    }
}
?>
<section class="scoreboard">
    <h3 class="orange anim-flip">Scoreboard</h3>
    <ul class="table">
        <li class="table-header">
            <span class="rank">RANK</span>
            <span class="username">USER</span>
            <span class="points">POINTS</span>
        </li>
        <?php for ($i = 0; $i < count($data); $i++) {
            if (isset($_SESSION['id']) && $data[$i]['id'] == $_SESSION['id']) {
                echo "<li class='table-element active'>";
            } else { echo '<li class="table-element">'; }
        ?>
                <span class="rank"><?= ordinalSuffix($i + 1) ?></span>
                <span class="username"><?= $data[$i]['username'] ?></span>
                <span class="points"><?= $data[$i]['points'] ?></span>
            </li>
        <?php } ?>
    </ul>
</section>