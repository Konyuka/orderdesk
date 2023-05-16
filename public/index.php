<?php
$fileType = '*';
if (isset($_POST['fileType'])) {
    $fileType = preg_replace('/[^a-zA-Z0-9\.\-\_]/', '', $_POST['fileType']);
    $fileType = '*.' . $fileType;
}

$files = glob(__DIR__ . "/../files/" . $fileType);

if (isset($_GET['action']) && $_GET['action'] === 'download') {
    $filename = preg_replace('/[^a-zA-Z0-9\.\-\_]/', '', $_GET['filename']);
    if (in_array(pathinfo($filename, PATHINFO_EXTENSION), array('jpg', 'jpeg', 'png', 'gif', 'pdf'))) {
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: Binary');
        header('Content-disposition: attachment; filename="' . basename($filename) . '"');
        readfile(__DIR__ . '/../files/' . $filename);
        exit;
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $filename = preg_replace('/[^a-zA-Z0-9\.\-\_]/', '', $_GET['filename']);
    if (in_array(pathinfo($filename, PATHINFO_EXTENSION), array('jpg', 'jpeg', 'png', 'gif', 'pdf'))) {
        unlink(__DIR__ . '/../files/' . $filename);
        exit;
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Directory Viewer</title>
</head>
<body>
    <h1>Directory Viewer</h1>
    <form method="post">
        <label for="fileType">File Type:</label>
        <input type="text" id="fileType" name="fileType" value="<?php echo htmlspecialchars($_POST['fileType'] ?? ''); ?>">
        <button type="submit">Filter</button>
    </form>
    <ul>
        <?php foreach ($files as $file): ?>
            <li>
                <?php echo htmlspecialchars(basename($file)); ?>
                <a href="?action=download&amp;filename=<?php echo urlencode(basename($file)); ?>">Download</a>
                <a href="?action=delete&amp;filename=<?php echo urlencode(basename($file)); ?>">Delete</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>