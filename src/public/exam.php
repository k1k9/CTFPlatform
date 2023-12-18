<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - CTFCM</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link href="/css/main.css" rel="stylesheet" />
</head>

<body>
    <main>
        <section class="content">
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['username']) && isset($_POST['password'])) {
                    if ($_POST['username'] === "exam" && $_POST['password'] === "awenq32412==") {
                        ?>CTFCM{S1MPL3_W3B_R3QU3ST}<?php
                    }else{
                        echo "files/for-exam.txt";
                    }
                } else {
                    echo "files/for-exam.txt";
                }
                
            } else {
             ?> ACCEPTS ONLY POST REQUESTS<?php   
            }?>
        </section>
    </main>
</body>

</html>