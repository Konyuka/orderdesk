<?php
$fileType = '*';
if (isset($_POST['fileType'])) {
    $fileType .= '.' . $_POST['fileType'];
}
$files = glob(__DIR__ . "/../files/*" . $fileType);

if (isset($_GET['action']) && $_GET['action'] === 'download') {
    header('Content-Type: application/octet-stream');
    header('Content-Transfer-Encoding: Binary');
    header('Content-disposition: attachment; filename="' . basename($_GET['filename']) . '"');
    readfile(__DIR__ . '/../files/' . $_GET['filename']);

    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    unlink(__DIR__ . '/../files/' . $_GET['filename']);

    exit;
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

        <?php if (isset($_POST['fileType'])) { ?>
            <p>Currently filtered by <?= $_POST['fileType']; ?></p>
        <?php } ?>

        <form method="post" action="index.php">
            Filter: <select name="fileType">
                <option value="">Please select</option>
                <option value="png">PNG files</option>
                <option value="pdf">PDF files</option>
            </select>
            <input type="submit" value="Filter!">
        </form>

        <table>
            <tr>
                <th>Filename</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
            <?php foreach ($files as $x) { ?>
                <tr>
                    <td><?= basename($x); ?></td>
                    <td><a href="?action=delete&filename=<?= basename($x); ?>">Delete file</a></td>
                    <td><a href="?action=download&filename=<?= basename($x); ?>">Download file</a></td>
                </tr>
            <?php } ?>
        </table>

    </body>
</html>
