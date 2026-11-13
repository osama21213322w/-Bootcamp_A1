<?php
$msg = "";
$color = "red";
$dir = __DIR__ . '/uploads';
$maxSize = 5 * 1024 * 1024;

if (!is_dir($dir)) {
    mkdir($dir, 0750, true);
}

$htaccess = $dir . '/.htaccess';
if (!file_exists($htaccess)) {
    file_put_contents($htaccess, "Options -Indexes\nRequire all denied\n");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['uploaded_file'])) {
    $file = $_FILES['uploaded_file'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $msg = "Upload error code: " . $file['error'];
    } 
    elseif (!is_uploaded_file($file['tmp_name'])) {
        $msg = "Invalid upload attempt.";
    }
    elseif ($file['size'] <= 0 || $file['size'] > $maxSize) {
        $msg = "File size is invalid. Max 5MB allowed.";
    } 
    else {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'pdf' => 'application/pdf'];

        if (!array_key_exists($ext, $allowed)) {
            $msg = "Invalid extension. Only JPG, PNG, PDF allowed.";
        } else {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if ($mime !== $allowed[$ext]) {
                $msg = "Content does not match extension (Possible Web Shell).";
            } else {
                try {
                    $newName = bin2hex(random_bytes(16)) . '.' . $ext;
                    $dest = $dir . '/' . $newName;

                    if (move_uploaded_file($file['tmp_name'], $dest)) {
                        chmod($dest, 0640);
                        $msg = "File uploaded safely! Saved as: " . $newName;
                        $color = "green";
                    } else {
                        $msg = "Failed to move uploaded file.";
                    }
                } catch (Exception $e) {
                    $msg = "Error generating secure file name.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .box { border: 1px solid #ccc; padding: 20px; width: 300px; }
        .msg { color: <?php echo $color; ?>; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Secure File Upload</h2>
        
        <?php if ($msg): ?>
            <div class="msg"><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <input type="file" name="uploaded_file" required>
            <br><br>
            <button type="submit">Upload</button>
        </form>
        <p style="font-size: 12px; color: #666;">JPG, PNG, PDF (Max 5MB)</p>
    </div>
</body>
</html>
