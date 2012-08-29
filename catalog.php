<?php 

/*
Plugin Name: Spider Catalog
Plugin URI: http://web-dorado.com/
Version: 1.0
Author: http://web-dorado.com/
License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/

$many_players=0;

add_action( 'init', 'catalog_lang_load' );

function catalog_lang_load() {
	 load_plugin_textdomain('sp_catalog', false, basename( dirname( __FILE__ ) ) . '/Languages' );
	
}

function Spider_Catalog_Products_list_shotrcode($atts) {
     extract(shortcode_atts(array(
	      'id' => 'no Spider catalog',
		  'details' => '1',
		  'type' => '',
     ), $atts));
     return spider_cat_Products_list($id,$details,$type);
}
add_shortcode('Spider_Catalog_Category', 'Spider_Catalog_Products_list_shotrcode');



function Spider_Catalog_Single_product_shotrcode($atts) {
     extract(shortcode_atts(array(
	      'id' => '',
     ), $atts));
     return spider_cat_Single_product($id);
}
add_shortcode('Spider_Catalog_Product', 'Spider_Catalog_Single_product_shotrcode');







//// singl product
 function   spider_cat_Products_list($id,$details,$type){
	require_once("front_end_functions .html.php");
	require_once("front_end_functions.php");
	if(isset($_GET['product_id'])){
		if(isset($_GET['view']))
		{
			if($_GET['view']=='spidercatalog')
			{
				return showPublishedProducts_1($id,$details,$type);
			}
			else
			{
			return 	front_end_single_product($_GET['product_id']);
			}
		}
			else{
			return 	front_end_single_product($_GET['product_id']);
			}
	}
	else
	{
	 return showPublishedProducts_1($id,$details,$type);
	}
	 }
	 
	 
	 
	 // prodact list
	 function spider_cat_Single_product($id)
	 {
		 
		 
		 	require_once("front_end_functions .html.php");
			require_once("front_end_functions.php");
			return front_end_single_product($id);
	
	 
		 
		 
	 }


//// add editor new mce button
add_filter('mce_external_plugins', "Spider_Catalog_register");
add_filter('mce_buttons', 'Spider_Catalog_add_button', 0);






/// function for add new button
function Spider_Catalog_add_button($buttons)
{
    array_push($buttons, "Spider_Catalog_mce");
    return $buttons;
}









 /// function for registr new button
function Spider_Catalog_register($plugin_array)
{
    $url = plugins_url( 'js/editor_plugin.js' , __FILE__ ); 
    $plugin_array["Spider_Catalog_mce"] = $url;
    return $plugin_array;
}










function add_button_style_Spider_Catalog()
{
echo '<style type="text/css">
.wp_themeSkin span.mce_Spider_Catalog_mce {background:url('.plugins_url( 'images/Spider_CatalogLogo.png' , __FILE__ ).') no-repeat !important;}
.wp_themeSkin .mceButtonEnabled:hover span.mce_Spider_Catalog_mce,.wp_themeSkin .mceButtonActive span.mce_Spider_Catalog_mce
{background:url('.plugins_url( 'images/Spider_CatalogLogoHover.png' , __FILE__ ).') no-repeat !important;}
</style>';
}

add_action('admin_head', 'add_button_style_Spider_Catalog');












function spiderbox_scripts_method() {
    wp_enqueue_script( 'spiderbox',plugins_url("spiderBox/spiderBox.js.php",__FILE__).'?delay=3000&allImagesQ=0&slideShowQ=0&darkBG=1&juriroot='.urlencode(plugins_url("",__FILE__)).'&spiderShop=1' );
	wp_enqueue_script( 'my_common',plugins_url("js/common.js",__FILE__));
	wp_enqueue_style('spider_cat_main',plugins_url("spidercatalog_main.css",__FILE__));
}    
 
add_action('wp_enqueue_scripts', 'spiderbox_scripts_method');











add_filter('admin_head','spider_cat_ShowTinyMCE');
function spider_cat_ShowTinyMCE() {
	// conditions here
	wp_enqueue_script( 'common' );
	wp_enqueue_script( 'jquery-color' );
	wp_print_scripts('editor');
	if (function_exists('add_thickbox')) add_thickbox();
	wp_print_scripts('media-upload');
	if (function_exists('wp_tiny_mce')) wp_tiny_mce();
	wp_admin_css();
	wp_enqueue_script('utils');
	do_action("admin_print_styles-post-php");
	do_action('admin_print_styles');
}




















add_action('admin_menu', 'Spider_Catalog_options_panel');
function Spider_Catalog_options_panel(){
		$page_cat  = add_menu_page(	'Theme page title', 'Spider Catalog', 'manage_options', 'Categories_Spider_Catalog', 'Categories_Spider_Catalog',plugins_url( 'images/Spider_CatalogLogoHover -for_menu.png' , __FILE__ ))  ;
 					 add_submenu_page( 'Categories_Spider_Catalog', 'Categories', 'Categories', 'manage_options', 'Categories_Spider_Catalog', 'Categories_Spider_Catalog');
		$page_prad=  add_submenu_page( 'Categories_Spider_Catalog', 'Products', 'Products', 'manage_options', 'Products_Spider_Catalog', 'Products_Spider_Catalog');
					 add_submenu_page( 'Categories_Spider_Catalog', 'Global Options', 'Global Options', 'manage_options', 'Options_Catalog_global', 'Options_Catalog_global');
 		$page_option=add_submenu_page( 'Categories_Spider_Catalog', 'Styles and Colors', 'Styles and Colors', 'manage_options', 'Options_Catalog_styles', 'Options_Catalog_styles');
  add_submenu_page( 'Categories_Spider_Catalog', 'Uninstall Spider_Catalog ', 'Uninstall  Spider Catalog', 'manage_options', 'Uninstall_Spider_Catalog', 'Uninstall_Spider_Catalog');
  
  add_action('admin_print_styles-' . $page_cat, 'Spider_Category_admin_script');
    add_action('admin_print_styles-' . $page_prad, 'Spider_prodact_admin_script');
	add_action('admin_print_styles-' . $page_option, 'Spider_option_admin_script');
}




/////////////////////             Spider_Category print styles


function Spider_Category_admin_script()
{

		wp_enqueue_script( 'param_block',plugins_url("js/param_block.js",__FILE__));	
}
function Spider_prodact_admin_script()
{
	
		wp_enqueue_script( 'param_block',plugins_url("js/param_block.js",__FILE__));	
}
function Spider_option_admin_script()
{

		wp_enqueue_script( 'param_block1',plugins_url("js/mootools.js",__FILE__));	
		wp_enqueue_script( 'param_block2',plugins_url("elements/moorainbow/mooRainbow.js",__FILE__));
		wp_enqueue_script( 'param_block3',plugins_url("elements/moorainbow/mootools.js",__FILE__));
		wp_enqueue_script( 'param_block4',plugins_url("js/product.javascript.js",__FILE__));
		wp_enqueue_style( 'param_block5',plugins_url("elements/moorainbow/mooRainbow.css",__FILE__) );
		
		
		
}






















///////////////////////////////////////////////////////////////////// TAGS

//////////require_once("nav_function/nav_html_func.php");
/*
add_filter('admin_head','ShowTinyMCE');
function ShowTinyMCE() {
	// conditions here
	wp_enqueue_script( 'common' );
	wp_enqueue_script( 'jquery-color' );
	wp_print_scripts('editor');
	if (function_exists('add_thickbox')) add_thickbox();
	wp_print_scripts('media-upload');
	if (function_exists('wp_tiny_mce')) wp_tiny_mce();
	wp_admin_css();
	wp_enqueue_script('utils');
	do_action("admin_print_styles-post-php");
	do_action('admin_print_styles');
}
*/
function Categories_Spider_Catalog()
{
	////////including functions for categories
	require_once("Categories.php");
	require_once("Categories.html.php");
	if(!function_exists ('print_html_nav' ))
	require_once("nav_function/nav_html_func.php");
	
	
	
	
	
	
	
	
	
$task=$_GET["task"];//get task for choosing function

if(isset($_GET["id"]))
	$id=$_GET["id"];
	else
		$id=0;
global $wpdb;
switch ($task)
{
	
	   case 'add_cat':
         add_category();
        break;
		case 'publish_cat':
			change_cat($id);
			showCategory();
				break;	 
		case 'unpublish_cat':
			change_cat($id);
			showCategory();
				break;	
	
    case 'edit_cat':
	if($id)
         editCategory($id);
	else
		{
			$id=$wpdb->get_var("SELECT MAX( id ) FROM ".$wpdb->prefix."spidercatalog_product_categories");
			 editCategory($id);
		}
        break;

    case 'save':
	if($id)
	 apply_cat($id);
	 else
      save_cat();
		   showCategory();
			break;
			
	case 'apply':
	if($id)
	{ 
		apply_cat($id);
         editCategory($id);
		
	}
	else
		{
			$true=save_cat();
			if($true){
			$id=$wpdb->get_var("SELECT MAX( id ) FROM ".$wpdb->prefix."spidercatalog_product_categories");
			 editCategory($id);
			}
			else
			{
				?><h1>Database Error Please install plugin again</h1><?php
				showCategory();
			}
		}
	
	break;
 
  
   case 'remove_cat':
            removeCategory($id);
			showCategory();
			break;

   default:
            showCategory();
        break;
}

	
}











	





