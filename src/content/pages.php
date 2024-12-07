<div class="pagination">
    <?php
    $params = $_SERVER['QUERY_STRING'];
    parse_str($params, $queryParams);
    unset($queryParams['o']);
    $queryString = http_build_query($queryParams);

    $totalPages = ceil($total / $perPage);
    $currentPage = isset($_GET['o']) ? (int)$_GET['o'] / $perPage + 1 : 1;
    $start = max(1, min($currentPage - 3, $totalPages - 6));
    $end = min($totalPages, $start + 6);
    if ($total != 0) {
        if ($currentPage < $totalPages) {
            echo '<a href="?' . $queryString . '&o=' . ($currentPage * $perPage) . '" class="page_button page_next">next &raquo;</a>';
        } else {
            echo '<span style="width:52px" class="page_button page_next">next &raquo;</span>';
        }
        for ($i = $end; $i >= $start; $i--) {
            if ($i == $currentPage) {
                echo '<span class="page_button page_current">' . $i . '</span>';
            } else {
                echo '<a href="?' . $queryString . '&o=' . (($i - 1) * $perPage) . '" class="page_button">' . $i . '</a>';
            }
        }
        if ($currentPage > 1) {
            echo '<a href="?' . $queryString . '&o=' . ((($currentPage - 2) * $perPage) - 12) . '" class="page_button">&laquo;</a>';
        } else {
            echo '<span class="page_button page_button_inactive">&laquo;</span>';
        }
        echo '<br><br><br>';
    }
    ?>
</div>