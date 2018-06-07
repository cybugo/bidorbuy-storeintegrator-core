jQuery(document).ready(function () {
    var select_text = "#tokenExportUrl, #tokenExportScript, #tokenDownloadUrl, #resetaudit";
    var ctrlDown = false, ctrlKey = 17, cKey = 67;

    jQuery(select_text).css({
        cursor: 'pointer'
    });

    jQuery(select_text).focus(function () {
        jQuery(this).select();
    }).click(function () {
        jQuery(this).select();
    });

    jQuery(".copy-button, " + select_text).click(function (evt) {
        jQuery("#ctrl-c-message").css({
            top: evt.pageY - 80,
            left: evt.pageX - 280
        }).show();
    });

    jQuery(document).keydown(function (e) {
        if (e.keyCode == ctrlKey) {
            ctrlDown = true;
        }
    }).keyup(function (e) {
        if (e.keyCode == ctrlKey) {
            ctrlDown = false;
        }
    });

    jQuery(document).keydown(function (e) {
        if (ctrlDown && e.keyCode == cKey) {
            jQuery("#ctrl-c-message").hide();
        }
    });

    jQuery("#ctrl-c-message").hover(function () {
        jQuery("#ctrl-c-message").hide('fast');
    });

    jQuery('.loggingFormButton').click(function () {
        jQuery('#loggingFormFilename').val(jQuery(this).attr('filename'));
        jQuery('#loggingFormAction').val(jQuery(this).attr('action'));
        jQuery('#loggingForm').submit();
    });


    jQuery("#include").click(function () {
        var newOptions = {};
        jQuery("#bobsi-exc-categories option:selected").each(function () {
            newOptions[jQuery(this).val()] = [];
            newOptions[jQuery(this).val()]['val'] = jQuery(this).text();
            newOptions[jQuery(this).val()]['style'] = (typeof jQuery(this).attr('style')) == 'undefined' ? '' : jQuery(this).attr('style');
            jQuery(this).remove();
        });

        jQuery.each(newOptions, function (key, value) {
            jQuery("#bobsi-inc-categories").append('<option style="' + value['style'] + '" value='+key+'>'+value['val']+'</option>');
        });

        return false;
    });

    jQuery("#exclude").click(function () {
        var newOptions = {};
        jQuery("#bobsi-inc-categories option:selected").each(function () {
            newOptions[jQuery(this).val()] = [];
            newOptions[jQuery(this).val()]['val'] = jQuery(this).text();
            newOptions[jQuery(this).val()]['style'] = (typeof jQuery(this).attr('style')) == 'undefined' ? '' : jQuery(this).attr('style');
            jQuery(this).remove();
        });

        jQuery.each(newOptions, function (key, value) {
            jQuery("#bobsi-exc-categories").append('<option style="' + value['style'] + '" value='+key+'>'+value['val']+'</option>');
        });

        return false;
    });

    jQuery("#include-allow-offers").click(function () {
        var newOptions = {};
        jQuery("#bobsi-exc-allow-offers-categories option:selected").each(function () {
            newOptions[jQuery(this).val()] = [];
            newOptions[jQuery(this).val()]['val'] = jQuery(this).text();
            newOptions[jQuery(this).val()]['style'] = (typeof jQuery(this).attr('style')) == 'undefined' ? '' : jQuery(this).attr('style');
            jQuery(this).remove();
        });

        jQuery.each(newOptions, function (key, value) {
            jQuery("#bobsi-inc-allow-offers-categories").append('<option style="' + value['style'] + '" value='+key+'>'+value['val']+'</option>');
        });

        return false;
    });

    jQuery("#exclude-allow-offers").click(function () {
        var newOptions = {};
        jQuery("#bobsi-inc-allow-offers-categories option:selected").each(function () {
            newOptions[jQuery(this).val()] = [];
            newOptions[jQuery(this).val()]['val'] = jQuery(this).text();
            newOptions[jQuery(this).val()]['style'] = (typeof jQuery(this).attr('style')) == 'undefined' ? '' : jQuery(this).attr('style');
            jQuery(this).remove();
        });

        jQuery.each(newOptions, function (key, value) {
            jQuery("#bobsi-exc-allow-offers-categories").append('<option style="' + value['style'] + '" value='+key+'>'+value['val']+'</option>');
        });

        return false;
    });

    jQuery("#include-stat").click(function () {
        var newOptions = {};
        jQuery("#bobsi-exc-statuses option:selected").each(function () {
            newOptions[jQuery(this).val()] = jQuery(this).text();
            jQuery(this).remove();
        });

        jQuery.each(newOptions, function (key, value) {
            jQuery("#bobsi-inc-statuses").append('<option value='+key+'>'+value+'</option>');
        });

        return false;
    });

    jQuery("#exclude-stat").click(function () {
        var newOptions = {};
        jQuery("#bobsi-inc-statuses option:selected").each(function () {
            newOptions[jQuery(this).val()] = jQuery(this).text();
            jQuery(this).remove();
        });

        jQuery.each(newOptions, function (key, value) {
            jQuery("#bobsi-exc-statuses").append('<option value='+key+'>'+value+'</option>');
        });

        return false;
    });

    // Secondhand action categories

    jQuery("#includeProductConditionSecondhandCategories").click(function () {
        var newOptions = {};
        jQuery("#productConditionNewCategories option:selected").each(function () {
            newOptions[jQuery(this).val()] = [];
            newOptions[jQuery(this).val()]['val'] = jQuery(this).text();
            newOptions[jQuery(this).val()]['style'] = (typeof jQuery(this).attr('style')) == 'undefined' ? '' : jQuery(this).attr('style');
            jQuery(this).remove();
        });

        jQuery.each(newOptions, function (key, value) {
            jQuery("#productConditionSecondhandCategories").append('<option style="' + value['style'] + '" value='+key+'>'+value['val']+'</option>');
        });

        return false;
    });

    jQuery("#excludeProductConditionSecondhandCategories").click(function () {
        var newOptions = {};
        jQuery("#productConditionSecondhandCategories option:selected").each(function () {
            newOptions[jQuery(this).val()] = [];
            newOptions[jQuery(this).val()]['val'] = jQuery(this).text();
            newOptions[jQuery(this).val()]['style'] = (typeof jQuery(this).attr('style')) == 'undefined' ? '' : jQuery(this).attr('style');
            jQuery(this).remove();
        });

        jQuery.each(newOptions, function (key, value) {
            jQuery("#productConditionNewCategories").append('<option style="' + value['style'] + '" value='+key+'>'+value['val']+'</option>');
        });

        return false;
    });

    // Refurbished action categories

    jQuery("#includeProductConditionRefurbishedCategories").click(function () {
        var newOptions = {};
        jQuery("#productConditionNewCategories option:selected").each(function () {
            newOptions[jQuery(this).val()] = [];
            newOptions[jQuery(this).val()]['val'] = jQuery(this).text();
            newOptions[jQuery(this).val()]['style'] = (typeof jQuery(this).attr('style')) == 'undefined' ? '' : jQuery(this).attr('style');
            jQuery(this).remove();
        });

        jQuery.each(newOptions, function (key, value) {
            jQuery("#productConditionRefurbishedCategories").append('<option style="' + value['style'] + '" value='+key+'>'+value['val']+'</option>');
        });

        return false;
    });

    jQuery("#excludeProductConditionRefurbishedCategories").click(function () {
        var newOptions = {};
        jQuery("#productConditionRefurbishedCategories option:selected").each(function () {
            newOptions[jQuery(this).val()] = [];
            newOptions[jQuery(this).val()]['val'] = jQuery(this).text();
            newOptions[jQuery(this).val()]['style'] = (typeof jQuery(this).attr('style')) == 'undefined' ? '' : jQuery(this).attr('style');
            jQuery(this).remove();
        });

        jQuery.each(newOptions, function (key, value) {
            jQuery("#productConditionNewCategories").append('<option style="' + value['style'] + '" value='+key+'>'+value['val']+'</option>');
        });

        return false;
    });

    jQuery('#submit').click(function () {
        jQuery('#bobsi-exc-categories option').prop('selected', 'selected');
        jQuery('#bobsi-inc-allow-offers-categories option').prop('selected', 'selected');
        jQuery('#bobsi-inc-statuses option').prop('selected', 'selected');
        jQuery('#productConditionSecondhandCategories option').prop('selected', 'selected');
        jQuery('#productConditionRefurbishedCategories option').prop('selected', 'selected');
    });

    /*
     * Defect 3734
     */
    function SetCssWidth()
    {
        var w = 200;
        if (screen.width<1280) {
            w = 115;
        } else if (screen.width<1360) {
            w = 180;
        }

        if (navigator.userAgent.search(/Chrome/)>0) {
            /*
             * For browsers based on Google Chrome
             */

            jQuery('body').append('<div class="tmp_options" ></div>');
            jQuery('select[multiple]').css({
                'min-width': w+'px',
                width: w+'px',
                'overflow-x': 'auto'
            });
            jQuery(".bobsi-categories-select option").each(function () {
                sp = "<span style='float:left;' id='sp_option_"+this.value+"'>"+this.text+"</span>";
                jQuery('.tmp_options').append(sp);
                opt_width = jQuery("#sp_option_"+this.value).width();
                jQuery(this).css('width', opt_width+25+"px");
            });
            jQuery('.tmp_options').detach();
        } else {
            /*
             * For other browsers
             */
            jQuery('.bobsi-categories-select').wrap('<div class="wrap_select"></div>');
            jQuery('select[multiple]').css('min-width',w);
            jQuery('.wrap_select').css({
                width:w+'px',
                'overflow-x': 'auto'
            });
        }
    }
    SetCssWidth();
});
