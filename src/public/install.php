<?php
/**
 * Initialize all nessesary database, tables
 * and settup the website settings and config
 * @author k1k9
 */

 function returnError($e) {
    ?>
    <section style="text-align: center;">
        <h3 class="red">Database error</h3>
        <p class="error-msg"><?= $e ?></p>

        <a class="return" href="/install.php">RETURN</a>
    </section> <?php
    die();
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        @import url('/css/main.css');

        section{
            width: 1000px;
            margin: 0 auto;
            padding: 1rem 0;
            box-sizing: border-box;
        }
        form{
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            gap: 2rem;
            box-sizing: border-box;
        }

        .element{
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .radio{
            display: flex;
            justify-content: space-between;
            padding: 1rem;
        }

        .radio label{
            display: block;
            width: 200px;
            box-sizing: border-box;
            padding: 1rem 0;
            cursor: pointer;
        }

        .radio div input[type=radio]{
            display: none;
        }

        label[for=restrictNo],
        label[for=devmodeNo] {
            text-align: right;
        }

        label[for=restrictYes]:hover,
        label[for=devmodeYes]:hover {
            color: var(--green);
        }

        label[for=restrictNo]:hover,
        label[for=devmodeNo]:hover {
            color: var(--red);
        }

        .radio div #restrictYes[type=radio]:checked + label,
        .radio div #devmodeYes[type=radio]:checked + label {
            color: var(--green-dark);
        }

        .radio div #restrictNo[type=radio]:checked + label,
        .radio div #devmodeNo[type=radio]:checked + label {
            color: var(--red-dark);
        }

        .time{
            display: flex;
            flex-direction: column;
            gap: 2rem;
            padding: 1rem;
        }

        .time div{
            display: flex;
            flex-direction: column;
            gap:1rem;
        }

        input[type=submit]{
            color: var(--white);
            background-color: var(--red);

            &:hover{
                background-color: var(--red-dark);
            }
        }

        h2{
            color: var(--red);
            text-shadow: 2px 2px 1px var(--red-dark);
        }

        a.return{
            display: block;
            margin-top: 4vh;
            padding: 1rem 2rem;
            color: var(--red-dark);
            text-decoration: none;
        }

        .error-msg{
            width: 1000px;
            display: block;
            box-sizing: border-box;
            overflow-wrap: break-word;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<?php
if (!isset($_POST['siteName']) || !isset($_POST['flagPrefix']) || !isset($_POST['restrict']) || !isset($_POST['devmode']) || !isset($_POST['dbHost']) || !isset($_POST['dbUser']) || !isset($_POST['dbPass']) || !isset($_POST['dbName'])) {
?>
    <section>
        <h3 class="red">Installation</h3>

        <form action="/install.php" method="POST">

            <div class="element">
                <label for="siteName">Site name</label>
                <input type="text" name="siteName" id="siteName" placeholder="Site name" required>
            </div>

            <div class="element">
                <label for="flagPrefix">Flag prefix</label>
                <input type="text" name="flagPrefix" id="flagPrefix" placeholder="prefix{}" required>
            </div>

            <fieldset class="radio" required>
                <legend>Restrict access to site</legend>

                <div>
                    <input type="radio" name="restrict" id="restrictYes" value="1">
                    <label for="restrictYes">YES</label>
                </div>

                <div>
                    <input type="radio" name="restrict" id="restrictNo" value="0">
                    <label for="restrictNo">NO</label>
                </div>
            </fieldset>

            <fieldset class="radio" required>
                <legend>Developer mode</legend>

                <div>
                    <input type="radio" name="devmode" id="devmodeYes" value="1">
                    <label for="devmodeYes">YES</label>
                </div>

                <div>
                    <input type="radio" name="devmode" id="devmodeNo" value="0">
                    <label for="devmodeNo">NO</label>
                </div>
            </fieldset>

            <fieldset class="time">
                <legend>Start time</legend>
                <div>
                    <label for="startDate">Day</label>
                    <input type="date" name="startDate" id="startDate">
                </div>
                <div>
                    <label for="startTime">Time</label>
                    <input type="time" name="startTime" id="startTime">
                </div>
            </fieldset>

            <h2>Database setup</h2>

            <div class="element">
                <label for="dbHost">Host</label>
                <input type="text" name="dbHost" id="dbHost" placeholder="localhost" default="localhost" required>
            </div>

            <div class="element">
                <label for="dbUser">User</label>
                <input type="text" name="dbUser" id="dbUser" placeholder="user" required>
            </div>

            <div class="element">
                <label for="dbPass">Password</label>
                <input type="password" name="dbPass" id="dbPass" placeholder="p4$$w0rd" required>
            </div>

            <div class="element">
                <label for="dbName">Database name</label>
                <input type="text" name="dbName" id="dbName" placeholder="Database" required>
            </div>

            <input type="submit" value="Install">
        </form>
    </section>
<?php } else {
    $settings = array(
        "siteName" => $_POST['siteName'],
        "flagPrefix" => $_POST['flagPrefix'],
        "restrict" => $_POST['restrict'],
        "devmode" => $_POST['devmode'],
        "startDate" => (strlen($_POST['startDate']) < 1) ? false : $_POST['startDate'],
        "startTime" => (strlen($_POST['startTime']) < 1) ? false : $_POST['startTime'],
    );
    $database = array(
        "dbHost" => $_POST['dbHost'],
        "dbName" => $_POST['dbName'],
        "dbUser" => $_POST['dbUser'],
        "dbPass" => $_POST['dbPass']
    );
    try{
        $mysqli = new mysqli($database['dbHost'], $database['dbUser'], $database['dbPass'], $database['dbName']);
    } catch(Exception $e){
        returnError($e);
    }

    // Creating Users
    $sql = "CREATE TABLE IF NOT EXISTS Users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        permissions INT DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        points INT DEFAULT 0)";

    if (!$mysqli->query($sql) === TRUE) {
        returnError($mysqli->error);
    }

    // Creating admin users
    $sql = "INSERT INTO Users (username, password, permissions, points) VALUES ('krystian', '$2y$10\$S6Ih4NIf.sMfIOi6PIOKJOvp0nOq5Hl2yNOHZTak2.hbDc7Xvj.Qq', 2, 0);";

    if (!$mysqli->query($sql) === TRUE) {
        returnError($mysqli->error);
    }

    $sql = "INSERT INTO Users (username, password, permissions, points) VALUES ('damian', '$2y$10\$NL2WUZkZJC5EHxXEnj5/FeNQlM4SJmUm8Nl1DxsQrwt3Rqx0w8Yc6', 2, 0);";

    if (!$mysqli->query($sql) === TRUE) {
        returnError($mysqli->error);
    }

    // Creating Categories
    $sql = "CREATE TABLE IF NOT EXISTS Categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL UNIQUE)";
    if (!$mysqli->query($sql) === TRUE) {
        returnError($mysqli->error);
    }

    // Creating Tasks
    $sql = "CREATE TABLE IF NOT EXISTS Tasks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        file VARCHAR(255),
        flag VARCHAR(255) NOT NULL,
        level VARCHAR(255) NOT NULL,
        points INT NOT NULL,
        category INT,
        author INT,
        FOREIGN KEY (category) REFERENCES Categories(id),
        FOREIGN KEY (author) REFERENCES Users(id))";
    if (!$mysqli->query($sql) === TRUE) {
        returnError($mysqli->error);
    }

    // Creating Solves
    $sql = "CREATE TABLE IF NOT EXISTS Solves (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        task_id INT,
        FOREIGN KEY (user_id) REFERENCES Users(id),
        FOREIGN KEY (task_id) REFERENCES Tasks(id))";
    if (!$mysqli->query($sql) === TRUE) {
        returnError($mysqli->error);
    }

    // Closing mysqli connection
    $mysqli->close();

    // Save database creddentials to config.json
    $jsonData = json_encode(array_merge($database, $settings), JSON_PRETTY_PRINT);
    $result = file_put_contents('../config.json', $jsonData);

    if (json_last_error() !== JSON_ERROR_NONE) {
        returnError("JSON error encoding: " . json_last_error_msg());
    }
    
    if ($result === false) {
        returnError("Error when saving into file. Please check your files permissions");
    }

    ?>
    <section style="text-align: center;">
        <h3 class="green">All good!</h3>
        <p>Please remove /public/install.php</p>
    </section>
    <?php
}
?>
</body>
</html>
