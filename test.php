<?php
require 'databasescript.php';
$DB = new objDatabaseConnection();

$connection = $DB->openConnection();

$testName = 'readcomptest';


if(isset($_GET['test'])) {
    $test = $_GET['test'];
    $testData = $DB->readData('SELECT * FROM test WHERE name = "' . $test . '"');
    $testName = $testData[0]['name'];
}
    /*
     * [
     *      ['id' => 1 , 'name' => 'readcomptest']
     * ]
     * */

    $questionData = $DB->readData('SELECT * FROM question WHERE test_id = "' . $testData[0]['id'] . '"');
    $testQuestions = [];
    foreach ($questionData as $questionDatum){
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
</head>
<body>
<form method="post">
<?php


foreach ($testQuestions as $question){
    echo '<h3>' . nl2br($question['question']['question']) . '</h3>';
    foreach ($question['choices'] as $choice){
        // <label><input type="checkbox" name="1-1"/>answer 1</label><br>
        echo '<label><input type="checkbox" name="'.$choice['question_id'].'-'.$choice['id'].'"/>'.$choice['choice'].'</label><br>';
    }
}
?>
    <input type="hidden" name="submission" value="test">
    <button type="submit">submit</button>
</form>
</body>
</html>
<?php

// evaluation
if(isset($_POST['submission'])){
    // '1-1' => string 'on'
    // insert into database
    $userId = 1; // $_SESSION['user_id'];
    $testID = $testData[0]['id'];
    $connection->query('INSERT INTO result SET test_id = '. $testID . ', user_id = '. $userId);
    $newResultId = $connection->insert_id;

    // all questions
    $totalQuestions = count($questionData);
    $correctAnswers = 0;

    foreach ($_POST as $key => $answer){
        if(is_numeric(substr($key,0,1)) && $answer){
            $answerParts = explode('-', $key); // [1,1]
            $questionId = $answerParts[0];
            $choiceId = $answerParts[1];
            $connection->query('INSERT INTO result_answer SET result_id = ' . $newResultId .', choice_id = ' . $choiceId);

            // correct?
            $answerKey = $DB->readData('SELECT is_correct FROM choice WHERE id = '. $choiceId);
            if($answerKey[0]['is_correct']){
                $correctAnswers++;
            }

        }
    }

    $score = $correctAnswers / $totalQuestions * 100;

    print_r('YOUR SCORE: '. $score . '%');

    /*foreach ($testQuestions as $single){
        $single['question']['id']
        $single['choices'][...['id']]
    }*/

    // evaluate results
}