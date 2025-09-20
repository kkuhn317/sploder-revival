<?php
ob_start();

// Measure page execution time
$pageExecutionStartTime = microtime(true);

// Add an error handler that catches fatal errors
// This checks if headers have been sent,
// If it is, it will attempt to add javascript
// that replaces the HTML content with a 500 error message
// else, just load the error 500 template and serve it directly
// Add an error handler that catches fatal errors
register_shutdown_function('fatal_error_handler');

function fatal_error_handler() {
    $last_error = error_get_last();
    // Check if the last error is a fatal error
    if ($last_error && in_array($last_error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        ob_clean();
        // Capture the output of err50x.php into a variable
        ob_start();
        require(__DIR__ . '/../error_pages/err50x.php');
        $error_page_content = ob_get_clean();

        // Check if headers have been sent
        if (headers_sent()) {
            // Headers sent, use JavaScript to replace content with the captured HTML
            echo '<script type="text/javascript">
    document.open();
    document.write(' . json_encode($error_page_content) . ');
    document.close();
</script>';
        } else {
            // Headers not sent, set a 500 status code and output the captured content
            http_response_code(500);
            echo $error_page_content;
        }
    }
    ob_end_flush();
}
?>