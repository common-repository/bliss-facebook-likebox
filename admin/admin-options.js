jQuery(document).ready(function () {
	jQuery("input.bfblColorpicker").ColorPicker({
		onBeforeShow: function () {
			jQuery(this).ColorPickerSetColor(this.value)
		},
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(200);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(200);
			return false;
		},
		onSubmit: function (hsb, hex, rgb, el) {
			jQuery(el).val('#' + hex);
			jQuery(el).next('.bfblColorpreview').css({
				'backgroundColor': '#' + hex
			});
			jQuery(el).ColorPickerHide();
		}
	}).keyup(function () {
		var color = jQuery(this).val();
		jQuery(this).ColorPickerSetColor(color);
		jQuery(this).next('.bfblColorpreview').css({
			'backgroundColor': color
		});
	});
	jQuery("span.bfblColorpreview").each(function () {
		jQuery(this).css("backgroundColor", jQuery(this).prev("input.bfblColorpicker").val());
	});
	jQuery("span.bfblColorpreview").ColorPicker({
		onBeforeShow: function () {
			jQuery(this).ColorPickerSetColor(jQuery(this).siblings("input.bfblColorpicker").val());
		},
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(200);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(200);
			return false;
		},
		onSubmit: function (hsb, hex, rgb, el) {
			jQuery(el).css({
				'backgroundColor': '#' + hex
			});
			jQuery(el).siblings("input.bfblColorpicker").val('#' + hex);
			jQuery(el).ColorPickerHide();
		}
	});

	jQuery("#bfblSaveButton").click(function () {
		var formdata = jQuery("#bfbl_form").serialize() + "&bfbl_Ajax_nonce=" + bfblSettings.bfblAjaxnonce;
		var btn = jQuery(this);
		jQuery.ajax({
			type: "POST",
			url: bfblSettings.bfblAjaxurl,
			data: formdata,
			beforeSend: function (xhr) {
				btn.attr("disabled", "");
				jQuery("#ajax_save_popup").text("Saving Options...").fadeIn(100);
			},
			success: function (responce) {
				//alert(responce);
				jQuery("#ajax_save_popup").text("Options Saved!");
				setTimeout(function () {
					jQuery("#ajax_save_popup").fadeOut(300);
				}, 1000);
				btn.removeAttr("disabled");
			}
		});
		return false;
	});
});