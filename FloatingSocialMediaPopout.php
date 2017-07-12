<?php
/**
 * @package Floating Social Media popout button
 * @version 1.0
 */
/*
Plugin Name: Floating Social Media Popout
Plugin URI: http://www.reviewresults.in/reviewresults/post/2012/09/08/Floating-Social-Media-Popout-WordPress-Plugin.aspx
Description: Floating Social Media popout allows your webpage to show a face book like box and Googleplus badge widget when a visitor mouse hovers the floating face book icon or Googleplus icon located on right side of webpage.
Author: Santosh Padire
Version: 1.0
Author URI: http://www.reviewresults.in
*/
 
add_action('init','FFB_facebook_share_init');
add_action('wp_footer', 'FFB_FaceBook_Float_Load',100);

function FFB_facebook_share_init() {
	// DISABLED IN THE ADMIN PAGES
	if (is_admin()) {
		return;
	}
    wp_enqueue_script( 'jquery' );
	wp_enqueue_style('fsb_style', '/wp-content/plugins/floatingsocialmediapopout/fsb_style.css');	
}   
function FFB_FaceBook_Float_Load()
{
	echo FFB_FaceBook_Float();
}

/* Facebook and Googleplus*/
function FFB_FaceBook_Float()
{	

		if (is_admin()) 
		{
			return;
		}
        $FFB_path = get_option('FF_facebook_path');
		$FBFloatImage = get_plugin_directory().'/Images/FBFloat.png"';
		$GplusID = get_option('FF_GplusID');
		$GPFloatImage = get_plugin_directory().'/Images/GPlus.png"';
			
		$FFB_path = preg_replace('/:/','%3A', $FFB_path);  
        $FFB_path = preg_replace('#/#','%2F', $FFB_path); 
         
        $str = 'http://www.facebook.com/plugins/likebox.php?href=' .$FFB_path. '&amp;locale=en_GB&amp;width=238&amp;connections=9&amp;stream=&amp;header=false&amp;show_faces=0&amp;height=256';

		$button = '';
	
		$button .='<script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery("#FSPfacebook").hover(function () { jQuery(this).stop(true, false).animate({ right: 0 }, 500); },
                                  function () { jQuery("#FSPfacebook").stop(true, false).animate({ right: -240 }, 500); });
				    jQuery("#FSPgoogleplus").hover(function(){ jQuery(this).stop(true,false).animate({right:  0}, 500); },
								  function(){ jQuery("#FSPgoogleplus").stop(true,false).animate({right: -304}, 500); }); 				  
                }); 
                </script>';
				
		if ($FFB_path != '')
		{
        $button .=' 
                <div id="FSPMain"> 
                <div id="FSPfacebook" style="top: 10%;"> 
                <div id="FSPfacebookDiv"> 
                <img id="FSPImage" runat="server" src="'.$FBFloatImage.'';
        $button .='" alt="" /> 
                <iframe src=
                "'.$str.'';
        $button .='"';
        $button .=' scrolling="no"> </iframe>
		        </div>
                </div>
				</div>';		
		}
		
		if ($GplusID != '')
		{
		$button .=' 
                <div id="FSPMain"> 
                <div id="FSPgoogleplus" style="top: 27%;"> 
                <div id="FSPGplusDiv"> 
                <img id="FSPgoogleplusimg" runat="server" src="'.$GPFloatImage.'';
        $button .='" alt="" /> 		        
				<div style="float:left;margin:0px 0px 0px 0px;">
				<!-- Place this tag where you want the badge to render. -->
				<div class="g-plus" data-href="https://plus.google.com/'.$GplusID.'" data-rel="publisher"></div>
				<!-- Place this tag after the last badge tag. -->
				<script type="text/javascript">
				  (function() {
					var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;
					po.src = "https://apis.google.com/js/plusone.js";
					var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);
				  })();
				</script>			
				</div>';								
        $button .='</div>
                </div>
				</div>';
        }				
		return $button;
}
	
function get_plugin_directory(){
	return WP_PLUGIN_URL . '/floatingsocialmediapopout';	
}
 
/* Runs when plugin is activated */
register_activation_hook(__FILE__,'FFB_facebook_install'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'FFB_facebook_remove' );

function FFB_facebook_install() 
{
/* Do Nothing */
}

function FFB_facebook_remove() {
/* Deletes the database field */
delete_option('FF_facebook_path');
delete_option('FF_GplusID');
} 
if ( is_admin() ){

/* Call the html code */
add_action('admin_menu', 'floatingFB_admin_menu');

function floatingFB_admin_menu() {
add_options_page('Floating SM Popout', 'Floating SM Popout', 'administrator',
'Floating_SMP', 'floatingFB_html_page');
}
} 
 
function floatingFB_html_page() {
?>
<div>
<h2>Floating Sharing Popout Options</h2>
 
<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>
 
<table width="800">
<tr valign="top">
<th width="120" scope="row">FaceBook Page URL</th>
<td width="680">
<input name="FF_facebook_path" type="text" id="FF_facebook_path"
value="<?php echo get_option('FF_facebook_path'); ?>" />
(ex. https://www.facebook.com/pages/ReviewResults/163924380399354)</td>
</tr>
<tr valign="top">
<th width="120" scope="row">GooglePlus Page ID</th>
<td width="680">
<input name="FF_GplusID" type="text" id="FF_GplusID"
value="<?php echo get_option('FF_GplusID'); ?>" />
(ex. 102267831171303361155)</td>
</tr>
</table> 
<table width="800">
<tr valign="left">
<th width="800">Note:Leave the fields blank if you don't have any GooglePlus Page or Facebook page.Only the Field with values will be enabled on the page.</th>
</tr>
</table> 
<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="FF_facebook_path,FF_GplusID" />
 
<p>
<input type="submit" value="<?php _e('Save Changes') ?>" />
</p> 
</form>
</div>
<?php
} 
?>