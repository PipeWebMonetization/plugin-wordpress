<?php
/**
 * Intentionally empty file.
 *
 * It exists to stop directory listings on poorly configured servers.
 *
 * @package     Pipe_Web_Monetization
 * @subpackage  Pipe_Web_Monetization/admin
 */

    function load_pointers_table() {

        global $wpdb;
        $table_name = $wpdb->prefix . 'payment_pointers';
        $pointers_list = $wpdb->get_results("SELECT * FROM $table_name");

        ?>
        <div id="is-form-empty-div" class="notice notice-error callback-div">
            <p><?php _e( 'The form is not filled in correctly, please check the data.', 'sample-text-domain' ); ?></p>
        </div>
        <div id="probability-empty-div" class="notice notice-error callback-div">
            <p><?php _e( 'A pointer cannot have probability 0%.', 'sample-text-domain' ); ?></p>
        </div>
        <div id="duplicated-pointer-div" class="notice notice-error callback-div">
            <p><?php _e( 'It is not possible to register identical pointers.', 'sample-text-domain' ); ?></p>
        </div>
        <div id="generic-error-div" class="notice notice-error callback-div">
            <p><?php _e( 'Unable to add payment pointer, please try again.', 'sample-text-domain' ); ?></p>
        </div>
        <div id="probability-error-div" class="notice notice-error callback-div">
            <p>The total probability must be equal to 100%.</p>
        </div>
        <div id="success-div" class="notice notice-success callback-div">
            <p><?php _e( 'Pointers successfully added!', 'sample-text-domain' ); ?></p>
        </div>

        <div class="wrap">
            <table class="wp-list-table widefat striped">
                <thead>
                    <tr>
                        <th width="60%"><b>Pointer</b></th>
                        <th width="40%"><b>Probability</b></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($pointers_list as $item) {
                            ?>
                                <tr>
                                    <td width="60%"><?php echo $item->pointer ?></td>
                                    <td width="40%"><?php echo $item->probability ?>%</td>
                                </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>
            <?php 

                if (isset($_GET['create-pointer'])) {
                    ?> 
                    <form name="add-pointer-form" id="add-pointer-form">  
                        <div class="pointer-form-div">  
                            <table id="add-dynamic-field">  
                                <tbody> 
                                    <tr id="row-add-0">  
                                        <td>
                                            <input class="pointer-input" type="text" name="add_pointer[]" placeholder="$wallet.example.com/gabriel">
                                        </td>  
                                        <td>
                                            <span class="probability-input"><input type="number" step=".01" maxlength="5" name="add_probability[]" placeholder="100">%</span>
                                        </td> 
                                        <td>
                                             <button type="button" name="add-remove-row" id="-add-0" class="button-delete">
                                                <img src="<?php echo plugin_dir_url( dirname(__FILE__) ) . 'img/icon_delete.png' ?>" />
                                            </button>
                                        </td>  
                                    </tr>  
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="button-plus-footer">
                                        <button type="button" id="add-new-pointer-row" name="add-new-pointer-row" class="button-plus">
                                                <img src="<?php echo plugin_dir_url( dirname(__FILE__) ) . 'img/icon_plus.png' ?>" />
                                            </button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>  
                            <input id="create-pointer" name="create-pointer" type="button" class="button button-primary" value="Save">
                        </div>  
                    </form>  
                    <?php
                }

                if (isset($_GET['edit-pointer'])) {
                    $numberOfPointers = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

                    ?> 
                    <form name="edit-pointer-form" id="edit-pointer-form">  
                        <div class="pointer-form-div">
                            <table id="edit-dynamic-field"> 
                                <tbody> 
                                    <?php
                                        for ($i = 0; $i < count($pointers_list); $i++) {
                                            ?>
                                                <tr id="<?php echo "row-edit-".$i ?>">
                                                    <td>
                                                        <input readonly class="pointer-input" type="text" name="edit_pointer[]" placeholder="$wallet.example.com/gabriel" value="<?php echo $pointers_list[$i]->pointer ?>">
                                                    </td>
                                                    <td>
                                                        <span class="probability-input">
                                                            <input id="teste" type="text" step=".01" maxlength="5" name="edit_probability[]" placeholder="100" value="<?php echo $pointers_list[$i]->probability ?>">
                                                            %
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button type="button" name="edit-remove-row" id="<?php echo "-edit-" . $i ?>"class="button-delete">
                                                            <img src="<?php echo plugin_dir_url( dirname(__FILE__) ) . 'img/icon_delete.png' ?>" />
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php
                                        }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="button-plus-footer">
                                            <button type="button" id="edit-new-pointer-row" name="edit-new-pointer-row" class="button-plus">
                                                <img src="<?php echo plugin_dir_url( dirname(__FILE__) ) . 'img/icon_plus.png' ?>" />
                                            </button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>  
                            <input id="update-pointer" name="update-pointer" type="button" class="button button-primary" value="Save">
                        </div>  
                    </form>  
                    <?php
                }

            ?>
             <div class="div-options-buttons">
                <?php 
                    $totalProbability = $wpdb->get_var("SELECT sum(probability) FROM $table_name");
                    if (isset($_GET["create-pointer"]) || isset($_GET["edit-pointer"])) {
                        ?>
                            <a id="cancel-button" href="?page=pipe-web-monetization-admin&tab=pointers">
                                <button id="cancel-create-pointer" name="cancel-create-pointer" class="button button-primary edit-button">
                                    Cancel
                                </button>
                            </a>
                        <?php
                    } else if($totalProbability > 0) {
                        ?>
                            <a href="?page=pipe-web-monetization-admin&tab=pointers&edit-pointer=true">
                                <button id="show-edit-pointer" name="show-edit-pointer" class="button button-primary edit-button">
                                    Edit Pointers
                                </button>
                            </a>
                        <?php
                    } else {
                        ?>
                            <a href="?page=pipe-web-monetization-admin&tab=pointers&create-pointer=true">
                                <button id="show-create-pointer" name="show-create-pointer" class="button button-primary edit-button">
                                    Add Pointers
                                </button>
                            </a>
                        <?php
                    }
                ?>
            </div>
        </div>
        <?php
    }
?>
