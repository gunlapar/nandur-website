
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="login_page.css">
    <title>Login Nandur</title>
</head>

<body>

    <div class="container" id="container"> 
        <!-- Form Registrasi -->
        <div class="form-container sign-up">
            <form action="proses_rgs.php" href="/var/www/html/halaman_utama/home.html" method="POST">
                <h1>Buat Akun</h1>
                <span>Gunakan Email untuk registrasi</span>
                <input type="hidden" name="action" value="register">
                <input type="text" name="nama" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <select name="role" required>
                    <option value="pemilik_lahan">Pemilik Lahan</option>
                    <option value="petani">Petani</option>
                </select>
                <a href="../home.html"><button type="submit">Sign Up</button></a>
            </form>
        </div>

        <!-- Form Login -->
        <div class="form-container sign-in">
            <form action="proses_lgn.php" method="POST">
                <h1>Sign In</h1>
                <span>Masukkan Email dan Password</span>
                <input type="hidden" name="action" value="login">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
               <a href="../home.html"><button type="submit">Sign In</button></a>
            </form>
        </div>

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Selamat Datang!</h1>
                    <p>Masukkan Data diri anda jika sudah memiliki akun</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Selamat Datang</h1>
                    <p>Registrasi Data diri Anda jika belum memiliki akun</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script src="login_page.js"></script>
</body>

</html>
