jQuery(function ($) {
    var add_counter = $("#add-new-pointer-row").length;
    var edit_counter = $("#edit-new-pointer-row").length;
    hideCallbackDiv();

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
        hideCallbackDiv();
        $.ajax({
            url: ajax_variables.ajax_url,
            type: "post",
            data: $("#add-pointer-form").serialize(),
            success: function (data) {
                if (data.includes("#success-div")) {
                    $("#cancel-button")[0].click();
                }
                $(data).css("display", "block");
            }
        });
    });

    $("#update-pointer").click(function () {
        hideCallbackDiv();
        $.ajax({
            url: ajax_variables.ajax_url,
            type: "post",
            data: $("#edit-pointer-form").serialize(),
            success: function (data) {
                if (data.includes("#success-div")) {
                    $("#cancel-button")[0].click();
                }
                $(data).css("display", "block");
            }
        });
    });

    function hideCallbackDiv() {
        $("#success-div").css("display", "none");
        $("#generic-error-div").css("display", "none");
        $("#is-form-empty-div").css("display", "none");
        $("#probability-error-div").css("display", "none");
        $("#probability-empty-div").css("display", "none");
        $("#duplicated-pointer-div").css("display", "none");
    }
});
