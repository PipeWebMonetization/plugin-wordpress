jQuery(function ($) {
    var add_counter = $("#add-new-pointer-row").length;
    var edit_counter = $("#edit-new-pointer-row").length;

    $(document).on("click", ".button-delete", function(){  
        var button_id = $(this).attr("id");
        $("#row" + button_id + '').remove();
    }); 

    if($("#success-message").attr("id") == "success-message"){
        $("#cancel-button")[0].click();
    }
      
    $("#add-new-pointer-row").click(function () {
        add_counter++;
        $("#add-dynamic-field").append(
            `<tr id="row-add-` + add_counter + `">
                <td>
                    <input class="pointer-input" type="text" name="add_pointer[]" placeholder="$wallet.example.com/gabriel">
                </td>
                <td>
                    <span class="probability-input"><input type="number" step=".01" maxlength="5" name="add_probability[]" placeholder="100">%</span>
                </td>
                <td>
                    <button type="button" name="add-remove-row" id="-add-` + add_counter + `"class="button-delete">
                        <img src="` + images_variables.icon_delete_url + `" />
                    </button>
                </td>
            </tr>`);
    });

    $("#edit-new-pointer-row").click(function () {
        edit_counter++;
        $("#edit-dynamic-field").append(
            `<tr id="row-edit-` + edit_counter + `">
                <td>
                    <input class="pointer-input" type="text" name="edit_pointer[]" placeholder="$wallet.example.com/gabriel">
                </td>
                <td>
                    <span class="probability-input"><input type="number" step=".01" maxlength="5" name="edit_probability[]" placeholder="100">%</span>
                </td>
                <td>
                    <button type="button" name="edit-remove-row" id="-edit-` + edit_counter + `"class="button-delete">
                        <img src="` + images_variables.icon_delete_url + `" />
                    </button>
                </td>
            </tr>`);
    });
});