function Products_Spider_Catalog(){
	
	global $wpdb;
		require_once("products.php");
	require_once("Products.html.php");
	if(!function_exists ('print_html_nav' ))
	require_once("nav_function/nav_html_func.php");
	
	if(isset($_GET['id']))
	{
	$id=$_GET['id'];
	}
	else
	{
		$id=0;
	}
	if(isset($_GET['task']))
	$task=$_GET['task'];
	else
	$task="";
	
	switch ($task)
{
    case 'edit_prad':
	      editProduct($id);
        break;
    case 'add_prad':
            addProduct();
        break;
    case 'apply':
	$saveeee=1;
	if($id){
	update_prad_cat($id);
	}
	else
	{
		$saveeee=save_prad_cat();
		$id=$wpdb->get_var("SELECT MAX(id) FROM ".$wpdb->prefix."spidercatalog_products");
	}
	if($saveeee)
	 editProduct($id);
	 else
	 {
	 $_POST['cat_search']='';
	 showProducts();
	 }
	break;
	  case 'save':
	  if($id)
	  update_prad_cat($id);
	  else
	   save_prad_cat();
	    showProducts();
	break;
	
    case 'saveorder':

        break;
			case 'unpublish_prad':
			change_prod($id);
			showProducts();
				break;	 
		case 'unpublish_prad':
			change_prod($id);
			showProducts();
				break;	
    case 'remove_prod':
            removeProduct($id);
			 showProducts();
			break;
		     case 'edit_reviews':
            spider_cat_prod_rev($id);
			
			break;

		     case 'delete_reviews':
            delete_rev($id);
			spider_cat_prod_rev($id);
			
			break;
		 case 'delete_review':
            delete_single_review($id);
			spider_cat_prod_rev($id);
			
			break;
		 case 'edit_rating':
            spider_cat_prod_rating($id);
			
			break;

		     case 'delete_ratings':
            delete_ratings($id);
			spider_cat_prod_rating($id);
			
			break;
		 case 'delete_rating':
            delete_single_rating($id);
			spider_cat_prod_rating($id);
			
			break;
			case 's_p_apply_rating':
            update_s_c_rating($id);
			spider_cat_prod_rating($id);
			
			break;
			case 's_p_save_rating':
            update_s_c_rating($id);
			editProduct($id);
			
			break;
			
   default:
            showProducts();

        break;
}
	
	
	
}











