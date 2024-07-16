<?php
// Credentials
$host = "localhost";
$username = "root";
$password = "";

// Database and Table Configuration
$db = "DB_210103020";

$pendaftar = 'tb_pendaftar';
$ortu = 'tb_ortu_pendaftar';
$akun = 'tb_akun_login';


try {
    // Query untuk koneksi
    $conn = new PDO("mysql:host=$host;", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Buat database jika database tidak ada dan 
    $conn->exec("CREATE DATABASE IF NOT EXISTS $db");
        echo "Database $db sudah dibuat <br>";

    // $conn = new PDO("mysql:host=$host;dbname=$db", $username, $password);
    $conn->exec('USE ' . $db);


    // Hapus table jika ada dan buat table jika tidak ada
    $conn->exec("DROP TABLE IF EXISTS $db.$pendaftar,$db.$ortu,$db.$akun");

   

    $conn->exec("CREATE TABLE IF NOT EXISTS tb_pendaftar( 
        id_pendaftar INT(30),
        nama_pendaftar CHAR(250) NOT NULL,
        tanggal_daftar DATE NULL,
        usia INT(25) NOT NULL,
        nomor_hp INT(30) NOT NULL,
        PRIMARY KEY(id_pendaftar)
        )");

    $conn->exec("CREATE TABLE IF NOT EXISTS tb_ortu_pendaftar (
        id_pendaftar INT(30),
        nama_ibu CHAR(80) NOT NULL,
        nama_ayah CHAR(80) NOT NULL,
        alamat_tinggal VARCHAR(80) NOT NULL,
        PRIMARY KEY(id_pendaftar),
        FOREIGN KEY (id_pendaftar) REFERENCES tb_pendaftar(id_pendaftar)
        )");

    $conn->exec("CREATE TABLE IF NOT EXISTS tb_akun_login (
        id_pendaftar INT(30),
        password VARCHAR(80) NOT NULL,
        status int(5) NOT NULL,
        PRIMARY KEY(id_pendaftar),
        FOREIGN KEY (id_pendaftar) REFERENCES tb_pendaftar(id_pendaftar)
        )");

         // Tambah rows untuk tb_pendaftar
         $records_pendaftar = [
            "INSERT INTO tb_pendaftar (id_pendaftar, nama_pendaftar, tanggal_daftar, usia, nomor_hp) VALUES(000098678, 'Ali', '2022-08-16', '20', '081678921')",
            "INSERT INTO tb_pendaftar (id_pendaftar, nama_pendaftar, tanggal_daftar, usia, nomor_hp) VALUES(000098679, 'Beatriz', '2022-03-17', '21', '081678922')",
            "INSERT INTO tb_pendaftar (id_pendaftar, nama_pendaftar, tanggal_daftar, usia, nomor_hp) VALUES(000098680, 'Charley', '2022-02-18', '19', '081678923')",
            "INSERT INTO tb_pendaftar (id_pendaftar, nama_pendaftar, tanggal_daftar, usia, nomor_hp) VALUES(000098681, 'Diya', '2022-12-19', '19', '081678924')",
            "INSERT INTO tb_pendaftar (id_pendaftar, nama_pendaftar, tanggal_daftar, usia, nomor_hp) VALUES(000098682, 'Eric', '2022-11-20', '19', '081678925')",
            "INSERT INTO tb_pendaftar (id_pendaftar, nama_pendaftar, tanggal_daftar, usia, nomor_hp) VALUES(000098683, 'Fatima', '2022-07-21', '19', '081678926')",
        ];

        foreach ($records_pendaftar as $pendaftar) {
            $conn->exec($pendaftar);
        }

        
        // Tambah rows untuk tb_ortu_pendaftar
        $records_ortu_pendaftar = [
            "INSERT INTO tb_ortu_pendaftar (id_pendaftar, nama_ibu, nama_ayah, alamat_tinggal) VALUES(000098679, 'Fatima', 'Ali', 'solo')",
            "INSERT INTO tb_ortu_pendaftar (id_pendaftar, nama_ibu, nama_ayah, alamat_tinggal) VALUES(000098680, 'Diya', 'Eric', 'sukoharjo')",
            "INSERT INTO tb_ortu_pendaftar (id_pendaftar, nama_ibu, nama_ayah, alamat_tinggal) VALUES(000098681, 'Beatriz', 'Budi', 'kalioso')",
            "INSERT INTO tb_ortu_pendaftar (id_pendaftar, nama_ibu, nama_ayah, alamat_tinggal) VALUES(000098682, 'Lusi', 'Lutfi', 'karanganyar')",
        ];

        foreach ($records_ortu_pendaftar as $ortu) {
            $conn->exec($ortu);
        }
        

        $records_akun_login = [
            // Tambah rows untuk tb_akun_login
            "INSERT INTO tb_akun_login (id_pendaftar, password, status) VALUES(000098679, '123456', '1')",
            "INSERT INTO tb_akun_login (id_pendaftar, password, status) VALUES(000098680, '654321', '1')",
            "INSERT INTO tb_akun_login (id_pendaftar, password, status) VALUES(000098681, '111111', '2')",
            "INSERT INTO tb_akun_login (id_pendaftar, password, status) VALUES(000098683, '221133', '2')",
        ];

        foreach ($records_akun_login as $akun_login) {
            $conn->exec($akun_login);
        }

        // Retrieve records yang tidak ada pada table selain tb_pendaftar
        // $stmt = $conn->query("SELECT * FROM tb_akun_login WHERE id_pendaftar NOT IN (SELECT id_pendaftar FROM tb_pendaftar )"); 
        $stmt = $conn->query("SELECT * FROM tb_pendaftar WHERE NOT EXISTS (SELECT * FROM tb_ortu_pendaftar WHERE tb_pendaftar.id_pendaftar = tb_ortu_pendaftar.id_pendaftar) AND NOT EXISTS (SELECT * FROM tb_akun_login WHERE tb_pendaftar.id_pendaftar = tb_akun_login.id_pendaftar);"); 
        // $stmt = $conn->query("SELECT * FROM tb_akun_login LEFT "); 

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($data as $row) {
            echo $row['id_pendaftar'] . ' ';
            echo $row['nama_pendaftar'] . ' ';
            echo $row['tanggal_daftar'] . ' ';
            echo $row['usia'] . ' ';
            echo $row['nomor_hp'] . ' ';
            echo "<br>";
            }

    } catch(PDOException $e) {
        // Menampilkan pesan error
        echo "Connection failed: " . $e->getMessage();
    }

    $conn = null;
?>