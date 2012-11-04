	<?php	
	
	if(!current_user_can('manage_options')) {
	die('Access Denied');
}	








 //////////////////////////////////////////////////////                                             /////////////////////////////////////////////////////// 
 //////////////////////////////////////////////////////         functions for categories            ///////////////////////////////////////////////////////
 //////////////////////////////////////////////////////                                             ///////////////////////////////////////////////////////
 //////////////////////////////////////////////////////                                             ///////////////////////////////////////////////////////






/////////////////// show categories





function showCategory() 
  {
	  
	  
	  
	  
	  
  global $wpdb;
	$sort["default_style"]="manage-column column-autor sortable desc";
	
	if(isset($_POST['page_number']))
	{
			
			if($_POST['asc_or_desc'])
			{
				$sort["sortid_by"]=$_POST['order_by'];
				if($_POST['asc_or_desc']==1)
				{
					$sort["custom_style"]="manage-column column-title sorted asc";
					$sort["1_or_2"]="2";
					$order="ORDER BY ".$sort["sortid_by"]." ASC";
				}
				else
				{
					$sort["custom_style"]="manage-column column-title sorted desc";
					$sort["1_or_2"]="1";
					$order="ORDER BY ".$sort["sortid_by"]." DESC";
				}
			}
	if($_POST['page_number'])
		{
			$limit=($_POST['page_number']-1)*20; 
		}
		else
		{
			$limit=0;
		}
	}
	else
		{
			$limit=0;
		}
	if(isset($_POST['search_events_by_title'])){
		$search_tag=$_POST['search_events_by_title'];
		}
		
		else
		{
		$search_tag="";
		}
	if ( $search_tag ) {
		$where= ' WHERE name LIKE "%'.$search_tag.'%"';
	}
	if(isset($_POST['saveorder']))
	{
		
		$id_order=array();
		if($_POST['saveorder']=="save")
		{
			foreach($_POST as $key=>$order1)
			{
				
				
				if(is_numeric(str_replace("order_","",$key))){
				$id_order[str_replace("order_","",$key)]=$order1;
				}
			}
		}
		if(isset($id_order)){
		$my_id_order=array();

		$i=1;
		$my_key=0;
		$j=0;
		while($saved_order!=1000000000000000)
		{
			$j++;
			$saved_order=100000000;
			
			foreach($id_order as $key=>$order1)
			{
				
				if($saved_order>$order1)
				{
					$saved_order=$order1;
					$my_key=$key;
				}
				
			}
			if($id_order[$my_key]==100000000)
			break;
			$my_id_order[$my_key]=$i;
			$id_order[$my_key]=100000000;
			$i++;
			if($j==1200)
			break;
		}		
		}
		foreach($my_id_order as $key=>$order1){
			$wpdb->update($wpdb->prefix.'spidercatalog_product_categories', array(
				'ordering'    =>$order1,
              ), 
              array('id'=>str_replace("order_","",$key)),
			  array(  '%d' )
			  );
		}
		
		
	}
	
	
	
	if(isset($_POST["oreder_move"]))
	{
		$ids=explode(",",$_POST["oreder_move"]);
		$this_order=$wpdb->get_var("SELECT ordering FROM ".$wpdb->prefix."spidercatalog_product_categories WHERE id=".$ids[0]);
		$next_order=$wpdb->get_var("SELECT ordering FROM ".$wpdb->prefix."spidercatalog_product_categories WHERE id=".$ids[1]);	
		$wpdb->update($wpdb->prefix.'spidercatalog_product_categories', array(
		'ordering'    =>$next_order,
          ), 
          array('id'=>$ids[0]),
		array(  '%d' )
			  );
		$wpdb->update($wpdb->prefix.'spidercatalog_product_categories', array(
		'ordering'    =>$this_order,
          ), 
          array('id'=>$ids[1]),
		array(  '%d' )
			  );
			  
			  		
	}
	
	
	
	// get the total number of records
	$query = "SELECT COUNT(*) FROM ".$wpdb->prefix."spidercatalog_product_categories". $where;
	
	$total = $wpdb->get_var($query);
	$pageNav['total'] =$total;
	$pageNav['limit'] =	 $limit/20+1;
	
	$query = "SELECT * FROM ".$wpdb->prefix."spidercatalog_product_categories".$where." ". $order." "." LIMIT ".$limit.",20";
	$rows = $wpdb->get_results($query);	   
		html_showcategories($option, $rows, $controller, $lists, $pageNav,$sort);
  }









