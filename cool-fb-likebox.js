/* Static Facebook Pop Out Like Box */

jQuery(document).ready(function () {

	var dur = "medium"; // Duration of Animation

	var offset = jQuery("#cfblikebox").attr("data-offset"); // Offset 

	if (jQuery("#cfblikebox").hasClass("cfbl_right")) {

		var margin = "-" + jQuery("#cfblikebox").attr("data-width"); // Width of Likebox

		jQuery("#cfblikebox").css({

			marginRight: margin,

			top: offset

		});

		jQuery("#cfblikebox").hover(function () {

			jQuery(this).stop().animate({

				marginRight: 0

			}, dur);

		}, function () {

			jQuery(this).stop().animate({

				marginRight: margin

			}, dur);

		});

	}

	if (jQuery("#cfblikebox").hasClass("cfbl_bottom")) {

		var margin = "-" + jQuery("#cfblikebox").attr("data-height"); // Width of Likebox

		jQuery("#cfblikebox").css({

			marginBottom: margin,

			left: offset

		});

		jQuery("#cfblikebox").hover(function () {

			jQuery(this).stop().animate({

				marginBottom: 0

			}, dur);

		}, function () {

			jQuery(this).stop().animate({

				marginBottom: margin

			}, dur);

		});

	}

	if (jQuery("#cfblikebox").hasClass("cfbl_left")) {

		var margin = "-" + jQuery("#cfblikebox").attr("data-width"); // Width of Likebox

		jQuery("#cfblikebox").css({

			marginLeft: margin,

			top: offset

		});

		jQuery("#cfblikebox").hover(function () {

			jQuery(this).stop().animate({

				marginLeft: 0

			}, dur);

		}, function () {

			jQuery(this).stop().animate({

				marginLeft: margin

			}, dur);

		});

	}

	if (jQuery("#cfblikebox").hasClass("cfbl_top")) {

		var margin = "-" + jQuery("#cfblikebox").attr("data-height"); // Width of Likebox

		jQuery("#cfblikebox").css({

			marginTop: margin,

			left: offset

		});

		jQuery("#cfblikebox").hover(function () {

			jQuery(this).stop().animate({

				marginTop: 0

			}, dur);

		}, function () {

			jQuery(this).stop().animate({

				marginTop: margin

			}, dur);

		});

	}

	// Show the Likebox

	jQuery("#cfblikebox").show();

});