function Options_Catalog_styles(){

 	require_once("catalog_Options.php");
	require_once("catalog_Options.html.php");
	if(isset($_GET['task']))
	if($_GET['task']=='save')
	save_styles_options();
	showStyles();
	

}

function Options_Catalog_global(){

 	require_once("catalog_Options.php");
	require_once("catalog_Options.html.php");
	if(isset($_GET['task']))
	if($_GET['task']=='save')
	save_global_options();
	showGloballll();
	

}













 //////////////////////////////////////////////////////                                             /////////////////////////////////////////////////////// 
 //////////////////////////////////////////////////////               Uninstall                     ///////////////////////////////////////////////////////
 //////////////////////////////////////////////////////                                             ///////////////////////////////////////////////////////
 //////////////////////////////////////////////////////                                             ///////////////////////////////////////////////////////










function Uninstall_Spider_Catalog(){
	
global $wpdb;
$base_name = plugin_basename('Spider_Catalog');
$base_page = 'admin.php?page='.$base_name;
$mode = trim($_GET['mode']);


if(!empty($_POST['do'])) {

	if($_POST['do']=="UNINSTALL Spider_Catalog") {
			check_admin_referer('Spider_Catalog uninstall');
			if(trim($_POST['Spider_Catalog_yes']) == 'yes') {
				
				echo '<div id="message" class="updated fade">';
				echo '<p>';
				echo "Table 'spidercatalog_params' has been deleted.";
				$wpdb->query("DROP TABLE ".$wpdb->prefix."spidercatalog_params");
				echo '<font style="color:#000;">';
				echo '</font><br />';
				echo '</p>';
				echo '<p>';
				echo "Table 'spidercatalog_products' has been deleted.";
				$wpdb->query("DROP TABLE ".$wpdb->prefix."spidercatalog_products");
				echo '<font style="color:#000;">';
				echo '</font><br />';
				echo '</p>';
				echo "Table 'spidercatalog_product_categories' has been deleted.";
				$wpdb->query("DROP TABLE ".$wpdb->prefix."spidercatalog_product_categories");
				echo '<font style="color:#000;">';
				echo '</font><br />';
				echo '</p>';
				echo "Table 'spidercatalog_product_reviews' has been deleted.";
				$wpdb->query("DROP TABLE ".$wpdb->prefix."spidercatalog_product_reviews");
				echo '<font style="color:#000;">';
				echo '</font><br />';
				echo '</p>';
				echo "Table 'spidercatalog_product_votes' has been deleted.";
				$wpdb->query("DROP TABLE ".$wpdb->prefix."spidercatalog_product_votes");

				echo '<font style="color:#000;">';
				echo '</font><br />';
				echo '</p>';
				echo '</div>'; 
				
				$mode = 'end-UNINSTALL';
			}
		}
}



switch($mode) {

		case 'end-UNINSTALL':
			$deactivate_url = wp_nonce_url('plugins.php?action=deactivate&amp;plugin='.plugin_basename(__FILE__), 'deactivate-plugin_'.plugin_basename(__FILE__));
			echo '<div class="wrap">';
			echo '<h2>Uninstall Spider Catalog</h2>';
			echo '<p><strong>'.sprintf('<a href="%s">Click Here</a> To Finish The Uninstallation And Spider Catalog Will Be Deactivated Automatically.', $deactivate_url).'</strong></p>';
			echo '</div>';
			break;
	// Main Page
	default:
?>
<form method="post" action="<?php echo admin_url('admin.php?page=Uninstall_Spider_Catalog'); ?>">
<?php wp_nonce_field('Spider_Catalog uninstall'); ?>
<div class="wrap">
	<div id="icon-Spider_Catalog" class="icon32"><br /></div>
	<h2><?php echo 'Uninstall Spider Catalog'; ?></h2>
	<p>
		<?php echo 'Deactivating Spider Catalog plugin does not remove any data that may have been created. To completely remove this plugin, you can uninstall it here.'; ?>
	</p>
	<p style="color: red">
		<strong><?php echo'WARNING:'; ?></strong><br />
		<?php echo 'Once uninstalled, this cannot be undone. You should use a Database Backup plugin of WordPress to back up all the data first.'; ?>
	</p>
	<p style="color: red">
		<strong><?php echo 'The following WordPress Options/Tables will be DELETED:'; ?></strong><br />
	</p>
	<table class="widefat">
		<thead>
			<tr>
				<th><?php echo 'WordPress Tables'; ?></th>
			</tr>
		</thead>
		<tr>
			<td valign="top">
				<ol>
				<?php
						echo '<li>spidercatalog_params</li>'."\n";
						echo '<li>spidercatalog_products</li>'."\n";
						echo '<li>spidercatalog_product_categories</li>'."\n";
						echo '<li>spidercatalog_product_reviews</li>'."\n";
						echo '<li>spidercatalog_product_votes</li>'."\n";
				?>
				</ol>
			</td>
		</tr>
	</table>
	<p style="text-align: center;">
		<?php echo 'Do you really want to uninstall Spider Catalog?'; ?><br /><br />
		<input type="checkbox" name="Spider_Catalog_yes" value="yes" />&nbsp;<?php echo 'Yes'; ?><br /><br />
		<input type="submit" name="do" value="<?php echo 'UNINSTALL Spider_Catalog'; ?>" class="button-primary" onclick="return confirm('<?php echo 'You Are About To Uninstall Spider Catalog From WordPress.\nThis Action Is Not Reversible.\n\n Choose [Cancel] To Stop, [OK] To Uninstall.'; ?>')" />
	</p>
</div>
</form>
<?php
} // End switch($mode)


	
	
	
	
}



 //////////////////////////////////////////////////////                                             /////////////////////////////////////////////////////// 
 //////////////////////////////////////////////////////               Activate                      ///////////////////////////////////////////////////////
 //////////////////////////////////////////////////////                                             ///////////////////////////////////////////////////////
 //////////////////////////////////////////////////////                                             ///////////////////////////////////////////////////////



