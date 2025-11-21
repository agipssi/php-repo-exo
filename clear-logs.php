<?php
$log_file = __DIR__ . '/logs/php-errors.log';

if (file_exists($log_file)) {
    file_put_contents($log_file, '');
    $message = " Logs vidés ";
} else {
    $message = " Aucun logs à tej";
}

header('Location: debug.php?msg=' . urlencode($message));
exit;
?>