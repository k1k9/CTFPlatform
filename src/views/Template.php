<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php
        if (key_exists('title', $head)) echo $head['title'] . ' - '; 
        else echo ' ';
        echo SITE_NAME;
        ?>
    </title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link href="/css/main.css" rel="stylesheet" />
    <?php if (key_exists('css', $head))
    {
        echo "<link href='" . $head['css'] . "' rel='stylesheet'>";
    }?>
</head>

<body>
    <main>
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
            include_once ROOT . '/views/admin/Header.php';
        } ?>

        <section class="content">
        {{content}}
        </section>

        <header class="pageHeader">
            <nav>
                <h1><a href="/"><?= SITE_NAME ?></a></h1>

                <ul>
                    <li><a href="/t">Challenges</a></li>
                    <li><a href="/scoreboard">Scoreboard</a></li>
                    <?php if (isset($_SESSION['username'])) { ?>
                        <li><a href="/u/logout">Logout</a></li>
                    <?php } else { ?>
                        <li><a href="/u/login">Log in</a></li>
                        <li><a href="/u/register">Register</a></li>
                    <?php } ?>
                </ul>
            </nav>
        </header>
    </main>
        <?= $footer['js'] ?? ''; ?>
</body>

</html>