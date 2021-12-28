<?php 
require 'functions.php';
session_start();

// check cookie
if (isset($_COOKIE['id']) && isset($_COOKIE['key']) ) {
  $id = $_COOKIE['id'];
  $key = $_COOKIE['key'];

  // ambil username berdasarkan id
  $result = mysqli_query($conn, "SELECT username FROM user WHERE id = $id");
  $row = mysqli_fetch_assoc($result);

  // check cookie dan username
  if ($key === hash('sha256', $row['username']) ) {
    $_SESSION['login'] = true;
  }
}

// kalau user sudah login, pindahkan ke halaman index.php
if (isset($_SESSION["login"]) ) {
  header("Location: index.php");
  exit;
}

// apakah tombol login sudah di klik atau belum
if (isset($_POST["login"])) {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // cek di dbs apakah ada yang di input oleh user
  $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' ");

  // cek username
  if (mysqli_num_rows($result) === 1 ) {
    
    // cek password
    $row = mysqli_fetch_assoc($result);
    if (password_verify($password, $row["password"])) {
      // set session
      $_SESSION["login"] = true;

      // check remember me
      if (isset($_POST['remember']) ) {
        // buat cookie

        setcookie('id', $row['id'], time() + 60);
        setcookie('key', hash('sha256', $row['username']), time() + 60);
      }

      // kalau user berhasil login, pindah ke index.php
      header("Location: index.php");
      exit;
    } 

  }

  $error = true;

}




?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Halaman Login</title>
</head>
<body>
  
  <h1>Halaman Login</h1>

  <?php if( isset($error) ) : ?>
    <p style="color:red; font-style:italic;" >username / password salah</p>
  <?php endif; ?>

  <form action="" method="POST" >
    <ul>
      <li>
        <label for="username">Username : </label>
        <input type="text" name="username" id="username">
      </li>
      <li>
        <label for="password">Password : </label>
        <input type="password" name="password" id="password">
      </li>
      <li>
        <input type="checkbox" name="remember" id="remember">
        <label for="remember">Remember me : </label>
      </li>
      <li>
        <button type="submit" name="login">Login</button>
      </li>
    </ul>
  </form>

</body>
</html>