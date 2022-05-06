<?php
    require_once ($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
    
    global $wpdb;

    $table_name = $wpdb->prefix . 'payment_pointers';
    $is_form_empty = false;
    $is_duplicated_pointer = false;
    $has_empty_probability = false;
    $total_probability = 0;
    $pointers = [];
    $probability = [];
    $is_editing = false;

    if ($_POST["add_pointer"] != '') {
        $pointers = $_POST["add_pointer"];
        $probability = $_POST["add_probability"];
    } else if ($_POST["edit_pointer"] != '') {
        $pointers = $_POST["edit_pointer"];
        $probability = $_POST["edit_probability"];
        $is_editing = true;
    }
    
    for ($i = 0; $i < count($pointers) ; $i++) {
        if (trim($pointers[$i] == '') || trim($probability[$i] == '')) {
            $is_form_empty = true;
        }

        if (trim($probability[$i]) == 0) {
            $has_empty_probability = true;
        }

        $total_probability = $total_probability + $probability[$i];

        $indexes = array_keys($pointers, $pointers[$i]);
        if(count($indexes) > 1) { 
            $is_duplicated_pointer = true;
        }
    }
    if ($is_form_empty || count($pointers) == 0) {
        echo "The form is not filled in correctly, please check the data.";
    } else if ($is_duplicated_pointer) {
        echo "It is not possible to register identical pointers.";
    } else if ($total_probability == 0 || $has_empty_probability) {
        echo "A pointer cannot have probability 0%.";
    } else if ($total_probability != 100) {
        echo "You are using $total_probability%, you have to use 100%.";
    } else {

        if ($is_editing) {
            $truncate_result = $wpdb->query("TRUNCATE TABLE $table_name");
        }

        if (!$truncate_result && $is_editing) {
            echo "Unable to add payment pointer, please try again.";
        } else {
            for ($i = 0; $i < count($pointers) ; $i++) {
                $result = $wpdb->query("INSERT INTO $table_name(pointer, probability) VALUES ('".$pointers[$i]."', '".$probability[$i]."')");
    
                if (!$result) {
                    echo "Unable to add payment pointer, please try again.";
                } else {
                    echo "success";
                }
            }
        }
    }
?>
