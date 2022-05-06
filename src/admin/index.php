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

        <div class="wrap">
            <div class="pointers–container">
                <div class="pointers-title-row">
                    <span>Total Revenue by Content</span>
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
                                                <td><?php echo $item->pointer ?></td>
                                                <td><?php echo $item->probability ?>%</td>
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
                            <form name="add-pointer-form" id="add-pointer-form" class="pointer-form">  
                                <div class="pointer-form-div">  
                                    <table class="pointers-table" id="add-dynamic-field">  
                                        <thead>
                                            <tr>
                                                <th><b>Payment Pointer</b></th>
                                                <th><b>Share</b></th>
                                            </tr>
                                        </thead>
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
                                                        <img src="<?php echo plugin_dir_url( dirname(__FILE__) ) . 'img/icon_delete.svg' ?>" />
                                                    </button>
                                                </td>  
                                            </tr>  
                                        </tbody>
                                    </table>  
                                    <button type="button" id="add-new-pointer-row" name="add-new-pointer-row" class="plus-button">
                                            Add payment pointer
                                    </button>
                                </div>
                                <input id="create-pointer" name="create-pointer" type="button" class="default-button" value="Save">
                            </form>  
                        </div>
                <?php
                    }
                    if (isset($_GET['edit-pointer'])) {
                ?>
                        <div class="card-div">
                            <form name="edit-pointer-form" id="edit-pointer-form" class="pointer-form">  
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
                                                            <img src="<?php echo plugin_dir_url( dirname(__FILE__) ) . 'img/icon_delete.svg' ?>" />
                                                        </button>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        ?>
                                        </tbody>
                                    </table>  
                                    <button type="button" id="edit-new-pointer-row" name="edit-new-pointer-row" class="plus-button">
                                            Add payment pointer
                                    </button>
                                </div>  
                                <input id="update-pointer" name="update-pointer" type="button" class="default-button" value="Save">
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
?>
