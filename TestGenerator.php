<?php

require 'databasescript.php';
$DB = new objDatabaseConnection();
$testName = 'readcomptest';

$connection = $DB->openConnection();

$questions = [
    [
        'question' => 'Walking around the Grand Bazaar is exciting. It is like visiting a small 
        town. There are more than 4,000 shops on sixty-five streets. The shops sell almost 
        anything you want or need. You can buy Turkish carpets, jewelry, and clothes. You can 
        also buy food and spices. The Grand Bazaar is always crowded. Every day, 250,000 people visit the Grand Bazaar. 
        If you get hungry or need to relax, there are cafes, restaurants, and tea houses. There 
        are al?  
        The main idea of this text is ________________.  '
        ,
        'choices' => [
            ['choice' => 'the exciting view of a small town', 'is_correct' => true],
            ['choice' => 'How visitors enjoy the Grand Bazaar', 'is_correct' => false]
        ]

    ],

];


$connection->query('INSERT INTO test SET name = "' . $testName . '"');
$testId = $connection->insert_id;

foreach ($questions as $question) {
    $connection->query('INSERT INTO question SET test_id = ' . $testId . ', question = "' . $question['question'] . '"');
    $questionId = $connection->insert_id;
    foreach ($question['choices'] as $choice) {
        $connection->query('INSERT INTO choice SET is_correct = "' . $choice['is_correct'] . '", question_id = ' . $questionId . ', choice = "' . $choice['choice'] . '"');
    }
}
