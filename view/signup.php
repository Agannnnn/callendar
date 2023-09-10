<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="<?= APP_URL ?>style/login.css">
</head>

<body>
    <form method="POST" action="<?= APP_URL ?>signup/">
        <h1>Sign Up</h1>
        <?php if (isset($error)): ?>
            <span class="error">Wrong username or password</span>
        <?php endif ?>
        <div class="form-input">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="my_username123">
        </div>
        <div class="form-input">
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
        </div>
        <button type="submit">Sign Up</button>
        <a href="<?= APP_URL ?>login/" class="option">Login</a>
    </form>
</body>

</html>