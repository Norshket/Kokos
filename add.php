<?php
error_reporting(-1);
include 'helpers.php';
session_start();
if (isset($_SESSION['id'])) {
  $user_id = $_SESSION['id'];
  $user_name = $_SESSION['user'];

  $conn = connect_db();
  $projects = select_projects($conn, $user_id);
  $all_tasks = select_tasks($conn, $user_id);
  $dir = '';
  $arr_id = [];
  $arr_error = [];
  $file_name = '';

  if ($_POST) {

    if ($_POST['project']) {
      foreach ($projects as $project) {
        $arr_id[] = $project['id'];
      }
      if (!in_array($_POST['project'], $arr_id)) {
        $arr_error['project'] = "УКАЖИТЕ СУЩЕСТВУЮЩИЙ ПРОЕКТ";
        $_POST['project'] = '';
      }
    }

    if (empty($_POST['date'])) {
      $task_deadline = $_POST['date'];
    } elseif (is_date_valid($_POST['date']) && strtotime($_POST['date']) >= strtotime(date('Y-m-d')) || is_numeric($_POST['date'])) {
      $task_deadline = $_POST['date'];
    } else {
      $arr_error['date'] = "ВВЕДИТЕ КОРРЕКТНУЮ ДАТУ";
      $task_deadline = 1;
    }

    if (empty($_POST["name"])) {
      $arr_error['name'] = "УКАЖИТЕ ХОТЬ ЧТО-НИБУДЬ";
      $_POST['name'] = '';
    } else {
      $task_name = htmlentities($_POST['name']);
      $project_id = htmlentities($_POST['project']);
    }

    if (!empty($_FILES['file']["tmp_name"])) {
      $dir = "files/" . uniqid();
      mkdir($dir, 0700, true);
      move_uploaded_file($_FILES['file']["tmp_name"], $dir . "/" . $_FILES['file']["name"]);
      $file_name = ($_FILES['file']["name"]);
    }


    if ($task_deadline != 1 && $project_id != '' &&  $task_name != '') {
      if ($task_deadline == '') {
        $sql = "INSERT INTO tasks (project_id ,task_name,user_id ,new_file, file_name )
    VALUES('$project_id',' $task_name', $user_id ,'" . $dir . "/" . $_FILES['file']["name"] . "', '$file_name')";
        if (mysqli_query($conn, $sql)) {
          header('Location: /index.php');
        }
      } else {
        $sql = "INSERT INTO tasks (project_id ,task_name, task_deadline, user_id ,new_file,file_name)
    VALUES('$project_id',' $task_name','$task_deadline', $user_id ,'" . $dir . "/" . $_FILES['file']["name"] . "','$file_name')";
        if (mysqli_query($conn, $sql)) {
          header('Location: /index.php');
        }
      }
    }
    close_db($conn);
  }
} else header('Location: index.php');


$main_content = include_template('add_content.php', ['projects' => $projects, 'all_tasks' => $all_tasks,  'arr_error' => $arr_error]);
$layout_content = include_template('layout.php', ['content' => $main_content, 'title' => "Дела в порядке", 'first_name' => "$user_name"]);
echo ($layout_content);
