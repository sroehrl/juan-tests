<?php
session_start();
require 'databasescript.php';
if(!$_SESSION['logged_in']){
    header('Location: index.php');
}

$user = $_SESSION['user'];

$DB = new objDatabaseConnection();

$connection = $DB->openConnection();

$testName = 'readcomptest';



if (isset($_GET['test'])) {
    $test = $_GET['test'];

    // either you are admin, or assigned
    if($user['is_admin']){
        $testData = $DB->readData('SELECT * FROM test WHERE name = "' . $test . '" AND delete_date IS NULL');
    } else {
        $testData = $DB->readData('SELECT test.*, a.id as assignment_id FROM test JOIN assignment a on test.id = a.test_id AND a.user_id = '. $user['id'].' WHERE test.name = "' . $test . '" AND test.delete_date IS NULL');
        if(empty($testData)){
            header('Location: index.php');
        }
    }


    $testName = $testData[0]['name'];
}
/*
 * [
 *      ['id' => 1 , 'name' => 'readcomptest']
 * ]
 * */

$questionData = $DB->readData('SELECT * FROM question WHERE test_id = "' . $testData[0]['id'] . '"');
$testQuestions = [];
foreach ($questionData as $questionDatum) {
    $testQuestions[] = [
        'question' => $questionDatum,
        'choices' => $DB->readData('SELECT * FROM choice WHERE question_id = ' . $questionDatum['id'])
    ];
}
/*
 * [
 *      ['question' => ['id' => 1 , 'question' => 'how are you?'], 'choices' => [...]]
 * ]
 * */

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $testName ?></title>
    <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre.min.css">
    <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre-exp.min.css">
    <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre-icons.min.css">
</head>
<body>
<div class="container">
    <div class="columns">
        <?php include 'navigation.php' ?>
        <div class="column">
            <?php

            // evaluation
            if (isset($_POST['submission'])) {
                // '1-1' => string 'on'
                // insert into database
                $userId =  $_SESSION['user']['id'];
                $testID = $testData[0]['id'];
                $assignmentId = $testData[0]['assignment_id'];
                $connection->query('INSERT INTO result SET test_id = ' . $testID . ', user_id = ' . $userId);
                $newResultId = $connection->insert_id;

                // all questions
                $totalQuestions = count($questionData);
                $correctAnswers = 0;

                foreach ($_POST as $key => $answer) {
                    if (is_numeric(substr($key, 0, 1)) && $answer) {
                        $answerParts = explode('-', $key); // [1,1]
                        $questionId = $answerParts[0];
                        $choiceId = $answerParts[1];
                        $connection->query('INSERT INTO result_answer SET result_id = ' . $newResultId . ', choice_id = ' . $choiceId);

                        // correct?
                        $answerKey = $DB->readData('SELECT is_correct FROM choice WHERE id = ' . $choiceId);
                        if ($answerKey[0]['is_correct']) {
                            $correctAnswers++;
                        }

                    }
                }

                $score = $correctAnswers / $totalQuestions * 100;

                // set assignment as complete

                $connection->query('UPDATE assignment SET completion_date = NOW(), score = '.round($score).', result_id = ' . $newResultId . '  WHERE id = '. $assignmentId);


                echo '<h1>YOUR SCORE: ' . $score . '% </h1>';


                // evaluate results
            } else { ?>
            <form method="post">
                <?php


                foreach ($testQuestions as $question) {
                    echo '<h3>' . nl2br($question['question']['question']) . '</h3>';
                    foreach ($question['choices'] as $choice) {
                        // <label><input type="checkbox" name="1-1"/>answer 1</label><br>
                        echo '<label><input type="checkbox" name="' . $choice['question_id'] . '-' . $choice['id'] . '"/>' . $choice['choice'] . '</label><br>';
                    }
                }
                ?>
                <input type="hidden" name="submission" value="test">
                <button type="submit">submit</button>
            </form>
            <?php } ?>
        </div>
    </div>
</div>

</body>
</html>
