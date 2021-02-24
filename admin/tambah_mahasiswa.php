<?php
  // periksa apakah user sudah login, cek kehadiran session name 
  // jika tidak ada, redirect ke login.php
  session_start();
  if (!isset($_SESSION["nama"])) {
     header("Location: login.php");
  }
  
  // buka koneksi dengan MySQL
  include("connection.php");
  
  // cek apakah form telah di submit
  if (isset($_POST["submit"])) {
    // form telah disubmit, proses data
    
    // ambil semua nilai form
    $npm = htmlentities(strip_tags(trim($_POST["npm"])));
    $nama = htmlentities(strip_tags(trim($_POST["nama"])));
    $tempat_lahir = htmlentities(strip_tags(trim($_POST["tempat_lahir"])));
    $prodi = htmlentities(strip_tags(trim($_POST["prodi"])));
    $jurusan = htmlentities(strip_tags(trim($_POST["jurusan"])));
    $ipk = htmlentities(strip_tags(trim($_POST["ipk"])));
    $tgl = htmlentities(strip_tags(trim($_POST["tgl"])));
    $bln = htmlentities(strip_tags(trim($_POST["bln"])));
    $thn = htmlentities(strip_tags(trim($_POST["thn"])));
    
    // siapkan variabel untuk menampung pesan error
    $pesan_error="";
    
    // cek apakah "npm" sudah diisi atau tidak
    if (empty($npm)) {
      $pesan_error .= "npm belum diisi <br>";
    }
    // npm harus angka dengan 8 digit
    elseif (!preg_match("/^[0-9]{8}$/",$npm) ) {
      $pesan_error .= "npm harus berupa 8 digit angka <br>";
    }
    
    // cek ke database, apakah sudah ada nomor npm yang sama    
    // filter data $npm
    $npm = mysqli_real_escape_string($link,$npm);
    $query = "SELECT * FROM mahasiswa WHERE npm='$npm'";
    $hasil_query = mysqli_query($link, $query);
  
    // cek jumlah record (baris), jika ada, $npm tidak bisa diproses
    $jumlah_data = mysqli_num_rows($hasil_query);
     if ($jumlah_data >= 1 ) {
       $pesan_error .= "npm yang sama sudah digunakan <br>";  
    }

    // cek apakah "nama" sudah diisi atau tidak
    if (empty($nama)) {
      $pesan_error .= "Nama belum diisi <br>";
    }
    
    // cek apakah "tempat lahir" sudah diisi atau tidak
    if (empty($tempat_lahir)) {
      $pesan_error .= "Tempat lahir belum diisi <br>";
    }
    
    // cek apakah "jurusan" sudah diisi atau tidak
    if (empty($jurusan)) {
      $pesan_error .= "Jurusan belum diisi <br>";
    }
           
    // siapkan variabel untuk menggenerate pilihan prodi
    $select_s1=""; $select_d3=""; $select_d1="";
    
    switch($prodi) {
     case "S1"         : $select_s1         = "selected";  break;
     case "D3"         : $select_d3         = "selected";  break;
     case "D1"         : $select_d1         = "selected";  break;
    } 
    
    
    // IPK harus berupa angka dan tidak boleh negatif
    if (!is_numeric($ipk) OR ($ipk <=0)) {
      $pesan_error .= "IPK harus diisi dengan angka";
    }   
    
    // jika tidak ada error, input ke database
    if ($pesan_error === "") {
      
      // filter semua data
      $npm = mysqli_real_escape_string($link,$npm);
      $nama = mysqli_real_escape_string($link,$nama );
      $tempat_lahir = mysqli_real_escape_string($link,$tempat_lahir);
      $prodi = mysqli_real_escape_string($link,$prodi);
      $jurusan = mysqli_real_escape_string($link,$jurusan);
      $tgl = mysqli_real_escape_string($link,$tgl);
      $bln = mysqli_real_escape_string($link,$bln);
      $thn = mysqli_real_escape_string($link,$thn);
      $ipk = (float) $ipk;
      
      //gabungkan format tanggal agar sesuai dengan date MySQL
      $tgl_lhr = $thn."-".$bln."-".$tgl;
      
      //buat dan jalankan query INSERT
      $query = "INSERT INTO mahasiswa VALUES ";
      $query .= "('$npm', '$nama', '$tempat_lahir', ";
      $query .= "'$tgl_lhr','$prodi','$jurusan',$ipk)";

      $result = mysqli_query($link, $query);
      
      //periksa hasil query
      if($result) {
      // INSERT berhasil, redirect ke tampil_mahasiswa.php + pesan
        $pesan = "Mahasiswa dengan nama = \"<b>$nama</b>\" sudah berhasil di tambah";
        $pesan = urlencode($pesan);
        header("Location: tampil_mahasiswa.php?pesan={$pesan}");
      } 
      else { 
      die ("Query gagal dijalankan: ".mysqli_errno($link).
           " - ".mysqli_error($link));
      }    
    }
  }
  else {
    // form belum disubmit atau halaman ini tampil untuk pertama kali 
    // berikan nilai awal untuk semua isian form
    $pesan_error = "";
    $npm = "";
    $nama = "";
    $tempat_lahir = "";
    $select_s1="selected"; 
    $select_d3=""; $select_d1="";
    $jurusan = "";
    $ipk="";
    $tgl=1;$bln="1";$thn=1996;
  }

  // siapkan array untuk nama bulan
  $arr_bln = array( "1"=>"Januari",
                    "2"=>"Februari",
                    "3"=>"Maret",
                    "4"=>"April",
                    "5"=>"Mei",
                    "6"=>"Juni",
                    "7"=>"Juli",
                    "8"=>"Agustus",
                    "9"=>"September",
                    "10"=>"Oktober",
                    "11"=>"Nopember",
                    "12"=>"Desember" );
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Sistem Informasi Mahasiswa</title>
  <link href="style.css" rel="stylesheet" >
  <link rel="icon" href="favicon.png" type="image/png" >
  <link rel="icon" href="../img/favicon.png" type="image/png">
