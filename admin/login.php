<?php

  // ambil pesan jika ada  
  if (isset($_GET["pesan"])) {
      $pesan = $_GET["pesan"];
  }
  
  // cek apakah form telah di submit
  if (isset($_POST["submit"])) {
    // form telah disubmit, proses data
   
    // ambil nilai form 
    $username = htmlentities(strip_tags(trim($_POST["username"])));
    $password = htmlentities(strip_tags(trim($_POST["password"])));

    // siapkan variabel untuk menampung pesan error
    $pesan_error="";
    
    // cek apakah "username" sudah diisi atau tidak
    if (empty($username)) {
      $pesan_error .= "Username belum diisi <br>";
    }
    
    // cek apakah "password" sudah diisi atau tidak
    if (empty($password)) {
      $pesan_error .= "Password belum diisi <br>";
    }
    
    // buat koneksi ke mysql dari file connection.php
    include("connection.php");
    
    // filter dengan mysqli_real_escape_string
    $username = mysqli_real_escape_string($link,$username);
    $password = mysqli_real_escape_string($link,$password);
    
    // generate hashing 
    $password_sha1 = sha1($password);
    
    // cek apakah username dan password ada di tabel admin
    $query = "SELECT * FROM admin WHERE username = '$username' 
              AND password = '$password_sha1'";
    $result = mysqli_query($link,$query);
    
    if(mysqli_num_rows($result) == 0 )  { 
      // data tidak ditemukan, buat pesan error
      $pesan_error .= "Username dan/atau Password tidak sesuai";
    }
    
      // bebaskan memory 
      mysqli_free_result($result);
    
      // tutup koneksi dengan database MySQL
      mysqli_close($link);

    // jika lolos validasi, set session 
    if ($pesan_error === "") {
      session_start();
      $_SESSION["nama"] = $username;
      header("Location: tampil_mahasiswa.php");
    }
  }
  else {
    // form belum disubmit atau halaman ini tampil untuk pertama kali 
    // berikan nilai awal untuk semua isian form
    $pesan_error = "";
    $username = "";
    $password = "";
  }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>

    .error {
      background-color: #FFECEC;
      padding: 10px 15px;
      margin: 0 20px 20px 20px;
      border: 1px solid red;
      box-shadow: 1px 0px 3px red;
    }
  </style>

    <link rel="icon" href="../img/favicon.png" type="image/png">
    <title>Login</title>
</head>
<body>

    <div class="overlay"></div>
    
    <div class="container">
        <div class="profile"></div>
        <h1>Login</h1>
		
		<?php
		  // tampilkan pesan jika ada
		  if (isset($pesan)) {
			  echo "<div class=\"pesan\">$pesan</div>";
		  }

		  // tampilkan error jika ada
		  if ($pesan_error !== "") {
			  echo "<div class=\"error\">$pesan_error</div>";
		  }
		?>
		
        <form action="login.php" id="form" method="POST">

            <p>Username</p>
            <input type="text" name="username" id="username" placeholder="Username anda ..." value="<?php echo $username ?>">
            
            <p>Password</p>
            <input type="password" name="password" id="password" placeholder="Password anda ..." value="<?php echo $username ?>">

            <input type="submit" name="submit" value="Login" alt="submit" title="Login">
        </form>
    </div>
</body>
</html>