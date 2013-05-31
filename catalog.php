<?php 

/*
Plugin Name: Spider Catalog
Plugin URI: http://web-dorado.com/
Version: 1.4.8
Author: http://web-dorado.com/
License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/

add_action( 'init', 'catalog_lang_load' );

function catalog_lang_load() {
	 load_plugin_textdomain('sp_catalog', false, basename( dirname( __FILE__ ) ) . '/Languages' );
	
}
$ident=1;
function Spider_Catalog_Products_list_shotrcode($atts) {
     extract(shortcode_atts(array(
	      'id' => 'no Spider catalog',
		  'details' => '1',
		  'type' => '',
		  'showsub'=> '1',
		  'showsubprod'=> '1',
		  'showprod'=> '1',
     ), $atts));
	if(!(is_numeric($atts['id']) || $atts['id']=='ALL_CAT'))
	return 'insert numerical or `ALL_CAT` shortcode in `id`';
	
	if(!($atts['details']==1 || $atts['details']==0))
	return 'insert valid `detalis`';
	
	if(!($atts['type']=='list' || $atts['type']==''))
	return 'insert valid `type`';
	
	if(!($atts['showsub']==1 || $atts['showsub']==0))
	return 'insert valid `showsub`';
	
	if(!($atts['showsubprod']==0 || $atts['showsubprod']==1 || $atts['showsubprod']==2))
	return  'insert valid `showsubprod`';
	
	if(!($atts['showprod']==0 || $atts['showprod']==1))
	return  'insert valid `showprod`';
	
     return spider_cat_Products_list($atts['id'],$atts['details'],$atts['type'],$atts['showsub'],$atts['showsubprod'],$atts['showprod']);
	 
}




/////////////// Filter catalog



function catalog_after_search_results($query){
	global $wpdb;
	if(isset($_REQUEST['s']) && $_REQUEST['s']){
	$serch_word=htmlspecialchars(($_REQUEST['s']));
	
	$query=str_replace($wpdb->prefix."posts.post_content",gen_string_catalog_search($serch_word,$wpdb->prefix.'posts.post_content')." ".$wpdb->prefix."posts.post_content",$query);
	}	
    return $query;

}
add_filter( 'posts_request', 'catalog_after_search_results');


function gen_string_catalog_search($serch_word,$wordpress_query_post)
{
	$string_search='';

	global $wpdb;
	if($serch_word){
	$rows_category=$wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."spidercatalog_product_categories WHERE (description LIKE %s) OR (name LIKE %s)",'%'.$serch_word.'%',"%".$serch_word."%"));
	
	$count_cat_rows=count($rows_category);
	if($count_cat_rows){ 
		$string_search .=$wordpress_query_post.' LIKE \'%[Spider_Catalog_Category id="ALL_CAT" details="1" %\' OR ';
	}
	for($i=0;$i<$count_cat_rows;$i++){
		$string_search .=$wordpress_query_post.' LIKE \'%[Spider_Catalog_Category id="'.$rows_category[$i]->id.'" details="1" %\' OR '.$wordpress_query_post.' LIKE \'%[Spider_Catalog_Category id="'.$rows_category[$i]->id.'" details="1"%\' OR ';
	}
	
	
	
	
	$rows_category=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."spidercatalog_product_categories WHERE (name LIKE '%".$serch_word."%')");
	$count_cat_rows=count($rows_category);
	for($i=0;$i<$count_cat_rows;$i++){
		$string_search .=$wordpress_query_post.' LIKE \'%[Spider_Catalog_Category id="'.$rows_category[$i]->id.'" details="0"%\' OR '.$wordpress_query_post.' LIKE \'%[Spider_Catalog_Category id="'.$rows_category[$i]->id.'" details="0"%\' OR ';
	}
	
	$rows_single=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."spidercatalog_products WHERE name LIKE '%".$serch_word."%'");
	
	$count_sing_rows=count($rows_single);
	if($count_sing_rows){		
		for($i=0;$i<$count_sing_rows;$i++)
		{
			$string_search .=$wordpress_query_post.' LIKE \'%[Spider_Catalog_Product id="'.$rows_single[$i]->id.'"]%\' OR ';
		}
	
	}
	}
	return 	$string_search;
}


///////////////////// end filter









add_shortcode('Spider_Catalog_Category', 'Spider_Catalog_Products_list_shotrcode');



function Spider_Catalog_Single_product_shotrcode($atts) {
     extract(shortcode_atts(array(
	      'id' => '',
     ), $atts));
	 if(!(is_numeric($atts['id'])))
	return 'insert numerical  shortcode in `id`';
	
     return spider_cat_Single_product($id);
}
add_shortcode('Spider_Catalog_Product', 'Spider_Catalog_Single_product_shotrcode');








//// singl product
 function   spider_cat_Products_list($id,$details,$type,$showsub,$showsubprod,$showprod){
	require_once("front_end_functions.html.php");
	require_once("front_end_functions.php");
	if(isset($_GET['product_id'])){
		if(isset($_GET['view']))
		{
			if($_GET['view']=='spidercatalog')
			{
			return	showPublishedProducts_1($id,$details,$type,$showsub,$showsubprod,$showprod);
			}
			else
			{
			return		front_end_single_product($_GET['product_id']);
			}
		}
			else{
			return		front_end_single_product($_GET['product_id']);
			}
	}
	else
	{
	 return	showPublishedProducts_1($id,$details,$type,$showsub,$showsubprod,$showprod);
	}	
	 }

	 
	 
	 // prodact list
	 function spider_cat_Single_product($id)
	 {
		 
		 
		 	require_once("front_end_functions.html.php");
			require_once("front_end_functions.php");
			return	front_end_single_product($id);
	 
		 
		 
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
.wp_themeSkin span.mce_Spider_Catalog_mce {background:url(\''.plugins_url( 'images/Spider_CatalogLogo.png' , __FILE__ ).'\') no-repeat !important;}
.wp_themeSkin .mceButtonEnabled:hover span.mce_Spider_Catalog_mce,.wp_themeSkin .mceButtonActive span.mce_Spider_Catalog_mce
{background:url(\''.plugins_url( 'images/Spider_CatalogLogoHover.png' , __FILE__ ).'\') no-repeat !important;}
</style>';
}

add_action('admin_head', 'add_button_style_Spider_Catalog');












function spiderbox_scripts_method() {
    wp_enqueue_script( 'spiderbox',admin_url('admin-ajax.php?action=spiderboxjsphp').'&delay=3000&allImagesQ=0&slideShowQ=0&darkBG=1&juriroot='.urlencode(plugins_url("",__FILE__)).'&spiderShop=1' );
	wp_enqueue_script( 'my_common',plugins_url("js/common.js",__FILE__));
	wp_enqueue_style('spider_cat_main',plugins_url("spidercatalog_main.css",__FILE__));
}     
 
add_action('wp_head', 'spiderbox_scripts_method',1);











add_filter('admin_head','spider_cat_ShowTinyMCE');
function spider_cat_ShowTinyMCE() {
	// conditions here
	wp_enqueue_script( 'common' );
	wp_enqueue_script( 'jquery-color' );
	wp_print_scripts('editor');
	if (function_exists('add_thickbox')) add_thickbox();
	wp_print_scripts('media-upload');
	if(version_compare(get_bloginfo('version'),3.3)<0){
	if (function_exists('wp_tiny_mce')) wp_tiny_mce();
	}
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
		            add_submenu_page( 'Categories_Spider_Catalog', 'Licensing', 'Licensing', 'manage_options', 'Spider_catalog_Licensing', 'Spider_catalog_Licensing');
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
		wp_enqueue_script( 'param_block4',plugins_url("js/joomla.javascript.js",__FILE__));
		wp_enqueue_style( 'param_block5',plugins_url("elements/moorainbow/mooRainbow.css",__FILE__) );
		
		
		
}






//////////////////////////////////////////
//           LICENS
/////////////////////////////////////
function Spider_catalog_Licensing(){
	
	?>
    <div style="width:95%">
    <p>
	This plugin is the non-commercial version of the Spider Catalog. If you want to customize to the styles and colors of your website,than you need to buy a license.
Purchasing a license will add possibility to customize the styles and colors, global options of the Spider Catalog. 

 </p>
<br /><br />
<a href="http://web-dorado.com/files/fromSpiderCatalog.php" class="button-primary" target="_blank">Purchase a License</a>
<br /><br /><br />
<p>After the purchasing the commercial version follow this steps:</p>
<ol>
	<li>Deactivate Spider Catalog Plugin</li>
	<li>Delete Spider Catalog Plugin</li>
	<li>Install the downloaded commercial version of the plugin</li>
</ol>
</div>
<?php
    
    
	
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
	
	
	
	
	
	
	
	
if(isset($_GET["task"]))	
$task=$_GET["task"];//get task for choosing function
else
$task='';
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
	if($id){
	update_prad_cat($id);
	}
	else
	{
		save_prad_cat();
		$id=$wpdb->get_var("SELECT MAX(id) FROM ".$wpdb->prefix."spidercatalog_products");
	}
	 editProduct($id);
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
if(isset($_GET['mode']))
$mode = trim($_GET['mode']);
else
$mode ='';
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
		<input type="submit" name="do" value="<?php echo 'UNINSTALL Spider_Catalog'; ?>" class="button-primary" onClick="return confirm('<?php echo 'You Are About To Uninstall Spider Catalog From WordPress.\nThis Action Is Not Reversible.\n\n Choose [Cancel] To Stop, [OK] To Uninstall.'; ?>')" />
	</p>
</div>
</form>
<?php
} // End switch($mode)


	
	
	
	
}

////////////////////////////////////////////////// ajax function

 add_action('wp_ajax_spidercatalogwindow', 'spider_catalog_window');
 function spider_catalog_window(){
 
 global $wpdb;
 
 function open_cat_in_tree($catt,$tree_problem='',$hihiih=1){

global $wpdb;
$search_tag='';
static $trr_cat=array();
if($hihiih)
$trr_cat=array();
foreach($catt as $dog){
	$dog->name=$tree_problem.$dog->name;
	array_push($trr_cat,$dog);
	$new_cat_query=	"SELECT  a.* ,  COUNT(b.id) AS count, g.par_name AS par_name FROM ".$wpdb->prefix."spidercatalog_product_categories  AS a LEFT JOIN ".$wpdb->prefix."spidercatalog_product_categories AS b ON a.id = b.parent LEFT JOIN (SELECT  ".$wpdb->prefix."spidercatalog_product_categories.ordering as ordering,".$wpdb->prefix."spidercatalog_product_categories.id AS id, COUNT( ".$wpdb->prefix."spidercatalog_products.category_id ) AS prod_count
FROM ".$wpdb->prefix."spidercatalog_products, ".$wpdb->prefix."spidercatalog_product_categories
WHERE ".$wpdb->prefix."spidercatalog_products.category_id = ".$wpdb->prefix."spidercatalog_product_categories.id
GROUP BY ".$wpdb->prefix."spidercatalog_products.category_id) AS c ON c.id = a.id LEFT JOIN
(SELECT ".$wpdb->prefix."spidercatalog_product_categories.name AS par_name,".$wpdb->prefix."spidercatalog_product_categories.id FROM ".$wpdb->prefix."spidercatalog_product_categories) AS g
 ON a.parent=g.id WHERE a.name LIKE '%".$wpdb->escape($search_tag)."%' AND a.parent=".$dog->id." group by a.id"; 
 $new_cat=$wpdb->get_results($new_cat_query);
 open_cat_in_tree($new_cat,$tree_problem. "— ",0);
}
return $trr_cat;

}

$single_products=$wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'spidercatalog_products WHERE published=\'1\'');
$categories=$wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'spidercatalog_product_categories WHERE parent=0 AND published=\'1\'');
$categories=open_cat_in_tree($categories);

?>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
	<title>Spider Catalog</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<script language="javascript" type="text/javascript" src="<?php echo get_option("siteurl"); ?>/wp-includes/js/jquery/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option("siteurl"); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script><link rel="stylesheet" href="<?php echo get_option("siteurl"); ?>/wp-includes/js/tinymce/themes/advanced/skins/wp_theme/dialog.css?ver=342-20110630100">
	<script language="javascript" type="text/javascript" src="<?php echo get_option("siteurl"); ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option("siteurl"); ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
	<base target="_self">
	<style>
	#link .panel_wrapper,
	#link div.current{
	height:160px !important;
	}
	
	</style>
</head>
<body id="link" onLoad="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';"  style="" dir="ltr" class="forceColors">
<form name="spider_cat" action="#" method="post">
	<div class="tabs" role="tablist" tabindex="-1">
		<ul>
			<li id="Single_product_tab" class="current" role="tab" tabindex="0"><span><a href="javascript:mcTabs.displayTab('Single_product_tab','Single_product_panel');" onMouseDown="return false;" tabindex="-1">Single product</a></span></li>
			<li id="Products_list_tab" role="tab" tabindex="-1"><span><a href="javascript:mcTabs.displayTab('Products_list_tab','Products_list_panel');" onMouseDown="return false;" tabindex="-1">Products list</a></span></li>
			
		</ul>
	</div>
	
	<div class="panel_wrapper">
		<div id="Single_product_panel" class="panel current">
		<br>
		<table border="0" cellpadding="4" cellspacing="0">
         <tbody><tr>
            <td nowrap="nowrap"><label for="gallerytag">Select product</label></td>
            <td><select name="spider_catalog_product" id="spider_catalog_product" >
<option value="- Select a Product -" selected="selected">- Select a Product -</option>
<?php 
	   foreach($single_products as $single_product)
	   {
		   ?>
           <option value="<?php echo $single_product->id; ?>"><?php echo $single_product->name; ?></option>
           <?php }?>
</select>
            </td>
          </tr>
        </tbody></table>
		</div>
		<div id="Products_list_panel" class="panel">
		<br>
		<table border="0" cellpadding="4" cellspacing="0">
         <tbody><tr>
            <td nowrap="nowrap"><label for="Spider_cat_Category">Select Category</label></td>
            <td><select name="Spider_cat_Category" id="Spider_cat_Category" >
<option value="ALL_CAT" selected="selected">- All categories -</option>
<?php 
	   foreach($categories as $categorie)
	   {
		   ?>
           <option value="<?php echo $categorie->id; ?>"><?php echo $categorie->name; ?></option>
           <?php }?>
</select>
            </td>
          </tr>
          <tr>
            <td nowrap="nowrap" valign="top"><label for="showtype">Show category details</label></td>
            <td>	<input type="radio" name="cat_show" id="paramsshow_category_details1" value="1" checked="checked">
	<label for="paramsshow_category_details1">Show</label>
	<input type="radio" name="cat_show" id="paramsshow_category_details0" value="0">
	<label for="paramsshow_category_details0">Hide</label></td>
          </tr>
                    <tr>
            <td nowrap="nowrap" valign="top"><label for="showtype">Product display type</label></td>
            <td>	<input type="radio" name="cat_list" id="paramslist_category_type1" value="list" checked="checked">
	<label for="paramslist_category_type1">Cells</label>
	<input type="radio" name="cat_list" id="paramslist_category_type0" value="">
	<label for="paramslist_category_type0">List</label></td>
          </tr>
		    <tr>
            <td nowrap="nowrap" valign="top"><label for="showtype">Show Subcategories</label></td>
            <td>	<input type="radio"  name="show_sub" id="show_subategory1" value="1" checked="checked">
	<label for="show_subategory1">Yes</label>
	<input type="radio" name="show_sub" style="margin-left: 12px;" id="show_subcategory0" value="0">
	<label for="show_subcategory0">No</label></td>
          </tr>
		    <tr>
            <td nowrap="nowrap" valign="top"><label for="showtype">Show Subcategory Products</label></td>
            <td>	<input type="radio" name="show_sub_prod" id="show_sub_products1" value="1" >
	<label for="show_sub_products1">All</label>
	<input type="radio" name="show_sub_prod" style="margin-left: 19px;" id="show_sub_products0" value="0">
	<label for="show_sub_products0">No</label>
	<input type="radio" name="show_sub_prod" id="show_sub_products2" style="margin-left: 12px;" value="2" checked="checked">
	<label for="show_sub_products2">Chosen</label></td>
          </tr>
		    <tr>
            <td nowrap="nowrap" valign="top"><label for="showtype">Show Products</label></td>
            <td>	<input type="radio" name="show_prod" id="show_products1" value="1" checked="checked">
	<label for="show_products1">Yes</label>
	<input type="radio" name="show_prod" style="margin-left: 12px;" id="show_products0" value="0">
	<label for="show_products0">No</label></td>
          </tr>
        </tbody></table>
		</div>
        </div>
        <div class="mceActionPanel">
		<div style="float: left">
			<input type="button" id="cancel" name="cancel" value="Cancel" onClick="tinyMCEPopup.close();" />
		</div>

		<div style="float: right">
			<input type="submit" id="insert" name="insert" value="Insert" onClick="insert_spider_catalog();" />
			<input type="hidden"  name="iden" value="1"  />
		</div>
	</div>

</form>

<script type="text/javascript">
function insert_spider_catalog() {
	
	if(document.getElementById('Single_product_panel').className==='panel')
	{
	

	
				
					
					var lists;
					var show;
					var showsub;
					var showsubprod;
					var showprod;
					
					lists="";
					show=0;
					showsub=0;
					showsubprod=0;
					showprod=0;
					if(!document.getElementById('paramslist_category_type1').checked)
					{
					lists='list';
					}
					if(document.getElementById('paramsshow_category_details1').checked)
					{
					show=1;
					}
					if(document.getElementById('show_subategory1').checked)
					{
					showsub=1;
					}
					if(document.getElementById('show_sub_products1').checked)
					{
					showsubprod=1;
					}
					if(document.getElementById('show_sub_products2').checked)
					{
					showsubprod=2;
					}
					if(document.getElementById('show_products1').checked)
					{
					showprod=1;
					}

				   var tagtext;
				   tagtext='[Spider_Catalog_Category id="'+document.getElementById('Spider_cat_Category').value+'" details="'+show+'" type="'+lists+'" showsub="'+showsub+'" showsubprod="'+showsubprod+'" showprod="'+showprod+'"]';
				   window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, tagtext);
				   tinyMCEPopup.editor.execCommand('mceRepaint');
				   tinyMCEPopup.close();		
				
	
	
	}
	else
	{
		
		
			if(document.getElementById('spider_catalog_product').value=='- Select a Product -')
			{
				tinyMCEPopup.close();
			}
			else
			{
			   var tagtext;
			   tagtext='[Spider_Catalog_Product id="'+document.getElementById('spider_catalog_product').value+'"]';
			   window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, tagtext);
			   tinyMCEPopup.editor.execCommand('mceRepaint');
			   tinyMCEPopup.close();		
			}
		
		
		
		
	}
}

</script>
</body></html>
     
     
     <?php	
	 die(); 
 }




add_action('wp_ajax_spiderboxjsphp', 'spider_box_js_php');
 add_action('wp_ajax_nopriv_spiderboxjsphp', 'spider_box_js_php');

function spider_box_js_php(){
  header('Content-Type: text/javascript; charset=UTF-8');
?>
var keyOfOpenImage;
var listOfImages=Array();
var slideShowOn;
var globTimeout;
var slideShowDelay;
var viewportheight;
var viewportwidth;
var newImg = new Image();
var LoadingImg = new Image();
var spiderShop;

function SetOpacity(elem, opacityAsInt)
 {
     var opacityAsDecimal = opacityAsInt;
     
     if (opacityAsInt > 100)
         opacityAsInt = opacityAsDecimal = 100; 
     else if (opacityAsInt < 0)
         opacityAsInt = opacityAsDecimal = 0; 
     
     opacityAsDecimal /= 100;
     if (opacityAsInt < 1)
         opacityAsInt = 1; // IE7 bug, text smoothing cuts out if 0
     
     elem.style.opacity = (opacityAsDecimal);
     elem.style.filter  = "alpha(opacity=" + opacityAsInt + ")";
 }
 
 function FadeOpacity(elemId, fromOpacity, toOpacity, time, fps)
  {
      var steps = Math.ceil(fps * (time / 1000));
      var delta = (toOpacity - fromOpacity) / steps;
      
      FadeOpacityStep(elemId, 0, steps, fromOpacity, 
                      delta, (time / steps));
  }
  
 function FadeOpacityStep(elemId, stepNum, steps, fromOpacity, 
                          delta, timePerStep)
 {
     SetOpacity(document.getElementById(elemId), 
                Math.round(parseInt(fromOpacity) + (delta * stepNum)));
 
     if (stepNum < steps)
         setTimeout("FadeOpacityStep('" + elemId + "', " + (stepNum+1) 
                  + ", " + steps + ", " + fromOpacity + ", "
                  + delta + ", " + timePerStep + ");", 
                    timePerStep);
 }

function getWinHeight() {
	winH=0;
 if (navigator.appName=="Netscape") {
  winH = window.innerHeight;
 }
 if (navigator.appName.indexOf("Microsoft")!=-1) {
  winH = document.body.offsetHeight;
 }
 return winH;
}

function getImageKey(href)
{
	var key=-1;
for(i=0; i<listOfImages.length; i++)	{
		if(encodeURI(href)==encodeURI(listOfImages[i]) || href==encodeURI(listOfImages[i]) || encodeURI(href)==listOfImages[i])
			{
				key=i;				break;
			}	}	return key;	
}

function hidePictureAnimated()
{
FadeOpacity("showPictureAnimated", 100, 0, 500, 10);
FadeOpacity("showPictureAnimatedBg", 70, 0, 500, 10);
setTimeout('document.getElementById("showPictureAnimated").style.display="none";',700);
setTimeout('document.getElementById("showPictureAnimatedBg").style.display="none";',700);
setTimeout('document.getElementById("showPictureAnimatedTable").style.display="none";',700);
	keyOfOpenImage=-1;
clearTimeout(globTimeout);
slideShowOn=0;
}

function showPictureAnimated(href)
{
newImg = new Image();

newImg.onload=function() {if(encodeURI(href)==encodeURI(newImg.src) || href==encodeURI(newImg.src) || encodeURI(href)==newImg.src) showPictureAnimatedInner(href, newImg.height, newImg.width);}

if(document.getElementById("showPictureAnimated").clientHeight>0)
{
document.getElementById("showPictureAnimated").style.height=""+document.getElementById("showPictureAnimated").clientHeight+"px";
LoadingImgMargin=(document.getElementById("showPictureAnimated").clientHeight-LoadingImg.height)/2;
}
else
{
document.getElementById("showPictureAnimated").style.height="400px";
LoadingImgMargin=(400-LoadingImg.height)/2;
}


document.getElementById("showPictureAnimated").innerHTML='<img style="margin-top:'+LoadingImgMargin+'px" src="'+spiderBoxBase+'loadingAnimation.gif" />';

if(document.getElementById("showPictureAnimatedBg").style.display=="none")
FadeOpacity("showPictureAnimatedBg", 0, 70, 500, 10);
document.getElementById("showPictureAnimatedTable").style.display="table";
if(darkBG) document.getElementById("showPictureAnimatedBg").style.display="block";
SetOpacity(document.getElementById("showPictureAnimated"), 100);
document.getElementById("showPictureAnimated").style.display="block";
newImg.src = href;
}

function showPictureAnimatedInner(href, newImgheight, newImgwidth)
{
document.getElementById("showPictureAnimated").style.display="none";

if(keyOfOpenImage<0) keyOfOpenImage = getImageKey(href);
boxContainerWidth=0;
if(newImgheight<=(viewportheight-100) && newImgwidth<=(viewportwidth-50))
{
document.getElementById("showPictureAnimated").innerHTML='<img src="'+href+'" border="0" style="cursor:url(\''+spiderBoxBase+'cursor_magnifier_minus.cur\'),pointer;border:" onClick="hidePictureAnimated();" />';

boxContainerWidth=newImgwidth;
}
else
{
if((newImgheight/viewportheight)>(newImgwidth/viewportwidth))
{
document.getElementById("showPictureAnimated").innerHTML='<img src="'+href+'" border="0" style="cursor:url(\''+spiderBoxBase+'cursor_magnifier_minus.cur\'),pointer;" onClick="hidePictureAnimated();" height="'+(viewportheight-100)+'" />';

boxContainerWidth=((newImgwidth*(viewportheight-100))/newImgheight);
}
else
{
document.getElementById("showPictureAnimated").innerHTML='<img src="'+href+'" border="0" style="cursor:url(\''+spiderBoxBase+'cursor_magnifier_minus.cur\'),pointer;" onClick="hidePictureAnimated();" width="'+(viewportwidth-50)+'" />';
boxContainerWidth=(viewportwidth-40);
}
}
document.getElementById("boxContainer").style.width=(boxContainerWidth>300)?(""+boxContainerWidth+"px"):"300px";

document.getElementById("showPictureAnimated").style.height="";

FadeOpacity("showPictureAnimated", 0, 100, 500, 10);
document.getElementById("showPictureAnimated").style.display="block";

if(slideShowOn) 
	{
		clearTimeout(globTimeout);
		globTimeout=setTimeout('nextImage()',slideShowDelay);
	}
}

function nextImage()
{
	if(keyOfOpenImage<listOfImages.length-1)
		keyOfOpenImage=keyOfOpenImage+1;
	else
		keyOfOpenImage=0;
		
		showPictureAnimated(listOfImages[keyOfOpenImage]);
}

function prevImage()
{
	if(keyOfOpenImage>0)
		keyOfOpenImage=keyOfOpenImage-1;
	else
		keyOfOpenImage=listOfImages.length-1;		

		showPictureAnimated(listOfImages[keyOfOpenImage]);
}

function toggleSlideShow()
{
	clearTimeout(globTimeout);
    if(!(slideShowOn))
    	{
			document.getElementById("toggleSlideShowCheckbox").src=spiderBoxBase+"pause.png";
			slideShowOn=1;
    		nextImage();
        }
    else
		{
			document.getElementById("toggleSlideShowCheckbox").src=spiderBoxBase+"play.png";
			slideShowOn=0;
		}
}

window.onresize=getViewportSize;

function getViewportSize()
{
 if (typeof window.innerWidth != 'undefined')
 {
      viewportwidth = window.innerWidth,
      viewportheight = window.innerHeight
 }
 
 else if (typeof document.documentElement != 'undefined'
     && typeof document.documentElement.clientWidth !=
     'undefined' && document.documentElement.clientWidth != 0)
 {
       viewportwidth = document.documentElement.clientWidth,
       viewportheight = document.documentElement.clientHeight
 }
 
 else
 {
       viewportwidth = document.getElementsByTagName('body')[0].clientWidth,
       viewportheight = document.getElementsByTagName('body')[0].clientHeight
 }
 
 if(document.getElementById("tdviewportheight")!=undefined)
 document.getElementById("tdviewportheight").style.height=(viewportheight-35)+"px"; 
 
if(document.getElementById("showPictureAnimatedBg")!=undefined)
document.getElementById("showPictureAnimatedBg").style.height=(viewportheight+300)+"px";
}



function SpiderCatAddToOnload()
{
    if((listOfImages.length) == 0){
	

    getViewportSize();

	slideShowDelay=<?php echo esc_js($_GET['delay']); ?>;
	slideShowQ=<?php echo esc_js($_GET['slideShowQ']); ?>;	
	allImagesQ=<?php echo esc_js($_GET['allImagesQ']); ?>;
	spiderShop=<?php echo isset($_GET['spiderShop'])?esc_js($_GET['spiderShop']):0; ?>;
	darkBG=<?php echo esc_js($_GET['darkBG']); ?>;
	keyOfOpenImage=-1;
	spiderBoxBase="<?php echo urldecode($_GET['juriroot']); ?>/spiderBox/";
	LoadingImg.src=spiderBoxBase+"loadingAnimation.gif";

	
		var spiderBoxElement = document.createElement('span');
	spiderBoxElement.innerHTML+='<div style="position:fixed; top:0px; left:0px; width:100%; height:'+(viewportheight+300)+'px; background-color:#000000; z-index:98; display:none" id="showPictureAnimatedBg"></div>  <table border="0" cellpadding="0" cellspacing="0" id="showPictureAnimatedTable" style="position:fixed; top:0px; left:0px; width:100%; vertical-align:middle; text-align:center; z-index:10000; display:none"><tr><td valign="middle" id="tdviewportheight" style="height:'+(viewportheight-35)+'px; text-align:center;"><div id="boxContainer" style="margin:auto;width:400px; border:5px solid white;"><div id="showPictureAnimated" style=" height:400px">&nbsp;</div><div style="text-align:center;background-color:white;padding:5px;padding-bottom:0px;"><div style="width:48px;float:left;">&nbsp;</div><span onClick="prevImage();" style="cursor:pointer;"><img src="'+spiderBoxBase+'prev.png" /></span>&nbsp;&nbsp;<span onClick="nextImage();" style="cursor:pointer;"><img src="'+spiderBoxBase+'next.png" /></span><span onClick="hidePictureAnimated();" style="cursor:pointer;"><img src="'+spiderBoxBase+'close.png" align="right" /></span></div></div></td></tr></table>';

	document.body.appendChild(spiderBoxElement);

	
			for ( i = 0; i < document.getElementsByTagName( 'a' ).length; i++ )
				if(document.getElementsByTagName( 'a' )[i].target=="spiderbox" || ((allImagesQ || spiderShop) && (document.getElementsByTagName( 'a' )[i].href.substr(document.getElementsByTagName( 'a' )[i].href.length-4)==".jpg" || document.getElementsByTagName( 'a' )[i].href.substr(document.getElementsByTagName( 'a' )[i].href.length-4)==".png" || document.getElementsByTagName( 'a' )[i].href.substr(document.getElementsByTagName( 'a' )[i].href.length-4)==".gif" || document.getElementsByTagName( 'a' )[i].href.substr(document.getElementsByTagName( 'a' )[i].href.length-4)==".bmp" || document.getElementsByTagName( 'a' )[i].href.substr(document.getElementsByTagName( 'a' )[i].href.length-4)==".JPG" || document.getElementsByTagName( 'a' )[i].href.substr(document.getElementsByTagName( 'a' )[i].href.length-4)==".PNG" || document.getElementsByTagName( 'a' )[i].href.substr(document.getElementsByTagName( 'a' )[i].href.length-4)==".GIF" || document.getElementsByTagName( 'a' )[i].href.substr(document.getElementsByTagName( 'a' )[i].href.length-4)==".BMP"))) 
					{
						if(typeof(listOfImages.indexOf)=="function")
						{
							if(listOfImages.indexOf(document.getElementsByTagName( 'a' )[i].href)===-1)
								listOfImages[listOfImages.length]=document.getElementsByTagName( 'a' )[i].href;
						}
						else
							listOfImages[listOfImages.length]=document.getElementsByTagName( 'a' )[i].href;

						document.getElementsByTagName( 'a' )[i].href="javascript:showPictureAnimated('"+document.getElementsByTagName( 'a' )[i].href+"')";						document.getElementsByTagName( 'a' )[i].style.cursor="url('<?php echo urldecode($_GET['juriroot']); ?>/spiderBox/cursor_magnifier_plus.cur'),pointer";						
						document.getElementsByTagName( 'a' )[i].target="";
						
					}
   }
}
	<?php
	die();
	
	}
 
 
 
 add_action('wp_ajax_catalogstarerate', 'spider_catalog_stare_rate');
 add_action('wp_ajax_nopriv_catalogstarerate', 'spider_catalog_stare_rate');
function spider_catalog_stare_rate(){
spider_starrating();
die();
	}









function spider_starrating()
		{
			global $wpdb;
			if(isset($_POST['product_id']))
			$product_id = $_POST['product_id'];
			else
			$product_id=0;
			
			if(isset($_POST['vote_value']))
			$vote_value = $_POST['vote_value'];
			else
			$vote_value=0;
			
			
			
			$save_or_no=$wpdb->insert($wpdb->prefix.'spidercatalog_product_votes', 
				array( 
					'product_id' => $product_id, 
					'vote_value' => $vote_value, 
					'remote_ip' => $_SERVER['REMOTE_ADDR']
				), 
				array( 
					'%d', 
					'%d',
					'%s' 
				) 
			);


			if (!$save_or_no)
				{
					echo "<script> alert('".$row->getError()."');
					window.history.go(-1); </script>\n";
					exit();
				}

	$query= $wpdb->prepare( "SELECT AVG(vote_value) as rating FROM ".$wpdb->prefix."spidercatalog_product_votes  WHERE product_id =%d ",$product_id);
	$row1 = $wpdb->get_var($query);

	$rating=substr($row1,0,3);

			spider_print_starin_catalog($product_id,$rating);

		}
		
		
		
		function 	spider_print_starin_catalog($product_id,$rating){
			
			
			
			
$title=__('Rating','sp_catalog').' '.$rating.'&nbsp;&nbsp;&nbsp;&nbsp;&#013;'.__('You have already rated this product.','sp_catalog');

echo "<ul class='star-rating1'>	
<li class='current-rating' id='current-rating' style=\"width: ".($rating*25)."px\"></li>
	<li><a  title='".$title."'  class='one-star'>1</a></li>
	<li><a   title='".$title."'  class='two-stars'>2</a></li>
	<li><a  title='".$title."'  class='three-stars'>3</a></li>
	<li><a  title='".$title."'  class='four-stars'>4</a></li>
	<li><a  title='".$title."'  class='five-stars'>5</a></li>
	</ul>
	</td></tr>";
}
 
 
add_action('wp_ajax_spidercatalogwdcaptchae', 'spider_catalog_wd_captcha');
 add_action('wp_ajax_nopriv_spidercatalogwdcaptchae', 'spider_catalog_wd_captcha');
function spider_catalog_wd_captcha(){
	
$cap_width=80;
$cap_height=30;
$cap_quality=100;
$cap_length_min=6;
$cap_length_max=6;
$cap_digital=1;
$cap_latin_char=1;
function code_generic($_length,$_digital=1,$_latin_char=1)
{
$dig=array(0,1,2,3,4,5,6,7,8,9);
$lat=array('a','b','c','d','e','f','g','h','j','k','l','m','n','o',
'p','q','r','s','t','u','v','w','x','y','z');
$main=array();
if ($_digital) $main=array_merge($main,$dig);
if ($_latin_char) $main=array_merge($main,$lat);
shuffle($main);
$pass=substr(implode('',$main),0,$_length);
return $pass;
}
if(isset($_GET['checkcap'])=='1'){
if($_GET['checkcap']=='1')
{
@session_start();
if(isset($_GET['cap_code'])){ 
if($_GET['cap_code']==$_SESSION['captcha_code'])
echo 1;
}
else echo 0;
}}
else
{
$l=rand($cap_length_min,$cap_length_max);
$code=code_generic($l,$cap_digital,$cap_latin_char);
@session_start();
$_SESSION['captcha_code']= $code;
$canvas=imagecreatetruecolor($cap_width,$cap_height);
$c=imagecolorallocate($canvas,rand(150,255),rand(150,255),rand(150,255));
imagefilledrectangle($canvas,0,0,$cap_width,$cap_height,$c);
$count=strlen($code);
$color_text=imagecolorallocate($canvas,0,0,0);
for($it=0;$it<$count;$it++)
{ $letter=$code[$it];
  imagestring($canvas,6,(10*$it+10),$cap_height/4,$letter,$color_text);
}
for ($c = 0; $c < 150; $c++){
	$x = rand(0,79);
	$y = rand(0,29);
	$col='0x'.rand(0,9).'0'.rand(0,9).'0'.rand(0,9).'0';
	imagesetpixel($canvas, $x, $y, $col);
	}
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
header('Cache-Control: no-store, no-cache, must-revalidate'); 
header('Cache-Control: post-check=0, pre-check=0',false);
header('Pragma: no-cache');
header('Content-Type: image/jpeg');
imagejpeg($canvas,null,$cap_quality);

}	
die('');
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
  `image_url` text,
  `cost` varchar(128) DEFAULT NULL,
  `market_cost` varchar(128) DEFAULT NULL,
  `param` text,
  `ordering` int(11) NOT NULL,
  `published` tinyint(4) unsigned DEFAULT NULL,
  `published_in_parent` tinyint(4) unsigned DEFAULT NULL,

  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)

) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5";

$sql_spidercatalog_product_categories="
CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."spidercatalog_product_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `parent` int(11) unsigned DEFAULT NULL,
  `category_image_url` text,
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
INSERT INTO `$table_name` (`id`, `name`, `title`,`description`, `value`) VALUES
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
$sql_2="
INSERT INTO 

`".$table_name."` (`id`, `name`, `category_id`, `description`, `image_url`, `cost`, `market_cost`, `param`, 

`ordering`, `published`, `published_in_parent`) VALUES
(1, 'Panasonic Television TX-PR50U30', 1, '<p>50&quot; / 127 cm, Full-HD Plasma Display Panel, 

600 Hz Sub Field Drive , DVB-T, DVB-C, RCA, RGB, VGA, HDMI x2, Scart, SD card</p>', 

'".plugins_url("Front_images/sampleimages/7_19977_1324390185.jpg",__FILE__)."******0;;;".plugins_url("Front_images/sampleimages/11448_2.jpg",__FILE__)."******0;;;".plugins_url("Front_images/sampleimages/panasonictx_pr50u30.jpg",__FILE__)."', '950.00', '1000.00', 'par_TV 

System@@:@@DVB-T	DVB-C		par_Diagonal@@:@@50&quot; / 127 cm		par_Interface@@:@@RCA, RGB, VGA, HDMI 

x2, Scart, SD card		par_Refresh Rate@@:@@600 Hz Sub Field Drive		', 2, 1, 0),
(2, 'Sony KDL-46EX710AEP ', 

1, '<p><b>Sony Television KDL-46EX710AEP</b></p><p>46&quot; / 117 cm, MotionFlow 100Hz, Bravia Engine 3, Analog, DVB-T, DVB-

C, 4xHDMI, VGA, RGB, RCA, USB, 2xSCART </p>', 

'".plugins_url("Front_images/sampleimages/7_7557_1298400832.jpg",__FILE__)."******0;;;".plugins_url("Front_images/sampleimages/r1.jpg",__FILE__)."******0;;;".plugins_url("Front_images/sampleimages/sony_kdl32ex700aep_3.jpg",__FILE__)."', '1450.00', '1700.00', 'par_TV 

System@@:@@Analog	DVB-T	DVB-C		par_Diagonal@@:@@46&quot; / 117 cm		par_Interface@@:@@4xHDMI, 

VGA, RGB, RCA, USB, 2xSCART		par_Refresh Rate@@:@@MotionFlow 100Hz		', 1, 1, 0),
(3, 'Samsung UE46D6100S', 1, 

'<p><strong>Samsung Television UE46D6100S </strong></p><p>46&quot; / 117 cm, 200Hz , 6 Series, 3D, SMART TV ,Smarthub, 3D 

HyperReal Engine, Samsung Apps, Social TV, WiFi Ready</p>', 

'".plugins_url("Front_images/sampleimages/7_19644_1323935170.jpg",__FILE__)."', '1630.00', '1900.00', 'par_TV System@@:@@DTV 

DVB-T/C		par_Diagonal@@:@@46&quot; / 117 cm		par_Interface@@:@@4xHDMI,3xUSB, RGB, RCA, D-SUB,1xSCART, 

Ethernet (LAN)		par_Refresh Rate@@:@@200Hz		', 3, 1, 0),
(4, 'Sony KDL-32EX421BAEP ', 1, '<p><strong>Sony 

Television KDL-32EX421BAEP </strong></p><p>32&quot; / 80 cm, 50 Hz, Analog, DVB-T/T2/C, AV, SCART, RGB, VGA, HDMI x4, USB x2, 

Ethernet (RJ-45),24p True Cinema, X-Reality, DLNA, WiFi Ready, Internet Video, Internet Widgets, Web Browser, Skype, USB HDD 

Recording</p>', '".plugins_url("Front_images/sampleimages/7_20107_1324712747.jpg",__FILE__)."', '950.00', '0.00', 'par_TV 

System@@:@@	par_Diagonal@@:@@32&quot; / 80 cm		par_Interface@@:@@AV, VGA, HDMI, USB, Ethernet 		

par_Refresh Rate@@:@@	', 4, 1, 0)";




$table_name=$wpdb->prefix."spidercatalog_product_categories";


$sql_3="

INSERT INTO `$table_name` (`id`, `name`, `parent`, `category_image_url`, 

`description`, `param`, `ordering`, `published`) VALUES
(1, 'Televisions', 0,

'".plugins_url("Front_images/sampleimages/category242.jpg",__FILE__)."', 'Television (TV) is a telecommunication medium for 

transmitting and receiving moving images that can be monochrome (black-and-white) or colored, with or without accompanying 

sound. &quot;Television&quot; may also refer specifically to a television set, television programming, or television 

transmission.The etymology of the word has a mixed Latin and Greek origin, meaning &quot;far sight&quot;: Greek tele, far, 

and Latin visio, sight (from video, vis- to see, or to view in the first person).', 'TV System	Diagonal	Interface	

Refresh Rate		', 1, 1)";


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

function get_attachment_id_from_src($image_src) {
        global $wpdb;
		$id=0;
		 $image_src = preg_replace('/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $image_src);
        $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
        $id = $wpdb->get_var($query);
		if(!$id)
		$id=0;
        return $image_src.'******'.$id;
}
if(get_bloginfo ('version')>=3.1){

add_action('plugins_loaded', 'spidercatalog');

}
else{
	spidercatalog();
}
function spidercatalog(){
   global $wpdb;

$parent = $wpdb->get_results("DESCRIBE ".$wpdb->prefix."spidercatalog_product_categories",ARRAY_A);
$published_in_parent= $wpdb->get_results("DESCRIBE ".$wpdb->prefix."spidercatalog_products",ARRAY_A);
			$exists_parent = 0;
			$exists_published_in_parent = 0;
			foreach($parent as $parentt){
              if($parentt['Field'] == 'parent') 	$exists_parent = 1;  }
	 foreach($published_in_parent as $published_in_parentt){
     if($published_in_parentt['Field'] == 'published_in_parent') 	$exists_published_in_parent = 1;}
 
 if(!$exists_parent) 		{ $wpdb->query("ALTER TABLE ".$wpdb->prefix."spidercatalog_product_categories ADD `parent` int(11) unsigned NOT NULL AFTER `name`"); }
  if(!$exists_published_in_parent) 		{ $wpdb->query("ALTER TABLE ".$wpdb->prefix."spidercatalog_products ADD `published_in_parent` tinyint(4) unsigned NOT NULL AFTER `published`"); }
}