function Spider_Catalog_activate()
{
	global $wpdb;
/// creat database tables



$sql_spidercatalog_params="
CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."spidercatalog_params`(
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `name` varchar(50) 
CHARACTER SET utf8 NOT NULL,
  `title` varchar(200) CHARACTER SET utf8 NOT NULL,
 `description` text CHARACTER SET utf8 NOT NULL,
  `value` varchar(200) CHARACTER SET utf8 NOT NULL,
  
 PRIMARY KEY (`id`)
 
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=89 ";

$sql_spidercatalog_products="
CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."spidercatalog_products` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
 `category_id` int(11) unsigned DEFAULT NULL,
 `description` text,
  `image_url` varchar(2000) DEFAULT NULL,
  `cost` decimal(11,2) unsigned DEFAULT NULL,
  `market_cost` decimal(11,2) unsigned 

DEFAULT NULL,
  `param` text,
  `ordering` int(11) NOT NULL,
  `published` tinyint(4) unsigned DEFAULT NULL,

  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)

) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5";

$sql_spidercatalog_product_categories="
CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."spidercatalog_product_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `category_image_url` varchar(2000) NOT NULL,
  `description` text,
  `param` text,
  `ordering` int(11) NOT NULL,
  `published` tinyint(4) unsigned DEFAULT NULL,
  
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
  
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ";

