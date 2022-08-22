<?php
    require_once ($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
    $plugin_id = $_POST["plugin_id"];
  
    if ($plugin_id != '') {
        $option = get_option('pwm_plugin_id');
        if ($option == false) {
            $result = add_option('pwm_plugin_id', $plugin_id, NULL, 'yes');
        } else {
            delete_option('pwm_plugin_id');
            $result = add_option('pwm_plugin_id', $plugin_id, NULL, 'yes');
        }
        echo $result;
    }
?>
