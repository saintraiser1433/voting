<?php
/**
 * Legacy Jasper report — not compatible with PHP 8.x (uses removed mysql_* API).
 * Use admin result pages (resultm.php / resultm_dept.php) for PDF output instead.
 */
header('Content-Type: text/html; charset=utf-8');
http_response_code(503);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Report unavailable</title>
</head>
<body>
    <h1>Report unavailable on PHP 8.2</h1>
    <p>This legacy Jasper report relies on removed <code>mysql_*</code> functions and is not supported on PHP 8.2.</p>
    <p>Please use the admin <strong>Results</strong> page and click <strong>PRINT</strong> for official election PDFs.</p>
    <p><a href="admin/dashboard.php">Go to admin dashboard</a></p>
</body>
</html>
