<?php
error_reporting(-1);
include 'helpers.php';
$conn = connect_db();
$error_user = [];
$email = '';
$user = '';


if (!empty($_POST)) {
  $email = $_POST['email'];
  $sql = "SELECT `user_name`,`user_password`,`id` FROM users WHERE email = '$email' ";
  $result = mysqli_query($conn, $sql);
  $user = mysqli_fetch_assoc($result);
  $pass=$_POST['password'];

  if (!isset($user) && $_POST['email'] != '') {
    $error_user['email'] = 'Введите корректный Email';
  } elseif ($_POST['email'] == '') {
    $error_user['email'] = 'Введите Email';
    $email = '';
  } else {
    $email = $_POST['email'];

    if ($_POST['password'] == '') {
      $error_user['password'] = 'Введите пароль';
     
    } elseif (!password_verify($pass, $user['user_password'])) {
      $error_user['password'] = 'Введите верный пароль';
     
    } else {
      session_start();
      $_SESSION['id'] = $user['id'];
      $_SESSION['user'] = $user['user_name'];
      header('Location: index.php');
    }
  }
  }

close_db($conn);

$main_content = include_template('form_autho.php', ['error_user' => $error_user]);
$layout_content = include_template('layout.php', ['content' => $main_content, 'title' => "Дела в порядке"]);
echo ($layout_content);
