<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin</title>
</head>
<body>
<?php

/*
 * PART 1: Maintain tests
 */

// list of test
require 'databasescript.php';
$DB = new objDatabaseConnection();

$connection = $DB->openConnection();



/*
 * actions
*/

if(isset($_GET['action'])){
    // delete
    if($_GET['action'] === 'delete'){
        $connection->query('UPDATE test SET delete_date = NOW() WHERE id ='. (int) $_GET['id']);
    }
}

$availableTests = $DB->readData('SELECT * FROM test WHERE delete_date IS NULL');
?>
<table>
    <tr>
        <td>Test name</td>
        <td>actions</td>
    </tr>
    <?php
    foreach ($availableTests as $test){
        echo "<tr>";
        echo "<td><a href='index.php?test={$test['name']}'>{$test['name']}</a></td>";
        echo "<td>";
        echo "<a style='margin-right: 6px' href='index.php?test={$test['name']}'>edit</a>";
        echo "<a href='administrator.php?action=delete&id={$test['id']}'>delete</a>";
        echo "</td>";
        echo "</tr>";
    }
    ?>
</table>



<?php
/*
 * PART 2: Analysing test results & administer
 */
?>




</body>
</html>
