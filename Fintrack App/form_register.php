<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi</title>
    <link rel="stylesheet" href="style_register.css">
</head>
<body>
    <div class="container">
    <div style="text-align: center;">
    <img src="Group 5.png" alt="Logo" style="width: 300px; height: auto; border-radius: 5px;">
    </div>

                    
    <form action="aksi_register.php" method="POST">
    <div class="form-group" style="margin-top: 30px;">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
    </div>
    <div class="form-group" style="margin-top: 10px;">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div class="form-group" style="margin-top: 10px;">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div class="form-group" style="margin-top: 20px;">
        <button type="submit" name="register">Register</button>
    </div>
</form>

        <p>Sudah punya akun? <a href="form_login.php">Login</a></p>
    </div>
</body>
</html>
