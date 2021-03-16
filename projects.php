<?php
include 'helpers.php';
error_reporting(-1);
session_start();
if (isset($_SESSION['id'])) {
  $user_id = $_SESSION['id'];
  $user_name = $_SESSION['user'];
  $project_err = '';

  $conn = connect_db();
  $projects = select_projects($conn, $user_id);
  $all_tasks = select_tasks($conn, $user_id);
  $project_searh = false;


  if (isset($_POST['project'])) {
    if ($projects) {
      foreach ($projects as $project) {
        if ($project['project_name'] == $_POST['project']) {
          $project_searh = true;
        }
      }
    }

    if ($project_searh == true) {
      $project_err = 'Такой проект уже есть';
    } elseif ($_POST['project'] == '') {
      $project_err = 'Поле пустует милорд';
    } else {
      $project = htmlspecialchars($_POST['project']);

      $sql = "INSERT INTO projects ( project_name	, user_id)
    VALUES('$project' , '$user_id')";
      if (mysqli_query($conn, $sql)) {
        header('Location: /index.php');
      }
    }
  }

  close_db($conn);
} else header('Location: index.php');



$main_content = include_template('add_projects.php', ['projects' => $projects, 'all_tasks' => $all_tasks, 'project_err' =>  $project_err]);
$layout_content = include_template('layout.php', ['content' => $main_content, 'title' => "Дела в порядке", 'first_name' => "$user_name"]);
echo ($layout_content);
