(function ($) {
    var ajaxUrl = window.location.origin + "/wp-admin/admin-ajax.php";

    ("use strict");
    $(function () {
        if (window.location.hash) {
            let tab_id = window.location.hash.replace("#", "");
            $(".wpco-panel-main").removeClass("wpco-tab-current");
            $("#" + tab_id).addClass("wpco-tab-current");

            $(".wpco-menu-item").removeClass("wpco-menu-current");
            $(
                '.wpco-menu-item[data-tab="' + window.location.hash + '"]'
            ).addClass("wpco-menu-current");
        }

        $(".wpco-menu-goto").on("click", function () {
            let tab_id = $(this).attr("href");
            tab_id = tab_id.replace("#", "");

            $(".wpco-panel-main").removeClass("wpco-tab-current");
            $("#" + tab_id).addClass("wpco-tab-current");

            $(this)
                .closest(".wpco-menu")
                .find(".wpco-menu-item")
                .removeClass("wpco-menu-current");
            $(this).closest(".wpco-menu-item").addClass("wpco-menu-current");
        });

        $(".wpco-group-add-btn").on("click", function () {
            let section = $(".wpco-panel-section");

            $(this)
                .closest(".wpco-settings-form")
                .find(".wpco-empty")
                .addClass("hidden");

            let html = "";
            html +=
                '<div class="wpco-panel-section" data-group-number="' +
                (parseInt(section.length) + 1) +
                '">';
            html += '<div class="wpco-panel-section-header">';
            html +=
                '<input name="group[' +
                (parseInt(section.length) + 1) +
                '][group_name]" type="text" placeholder="Group name" value="">';
            html += '<div class="wpco-panel-group-action">';
            html +=
                '<button type="button" class="button mr-8 wpco-fields-add-btn">Add field</button>';
            html +=
                '<button type="button" class="button wpco-fields-delete-btn">Delete group</button>';
            html += "</div>";
            html += "</div>";
            html += '<div class="wpco-controls">';
            html += "</div>";
            html += "</div>";

            $(".wpco-panel-section-main").append(html);
        });

        $(document).on("click", ".wpco-fields-add-btn", function () {
            let field_type_option = "";

            $.each(wpco_types, function (key, val) {
                field_type_option +=
                    '<option value="' + key + '">' + val + "</option>";
            });

            let fields = $(this)
                .closest(".wpco-panel-section")
                .find(".wpco-controls .wpco-fields");
            let group_number = $(this)
                .closest(".wpco-panel-section")
                .attr("data-group-number");

            $(this)
                .closest(".wpco-panel-section")
                .find(".wpco-empty-field")
                .addClass("hidden");

            let html = "";

            html += '<div class="wpco-fields">';
            html += '<div class="field input">';
            html +=
                '<input type="text" name="group[' +
                group_number +
                "][fields][" +
                (parseInt(fields.length) + 1) +
                '][field_title]" class="wpco-input" placeholder="Field title">';
            html += "</div>";
            html += '<div class="field input">';
            html +=
                '<input type="text" name="group[' +
                group_number +
                "][fields][" +
                (parseInt(fields.length) + 1) +
                '][field_name]" class="wpco-input" placeholder="Field name">';
            html += "</div>";
            html += '<div class="field select">';
            html +=
                '<select name="group[' +
                group_number +
                "][fields][" +
                (parseInt(fields.length) + 1) +
                '][field_type]">';
            html += field_type_option;
            html += "</select>";
            html += "</div>";
            html += '<div class="field action">';
            html +=
                '<button type="button" class="button wpco-field-delete-btn">Delete field</button>';
            html += "</div>";
            html += "</div>";

            $(this)
                .closest(".wpco-panel-section")
                .find(".wpco-controls")
                .append(html);
        });

        $(document).on("click", ".wpco-group-delete-btn", function () {
            $(this).closest(".wpco-panel-section").remove();
        });

        $(document).on("click", ".wpco-field-delete-btn", function () {
            $(this).closest(".wpco-fields").remove();
        });

        $(".wpco-submit-btn").on("click", function () {
            var form_data = new FormData(
                document.getElementById("wpco_settings_form")
            );
            $.ajax({
                url: ajaxUrl + "?action=wpco_save_settings",
                type: "POST",
                data: form_data,
                success: function (data) {
                    if (data.success) {
                        location.reload();
                    }
                },
                cache: false,
                contentType: false,
                processData: false,
            });
        });

        $(".wpco-submit-option-btn").on("click", function () {
            let form_id = $(this).attr('data-form-id');
            var form_data = new FormData(
                document.getElementById(form_id)
            );
            $.ajax({
                url: ajaxUrl + "?action=wpco_save_options",
                type: "POST",
                data: form_data,
                success: function (data) {
                    if (data.success) {
                        location.reload();
                    }
                },
                cache: false,
                contentType: false,
                processData: false,
            });
        });
    });
})(jQuery);
