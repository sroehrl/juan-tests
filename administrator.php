<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin</title>
    <!-- spectrecss: https://picturepan2.github.io/spectre -->
    <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre.min.css">
    <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre-exp.min.css">
    <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre-icons.min.css">
</head>
<body>
<style>
    .question{
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 7px;
        box-shadow: 2px 3px 1px #ece;
    }
</style>
<div class="container">


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

function getTestModel($id){
    global $DB;
    $test = $DB->readData('SELECT * FROM test WHERE id ='.$id);
    $test = $test[0];
    $test['questions'] = $DB->readData('SELECT * FROM question WHERE test_id = '.$id);
    foreach ($test['questions'] as $i => $question){
        $test['questions'][$i]['choices'] = $DB->readData('SELECT * FROM choice WHERE question_id = '. $question['id']);
    }
    return $test;
}

function displayTestForm($testModel){
    $formHTML = '<form method="post" action="administrator.php?action=update&id='. $testModel['id'].'">';
    $formHTML .= '<input type="hidden" name="id" value="'. $testModel['id'].'">';
    $formHTML .= '<div class="form-group"><input class="form-input" name="name" value="'.$testModel['name'].'" placeholder="name"></div>';
    foreach ($testModel['questions'] as $i => $question){
        $formHTML .= '<div class="question">';
        $formHTML .= '<p>'.($i+1).'. Question </p>';
        $formHTML .= '<textarea class="form-input" rows="5" cols="15" name="question_id-'.$question['id'].'">'.$question['question'].'</textarea>';
        $formHTML .= '<p>Answers</p>';
        foreach($question['choices'] as $choice){
            $markAsChecked = $choice['is_correct'] ? 'checked' : '';
            $formHTML .= '<input class="form-input" name="choice_choice-'.$choice['id'].'" value="'.$choice['choice'].'">';
            $formHTML .= '<input type="checkbox" name="choice_correct-'.$choice['id'].'" '.$markAsChecked.'><br/>';
            $formHTML .= '<a class="btn btn-error m-2" href="administrator.php?action=delete_choice&choice_id='.$choice['id'].'&id='. $testModel['id'].'" >delete choice</a>';
        }
        $formHTML .= '<a class="btn btn-success m-2" href="administrator.php?action=add_choice&question_id='.$question['id'].'&id='. $testModel['id'].'" >add choice</a>';
        $formHTML .= '<p><a class="btn btn-error mt-2" href="administrator.php?action=delete_question&question_id='.$question['id'].'&id='. $testModel['id'].'" >delete question</a></p>';
        $formHTML .= '</div>';
    }
    $formHTML .= '<p><a class="btn btn-success mt-2" href="administrator.php?action=add_question&id='.$testModel['id'].'" >add question</a></p>';
    $formHTML .= '<input type="submit" value="update"></form>';
    return $formHTML;
}

function editView(){
    $testModel = getTestModel($_GET['id']);
    echo displayTestForm($testModel);
}

if(isset($_GET['action'])){
    switch ($_GET['action']){
        case 'delete':
            // here we delete tests
            $connection->query('UPDATE test SET delete_date = NOW() WHERE id ='. (int) $_GET['id']);
            break;
        case 'edit':
            // here we show a test to edit
            editView();
            break;
        case 'delete_choice':
            // here we are going to delete a specific choice & show edit
            $connection->query('UPDATE choice SET delete_date = NOW() WHERE id ='. (int) $_GET['choice_id']);
            editView();
            break;
        case 'delete_question':
            // here we are going to delete a specific question & show edit
            $connection->query('UPDATE question SET delete_date = NOW() WHERE id ='. (int) $_GET['question_id']);
            editView();
            break;
        case 'add_question':
            // adding a question & showing edit
            $connection->query('INSERT INTO question SET question = "new question", test_id ='. (int) $_GET['id']);
            editView();
            break;
        case 'add_choice':
            // add choice to a question
            $connection->query('INSERT INTO choice SET choice = "answer", question_id ='. (int) $_GET['question_id']);
            editView();
            break;
        case 'update':
            // update test
            $connection->query('UPDATE test SET  name = "'.$_POST['name'].'", WHERE id ='. (int) $_POST['id']);
            // update all questions
            // update all choices
            break;
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
        echo "<a style='margin-right: 6px' href='index.php?test={$test['name']}'>go to test</a>";
        echo "<a style='margin-right: 6px' href='administrator.php?action=edit&id={$test['id']}'>edit</a>";
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



</div>
</body>
</html>
