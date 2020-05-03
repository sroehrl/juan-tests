
<div class="column col-3">
    <ul class="nav">
        <li class="nav-item">
            <a href="index.php">HOME</a>
        </li>
        <?php
        if(!$_SESSION['logged_in']){
            ?>
            <li class="nav-item">
                <a href="login.php">Login / Sign up</a>
            </li>
        <?php
        } elseif($_SESSION['user']['is_admin']){
            ?>
            <li class="nav-item">
                <a href="administrator.php">Administrator</a>
            </li>
            <?php
        }
        if($_SESSION['logged_in']){
            ?>
            <li class="nav-item">
                <a href="logout.php">Log out</a>
            </li>
            <?php
        }
        ?>

    </ul>
</div>