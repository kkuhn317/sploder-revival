<?php
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
        // Check if headers have been sent
        if (headers_sent()) {
            // Headers sent, use JavaScript to fetch the error page and replace content
            echo '<script type="text/javascript">
    document.body.style.display = "none";
    fetch("/error_pages/err50x.php")
    .then(response => {
    if (!response.ok) {
        throw new Error("Network response was not ok");
    }
    return response.text();
    })
    .then(html => {
        document.open();
        document.write(html);
        document.close();
    })
    .catch(error => {
    document.body.innerHTML = "<h1>500 Internal Server Error</h1><p>An unexpected error occurred. Please try again later.</p>";
    console.error("Failed to fetch error page:", error);
    });
</script>';
        } else {
            // Headers not sent, set a 500 status code and include the error template
            http_response_code(500);
            include __DIR__ . '/../error_pages/err50x.php';
        }
    }
}
?>