//////////////////////        edit or add categories




function editCategory($id)
  {
	  
	  global $wpdb;
	  $query="SELECT name,ordering FROM ".$wpdb->prefix."spidercatalog_product_categories  ORDER BY `ordering`";
	  
	  $ord_elem=$wpdb->get_results($query);
	  $query="SELECT * FROM ".$wpdb->prefix."spidercatalog_product_categories WHERE id='".$id."'";
	   $row=$wpdb->get_row($query);
	   $images=explode(";;;",$row->category_image_url);
	   $par=explode('	',$row->param);
	  $count_ord=count($images);


    Html_editCategory($ord_elem, $count_ord,$images,$row);
  }
  
  
  
  
  
  
  
  
function add_category()
{
	global $wpdb;
	
	
	$query="SELECT name,ordering FROM ".$wpdb->prefix."spidercatalog_product_categories ORDER BY `ordering`";
	$ord_elem=$wpdb->get_results($query); ///////ordering elements list
	html_add_category($ord_elem);
	


	
	
}






function save_cat()
{
	
	 global $wpdb;
	 if(isset($_POST["ordering"])){	 
	 	$rows=$wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'spidercatalog_product_categories WHERE ordering>='.$_POST["ordering"].'  ORDER BY `ordering` ASC ');
	 }
	 else{
		 		echo "<h1>Error</h1>";
		return false;
	 }
	 
	$count_of_rows=count($rows);
	$ordering_values==array();
	$ordering_ids==array();
	for($i=0;$i<$count_of_rows;$i++)
	{		
	
		$ordering_ids[$i]=$rows[$i]->id;
		$ordering_values[$i]=$i+1+$_POST["ordering"];
	}
	for($i=0;$i<$count_of_rows;$i++){
				$wpdb->update($wpdb->prefix.'spidercatalog_product_categories', 
			  array('ordering'    =>$ordering_values[$i]), 
              array('id'=>$ordering_ids[$i]),
			  array(  '%d' )
			  );
			
			 
	}
	 
	 
	 
	 
	 $save_or_no= $wpdb->insert($wpdb->prefix.'spidercatalog_product_categories', array(
		'id'	=> NULL,
		'name'   				 => $_POST["name"],
        'category_image_url'     => $_POST["uploadded_images_list"],
        'description'			 => stripslashes($_POST["content"]),
        'param'  				 =>$_POST["param"],
        'ordering' 				 => $_POST["ordering"],
		'published'				 =>$_POST["published"],
                ),
				array(
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',	
				'%d',
						
				)
                );
					if(!$save_or_no)
	{
		?>
	<div class="updated"><p><strong><?php _e('Error. Please install plugin again'); ?></strong></p></div>
	<?php
		return false;
	}
	

	
	
	?>
	<div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
	<?php
	
    return true;
	
	
}




function change_cat( $id ){
  global $wpdb;
  $published=$wpdb->get_var("SELECT published FROM ".$wpdb->prefix."spidercatalog_product_categories WHERE `id`=".$id );
  if($published)
   $published=0;
  else
   $published=1;
  $savedd=$wpdb->update($wpdb->prefix.'spidercatalog_product_categories', array(
			'published'    =>$published,
              ), 
              array('id'=>$id),
			  array(  '%d' )
			  );
	if($save_or_no)
	{
		?>
	<div class="error"><p><strong><?php _e('Error. Please install plugin again'); ?></strong></p></div>
	<?php
		return false;
	}
	?>
	<div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
	<?php
	
    return true;
}



