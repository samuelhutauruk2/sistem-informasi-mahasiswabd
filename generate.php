<?php
  // buat koneksi dengan database mysql
  $dbhost = "localhost";
  $dbuser = "root";
  $dbpass = "";
  $link = mysqli_connect($dbhost,$dbuser,$dbpass);
  
  //periksa koneksi, tampilkan pesan kesalahan jika gagal
  if(!$link){
    die ("Koneksi dengan database gagal: ".mysqli_connect_errno().
         " - ".mysqli_connect_error());
  }
  
  //buat database kampusku jika belum ada
  $query = "CREATE DATABASE IF NOT EXISTS budidarma";
  $result = mysqli_query($link, $query);
  
  if(!$result){
    die ("Query Error: ".mysqli_errno($link).
         " - ".mysqli_error($link));
  }
  else {
    echo "Database <b>'budidarma'</b> berhasil dibuat... <br>";
  }
  
  //pilih database budidarma
  $result = mysqli_select_db($link, "budidarma");
  
  if(!$result){
    die ("Query Error: ".mysqli_errno($link).
         " - ".mysqli_error($link));
  }
  else {
    echo "Database <b>'budidarma'</b> berhasil dipilih... <br>";
  }
 
  // cek apakah tabel mahasiswa sudah ada. jika ada, hapus tabel
  $query = "DROP TABLE IF EXISTS mahasiswa";
  $hasil_query = mysqli_query($link, $query);
  
  if(!$hasil_query){
    die ("Query Error: ".mysqli_errno($link).
         " - ".mysqli_error($link));
  }
  else {
    echo "Tabel <b>'mahasiswa'</b> berhasil dihapus... <br>";
  }
  
  // buat query untuk CREATE tabel mahasiswa
  $query  = "CREATE TABLE mahasiswa (npm CHAR(8), nama VARCHAR(100), "; 
  $query .= "tempat_lahir VARCHAR(50), tanggal_lahir DATE, ";
  $query .= "prodi VARCHAR(50), jurusan VARCHAR(50), ";
  $query .= "ipk DECIMAL(3,2), PRIMARY KEY (npm))";

  $hasil_query = mysqli_query($link, $query);
  
  if(!$hasil_query){
      die ("Query Error: ".mysqli_errno($link).
           " - ".mysqli_error($link));
  }
  else {
    echo "Tabel <b>'mahasiswa'</b> berhasil dibuat... <br>";
  }
  
  // buat query untuk INSERT data ke tabel mahasiswa
  $query  = "INSERT INTO mahasiswa VALUES "; 
  $query .= "('16110614', 'Samuel Hutauruk', 'Laguboti', '1998-02-04', ";
  $query .= "'S1', 'Teknik Informatika', 3.9), ";
  $query .= "('16111044', 'Rudi Permana', 'Bandung', '1997-08-22', ";
  $query .= "'S1', 'Teknik Informatika', 2.9), ";
  $query .= "('16003036', 'Sari Citra Lestari', 'Jakarta', '1997-12-31', ";
  $query .= "'D3', 'Manajemen Informatika', 3.5), ";
  $query .= "('15002032', 'Rina Kumala Sari', 'Jakarta', '1997-06-28', ";
  $query .= "'D1', 'Desain Grafis', 3.4), ";
  $query .= "('13012012', 'James Situmorang', 'Medan', '1995-04-02', ";
  $query .= "'S1','Sistem Informasi', 2.7)";

  $hasil_query = mysqli_query($link, $query);
  
  if(!$hasil_query){
      die ("Query Error: ".mysqli_errno($link).
           " - ".mysqli_error($link));
  }
  else {
    echo "Tabel <b>'mahasiswa'</b> berhasil diisi... <br>";
  }
    
  // cek apakah tabel admin sudah ada. jika ada, hapus tabel
  $query = "DROP TABLE IF EXISTS admin";
  $hasil_query = mysqli_query($link, $query);
  
  if(!$hasil_query){
    die ("Query Error: ".mysqli_errno($link).
         " - ".mysqli_error($link));
  }
  else {
    echo "Tabel <b>'admin'</b> berhasil dihapus... <br>";
  }
  
  // buat query untuk CREATE tabel admin
  $query  = "CREATE TABLE admin (username VARCHAR(50), password CHAR(40))"; 
  $hasil_query = mysqli_query($link, $query);
  
  if(!$hasil_query){
      die ("Query Error: ".mysqli_errno($link).
           " - ".mysqli_error($link));
  }
  else {
    echo "Tabel <b>'admin'</b> berhasil dibuat... <br>";
  }
  
  // buat username dan password untuk admin
  $username = "admin";
  $password = sha1("admin");
  
  // buat query untuk INSERT data ke tabel admin
  $query  = "INSERT INTO admin VALUES ('$username','$password')"; 

  $hasil_query = mysqli_query($link, $query);
  
  if(!$hasil_query){
      die ("Query Error: ".mysqli_errno($link).
           " - ".mysqli_error($link));
  }
  else {
    echo "Tabel <b>'admin'</b> berhasil diisi... <br>";
  }
  
  // tutup koneksi dengan database mysql
  mysqli_close($link);
?>