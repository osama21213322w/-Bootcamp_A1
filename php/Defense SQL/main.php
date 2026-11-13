<?php
header("Content-Type: text/html; charset=UTF-8");

$mode = $_GET['mode'] ?? 'safe';
$user = $_GET['username'] ?? '';

if ($mode != 'safe' && $mode != 'unsafe') {
    die("invalid mode");
}

try {
    $db = new PDO("sqlite::memory:");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->exec("CREATE TABLE users (id INTEGER PRIMARY KEY, username TEXT)");
    
    $stmt = $db->prepare("INSERT INTO users (username) VALUES (?)");
    foreach (["admin", "alice", "bob"] as $name) {
        $stmt->execute([$name]);
    }

    echo "<h2>SQLi Tester</h2>";
    echo "<b>Input:</b> $user <br>";
    echo "<b>Mode:</b> $mode <br><hr>";

    if ($mode == "unsafe") {
        echo "<h3 style='color:red'>Unsafe Mode</h3>";
        $q = "SELECT * FROM users WHERE username = '$user'";
        echo "<i>Query: $q</i><br><br>";
        $res = $db->query($q);
    } else {
        echo "<h3 style='color:green'>Safe Mode</h3>";
        $q = "SELECT * FROM users WHERE username = ?";
        echo "<i>Query: $q</i><br><br>";
        $res = $db->prepare($q);
        $res->execute([$user]);
    }

    $rows = $res->fetchAll(PDO::FETCH_ASSOC);

    if (count($rows) > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Username</th></tr>";
        foreach ($rows as $r) {
            echo "<tr><td>{$r['id']}</td><td>{$r['username']}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "no results found";
    }

} catch (Exception $e) {
    echo "database error";
}
?>
