<?php
error_reporting(-1);
require_once "helpers.php";
require_once "vendor/autoload.php";

$conn = connect_db();

$transport = new Swift_SmtpTransport("phpdemo.ru", 25);
$transport->setUsername("keks@phpdemo.ru");
$transport->setPassword("htmlacademy");

$mailer = new Swift_Mailer($transport);


$users = user_tasks_today($conn);
close_db($conn);
$data_send = [];

foreach ($users as $user) {

  $data_send[$user['id']]['name'] = $user['user_name'];
  $data_send[$user['id']]['email'] = $user['email'];
  $data_send[$user['id']]['tasks'][] = [
    'title' => $user['task_name'],
    'deadline' => $user['task_deadline']
  ];
};
echo '<pre>';
var_dump($data_send);
echo '</pre>';

foreach ($data_send as $task_date) {

  $message = new Swift_Message();
  $message->setSubject('Уведомление от приложения "Дела в порядке"');
  $message->setFrom('keks@phpdemo.ru');
  $message->setBcc($task_date['email']);
}
$massage_content = 'Привет, ' . $task_date['name'];
foreach ($task_date['tasks'] as $task) {
  $massage_content .= 'у Вас есть великое дело:';
  $massage_content .= $task['title'];
  $massage_content .= ' Запланированное на ' . date('d-m-Y', strtotime($task['deadline'])) . '<br>';
}


$message->addPart($massage_content, 'text/html');
$result = $mailer->send($message);

if ($result) {
  echo "рассылка сделана";
} else {
  echo "всё пропало давай заново";
}
