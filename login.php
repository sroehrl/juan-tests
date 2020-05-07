<?php
session_start();
require 'databasescript.php';
$DB = new objDatabaseConnection();
ini_set('error_reporting',E_ALL);
ini_set('display_errors', true);

$connection = $DB->openConnection();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LOGIN</title>
    <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre.min.css">
    <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre-exp.min.css">
    <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre-icons.min.css">
</head>
<body>
<div class="container">
    <?php
        if(isset($_POST['userName'])){

            if(isset($_POST['signup']) && $_POST['signup']){
                // new user
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $connection->query('INSERT INTO user SET userName = "' . $_POST['userName'] .'", password = "'. $password .'"');
                if($connection->insert_id){
                   /* $_SESSION['logged_in'] = true;
                    $_SESSION['user'] = $_POST;
                    $_SESSION['user']['is_admin'] = false;
                    $_SESSION['user']['id'] = $connection->insert_id;*/
                    header('Location: administrator.php');
                }
            } else {
                // known user
                $user = $DB->readData('SELECT * FROM user WHERE userName = "' . $_POST['userName'] . '"');
                if(!empty($user) && password_verify($_POST['password'], $user[0]['password'])){
                    $_SESSION['logged_in'] = true;
                    $_SESSION['user'] = $user[0];
                    if($user[0]['is_admin']){
                        header('Location: administrator.php');
                    } else {
                        header('Location: index.php');
                    }

                }
            }
            echo '<h3>Error: credentials not valid or userName not unique</h3>';


        }

    ?>
    <form method="post">
        <div class="form-group">
            <label class="form-label" for="userName">User name</label>
            <input class="form-input" name="userName" required minlength="4" type="text" id="userName">
        </div>
        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input class="form-input" name="password" required minlength="6" type="password" id="password">
        </div>
        <div>
            <p>Please reach out to an administrator to create a new account</p>
        </div>
        <div>
            <button class="btn btn-success" type="submit">LOGIN</button>
        </div>
    </form>
</div>
</body>
</html>