function removeCategory($id)
{
	
	
	global $wpdb;
	 $sql_remov_tag="DELETE FROM ".$wpdb->prefix."spidercatalog_product_categories WHERE id='".$id."'";
 if(!$wpdb->query($sql_remov_tag))
 {
	  ?>
	  <div id="message" class="error"><p>Spider Video Player Tag Not Deleted</p></div>
      <?php
	 
 }
 else{
 ?>
 <div class="updated"><p><strong><?php _e('Item Deleted.' ); ?></strong></p></div>
 <?php
 }
	$rows=$wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'spidercatalog_product_categories  ORDER BY `ordering` ASC ');
	
	$count_of_rows=count($rows);
	$ordering_values==array();
	$ordering_ids==array();
	for($i=0;$i<$count_of_rows;$i++)
	{		
	
		$ordering_ids[$i]=$rows[$i]->id;
		$ordering_values[$i]=$i+1+$_POST["ordering"];
	}

		for($i=0;$i<$count_of_rows;$i++)
	{	
			$wpdb->update($wpdb->prefix.'spidercatalog_product_categories', 
			  array('ordering'      =>$ordering_values[$i]), 
              array('id'			=>$ordering_ids[$i]),
			  array('%s'),
			  array( '%s' )
			  );
	}
		

  

}






function apply_cat($id)
{
	
	
		 global $wpdb;
		 $corent_ord=$wpdb->get_var('SELECT `ordering` FROM '.$wpdb->prefix.'spidercatalog_product_categories WHERE id=\''.$id.'\'');
		 $max_ord=$wpdb->get_var('SELECT MAX(ordering) FROM '.$wpdb->prefix.'spidercatalog_product_categories');
		 if($corent_ord>$_POST["ordering"])
		 {
				$rows=$wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'spidercatalog_product_categories WHERE ordering>='.$_POST["ordering"].' AND id<>\''.$id.'\'  ORDER BY `ordering` ASC ');
			 
			$count_of_rows=count($rows);
			$ordering_values==array();
			$ordering_ids==array();
			for($i=0;$i<$count_of_rows;$i++)
			{		
			
				$ordering_ids[$i]=$rows[$i]->id;
				$ordering_values[$i]=$i+1+$_POST["ordering"];
			}
			for($i=0;$i<$count_of_rows;$i++){
					$wpdb->update($wpdb->prefix.'spidercatalog_product_categories', 
					  array('ordering'    =>$ordering_values[$i]), 
					  array('id'=>$ordering_ids[$i]),
					  array(  '%d' )
					  );
		
			}
		 }
		 if($corent_ord<$_POST["ordering"])
		 {
			 $rows=$wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'spidercatalog_product_categories WHERE ordering<='.$_POST["ordering"].' AND id<>\''.$id.'\'  ORDER BY `ordering` ASC ');
			 
			$count_of_rows=count($rows);
			$ordering_values==array();
			$ordering_ids==array();
			for($i=0;$i<$count_of_rows;$i++)
			{		
			
				$ordering_ids[$i]=$rows[$i]->id;
				$ordering_values[$i]=$i+1;
			}
			if($max_ord==$_POST["ordering"]-1)
			{
				$_POST["ordering"]--;
			}
			for($i=0;$i<$count_of_rows;$i++){
					$wpdb->update($wpdb->prefix.'spidercatalog_product_categories', 
					  array('ordering'    =>$ordering_values[$i]), 
					  array('id'=>$ordering_ids[$i]),
					  array(  '%d' )
					  );
		
			}
		 }
	
	
	$savedd=$wpdb->update($wpdb->prefix.'spidercatalog_product_categories', array(
					'name'   				 => $_POST["name"],
					'category_image_url'     => $_POST["uploadded_images_list"],
					'description'			 => stripslashes($_POST["content"]),
					'param'  				 =>$_POST["param"],
					'ordering' 				 => $_POST["ordering"],
					'published'				 =>$_POST["published"],
              ), 
              array('id'=>$id),
			  array( 
			    '%s',
				'%s',
				'%s',
				'%s',
				'%d',	
				'%d', )
			  );
	if($save_or_no)
	{
		?>
	<div class="error"><p><strong><?php _e('Error. Please install plugin again'); ?></strong></p></div>
	<?php
		return false;
	}
	?>
	<div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
	<?php
	
    return true;
	
}








?>