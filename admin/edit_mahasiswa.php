<?php
  // periksa apakah user sudah login, cek kehadiran session name 
  // jika tidak ada, redirect ke login.php
  session_start();
  if (!isset($_SESSION["nama"])) {
     header("Location: login.php");
  }
 
  // buka koneksi dengan MySQL
  include("connection.php");
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
<h2>Edit Data Mahasiswa</h2>
<?php
  // tampilkan pesan jika ada
  if ((isset($_GET["pesan"]))) {
      echo "<div class=\"pesan\">{$_GET["pesan"]}</div>";
  }
?>
 <table border="1">
  <tr>
  <th>NPM</th>
  <th>Nama</th>
  <th>Tempat Lahir</th>
  <th>Tanggal Lahir</th>
  <th>Prodi</th>
  <th>Jurusan</th>
  <th>IPK</th>
  </tr>
  <?php
  // buat query untuk menampilkan seluruh data tabel mahasiswa
  $query = "SELECT * FROM mahasiswa ORDER BY nama ASC";
  $result = mysqli_query($link, $query);
  
  if(!$result){
      die ("Query Error: ".mysqli_errno($link).
           " - ".mysqli_error($link));
  }
  
  //buat perulangan untuk element tabel dari data mahasiswa
  while($data = mysqli_fetch_assoc($result))
  {
    echo "<tr>";
    echo "<td>$data[npm]</td>";
    echo "<td>$data[nama]</td>";
    echo "<td>$data[tempat_lahir]</td>";
    echo "<td>$data[tanggal_lahir]</td>";
    echo "<td>$data[prodi]</td>";
    echo "<td>$data[jurusan]</td>";
    echo "<td>$data[ipk]</td>";
    echo "<td>";
    ?>
      <form action="form_edit.php" method="post" >
      <input type="hidden" name="npm" value="<?php echo "$data[npm]"; ?>" >
      <input type="submit" name="submit" value="Edit" >
      </form>
    <?php
    echo "</td>";
    echo "</tr>";
  }
  
  // bebaskan memory 
  mysqli_free_result($result);
  
  // tutup koneksi dengan database mysql
  mysqli_close($link);
  ?>
  </table>
  <div id="footer">
    Copyright © <?php echo date("Y"); ?> Budi Darma
  </div>
</div>
</body>
</html>