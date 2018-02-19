<?php

require_once "Threading/ThreadManager.php";
require_once "Threading/Task/BaseTask.php";
require_once "Threading/Task/Task.php";

$maxThreads = 5;
$pushNotifications = 30;
echo 'Ejemplo de forking de procesos con PHP con un máximo de ' . $maxThreads . ' hilos' . PHP_EOL . PHP_EOL;
$exampleTask = new Threading\Task\Task();
$multithreadManager = new Threading\ThreadManager();

$cpt = 0;
while (++$cpt <= $pushNotifications)
{
    $multithreadManager->start($exampleTask);
}