$sql_spidercatalog_product_reviews="
CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."spidercatalog_product_reviews` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `product_id` int(11) 

NOT NULL,
  `remote_ip` varchar(15) NOT NULL,
  
  PRIMARY KEY (`id`)

) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3";

$sql_spidercatalog_product_votes="
CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."spidercatalog_product_votes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `remote_ip` varchar(15) NOT NULL,
  `vote_value` int(3) NOT NULL,
  `product_id` int(11) NOT NULL,
  
  PRIMARY KEY (`id`)

) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3";


$table_name=$wpdb->prefix."spidercatalog_params";
$sql_1=<<<query1
INSERT INTO `$table_name` (`id`, `name`, `title`, 

`description`, `value`) VALUES
(1, 'market_price', 'Market Price', 'Show or hide market Price', '1'),
(5, 'currency_symbol_position', 'Currency Symbol Position', 'Currency Symbol Position (after or before number )', '0'),
(15, 'params_background_color1', 'Parameters Background color 1', 'Background Color of odd parameters', '#c9effe'),
(9, 'currency_symbol', 'Currency Symbol', 'Currency Symbol', '$'),
(10, 'enable_rating', 'Enable/Disable Product Ratings', 'Enable/Disable Product Ratings', '1'),
(11, 'enable_review', 'Enable/Disable Customer Reviews', 'Enable/Disable Customer \r\n\r\nReviews', '1'),
(16, 'params_background_color2', 'Parameters Background color 2', 'Background Color of odd parameters', '#eff9fd'),
(17, 'parameters_select_box_width', 'Parameters Select Box Width', 'Parameters Select Box Width', '104'),
(18, 'background_color', 'Background color', 'Background color', '#f2f2f2'),
(19, 'border_style', 'Border Style', 'Border Style', 'solid'),
(20, 'border_width', 'Border Width', 'Border Width', '1'),
(21, 'border_color', 'Border Color', 'Border Color', '#00aeef'),
(22, 'text_color', 'Text Color', 'Text Color', '#20b8f1'),
(23, 'params_color', 'Color of Parameter Values', 'Color \r\n\r\nof Parameter Values', '#2f699e'),
(24, 'hyperlink_color', 'Hyperlink Color', 'Hyperlink Color', '#000000'),
(25, 'price_color', 'Price Color', 'Price Color', '#fc0303'),
(26, 'title_color', 'Title Color', 'Title Color', '#ffffff'),
(27, 'title_background_color', 'Title Background color', 'Title Background color', '#00aeef'),
(28, 'button_color', 'Buttons Text \r\n\r\ncolor', 'Color of text of buttons', '#ffffff'),
(29, 'button_background_color', 'Buttons Background color', 'Background Color \r\n\r\nof buttons', '#00aeef'),
(30, 'rating_star', 'Rating Star Design', 'Rating Star Design', '4'),
(31, 'count_of_product_in_the_row', 'Count of Products in the Row', 'Count of Products in the Row', '2'),
(32, 'count_of_rows_in_the_page', 'Count of Rows in the Page', 'Count of Rows in the Page', '3'),
(33, 'product_cell_width', 'Product Cell Width', 'Product Cell Width', '278'),
(34, 'product_cell_height', 'Product Cell Height', 'Product Cell Height', '520'),
(35, 'small_picture_width', 'Picture Width', 'Picture Width', '80'),
(36, 'small_picture_height', 'Picture Height', 'Picture Height', '80'),
(37, 'text_size_small', 'Text Size', 'Text Size', '13'),
(38, 'price_size_small', 'Price Size', 'Price \r\n\r\nSize', '14'),
(39, 'title_size_small', 'Title Size', 'Title Size', '14'),
(40, 'large_picture_width', 'Picture Width', 'Picture \r\n\r\nWidth', '250'),
(41, 'large_picture_height', 'Picture Height', 'Picture Height', '200'),
(42, 'text_size_big', 'Text Size', 'Text Size', '12'),
(43, 'price_size_big', 'Price Size', 'Price Size', '20'),
(44, 'title_size_big', 'Title Size', 'Title Size', '13'),
(45, 'review_background_color', 'Background Color of ''Add your review here'' block', 'Background Color of ''Add your \r\n\r\nreview here'' block', '#EFF9FD'),
(46, 'review_color', 'Color of Review Texts', 'Color of Review Texts', '#2f699e'),
(47, 'review_author_background_color', 'Background Color of Review Author block', 'Background Color of Review Author block', '#c9effe'),
(48, 'review_text_background_color', 'Background Color of Author block', 'Background Color of Review text', '#eff9fd'),
(49, 'reviews_perpage', 'Number of reviews per page', 'Number of reviews per page', '10'),
(70, 'choose_category', 'Choose category', 'Search product on frontend by category', '1'),
(71, 'price', 'Price', 'Show or hide Price', '1'),
(73, 'search_by_name', 'Search by name', 'Search product on frontend by name', '1'),
(74, 'list_picture_width', 'Picture Width', 'Picture Width', '50'),
(75, 'list_picture_height', 'Picture Height', 'Picture Height', '50'),
(76, 'count_of_products_in_the_page', 'Count of Products in the page', 'Count of Products in the page', '10'),
(77, 'category_picture_height', 'Category picture height', 'Category picture height', '200'),
(78, 'category_picture_width', 'Category picture width', 'Category picture width', '250'),
(79, 'text_size_list', 'List text size', 'List text size', '14'),
(80, 'price_size_list', 'List price size', 'List price size', '15'),
(81, 'cell_show_category', 'Show Category', 'Show \r\n\r\nCategory', '1'),
(82, 'list_show_category', 'Show Category', 'Show Category', '1'),
(83, 'cell_show_parameters', 'Show \r\n\r\nParameters', 'Show Parameters', '1'),
(84, 'list_show_parameters', 'Show Parameters', 'Show Parameters', '1'),
(85, 'category_title_size', 'Category title size', 'Category title size', '22'),
(87, 'rounded_corners', 'Rounded Corners', 'Rounded Corners', '1'),
(88, 'list_show_description', 'Show Description', 'Show Description', '0'),
(89, 'width_spider_main_table_lists', 'Product List  Width', 'Product List  Width', '620'),
(90, 'category_details_width', 'Category Details Width', 'Category Details Width', '600'),
(91, 'spider_catalog_product_page_width', 'Product Page Width', 'Product Page Width', '600'),
(92, 'description_size_list', 'Description Text Size', 'Description Text Size', '12'),
(93, 'name_price_size_list', 'Name Price List Text Size', 'Name Price List Text Size', '12'),
(94, 'Parameters_size_list', 'Parameters List Text Size', 'Parameters List Text Size', '12');

