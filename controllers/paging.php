<?php
/**
 * @var string $home_url
 * @var $total_rows
 * @var $records_per_page
 * @var $page
 * @var $page_url
 */

echo "<ul class=\"pagination \">";

// botón a la 1ra pag
if($page>1){
    echo "<li class='page-item'><a class='page-link bg-secondary text-light' href='{$page_url}' 
title='Ir a la primera página'>";
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
            echo "<li class='page-item active'><a class='page-link bg-success' href=\"#\">$x </a></li>";
        }

        // otras pags
        else {
            echo "<li class='page-item'><a class='page-link bg-secondary text-light' href='{$page_url}page=$x'>$x</a></li>";
        }
    }
}

// botón a la última pag
if($page<$total_pages){
    echo "<li class='page-item'><a class='page-link bg-secondary text-light' href='" .$page_url . "page={$total_pages}' title='La última página es la {$total_pages}.'>";
    echo "Última página";
    echo "</a></li>";
}

echo "</ul>";
?>