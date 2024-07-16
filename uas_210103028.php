<?php
// Credentials
$host       = "localhost";
$username   = "root";
$password   = "";

// Database and Table Configuration
$db         = "DB_210103028";

$pendaftar  = 'tb_pendaftar';
$ortu       = 'tb_ortu_pendaftar';
$akun       = 'tb_akun_login';


try {
    /* 1. Buat koneksi dengan MySQL menggunakan PDO selanjutnya buat database dengan 
          nama ”DB_NIM masing-masing”*/ 
    $conn = new PDO("mysql:host=$host;", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Buat database
    $conn->exec("CREATE DATABASE IF NOT EXISTS $db");
        echo "Database $db sudah dibuat \n\n";
        echo "<br>";

    // $conn = new PDO("mysql:host=$host;dbname=$db", $username, $password);
    $conn->exec('USE ' . $db);
    
    // 2. Hapus tabel ”tb_pendaftar”, ”tb_ortu_pendaftar”, dan ”tb_akun_login” jika ada
    $conn->exec("DROP TABLE IF EXISTS $db.$ortu, $db.$akun, $db.$pendaftar");


    /* 3. Buat tabel dengan nama ”tb_pendaftar”, ”tb_ortu_pendaftar”, dan ”tb_akun_login”.
          (tipe data dan panjang data bebas)*/
    // Buat Table Pendaftar
    $conn->exec("CREATE TABLE IF NOT EXISTS DB_210103028.tb_pendaftar( 
        id_pendaftar VARCHAR(15),
        nama_pendaftar VARCHAR(30) NOT NULL,
        tanggal_daftar DATE NULL,
        usia INT(2) NOT NULL,
        nomor_hp VARCHAR(15) NOT NULL,
        PRIMARY KEY(id_pendaftar)
        )");
    // Buat Table ortu_Pendaftar
    $conn->exec("CREATE TABLE IF NOT EXISTS DB_210103028.tb_ortu_pendaftar (
        id_pendaftar VARCHAR(15),
        nama_ibu VARCHAR(30) NOT NULL,
        nama_ayah VARCHAR(30) NOT NULL,
        alamat_tinggal VARCHAR(50) NOT NULL,
        PRIMARY KEY(id_pendaftar),
        FOREIGN KEY (id_pendaftar) REFERENCES tb_pendaftar(id_pendaftar)
        )");
    // Buat Table Akun_login
    $conn->exec("CREATE TABLE IF NOT EXISTS DB_210103028.tb_akun_login (
        id_pendaftar VARCHAR(15),
        password VARCHAR(32) NOT NULL,
        status int(5) NOT NULL,
        PRIMARY KEY(id_pendaftar),
        FOREIGN KEY (id_pendaftar) REFERENCES tb_pendaftar(id_pendaftar)
        )");


    // 4. Tambahkan record seperti berikut
        // Tambah rows untuk tb_pendaftar
        $records_pendaftar = [
          "INSERT INTO DB_210103028.tb_pendaftar (id_pendaftar, nama_pendaftar, tanggal_daftar, usia, nomor_hp) VALUES('000098678', 'Ali',     '2022-08-16', '20', '081678921')",
          "INSERT INTO DB_210103028.tb_pendaftar (id_pendaftar, nama_pendaftar, tanggal_daftar, usia, nomor_hp) VALUES('000098679', 'Beatriz', '2022-03-17', '21', '081678922')",
          "INSERT INTO DB_210103028.tb_pendaftar (id_pendaftar, nama_pendaftar, tanggal_daftar, usia, nomor_hp) VALUES('000098680', 'Charles', '2022-02-18', '19', '081678923')",
          "INSERT INTO DB_210103028.tb_pendaftar (id_pendaftar, nama_pendaftar, tanggal_daftar, usia, nomor_hp) VALUES('000098681', 'Diya',    '2022-12-19', '19', '081678924')",
          "INSERT INTO DB_210103028.tb_pendaftar (id_pendaftar, nama_pendaftar, tanggal_daftar, usia, nomor_hp) VALUES('000098682', 'Eric',    '2022-11-20', '19', '081678925')",
          "INSERT INTO DB_210103028.tb_pendaftar (id_pendaftar, nama_pendaftar, tanggal_daftar, usia, nomor_hp) VALUES('000098683', 'Fatima',  '2022-07-21', '19', '081678926')"
        ];
        foreach ($records_pendaftar as $pendaftar) {
          $conn->exec($pendaftar);
        }
        // Tambah rows untuk tb_ortu_pendaftar
        $records_ortu_pendaftar = [
          "INSERT INTO DB_210103028.tb_ortu_pendaftar (id_pendaftar, nama_ibu, nama_ayah, alamat_tinggal) VALUES('000098679', 'Fatima',  'Ali',   'solo')",
          "INSERT INTO DB_210103028.tb_ortu_pendaftar (id_pendaftar, nama_ibu, nama_ayah, alamat_tinggal) VALUES('000098680', 'Diya',    'Eric',  'sukoharjo')",
          "INSERT INTO DB_210103028.tb_ortu_pendaftar (id_pendaftar, nama_ibu, nama_ayah, alamat_tinggal) VALUES('000098681', 'Beatriz', 'Budi',  'kalioso')",
          "INSERT INTO DB_210103028.tb_ortu_pendaftar (id_pendaftar, nama_ibu, nama_ayah, alamat_tinggal) VALUES('000098682', 'Lusi',    'Lutfi', 'karanganyar')"
        ];
        foreach ($records_ortu_pendaftar as $ortu) {
          $conn->exec($ortu);
        }
        // Tambah rows untuk tb_akun_login
        $records_akun_login = [
          "INSERT INTO DB_210103028.tb_akun_login (id_pendaftar, password, status) VALUES('000098679', '123456', '1')",
          "INSERT INTO DB_210103028.tb_akun_login (id_pendaftar, password, status) VALUES('000098680', '654321', '1')",
          "INSERT INTO DB_210103028.tb_akun_login (id_pendaftar, password, status) VALUES('000098681', '111111', '2')",
          "INSERT INTO DB_210103028.tb_akun_login (id_pendaftar, password, status) VALUES('000098683', '221133', '2')"
        ];
        foreach ($records_akun_login as $akun_login) {
            $conn->exec($akun_login);
        }


    /* 5. Tampilkan record ”tb_pendaftar” yang tidak ada di ”tb_ortu_pendaftar” dan
          ”tb_akun_login”*/
        // Retrieve records yang tidak ada pada table selain tb_pendaftar
        $stmt = $conn->query("SELECT * FROM tb_pendaftar WHERE NOT EXISTS (SELECT * FROM tb_ortu_pendaftar WHERE tb_pendaftar.id_pendaftar = tb_ortu_pendaftar.id_pendaftar) AND NOT EXISTS (SELECT * FROM tb_akun_login WHERE tb_pendaftar.id_pendaftar = tb_akun_login.id_pendaftar);"); 
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($data as $row) {
            echo "<br>";
            echo "ID Pendaftar   : ";     echo $row['id_pendaftar'] . ' ';    echo "<br>";
            echo "Nama Pendaftar : ";     echo $row['nama_pendaftar'] . ' ';  echo "<br>";
            echo "Tanggal daftar : ";     echo $row['tanggal_daftar'] . ' ';  echo "<br>";
            echo "Usia Pendaftar : ";     echo $row['usia'] . ' ';            echo "<br>";
            echo "Nohp Pendaftar : ";     echo $row['nomor_hp'] . ' ';        echo "<br><br>"; 
            }

    } catch(PDOException $error) {
        // Menampilkan pesan error
        echo "Connection failed: " . $error->getMessage();
    }

    $conn = null;
?>