<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="<?= APP_URL ?>style/login.css">
</head>

<body>
  <form method="POST" action="<?= APP_URL ?>login/">
    <h1>Login</h1>
    <label for="username">Username</label>
    <input type="text" name="username" id="username" placeholder="Masukan username anda">
    <label for="password">Password</label>
    <input type="password" name="password" id="password" placeholder="Masukan password anda">
    <button type="submit">Login</button>
  </form>
</body>

</html>