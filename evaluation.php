<?php
require 'databasescript.php';
$DB = new objDatabaseConnection();

function getTestResultByAssignmentId($assignmentId)
{
    global $DB;
    $result = $DB->readData('SELECT r.* FROM assignment a JOIN result r on a.result_id = r.id WHERE a.id = ' . $assignmentId);
}