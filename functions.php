<?php 
// koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "phpdasar");

function query($query){
  global $conn;
  $result = mysqli_query($conn, $query);
  $rows = [];
  while ( $row = mysqli_fetch_assoc($result) ) {
    $rows[] = $row; 
  }
  return $rows;
}

// function menambahkan data
function tambah($data){
  global $conn;

  // ambil data dari tiap elemen dalam form

  // htmlspecialchars berfungsi agar user tidak bisa memasukan elemen html
  $nim = htmlspecialchars($data["nim"]);
  $nama = htmlspecialchars($data["nama"]);
  $email = htmlspecialchars($data["email"]);
  $jurusan = htmlspecialchars($data["jurusan"]);

  // upload gambar
  $gambar = upload();
  if (!$gambar) {
    return false;
  }

  // query insert data
  $query = "INSERT INTO mahasiswa
              VALUES
              ('', '$nim', '$nama', '$email', '$jurusan', '$gambar')
            ";
  mysqli_query($conn,$query);

  return mysqli_affected_rows($conn);
}

// function upload gambar
function upload() {
  $namaFile = $_FILES['gambar']['name'];
  $ukuranFile = $_FILES['gambar']['size'];
  $error = $_FILES['gambar']['error'];
  $tmpName = $_FILES['gambar']['tmp_name'];

  // cek apakah tidak ada gambar yang di upload
  if ($error === 4) { // 4 adalah tidak ada gambar yang di upload
    echo "<script> alert('Pilih Gambar Terlebih Dahulu!') </script>";
    return false;
  }

  // cek apakah yang diupload adalah gambar
  $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
  $ekstensiGambar = explode('.', $namaFile);
  $ekstensiGambar = strtolower(end($ekstensiGambar));
  if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
    echo "<script> alert('Yang anda upload bukan gambar!') </script>";
    return false;
  }
  
  // cek jika ukurannya terlalu besar
  if ($ukuranFile > 1000000 ) {
    echo "<script> alert('Ukurang Gambar Terlalu Besar!') </script>";
    return false;
  }

  // lolos pengecekan, gambar siap di upload
  $namaFileBaru = uniqid(); // generate nama gambar baru
  $namaFileBaru .= '.';
  $namaFileBaru .= $ekstensiGambar;

  move_uploaded_file($tmpName, 'img/' . $namaFileBaru);

  return $namaFileBaru;

}


// function hapus data
function hapus($id) {
  global $conn;
  mysqli_query($conn, "DELETE FROM mahasiswa WHERE id = $id ");
  
  return mysqli_affected_rows($conn);
}

// funtion ubah data
function ubah($data) {
  global $conn;

  // ambil data dari tiap elemen dalam form

  // htmlspecialchars berfungsi agar user tidak bisa memasukan elemen html
  $id = $data["id"];
  $nim = htmlspecialchars($data["nim"]);
  $nama = htmlspecialchars($data["nama"]);
  $email = htmlspecialchars($data["email"]);
  $jurusan = htmlspecialchars($data["jurusan"]);
  $gambarLama = htmlspecialchars($data["gambarLama"]);

  // cek apakah user pilih gambar baru atau tidak
  if ($_FILES['gambar']['error'] === 4) {
    $gambar = $gambarLama;
  } else {
    $gambar = upload();
  }

  // query insert data
  $query = "UPDATE mahasiswa SET
              nim = '$nim',
              nama = '$nama',
              email = '$email',
              jurusan = '$jurusan',
              gambar = '$gambar'
            WHERE id = $id
            ";
  mysqli_query($conn,$query);

  return mysqli_affected_rows($conn);
}

// funtion cari
function cari($keyword) {
  $query = "SELECT * FROM mahasiswa
              WHERE
              -- cari sesuatu dengan fleksibel menggunakan LIKE dan %
              nim LIKE '%$keyword%' OR
              nama LIKE '%$keyword%' OR
              email LIKE '%$keyword%' OR
              jurusan LIKE '%$keyword%'
            ";

      return query($query);
}

// function registrasi
function register($data) {
  global $conn;

  $username = strtolower(stripslashes($data["username"]));
  $password = mysqli_real_escape_string($conn, $data["password"]);
  $password2 = mysqli_real_escape_string($conn, $data["password2"]);

  // cek duplikat username
  $result = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username' ");
  if (mysqli_fetch_assoc($result)) {
    echo "<script> alert('Username sudah terdaftar!') </script>";
    return false;
  }

  // untuk mengatasi string kosong
  if (empty(trim($username))) {
    echo "<script> alert('Username anda kosong!') </script>";
    return false;
  }

  // cek konfirmasi password
  if ($password !== $password2) {
    echo "<script> alert('Konfirmasi Password tidak sesuai!') </script>";
    return false;
  }

  // enkripsi password
  $password = password_hash($password, PASSWORD_DEFAULT);

  // tambahkan user baru ke database
  mysqli_query($conn, "INSERT INTO users VALUES('', '$username', '$password')");

  return mysqli_affected_rows($conn);

}


?>