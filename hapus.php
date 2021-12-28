<?php 
session_start();

// kalau usernya belum login, pindahkan ke halaman login.php
if (!isset($_SESSION["login"]) ) {
  header("Location: login.php");
  exit;
}

require 'functions.php';

// menangkap id dari url nya
$id = $_GET["id"];

if ( hapus($id) > 0 ) {
  echo "
          <script>
            alert('Data Berhasil Dihapus');
            document.location.href =  'index.php';
          </script>
        ";
} else {
  echo "
          <script>
            alert('Data Gagal Dihapus');
            document.location.href =  'index.php';
          </script>
        ";
}



?>