</head>
<body>
<div class="container">
<div id="header">
<div class="logobd"></div>
  <h1 id="logo">Sistem Informasi <span>Mahasiswa</span></h1>
  <p id="tanggal"><?php echo date("d M Y"); ?></p>
</div>
<hr>
  <nav>
  <ul>
    <li><a href="tampil_mahasiswa.php">Tampil</a></li>
    <li><a href="tambah_mahasiswa.php">Tambah</a>
    <li><a href="edit_mahasiswa.php">Edit</a>
    <li><a href="hapus_mahasiswa.php">Hapus</a></li>
    <li><a href="logout.php">Logout</a>
  </ul>
  </nav>
  <form id="search" action="tampil_mahasiswa.php" method="get">
    <p>
      <label for="npm">Nama : </label> 
      <input type="text" name="nama" id="nama" placeholder="search..." >
      <input type="submit" name="submit" value="Search">
    </p>
  </form>
<h2>Tambah Data Mahasiswa</h2>
<?php
  // tampilkan error jika ada
  if ($pesan_error !== "") {
      echo "<div class=\"error\">$pesan_error</div>";
  }
?>
<form id="form_mahasiswa" action="tambah_mahasiswa.php" method="post">
<fieldset>
<legend>Mahasiswa Baru</legend>
  <p>
    <label for="npm">NPM : </label> 
    <input type="text" name="npm" id="npm" value="<?php echo $npm ?>"
    placeholder="Contoh: 12345678">
    (8 digit angka)
  </p>
  <p>
    <label for="nama">Nama : </label> 
    <input type="text" name="nama" id="nama" value="<?php echo $nama ?>">
  </p>
  <p>
    <label for="tempat_lahir">Tempat Lahir : </label> 
    <input type="text" name="tempat_lahir" id="tempat_lahir" 
    value="<?php echo $tempat_lahir ?>">
  </p>
  <p>
    <label for="tgl" >Tanggal Lahir : </label> 
      <select name="tgl" id="tgl">
        <?php
          for ($i = 1; $i <= 31; $i++) {
            if ($i==$tgl){
              echo "<option value = $i selected>";
            }
            else {
              echo "<option value = $i >";
            }
            echo str_pad($i,2,"0",STR_PAD_LEFT);
            echo "</option>";
          }
        ?>
      </select>
        <select name="bln">
        <?php 
        foreach ($arr_bln as $key => $value) {
          if ($key==$bln){
            echo "<option value=\"{$key}\" selected>{$value}</option>";
          }
          else {
            echo "<option value=\"{$key}\">{$value}</option>";
          } 
        } 
        ?>
      </select>
      <select name="thn">
        <?php
          for ($i = 1990; $i <= 2005; $i++) {
          if ($i==$thn){
              echo "<option value = $i selected>";
            }
            else {
              echo "<option value = $i >";
            }
            echo "$i </option>";
          }
        ?>
      </select>
  </p>
  <p>
    <label for="prodi" >Prodi : </label> 
      <select name="prodi" id="prodi">
        <option value="S1" <?php echo $select_s1 ?>>
        S1 </option>
        <option value="D3" <?php echo $select_d3 ?>>
        D3</option>
        <option value="D1" <?php echo $select_d1 ?>>
        D1</option>
      </select>
  </p>
  <p>
    <label for="jurusan">Jurusan : </label> 
    <input type="text" name="jurusan" id="jurusan" value="<?php echo $jurusan ?>">
  </p>
  <p >
    <label for="ipk">IPK : </label> 
    <input type="text" name="ipk" id="ipk" value="<?php echo $ipk ?>"
    placeholder="Contoh: 2.75">
    (angka desimal dipisah dengan karakter titik ".")
  </p>
  
</fieldset>
  <br>
  <p>
    <input type="submit" name="submit" value="Tambah Data">
  </p>
</form> 
  
  <div id="footer">
    Copyright © <?php echo date("Y"); ?> Budi Darma
  </div>
  
</div>

</body>
</html>
<?php
  // tutup koneksi dengan database mysql
  mysqli_close($link);
?>