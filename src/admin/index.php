<?php
/**
 * Intentionally empty file.
 *
 * It exists to stop directory listings on poorly configured servers.
 *
 * @package     Pipe_Web_Monetization
 * @subpackage  Pipe_Web_Monetization/admin
 */
    require_once plugin_dir_path( __FILE__ ) . 'payment-pointer.php';

    function load_pointers_table() {

        global $wpdb;
        $table_name = $wpdb->prefix . 'payment_pointers';
        $pointers_list = $wpdb->get_results("SELECT * FROM $table_name");

        ?>

        <div class="wrap">
            <div class="tab–container">
                <div class="pointers-title-row">
                    <span>Payment Pointers and Revenue Share</span>
                        <?php 
                            if($pointers_list[0] && !isset($_GET['edit-pointer'])) {
                        ?>
                                <a href="?page=pipe-web-monetization-admin&tab=pointers&edit-pointer=true">
                                    <button id="show-edit-pointer" name="show-edit-pointer" class="default-button">
                                        Edit
                                    </button>
                                </a>
                        <?php
                            } else if (!isset($_GET['create-pointer']) && !isset($_GET['edit-pointer'])) {
                        ?>
                                <a href="?page=pipe-web-monetization-admin&tab=pointers&create-pointer=true">
                                    <button id="show-create-pointer" name="show-create-pointer" class="default-button">
                                        Add payment pointer
                                    </button>
                                </a>
                        <?php
                            }
                        ?>
                </div>
                <?php
                    if (!isset($_GET['edit-pointer']) && !isset($_GET['create-pointer'])) {
                ?>
                        <div class="card-div">
                            <table class="pointers-table">
                                <thead>
                                    <tr>
                                        <th><b>Payment Pointer</b></th>
                                        <th><b>Share</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach ($pointers_list as $item) {
                                    ?>
                                            <tr>
                                                <td><?php echo esc_html($item->pointer) ?></td>
                                                <td><?php echo esc_html($item->probability) ?>%</td>
                                            </tr>
                                    <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                <?php
                    }
                    if (isset($_GET['create-pointer'])) {
                ?>
                        <div class="card-div">
                            <form method="post" action="" name="add-pointer-form" id="add-pointer-form" class="pointer-form">  
                                <div class="pointer-form-div">  
                                    <table class="pointers-table" id="add-dynamic-field">  
                                        <thead>
                                            <tr>
                                                <th><b>Payment Pointer</b></th>
                                                <th><b>Share</b></th>
                                            </tr>
                                        </thead>
                                        <tbody> 
                                        <?php
                                            if (isset($_POST['add_pointer'])) {
                                                for ($i = 0; $i < count($_POST['add_pointer']); $i++) {
                                                    ?>
                                                     <tr id="<?php echo esc_attr("row-add-".$i) ?>">  
                                                        <td>
                                                            <input class="pointer-input" type="text" name="add_pointer[]" placeholder="$wallet.example.com/gabriel" value="<?php echo esc_attr($_POST['add_pointer'][$i]) ?>">
                                                        </td>  
                                                        <td>
                                                            <span class="probability-input"><input type="number" step=".01" maxlength="5" name="add_probability[]" placeholder="100" value="<?php echo esc_attr($_POST['add_probability'][$i]) ?>">%</span>
                                                        </td> 
                                                        <td>
                                                            <button type="button" name="add-remove-row" id="<?php echo esc_attr("-add-" . $i) ?>" class="button-delete">
                                                                <img src="<?php echo esc_attr(plugin_dir_url( dirname(__FILE__) ) . 'img/icon_delete.svg') ?>" />
                                                            </button>
                                                        </td>  
                                                    </tr>  
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                    <tr id="row-add-0">  
                                                        <td>
                                                            <input class="pointer-input" type="text" name="add_pointer[]" placeholder="$wallet.example.com/gabriel">
                                                        </td>  
                                                        <td>
                                                            <span class="probability-input"><input type="number" step=".01" maxlength="5" name="add_probability[]" placeholder="100">%</span>
                                                        </td> 
                                                        <td>
                                                            <button type="button" name="add-remove-row" id="-add-0" class="button-delete">
                                                                <img src="<?php echo esc_attr(plugin_dir_url( dirname(__FILE__) ) . 'img/icon_delete.svg') ?>" />
                                                            </button>
                                                        </td>  
                                                    </tr>        
                                                <?php
                                            }

                                        ?>
                                        </tbody>
                                        <?php
                                            if (isset($_POST['create-pointer'])) {
                                                ?>
                                                    <tfoot id="add-footer">
                                                        <tr>
                                                            <td colspan="3" class="table-footer">
                                                                <?php
                                                                    validateForm();
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                <?php                
                                            }
                                        ?>
                                    </table>  
                                    <button type="button" id="add-new-pointer-row" name="add-new-pointer-row" class="plus-button">
                                            Add payment pointer
                                    </button>
                                </div>
                                <input id="create-pointer" name="create-pointer" type="submit" class="default-button" value="Save">
                            </form>  
                        </div>
                <?php
                    }
                    if (isset($_GET['edit-pointer'])) {
                ?>
                        <div class="card-div">
                            <form method="post" action="" name="edit-pointer-form" id="edit-pointer-form" class="pointer-form">  
                                <div class="pointer-form-div">
                                    <table class="pointers-table" id="edit-dynamic-field"> 
                                        <thead>
                                            <tr>
                                                <th><b>Payment Pointer</b></th>
                                                <th><b>Share</b></th>
                                            </tr>
                                        </thead>
                                        <tbody> 
                                        <?php
                                            if (isset($_POST['edit_pointer'])) {
                                                $pointers_list = [];
                                                for ($i = 0; $i < count($_POST['edit_pointer']); $i++) {
                                                    $pointers_list[$i] = new Payment_Pointer(
                                                        sanitize_text_field($_POST['edit_pointer'][$i]), 
                                                        sanitize_text_field($_POST['edit_probability'][$i]));
                                                }
                                            }
                                            for ($i = 0; $i < count($pointers_list); $i++) {
                                        ?>
                                                <tr id="<?php echo esc_attr("row-edit-".$i) ?>">
                                                    <td>
                                                        <input readonly class="pointer-input form-input" type="text" name="edit_pointer[]" placeholder="$wallet.example.com/gabriel" value="<?php echo esc_attr($pointers_list[$i]->pointer) ?>">
                                                    </td>
                                                    <td>
                                                        <span class="probability-input">
                                                            <input type="text" step=".01" maxlength="5" name="edit_probability[]" placeholder="100" value="<?php echo esc_attr($pointers_list[$i]->probability) ?>">
                                                            %
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button type="button" name="edit-remove-row" id="<?php echo esc_attr("-edit-" . $i) ?>"class="button-delete">
                                                            <img src="<?php echo esc_attr(plugin_dir_url( dirname(__FILE__) ) . 'img/icon_delete.svg') ?>" />
                                                        </button>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        ?>
                                        </tbody>
                                        <?php
                                            if (isset($_POST['update-pointer'])) {
                                                ?>
                                                    <tfoot id="edit-footer">
                                                        <tr>
                                                            <td colspan="3" class="table-footer">
                                                                <?php
                                                                    validateForm();
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                <?php                
                                            }
                                        ?>
                                    </table>  
                                    <button type="button" id="edit-new-pointer-row" name="edit-new-pointer-row" class="plus-button">
                                            Add payment pointer
                                    </button>
                                </div>  
                                <input id="update-pointer" name="update-pointer" type="submit" class="default-button" value="Save">
                            </form>  
                        </div>
                <?php
                    }
                ?>
            </div>
            <div class="div-options-buttons">
                <a id="cancel-button" href="?page=pipe-web-monetization-admin&tab=pointers">
                    <button id="cancel-create-pointer" name="cancel-create-pointer"></button>
                </a>
            </div>
        </div>
        <?php
    }

    function load_config_tab() { 
        $option = get_option('pwm_plugin_id');
        ?>
            <div class="wrap">
                <form method="post" action="" class="tab–container settings-tab">                    
                    <span>Set up your dashboard</span>
                    <div class="rules-div">
                        <div class="circle"><span>1<span></div>
                        <span>Sign in to your <span>dashboard</span>.</span>
                    </div>
                    <div class="rules-div">
                        <div class="circle"><span>2<span></div>
                        <span>Get your  sync code.</span>
                    </div>
                    <div class="rules-div">
                        <div class="circle"><span>3<span></div>
                        <span>Paste the  code here:</span>
                    </div>
                    <div class="rules-div">
                        <input type="text" id="plugin_id" name="plugin_id" placeholder="00000000-0000-0000-0000-000000000000" value="<?php if($option != false) echo esc_attr($option) ?>" />
                    </div>
                    <div id="feedback-div" class="rules-div">
                        <?php
                            if(isset($_POST['sync-plugin-button'])){
                                save_settings();
                            }
                        ?>
                    </div>
                    <div class="rules-button-div">
                        <button type="submit" id="sync-plugin-button" name="sync-plugin-button" class="default-button">Confirm</button>
                    </div>
                </form>    
            </div>
        <?php
    }

    function sanitize_any_field( $field ) {
        foreach ( (array) $field as $key => $value ) {
            $field[$key] = sanitize_text_field( $value );  
        }
        return $field;
    }

    function validateForm() {
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
			$pointers = sanitize_any_field($_POST["add_pointer"]);
			$probability = sanitize_any_field($_POST["add_probability"]);
		} else if ($_POST["edit_pointer"] != '') {
			$pointers = sanitize_any_field($_POST["edit_pointer"]);
			$probability = sanitize_any_field($_POST["edit_probability"]);
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
			echo "<span class='error-message'>The form is not filled in correctly, please check the data.";
		} else if ($is_duplicated_pointer) {
			echo "<span class='error-message'>It is not possible to register identical pointers.";
		} else if ($total_probability == 0 || $has_empty_probability) {
			echo "<span class='error-message'>A pointer cannot have probability 0%.";
		} else if ($total_probability != 100) {
			echo "<span class='error-message'>You are using $total_probability%, you have to use 100%.";
		} else {
			if ($is_editing) {
				$truncate_result = $wpdb->query("TRUNCATE TABLE $table_name");
			}
	
			if (!$truncate_result && $is_editing) {
				echo "<span class='error-message'>Unable to add payment pointer, please try again.";
			} else {
				for ($i = 0; $i < count($pointers) ; $i++) {
					$result = $wpdb->query(
						$wpdb->prepare("INSERT INTO wp_payment_pointers(pointer, probability) VALUES (%s, %f)", array( $pointers[$i], $probability[$i] )));
					if ($result) {
						$success = true;
					} else {
						$success = false;
					}
				}

				if ($success) {
                    echo "<div id='success-message'></div>";
				} else {
					echo "<span class='error-message'>Unable to add payment pointer, please try again.";
				}
			}
		}
    }

    function save_settings() {
        if ($_POST["plugin_id"] != '') {
            $plugin_id = sanitize_text_field($_POST["plugin_id"]);
            $option = get_option('pwm_plugin_id');
            if ($option == false) {
                $result = add_option('pwm_plugin_id', $plugin_id, NULL, 'yes');
            } else {
                delete_option('pwm_plugin_id');
                $result = add_option('pwm_plugin_id', $plugin_id, NULL, 'yes');
            }
			if ($result == 1) {
				echo "<span id='feedback-span-success' class='feedback-span-success'>The code has been saved successfully.</span>";
			} else {
				echo "<span id='feedback-span-error' class='feedback-span-error'>The code has not been saved. Please try again.</span>";			
			}
        } else {
            echo "<span id='feedback-span-error' class='feedback-span-error'>The code cannot be empty.</span>";			
        }
    }

?>
