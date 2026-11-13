<?php
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
$isLocal = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1']);

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $isHttps || $isLocal,
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();

if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(16));
}

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['token'] !== $_SESSION['token']) {
        $msg = "Invalid CSRF token!";
    } 
    elseif (isset($_POST['login'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = 'admin';
        $msg = "Logged in! Session ID regenerated.";
    } 
    elseif (isset($_POST['set_cookies'])) {
        setcookie('normal_cookie', 'hello', [
            'expires' => time() + 3600, 'path' => '/', 'httponly' => false, 'samesite' => 'Lax'
        ]);
        setcookie('secure_cookie', 'secret', [
            'expires' => time() + 3600, 'path' => '/', 'httponly' => true, 'samesite' => 'Strict'
        ]);
        $msg = "Cookies set! Check your browser console.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Session Test</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .box { border: 1px solid #ccc; padding: 20px; width: 400px; }
        button { margin: 5px 0; padding: 8px 15px; cursor: pointer; }
        pre { background: #eee; padding: 10px; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Session & Cookie Security</h2>
        
        <?php if ($msg): ?>
            <p style="color: green;"><b><?php echo $msg; ?></b></p>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
            <button name="login">1. Login (Regenerate ID)</button><br>
            <button name="set_cookies">2. Set Cookies</button>
        </form>

        <h3>JavaScript Test</h3>
        <pre id="js-output"></pre>
    </div>

    <script>
        document.getElementById('js-output').innerText = document.cookie || "No cookies visible to JS.";
    </script>
</body>
</html>
