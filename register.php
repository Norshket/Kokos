<?php
error_reporting(-1);
session_start();
if (isset($_SESSION['id'])) {
  header('Location: /index.php');
}
include 'helpers.php';
$conn = connect_db();
$reg_error = [];

if ($_POST) {
  if ($_POST['name'] == '') {
    $reg_error['name'] = 'Введите имя';
    $_POST['name'] = '';
  } else $name = $_POST['name'];


  if ($_POST['password'] == '') {
    $reg_error['password'] = 'Введите пароль';
  } else $password =  password_hash("{$_POST['password']}", PASSWORD_DEFAULT);


  $sql = "SELECT user_name FROM users WHERE email= '" . $_POST['email'] . "'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
    $search_mail = true;
  }

  $is_mail = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

  if (isset($search_mail) == true) {
    $reg_error['email'] = 'Пользователь с такой email уже зарегистрирован';
    $_POST['email'] = '';
  } elseif ($_POST['email'] == '' || $is_mail == false) {
    $reg_error['email'] = 'Введите корректный Email';
    $_POST['email'] = '';
  } else $email = $_POST['email'];

  if ($_POST['email'] == '' || $_POST['password'] == '' || $_POST['name'] == '') {
    $reg_error['all'] = 'Пожалуйста, исправьте ошибки в форме';
  } else {
    $sql = "INSERT INTO users (	user_name , email, user_password )
      VALUES('$name', '$email','$password' )";
    if (mysqli_query($conn, $sql)) {
      header('Location: /autho.php');
    }
  }
  close_db($conn);
}

$main_content = include_template('register.php', ['reg_error' => $reg_error]);
$layout_content = include_template('layout.php', ['content' => $main_content, 'title' => "Дела в порядке"]);
echo ($layout_content);
