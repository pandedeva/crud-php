<?php 
session_start();

// kalau usernya belum login, pindahkan ke halaman login.php
if (!isset($_SESSION["login"]) ) {
  header("Location: login.php");
  exit;
}

require 'functions.php';

// tampilkan seluruh data mahasiswa tapi urutkan berdasarkan id yang terbesar ke kecil
$mahasiswa = query("SELECT * FROM mahasiswa ORDER BY id DESC");

// jika tombol cari di klik
if (isset($_POST["cari"]) ) {
  // variable mahasiswa akan berisi data hasil pencarian dari function cari, lalu funtion cari mendapatkan data dari apapun yang diketikan oleh user
  $mahasiswa = cari($_POST["keyword"]);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Halaman Admin</title>
  <style>
    body{
      font-family: Arial, Helvetica, sans-serif;
    }
    .loader{
      width: 100px;
      position: absolute;
      top: 105px;
      margin-left: -28px;
      z-index: -1;
      display: none;
    }

    @media print{
      .logout, .form-cari, .tambah-data, .aksi{
        display: none;
      }
    }
  </style>

  <!-- jquery -->
  <script src="./js/jquery-3.6.0.min.js"></script>
  <script src="./js/script.js"></script>
</head>
<style>
  img{
    width: 90px;
    height: 90px;
  }
</style>
<body>

<a href="logout.php" class="logout">Logout</a>
  
  <h1>Daftar Mahasiswa</h1>

  <a href="tambah.php" class="tambah-data">Tambah Data Mahasiswa</a>
  <br><br>

  <!-- membuat tombol search -->
  <form action="" method="POST" class="form-cari">    
    <input type="text" name="keyword" size="30" autofocus placeholder="Masukan keywoard pencarian.." autocomplete="off" id="keyword">
    <button type="submit" name="cari" id="tombol-cari">Cari!</button>

    <img src="./img/loader.gif" alt="loading" class="loader">

  </form>
  <br>

<div id="container">
  <table border="1" cellpadding="10" cellspacing="0">
    <tr>
      <th>No.</th>
      <th class="aksi">Aksi</th>
      <th>Gambar</th>
      <th>NIM</th>
      <th>Nama</th>
      <th>Email</th>
      <th>Jurusan</th>
    </tr>

    <?php $i = 1; ?>
    <?php foreach($mahasiswa as $row) :?>
      <tr>
      <td><?= $i; ?></td>
      <td class="aksi">
        <a href="ubah.php?id=<?= $row["id"]; ?>">Ubah</a> |

        <a href="hapus.php?id=<?= $row["id"] ?>" onclick="return confirm('Yakin Ingin Menghapus?');">Hapus</a>
      </td>
      <td><img src="./img/<?= $row["gambar"] ?>" alt=""></td>
      <td><?= $row["nim"] ?></td>
      <td> <?= $row["nama"] ?> </td>
      <td><?= $row["email"] ?></td>
      <td><?= $row["jurusan"] ?></td>
    </tr>
    <?php $i++;?>
    <?php endforeach; ?>
  </table>
</div>


</body>
</html>