<?php


/*

Plugin Name: Cool FB Likebox
Plugin URI: 
Description: Shows Static Facebook Like Box that pops out with Smooth Jquery Hover Effect
Version: 1.0
Author: Mark
Author URI: http://wordpress.org/support/profile/markkirkwp
License: GPL3

*/

/*

	This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of

	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.


	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/





if(function_exists('plugin_dir_url')){


	define('cfblDIR', plugin_dir_url(__FILE__));


} else {


	define('cfblDIR', get_bloginfo('wpurl') . "/wp-content/plugins/cool-fb-likebox/");


}





if(!class_exists('CoolFBLikebox')){


	class CoolFBLikebox {


	


		function CoolFBLikebox(){


			//	Adding admin action


			add_action('admin_menu', array($this,'bliss_facebook_likebox_page_admin_action'));


			//	Plugin Activation Hook


			register_activation_hook( __FILE__, array($this, 'cfbl_install'));


			//	Plugin Deactivation Hook


			register_deactivation_hook( __FILE__, array($this, 'cfbl_uninstall'));


			//	Adding Ajax Action


			add_action('wp_ajax_save_cfbl_options', array($this, 'cfbl_ajax_save_options'));


		}


		//	Update Default option after Activation


		function cfbl_install(){


			$cfbl_options                        = get_option("cfbl_options");


			$cfbl_options['enablecfbl']          = "yes"; 


			$cfbl_options['fbpageurl']           = "http://facebook.com/wordpress";


			$cfbl_options['likeboxwidth']        = "300";


			$cfbl_options['likeboxheight']       = "300";


			$cfbl_options['likeboxposition']     = "left";


			$cfbl_options['likeboxoffset']       = "350";


			$cfbl_options['likeboxbgcolor']      = "#d5dcfa";


			$cfbl_options['likeboxbordercolor']  = "#fab957";


			$cfbl_options['likeboxbadgebgcolor'] = "#3B5998";


			$cfbl_options['likeboxcolorscheme']  = "light";


			$cfbl_options['displaycredit']		= "0";


			update_option("cfbl_options", $cfbl_options);


		}


		


		//	Remove Plugin Option after Deactivation 


		function cfbl_uninstall(){


			delete_option("cfbl_options");


		}


		


		//	Admin Action callback function


		function bliss_facebook_likebox_page_admin_action() {


			//	Adding option page


			$bliss_facebook_likebox_page = add_options_page(


				"Cool FB Likebox Options",


				"Cool FB Likebox",


				"activate_plugins",


				"bliss_facebook_likebox",


				array($this, "bliss_facebook_likebox_adminpage")


			);


			


			//	Priniting Admin Scripts and Styles


			add_action("admin_print_scripts-{$bliss_facebook_likebox_page}", array($this, "bliss_facebook_likebox_adminscripts"));


			add_action("admin_print_styles-{$bliss_facebook_likebox_page}", array($this, "bliss_facebook_likebox_adminstyles"));


		}


		


		//	Add scripts Styles to 


		function cfbl_add_scripts(){


			$get_opt = get_option("cfbl_options");


			if(!is_admin() && $get_opt['enablecfbl'] == "yes"){


				$get_wp_ver = get_bloginfo("version");


				if(version_compare($get_wp_ver, "2.7", ">")){


					add_action("wp_print_scripts", array($this,"bliss_facebook_likebox_scripts"));


					add_action("wp_print_styles", array($this,"bliss_facebook_likebox_styles"));


					add_action("wp_footer", array($this, "bliss_facebook_likebox_html"));


				} else {


					add_action("wp_head", array($this, "cfbl_wp_head_scripts"));


					add_action("wp_footer", array($this, "bliss_facebook_likebox_html"));


				}





			}


		}


	


		//	Generation Admin Options Page


		function bliss_facebook_likebox_adminpage(){


			if(isset($_POST['action']) && $_POST['action'] == "save_cfbl_options"){


				$this->save_bliss_facebook_likebox_options("post");


			}


			


			$print_option = get_option("cfbl_options");


			$selected = " selected=\"selected\"";


			?>


			<div class="wrap" id="cfbl_wrap">


				<div class="icon32" id="icon-plugins"></div><h2><?php _e("Cool FB Likebox Options");?></h2>


				<?php


				if(isset($_GET['saved']) && $_GET['saved'] == "true"){


					echo '<div class="updated fade"><p>';


					_e("Cool FB Likebox Options are Saved!");


					echo '</p></div>';


				}


				?>


				<form action="" method="POST" enctype="multipart/form-data" id="cfbl_form">


					<fieldset>


						<legend><?php _e("Cool FB Like box Settings");?></legend>


	


						<table>


							<tr>


								<td><label for="enablecfbl"><?php _e("Enable Facebook Popup Likebox?");?></label></td>


								<td><input type="checkbox" name="enablecfbl" value="yes" id="enablecfbl" <?php if($print_option['enablecfbl']=="yes") echo "checked='checked'";?> style="width:auto;"/> </td>


							</tr>


<tr>


								<td><label for="displaycredit"><?php _e("Display Credits?");?></label></td>


								<td><input type="checkbox" name="displaycredit" value="1" id="displaycredit" <?php if($print_option['displaycredit']=="1") echo "checked='checked'";?> style="width:auto;"/> </td>


							</tr>


							<tr>


								<td><label for="fbpageurl"><?php _e("Facebook Page URL");?></label></td>


								<td><input type="text" name="fbpageurl" value="<?php echo $print_option['fbpageurl'];?>" id="fbpageurl"/> </td>


							</tr>


							<tr>


								<td><label for="likeboxwidth"><?php _e("Likebox Width");?></label></td>


								<td><input type="text" name="likeboxwidth" id="likeboxwidth" value="<?php echo $print_option['likeboxwidth'];?>"/> px</td>


							</tr>


							<tr>


								<td><label for="likeboxheight"><?php _e("Likebox Height");?></label></td>


								<td><input type="text" name="likeboxheight" id="likeboxheight" value="<?php echo $print_option['likeboxheight'];?>"/> px</td>


							</tr>


							<tr>


								<td><label for="likeboxcolorscheme"><?php _e("Likebox Color Scheme");?></label></td>


								<td><select name="likeboxcolorscheme" id="likeboxcolorscheme">


									<option value="light"<?php if($print_option['likeboxcolorscheme'] == "light") echo $selected;?> ><?php _e("Light");?></option>


									<option value="dark"<?php if($print_option['likeboxcolorscheme'] == "dark") echo $selected;?>><?php _e("Dark");?></option>


								</select></td>


							</tr>


							<tr>


								<td><label for="likeboxbgcolor"><?php _e("Likebox Background Color");?></label></td>


								<td><input type="text" name="likeboxbgcolor" id="likeboxbgcolor" value="<?php echo $print_option['likeboxbgcolor'];?>" class="cfblColorpicker"/>  <span class="cfblColorpreview"></span></td>


							</tr>


							<tr>


								<td><label for="likeboxbordercolor"><?php _e("Likebox Border Color");?></label></td>


								<td><input type="text" name="likeboxbordercolor" id="likeboxbordercolor" value="<?php echo $print_option['likeboxbordercolor'];?>" class="cfblColorpicker"/>  <span class="cfblColorpreview"></span></td>


							</tr>


						</table>


						


					</fieldset>


					


					<fieldset>


						<legend><?php _e("Styling Options");?></legend>


						<table>


							<tr>


								<td><label for="likeboxposition"><?php _e("Likebox Position");?></label></td>


								<td><select name="likeboxposition" id="likeboxposition">


									<option value="left"<?php if($print_option['likeboxposition'] == "left") echo $selected;?>><?php _e("Left");?></option>


									<option value="right"<?php if($print_option['likeboxposition'] == "right") echo $selected;?>><?php _e("Right");?></option>


									<option value="bottom"<?php if($print_option['likeboxposition'] == "bottom") echo $selected;?>><?php _e("Bottom");?></option>


									<option value="top"<?php if($print_option['likeboxposition'] == "top") echo $selected;?>><?php _e("Top");?></option>


								</select></td>


							</tr>


							<tr>


								<td><label for="likeboxoffset"><?php _e("Position Offset");?></label></td>


								<td><input type="text" name="likeboxoffset" id="likeboxoffset" value="<?php echo $print_option['likeboxoffset'];?>"/> px</td>


							</tr>


							<tr>


								<td><label for="likeboxbadgebgcolor"><?php _e("Facebook Badge Color");?></label></td>


								<td><input type="text" name="likeboxbadgebgcolor" id="likeboxbadgebgcolor" value="<?php echo $print_option['likeboxbadgebgcolor'];?>"class="cfblColorpicker"/>  <span class="cfblColorpreview"></span></td>


							</tr>


						</table>


					</fieldset>


					


					<div class="submitwrap">


						<input type="submit" value="<?php _e("Save Options");?>" class="button-secondary" id="cfblSaveButton"/>


						<input type="hidden" name="action" value="save_cfbl_options" />


						<?php if(function_exists("wp_nonce_field")){ wp_nonce_field("bliss_facebook_likebox");} ?>


					</div>


				</form>


				<div class="cfbl_footer">


					Please Share this Plugin : <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.blissdrive.com/" data-text="Static FaceBook Pop Out Like Box WordPress Plugin Static FaceBook Pop Out Like Box WordPress Plugin" data-via="way2blogging">Tweet</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script> <a name="fb_share" share_url="http://www.blissdrive.com"></a><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>


					<br/>


					</div>


				<div id="ajax_save_popup" style="display:none;"></div>


			</div><!-- end #cfbl_wrap -->


			<?php


		}


		


		//	Ajax Callback Function


		function cfbl_ajax_save_options(){


			//	Ajax Security check


			if(function_exists('wp_verify_nonce')){


				if (!wp_verify_nonce($_POST['cfbl_Ajax_nonce'],"cfbl_Ajax_nonce")) die ('Error!!!');	


			}


			if(isset($_POST['action']) && $_POST['action'] == "save_cfbl_options"){


				$this->save_bliss_facebook_likebox_options("ajax");


			}


			die('-1');


		}


		


		//	Save the Options


		function save_bliss_facebook_likebox_options($method){


			//	Security check


			if(function_exists("check_admin_referer")){


				check_admin_referer("bliss_facebook_likebox");


			}


			//	Options array


			$options = array("enablecfbl","displaycredit","fbpageurl","likeboxwidth","likeboxheight","likeboxposition","likeboxbgcolor","likeboxbadgebgcolor","likeboxbordercolor","likeboxcolorscheme","likeboxoffset");


			//	New Options array


			$newoptions = array();


			foreach($options as $opt){


				if(isset($_REQUEST[$opt])){


					$newoptions[$opt] = $_REQUEST[$opt];


				}


			}


			//	Update Options with New Options


			update_option("cfbl_options", $newoptions);


			//	Redirections


			if($method == "post"){


				header("Location:options-general.php?page=bliss_facebook_likebox&saved=true");


			}


			//	Stop


			die('1');


		}


		


		//	Enqueue Plugin Scripts


		function bliss_facebook_likebox_scripts(){


			wp_enqueue_script("cool-fb-likebox", cfblDIR ."cool-fb-likebox.js", array("jquery"), "1.0.0", false );


		}


		


		//	Enqueue Plugin Styles


		function bliss_facebook_likebox_styles(){


			wp_enqueue_style("cool-fb-likebox", cfblDIR ."cool-fb-likebox.css");


		}


		


		//	Insert Scripts for Older versions


		function cfbl_wp_head_scripts(){


			$dir = get_bloginfo("wpurl");


			echo "\n<link rel=\"stylesheet\" type=\"text/css\" href=\"{$dir}/wp-content/plugins/cool-fb-likebox/cool-fb-likebox.css\" media=\"all\" />\n<script type=\"text/javascript\">/*<![CDATA[*/if (typeof jQuery == 'undefined') {document.write(unescape(\"%3Cscript src='http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js' type='text/javascript'%3E%3C/script%3E\"));} /*]]>*/</script>\n<script type=\"text/javascript\" src=\"{$dir}/wp-content/plugins/cool-fb-likebox/cool-fb-likebox.js\"></script>\n";


		}


		


		// 	Enqueue Admin Scripts


		function bliss_facebook_likebox_adminscripts(){


			//wp_enqueue_script('jquery');


			wp_enqueue_script("cool-fb-likebox-colorpicker", cfblDIR ."admin/js/colorpicker.js", array("jquery"), "1.0.1", false);


			wp_enqueue_script("cool-fb-likebox-admin", cfblDIR ."admin/admin-options.js", array("jquery"), "1.0.1", false);


			wp_localize_script("cool-fb-likebox-admin", "cfblSettings", array( "cfblAjaxurl" => admin_url("admin-ajax.php"), "cfblAjaxnonce" => wp_create_nonce("cfbl_Ajax_nonce")));


		}


		


		//	Enqueue Admin Styles


		function bliss_facebook_likebox_adminstyles(){


			wp_enqueue_style("cool-fb-likebox-colorpicker", cfblDIR ."admin/css/colorpicker.css");


			wp_enqueue_style("cool-fb-likebox-admin", cfblDIR ."admin/admin-options.css");


		}


		


		// 	Print HTML


		function bliss_facebook_likebox_html(){


			$get_cfbl_options = get_option("cfbl_options");


			$url              = urlencode($get_cfbl_options['fbpageurl']);


			$width            = $get_cfbl_options['likeboxwidth'];


			$height           = $get_cfbl_options['likeboxheight'];


			$colorscheme      = $get_cfbl_options['likeboxcolorscheme'];


			$backgroundcolor  = $get_cfbl_options['likeboxbgcolor'];


			$position         = "cfbl_".$get_cfbl_options['likeboxposition'];


			$badgebgcolor     = $get_cfbl_options['likeboxbadgebgcolor'];


			$offset           = $get_cfbl_options['likeboxoffset'];


			$bordercolor      = urlencode($get_cfbl_options['likeboxbordercolor']);


			echo "


			<div id=\"cfblikebox\" class=\"{$position}\" style=\"display:none;width:{$width}px; height:{$height}px;\" data-offset=\"{$offset}px\" data-width=\"{$width}px\" data-height=\"{$height}px\">


				<div class=\"cfblbadge\" style=\"background-color:{$badgebgcolor};\"></div>


		


				<iframe 


<?php function display_credits(){


src=\"//www.facebook.com/plugins/likebox.php?href={$url}&amp;width={$width}&amp;height={$height}&amp;colorscheme={$colorscheme}&amp;show_faces=true&amp;border_color={$bordercolor}&amp;stream=false&amp;header=false\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:{$width}px; height:{$height}px;background:{$backgroundcolor};\" allowTransparency=\"true\">


</iframe>





			</div>


			";


			


//display credits scripts


function display_credits(){


	$get_opt = get_option("cfbl_options");


	if($get_opt['displaycredit'] =="1") {


		echo '<div align="center"><small>Facebook Like Box provided by <a href="http://wordpress.org/support/profile/markkirkwp">Mark</a></small></div>';


	} else{


		echo '<!-- Facebook Like Box provided by Mark -->';}


}


display_credits();


		}


       	}//	end Class


}// 	end class_exists





//	New Cool FB Likebox


$cfbl = new CoolFBLikebox();





//	Add Scripts


$cfbl->cfbl_add_scripts();


?>