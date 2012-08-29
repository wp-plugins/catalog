<?php








function showGloballll($op_type="0")
  {
    global $wpdb;
    $lists = array();
  
    $query = "SELECT *  from ".$wpdb->prefix."spidercatalog_params ";
    $rows = $wpdb->get_results( $query);
    $param_values = array();
    foreach ($rows as $row)
      {
        $key                = $row->name;
        $value              = $row->value;
        $param_values[$key] = $value;
      }
  
    html_showGlobal($param_values,$op_type);
  }
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  function showStyles($op_type="0")
  {
    global $wpdb;
    $query = "SELECT *  from ".$wpdb->prefix."spidercatalog_params ";
 
    $rows = $wpdb->get_results($query);

    $param_values = array();
    foreach ($rows as $row)
      {
        $key                = $row->name;
        $value              = $row->value;
        $param_values[$key] = $value;
      }
    html_showStyles( $param_values,$op_type);
  }
  
  
  
  
 function save_styles_options()
 {
	 
	  global $wpdb;
	  ?>
	 <div class="updated" style="font-size: 14px; color:red !important"><p><strong><?php _e('Styles and Colors is disabled in free version. If you need this functionality, you need to buy the commercial version.'); ?></strong></p></div>
	  	<?php 
	 
 }
  function save_global_options()
 {
	 
	  global $wpdb;
	  ?>
	 <div class="updated" style="font-size: 14px; color:red !important"><p><strong><?php _e('Global Options is disabled in free version. If you need this functionality, you need to buy the commercial version.'); ?></strong></p></div>
	  	<?php 
	 
 }
  
  
  
  
  
  
  
  ?>
  