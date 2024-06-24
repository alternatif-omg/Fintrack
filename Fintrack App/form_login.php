<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="style_register.css">
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img src="Group 5.png" alt="Logo">
        </div>

        <form action="aksi_login.php?op=in" method="POST" class="login-form">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit" name="login">Login</button>
            </div>
        </form>

        <p>Don't have an account? <a href="form_register.php">Register</a></p>
    </div>
</body>
</html>