query1;

$table_name=$wpdb->prefix."spidercatalog_products";
$sql_2=<<<query2
INSERT INTO 

`$table_name` (`id`, `name`, `category_id`, `description`, `image_url`, `cost`, `market_cost`, `param`, 

`ordering`, `published`) VALUES
(1, 'Panasonic Television TX-PR50U30', 1, '<p>50&quot; / 127 cm, Full-HD Plasma Display Panel, 

600 Hz Sub Field Drive , DVB-T, DVB-C, RCA, RGB, VGA, HDMI x2, Scart, SD card</p>', 

'http://demo.web-dorado.com/components/com_spidercatalog/images/sampleimages/7_19977_1324390185.jpg;;;http://demo.web-dorado.com/components/com_spidercatalog/images/sampleimages/11448_2.jpg;;;http://demo.web-dorado.com/components/com_spidercatalog/images/sampleimages/panasonictx-pr50u30.jpg', '950.00', '1000.00', 'par_TV 

System@@:@@DVB-T	DVB-C		par_Diagonal@@:@@50&quot; / 127 cm		par_Interface@@:@@RCA, RGB, VGA, HDMI 

x2, Scart, SD card		par_Refresh Rate@@:@@600 Hz Sub Field Drive		', 2, 1),
(2, 'Sony KDL-46EX710AEP ', 

1, '<p><b>Sony Television KDL-46EX710AEP</b></p><p>46&quot; / 117 cm, MotionFlow 100Hz, Bravia Engine 3, Analog, DVB-T, DVB-

C, 4xHDMI, VGA, RGB, RCA, USB, 2xSCART </p>', 

'http://demo.web-dorado.com/components/com_spidercatalog/images/sampleimages/7_7557_1298400832.jpg;;;http://demo.web-dorado.com/components/com_spidercatalog/images/sampleimages/r1.jpg;;;http://demo.web-dorado.com/components/com_spidercatalog/images/sampleimages/sony-kdl32ex700aep-3.jpg', '1450.00', '1700.00', 'par_TV 

System@@:@@Analog	DVB-T	DVB-C		par_Diagonal@@:@@46&quot; / 117 cm		par_Interface@@:@@4xHDMI, 

VGA, RGB, RCA, USB, 2xSCART		par_Refresh Rate@@:@@MotionFlow 100Hz		', 1, 1),
(3, 'Samsung UE46D6100S', 1, 

'<p><strong>Samsung Television UE46D6100S </strong></p><p>46&quot; / 117 cm, 200Hz , 6 Series, 3D, SMART TV ,Smarthub, 3D 

HyperReal Engine, Samsung Apps, Social TV, WiFi Ready</p>', 

'http://demo.web-dorado.com/components/com_spidercatalog/images/sampleimages/7_20107_1324712747.jpg', '1630.00', '1900.00', 'par_TV System@@:@@DTV 

DVB-T/C		par_Diagonal@@:@@46&quot; / 117 cm		par_Interface@@:@@4xHDMI,3xUSB, RGB, RCA, D-SUB,1xSCART, 

Ethernet (LAN)		par_Refresh Rate@@:@@200Hz		', 3, 1),
(4, 'Sony KDL-32EX421BAEP ', 1, '<p><strong>Sony 

Television KDL-32EX421BAEP </strong></p><p>32&quot; / 80 cm, 50 Hz, Analog, DVB-T/T2/C, AV, SCART, RGB, VGA, HDMI x4, USB x2, 

Ethernet (RJ-45),24p True Cinema, X-Reality, DLNA, WiFi Ready, Internet Video, Internet Widgets, Web Browser, Skype, USB HDD 

Recording</p>', 'http://demo.web-dorado.com/components/com_spidercatalog/images/sampleimages/7_19644_1323935170.jpg', '950.00', '0.00', 'par_TV 

System@@:@@	par_Diagonal@@:@@32&quot; / 80 cm		par_Interface@@:@@AV, VGA, HDMI, USB, Ethernet 		

par_Refresh Rate@@:@@	', 4, 1);

query2;


$table_name=$wpdb->prefix."spidercatalog_product_categories";


$sql_3=<<<query3

INSERT INTO `$table_name` (`id`, `name`, `category_image_url`, 

`description`, `param`, `ordering`, `published`) VALUES
(1, 'Televisions', 

'http://demo.web-dorado.com/components/com_spidercatalog/images/sampleimages/category242.jpg', 'Television (TV) is a telecommunication medium for 

transmitting and receiving moving images that can be monochrome (black-and-white) or colored, with or without accompanying 

sound. &quot;Television&quot; may also refer specifically to a television set, television programming, or television 

transmission.The etymology of the word has a mixed Latin and Greek origin, meaning &quot;far sight&quot;: Greek tele, far, 

and Latin visio, sight (from video, vis- to see, or to view in the first person).', 'TV System	Diagonal	Interface	

Refresh Rate		', 1, 1);

query3;
$table_name=$wpdb->prefix."spidercatalog_product_reviews";

$sql_4=<<<query4

INSERT INTO `$table_name` (`id`, `name`, `content`, `product_id`, 

`remote_ip`) VALUES
(2, 'A Customer', 'A Good TV', 1, '127.0.0.1');

query4;

$table_name=$wpdb->prefix."spidercatalog_product_votes";
$sql_5=<<<query5
INSERT INTO `$table_name` (`id`, 

`remote_ip`, `vote_value`, `product_id`) VALUES
(6, '127.0.0.1', 4, 1),
(7, '127.0.0.1', 5, 2);

query5;





$wpdb->query($sql_spidercatalog_params);
$wpdb->query($sql_spidercatalog_products);
$wpdb->query($sql_spidercatalog_product_categories);
$wpdb->query($sql_spidercatalog_product_reviews);
$wpdb->query($sql_spidercatalog_product_votes);





$wpdb->query($sql_1);
$wpdb->query($sql_2);
$wpdb->query($sql_3);
$wpdb->query($sql_4);
$wpdb->query($sql_5);


}


register_activation_hook( __FILE__, 'Spider_Catalog_activate' );


