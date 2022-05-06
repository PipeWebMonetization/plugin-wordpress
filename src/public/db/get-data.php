<?php
    require_once ($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
    global $wpdb;
    $table_name = $wpdb->prefix . 'payment_pointers';
    $pointers_list = $wpdb->get_results("SELECT * FROM $table_name");

    $choice = $_POST["random"] * 100;
  
    for ($i = 0; $i < count($pointers_list) ; $i++) {
        $weight = $pointers_list[$i]->probability;
        if (($choice -= $weight) <= 0) {
            echo "<meta name='monetization' content='".$pointers_list[$i]->pointer."' />";
            return;
        }
    }
?>
