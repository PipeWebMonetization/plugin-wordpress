jQuery(function ($) {
    var add_counter = $("#add-new-pointer-row").length;
    var edit_counter = $("#edit-new-pointer-row").length;

    $(document).on("click", ".button-delete", function(){  
        var button_id = $(this).attr("id");
        $("#row" + button_id + '').remove();
    }); 

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

    $("#create-pointer").click(function () {
        $("#add-footer").remove();
        $.ajax({
            url: ajax_variables.ajax_url,
            type: "post",
            data: $("#add-pointer-form").serialize(),
            success: function (data) {
                if (String(data).includes("success")) {
                    $("#cancel-button")[0].click();
                } else {
                    $("#add-dynamic-field").append(`
                        <tfoot id="add-footer">
                            <tr>
                                <td colspan="3" class="table-footer">
                                    <span class="error-message">`+data+`</span>
                                </td>
                            </tr>
                        </tfoot>
                    `);
                }
            }
        });
    });

    $("#update-pointer").click(function () {
        $("#edit-footer").remove();
        $.ajax({
            url: ajax_variables.ajax_url,
            type: "post",
            data: $("#edit-pointer-form").serialize(),
            success: function (data) {
                if (String(data).includes("success")) {
                    $("#cancel-button")[0].click();
                } else {
                    $("#edit-dynamic-field").append(`
                        <tfoot id="edit-footer">
                            <tr>
                                <td colspan="3" class="table-footer">
                                    <span class="error-message">`+data+`</span>
                                </td>
                            </tr>
                        </tfoot>
                    `);
                }
            }
        });
    });

    $("#sync-plugin-button").click(function () {
        $.ajax({
            url: ajax_variables.ajax_settings_url,
            type: "post",
            data: { plugin_id: $("#plugin_id").val() },
            success: function(response) {
                if (response == 1) {
                    $("#feedback-span-success").remove()
                    $("#feedback-span-error").remove()
                    $("#feedback-div").append('<span id="feedback-span-success" class="feedback-span-success">The code has been saved successfully.</span>');
                } else {
                    $("#feedback-span-success").remove()
                    $("#feedback-span-error").remove()
                    $("#feedback-div").append('<span id="feedback-span-error" class="feedback-span-error">The code has not been saved. Please try again</span>');
                }
            }
        })
    })
});
