<?php
echo "<ul class=\"pagination margin-zero\">";

// botón a la 1ra pag
if($page>1){
    echo "<li><a href='{$page_url}' title='Ir a la primera página'>";
    echo "Primera página";
    echo "</a></li>";
}

// num de pags
$total_pages = ceil($total_rows / $records_per_page);

// range de enlaces (numeros) entre pag inicio y fin
$range = 2;

$initial_num = $page - $range;
$condition_limit_num = ($page + $range)  + 1;

for ($x=$initial_num; $x<$condition_limit_num; $x++) {

    // x > 0 y menor o igual al total
    if (($x > 0) && ($x <= $total_pages)) {

        // pag actual
        if ($x == $page) {
            echo "<li class='active'><a href=\"#\">$x <span class=\"sr-only\">(current)</span></a></li>";
        }

        // otras pags
        else {
            echo "<li><a href='{$page_url}page=$x'>$x</a></li>";
        }
    }
}

// botón a la última pag
if($page<$total_pages){
    echo "<li><a href='" .$page_url . "page={$total_pages}' title='La última página es la {$total_pages}.'>";
    echo "Última página";
    echo "</a></li>";
}

echo "</ul>";
?>