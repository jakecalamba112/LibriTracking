<?php
$filename = 'footer.php';
$content = '<footer style="text-align: center; padding: 20px; color: #666;">&copy; ' . date("Y") . ' TNTS Library Management System</footer></body></html>';

if (file_put_contents($filename, $content)) {
    echo "<h1>SUCCESS!</h1>";
    echo "<p>The file <b>footer.php</b> has been created in: <b>" . __DIR__ . "</b></p>";
    echo "<p>Now, delete this file (fix_footer.php) and refresh index.php.</p>";
} else {
    echo "<h1>FAILED!</h1>";
    echo "<p>Could not create the file. Please check if your folder is Read-Only or if you have permissions.</p>";
}
