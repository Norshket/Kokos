<?php
error_reporting(-1);
include 'helpers.php';
$user_name = '';
session_start();
if (isset($_SESSION['id'])) {
  $user_id = $_SESSION['id'];
  $user_name = $_SESSION['user'];
  $_SESSION['show'] = '';

  $conn = connect_db();
  $projects = select_projects($conn, $user_id);

  if (isset($_POST['show_ninja'])) {
    if ($_POST['show_ninja'] == 1) {
      $_SESSION['show'] = 0;
    } else {
      $_SESSION['show']  = 1;
    }
  }

  $show_complete_tasks = $_SESSION['show'];

  if (isset($_POST['ninja_place'])) {

    $task_id = $_POST['ninja_place'];
    $sql = "SELECT task_done FROM tasks WHERE id= $task_id AND user_id = $user_id ";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);


    if ($row['task_done'] == 1) {
      $sql = "UPDATE tasks SET task_done=0 WHERE id = $task_id AND user_id = $user_id";
      $result = mysqli_query($conn, $sql);
    } else {
      $sql = "UPDATE tasks SET task_done=1 WHERE id = $task_id AND user_id = $user_id";
      $result = mysqli_query($conn, $sql);
    }
  }
  $all_tasks = select_tasks($conn, $user_id);
  $tasks = select_tasks($conn, $user_id);

  if (isset($projects)) {
    $id = [];
    foreach ($projects as $project) {
      $id[] = $project['id'];
    }
  }
  if ($_GET) {

    if (isset($_GET['search'])) {

      if ($_GET['search'] != '') {
        $searh = htmlspecialchars($_GET['search']);
        $sql = "SELECT * FROM tasks WHERE MATCH(task_name) AGAINST('$searh') AND user_id = $user_id";
        $result = mysqli_query($conn, $sql);
        $tasks = [];
        $error_searh = '';
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            $tasks[] = $row;
          }
        } else {
          $error_searh = 'Такой задачи не найдено';
        }
      } else {
        $error_searh = 'Что бы найти что-нибудь нужно заполнить поле';
      }

      $main_content = include_template('main.php', ['projects' => $projects, 'all_tasks' => $all_tasks, 'tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks, 'error_searh' => $error_searh]);
    } elseif (isset($_GET['tag']) && in_array($_GET['tag'], $id)) {
      $tasks = filtering_by_projects($all_tasks, $_GET['tag']);

      $main_content = include_template('main.php', ['projects' => $projects, 'all_tasks' => $all_tasks, 'tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks]);
    } elseif (isset($_GET['date']) && isset($all_tasks)) {
      $tasks = filter_task($all_tasks, $_GET['date']);

      $main_content = include_template('main.php', ['projects' => $projects, 'all_tasks' => $all_tasks, 'tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks]);
    } else {
      http_response_code(404);
      $main_content = include_template('404.php', ['projects' => $projects, 'all_tasks' => $all_tasks]);
    }
  } else {

    $main_content = include_template('main.php', ['projects' => $projects, 'all_tasks' => $all_tasks, 'tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks]);
  }
} else $main_content = include_template('guest.php');

$layout_content = include_template('layout.php', ['content' => $main_content, 'title' => "Дела в порядке", 'first_name' => "$user_name"]);
echo ($layout_content);
close_db($conn);