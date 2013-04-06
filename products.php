	<?php	
	
	if(!current_user_can('manage_options')) {
	die('Access Denied');
}	




 //////////////////////////////////////////////////////                                             /////////////////////////////////////////////////////// 
 //////////////////////////////////////////////////////         functions for categories            ///////////////////////////////////////////////////////
 //////////////////////////////////////////////////////                                             ///////////////////////////////////////////////////////
 //////////////////////////////////////////////////////                                             ///////////////////////////////////////////////////////
 
 
 
 
 
 
 
 
function  showProducts(){





	  
	  
	  
	  
	  
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
	  if(isset($_GET["categoryid"]))
		{
	    $categ_id=$_GET["categoryid"];	
		}
		else{
		if(isset($_POST['cat_search'])){
		$categ_id=$_POST['cat_search'];
		}
		else{
		$categ_id=0;
		}
		
        }
		if ( $search_tag!="") {
	    $where= " WHERE ".$wpdb->prefix."spidercatalog_products.name LIKE '%".$search_tag."%' ";			  		
	}
	
	if($where){
	  if($categ_id){
	  $where.=" AND category_id=" .$categ_id;
	  }
	
	}
	else{
	if($categ_id){
	  $where.=" WHERE category_id=" .$categ_id;
	  }
	
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
			$wpdb->update($wpdb->prefix.'spidercatalog_products', array(
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
		$this_order=$wpdb->get_var("SELECT ordering FROM ".$wpdb->prefix."spidercatalog_products WHERE id=".$ids[0]);
		$next_order=$wpdb->get_var("SELECT ordering FROM ".$wpdb->prefix."spidercatalog_products WHERE id=".$ids[1]);	
		$wpdb->update($wpdb->prefix.'spidercatalog_products', array(
		'ordering'    =>$next_order,
          ), 
          array('id'=>$ids[0]),
		array(  '%d' )
			  );
		$wpdb->update($wpdb->prefix.'spidercatalog_products', array(
		'ordering'    =>$this_order,
          ), 
          array('id'=>$ids[1]),
		array(  '%d' )
			  );
			  
			  		
	}
	
	
	
	// get the total number of records
	$query = "SELECT COUNT(*) FROM ".$wpdb->prefix."spidercatalog_products". $where;
	
	$total = $wpdb->get_var($query);
	$pageNav['total'] =$total;
	$pageNav['limit'] =	 $limit/20+1;
	$query =	"SELECT ".$wpdb->prefix."spidercatalog_products.*,categories.name as category FROM ".$wpdb->prefix."spidercatalog_products left join ".$wpdb->prefix."spidercatalog_product_categories  as categories on  ".$wpdb->prefix."spidercatalog_products.category_id=categories.id ".$where." ". $order." "." LIMIT ".$limit.",20";
	$rows = $wpdb->get_results($query);
	$cat_row_query="SELECT id,name FROM ".$wpdb->prefix."spidercatalog_product_categories where parent=0 ORDER BY `ordering`";
	$cat_row=$wpdb->get_results($cat_row_query);
	$cat_row=open_cat_in_tree($cat_row);
		html_showProducts($option, $rows,  $lists, $pageNav,$sort,$cat_row);
  








}






function open_cat_in_tree($catt,$tree_problem='',$hihiih=1){

global $wpdb;
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
 ON a.parent=g.id WHERE a.name LIKE '%".$search_tag."%' AND a.parent=".$dog->id." group by a.id"; 
 $new_cat=$wpdb->get_results($new_cat_query);
 open_cat_in_tree($new_cat,$tree_problem. "— ",0);
}
return $trr_cat;

}








function change_prod( $id ){
  global $wpdb;
  
  $published=$wpdb->get_var("SELECT published FROM ".$wpdb->prefix."spidercatalog_products WHERE `id`=".$id );
  if($published)
   $published=0;
  else
   $published=1;
  $savedd=$wpdb->update($wpdb->prefix.'spidercatalog_products', array(
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























function editProduct($id)
  {
	  global $wpdb;
	 	  $params=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."spidercatalog_params");
	  $new_param=array();
	  foreach( $params as $param)
	  {
		  $new_param[$param->name]=$param->value;
	  }
	   $params=$new_param;
	  
    $row=$wpdb->get_row("SELECT * FROM ".$wpdb->prefix."spidercatalog_products WHERE id='".$id."'");
	$cat_id_for_order=$row->category_id;
    $parent_cat=$row->published_in_parent;
    $query = "SELECT id,name FROM ".$wpdb->prefix."spidercatalog_product_categories where published=1";
    $rows1 = $wpdb->get_results($query);
	  $category_id['0'] = array(
        'value' => '0',
        'text' => 'Uncategorised'
    );

    for ($i = 0, $n = count($rows1); $i < $n; $i++)
      {
        $row1 =& $rows1[$i];
        $id1               = $row1->id;
        $category_id[$id1] = array(
            'value' => $row1->id,
            'text' => $row1->name
        );
      }
    $ordering['0'] = array(
        'value' => '0',
        'text' => '0 First'
    );
    $query = "SELECT ordering,name FROM ".$wpdb->prefix."spidercatalog_products order by ordering";
    $rows1 =  $wpdb->get_results($query);
   if (!$row->id)
        $pub = 1;
    else
        $pub = $row->published;
    $query              = "SELECT * FROM ".$wpdb->prefix."spidercatalog_product_votes  WHERE product_id = '" . $id . "' ";
    $votes =  $wpdb->get_results( $query);

	$query ="SELECT param FROM ".$wpdb->prefix."spidercatalog_product_categories where id='".$row->category_id."'";

	$rows1 =$wpdb->get_results( $query);	
	$cat_row=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."spidercatalog_product_categories where parent=0");
	
	
	
	
	$lists=$wpdb->get_results("SELECT ordering,name FROM ".$wpdb->prefix."spidercatalog_products WHERE category_id='".$cat_id_for_order."' order by ordering");
	$cat_row=open_cat_in_tree($cat_row);
	
	
	
	
	
	
	
    html_editProduct($row, $lists, $votes, $option, $params, $rows1,$cat_row,$parent_cat);
  }







function  update_prad_cat($id){







		 global $wpdb;
		 $corent_ord=$wpdb->get_var('SELECT `ordering` FROM '.$wpdb->prefix.'spidercatalog_products WHERE id=\''.$id.'\'');
		 $max_ord=$wpdb->get_var('SELECT MAX(ordering) FROM '.$wpdb->prefix.'spidercatalog_products');
		 if($corent_ord>$_POST["ordering"])
		 {
				$rows=$wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'spidercatalog_products WHERE ordering>='.$_POST["ordering"].' AND id<>\''.$id.'\'  ORDER BY `ordering` ASC ');
			 
			$count_of_rows=count($rows);
			$ordering_values==array();
			$ordering_ids==array();
			for($i=0;$i<$count_of_rows;$i++)
			{		
			
				$ordering_ids[$i]=$rows[$i]->id;
				$ordering_values[$i]=$i+1+$_POST["ordering"];
			}
			for($i=0;$i<$count_of_rows;$i++){
					$wpdb->update($wpdb->prefix.'spidercatalog_products', 
					  array('ordering'    =>$ordering_values[$i]), 
					  array('id'=>$ordering_ids[$i]),
					  array(  '%d' )
					  );
		
			}
		 }
		 if($corent_ord<$_POST["ordering"])
		 {
			 $rows=$wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'spidercatalog_products WHERE ordering<='.$_POST["ordering"].' AND id<>\''.$id.'\'  ORDER BY `ordering` ASC ');
			 
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
					$wpdb->update($wpdb->prefix.'spidercatalog_products', 
					  array('ordering'    =>$ordering_values[$i]), 
					  array('id'=>$ordering_ids[$i]),
					  array(  '%d' )
					  );
		
			}
		 }
	













	 $images=explode(';;;',$_POST['uploadded_images_list']);
	 $kk=count($images);
 for($i=0;$i<$kk;$i++){
		 $image_with_id=get_attachment_id_from_src($images[$i]);
		 $images[$i]=$image_with_id;
	 }
	 $new_images=implode(';;;',$images);




		$savedd=$wpdb->update($wpdb->prefix.'spidercatalog_products', array(
			'name'   		 =>$_POST['name'],
			'category_id'    =>$_POST['cat_search'],
			'description'    =>$_POST['content'],
			'image_url'   	 =>$new_images,
			'cost'   		 =>stripslashes($_POST['cost']),
			'market_cost'	 =>$_POST['market_cost'],
			'param'	    	 =>$_POST['param'],
			'ordering'	     =>$_POST['ordering'],
			'published'	     =>$_POST['published'],
			'published_in_parent'	     =>$_POST['par_cat'],
              ), 
              array('id'=>$id),
			  array(  
			  '%s',
			  '%d',
			  '%s',
			  '%s',
			  '%s',
			  '%s',
			  '%s',
			  '%d',
			  '%d',
			  '%d'
			  
			   )
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



}



function save_prad_cat()
{
	
	
	 global $wpdb;
	 if(isset($_POST["ordering"])){	 
	 	$rows=$wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'spidercatalog_products WHERE ordering>='.$_POST["ordering"].'  ORDER BY `ordering` ASC ');
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
				$wpdb->update($wpdb->prefix.'spidercatalog_products', 
			  array('ordering'    =>$ordering_values[$i]), 
              array('id'=>$ordering_ids[$i]),
			  array(  '%d' )
			  );
			
			 
	}
	 
	 	 $images=explode(';;;',$_POST['uploadded_images_list']);
	 $kk=count($images);
 for($i=0;$i<$kk;$i++){
		 $image_with_id=get_attachment_id_from_src($images[$i]);
		 $images[$i]=$image_with_id;
	 }
	 $new_images=implode(';;;',$images);
	 
	 
	 $save_or_no= $wpdb->insert($wpdb->prefix.'spidercatalog_products', array(
		'id'	=> NULL,
		'name'   		 =>$_POST['name'],
			'category_id'    =>$_POST['cat_search'],
			'description'    =>$_POST['content'],
			'image_url'   	 =>$new_images,
			'cost'   		 =>stripslashes($_POST['cost']),
			'market_cost'	 =>$_POST['market_cost'],
			'param'	    	 =>$_POST['param'],
			'ordering'	     =>$_POST['ordering'],
			'published'	     =>$_POST['published'],
			'published_in_parent'	     =>$_POST['par_cat'],
                ),
				array(
				'%d',
				'%s',
			    '%d',
			 	'%s',
			    '%s',
			    '%s',
			    '%s',
			    '%s',
			    '%d',
				'%d',
			    '%d'
						
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



function addProduct()
{
	
	  global $wpdb;
	  
	  
	  $params=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."spidercatalog_params");
	  $new_param=array();
	  foreach( $params as $param)
	  {
		  $new_param[$param->name]=$param->value;
	  }
	   $params=$new_param;
	  
    $query = "SELECT id,name FROM ".$wpdb->prefix."spidercatalog_product_categories where published=1";
    $rows1 = $wpdb->get_results($query);
	  $category_id['0'] = array(
        'value' => '0',
        'text' => 'Uncategorised'
    );

    $query = "SELECT ordering,name FROM ".$wpdb->prefix."spidercatalog_products order by ordering";
    $rows1 =  $wpdb->get_results($query);
  //  $lists['ordering']             = JHTML::_('select.genericList', $ordering, 'ordering', 'class="inputbox" ' . '', 'value', 'text', $row->ordering);
//    $lists['category_id']          = JHTML::_('select.genericList', $category_id, 'category_id', 'class="inputbox"  onchange="submitbutton(\'apply\');"', 'value', 'text', $row->category_id);
    if (!$row->id)
        $pub = 1;
    else
        $pub = $row->published;
   // $lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $pub);
    $query              = "SELECT * FROM ".$wpdb->prefix."spidercatalog_product_votes  WHERE product_id = '" . $id . "' ";
    $votes =  $wpdb->get_results( $query);
	$cat_row=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."spidercatalog_product_categories where parent=0");
	$rows=$wpdb->get_row("SELECT * FROM ".$wpdb->prefix."spidercatalog_products WHERE id='".$id."'");
   $cat_row=open_cat_in_tree($cat_row);
	
	
	
	
	$lists=$wpdb->get_results("SELECT ordering,name FROM ".$wpdb->prefix."spidercatalog_products order by ordering");
	
	
	
	
	
	
	
	
    html_addProduct($lists, $votes, $option, $params, $rows1,$cat_row);
  
	
	
	
	
	
	
}



function removeProduct($id){


	global $wpdb;
	 $sql_remov_tag="DELETE FROM ".$wpdb->prefix."spidercatalog_products WHERE id='".$id."'";
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
	$rows=$wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'spidercatalog_products  ORDER BY `ordering` ASC ');
	
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
			$wpdb->update($wpdb->prefix.'spidercatalog_products', 
			  array('ordering'      =>$ordering_values[$i]), 
              array('id'			=>$ordering_ids[$i]),
			  array('%s'),
			  array( '%s' )
			  );
	}
		





}





































 
 
 
  //////////////////////////////////////////////////////                                            /////////////////////////////////////////////////////// 
 //////////////////////////////////////////////////////      revive and reting			            ///////////////////////////////////////////////////////
 //////////////////////////////////////////////////////                                             ///////////////////////////////////////////////////////
 //////////////////////////////////////////////////////                                             ///////////////////////////////////////////////////////
 
 
 
 
 
 
 
 
 
 
 
 
function   spider_cat_prod_rev($id)
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
		$where= " WHERE product_id='".$id."'";
	
	
	
	// get the total number of records
	$query = "SELECT COUNT(*) FROM ".$wpdb->prefix."spidercatalog_product_reviews". $where;
	$total = $wpdb->get_var($query);
	$pageNav['total'] =$total;
	$pageNav['limit'] =	 $limit/20+1;
	
	$query = "SELECT * FROM ".$wpdb->prefix."spidercatalog_product_reviews".$where." ". $order." "." LIMIT ".$limit.",20";
	$rows = $wpdb->get_results($query);	    	
	html_spider_cat_prod_rev($rows, $pageNav, $sort,$id);
	
	
}
 
 
 
 
 
 
 
 
 
function delete_rev($id){

global $wpdb;
    $cid=array();
    $cid = $_POST['post'];
    $product_id = $id;
    if (count($cid))
      {
        $cids  = implode(',', $cid);
        $query = "DELETE FROM ".$wpdb->prefix."spidercatalog_product_reviews WHERE id IN ( ".$cids." )";
        
      if(!$wpdb->query($query))
 {
	  ?>
	  <div id="message" class="error"><p>Spider Product Reviews Not Deleted</p></div>
      <?php
	 
 }
 else{
 ?>
 <div class="updated"><p><strong><?php _e('Items Deleted.' ); ?></strong></p></div>
 <?php
 }
 }
 }
 
 
 
 
 
 
 
 
 
 
 
 
 function delete_single_review($id)
 {
	 global $wpdb;
	 $del_id=$_GET['del_id'];
	  $query = "DELETE FROM ".$wpdb->prefix."spidercatalog_product_reviews WHERE id=".$del_id;
	  if(!$wpdb->query($query))
 {
	  ?>
	  <div id="message" class="error"><p>Spider Product Review Not Deleted</p></div>
      <?php
	 
 }
 else{
 ?>
 <div class="updated"><p><strong><?php _e('Items Deleted.' ); ?></strong></p></div>
 <?php
 }
	 
	 
 }





































//////////////////////////////////////////////////

























 
 
 
function   spider_cat_prod_rating($id)
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
	
		$where= " WHERE product_id='".$id."'";
	
	
	
	
	// get the total number of records
	$query = "SELECT COUNT(*) FROM ".$wpdb->prefix."spidercatalog_product_votes". $where;
	$total = $wpdb->get_var($query);
	$pageNav['total'] =$total;
	$pageNav['limit'] =	 $limit/20+1;
	
	$query = "SELECT * FROM ".$wpdb->prefix."spidercatalog_product_votes".$where." ". $order." "." LIMIT ".$limit.",20";
	$rows = $wpdb->get_results($query);	    	
	html_spider_cat_prod_rating($rows, $pageNav, $sort,$id);
	
	
}
 
 
 
 
 
 
 
 
 
function delete_ratings($id){

global $wpdb;
    $cid=array();
    $cid = $_POST['post'];
    $product_id = $id;
    if (count($cid))
      {
        $cids  = implode(',', $cid);
        $query = "DELETE FROM ".$wpdb->prefix."spidercatalog_product_votes WHERE id IN ( ".$cids." )";
        
      if(!$wpdb->query($query))
 {
	  ?>
	  <div id="message" class="error"><p>Spider Product Reviews Not Deleted</p></div>
      <?php
	 
 }
 else{
 ?>
 <div class="updated"><p><strong><?php _e('Items Deleted.' ); ?></strong></p></div>
 <?php
 }
 }
 }
 
 
 
 
 
 
 
 
 
 
 
 
 function delete_single_rating($id)
 {
	 global $wpdb;
	 $del_id=$_GET['del_id'];
	  $query = "DELETE FROM ".$wpdb->prefix."spidercatalog_product_votes WHERE id=".$del_id;
	  if(!$wpdb->query($query))
 {
	  ?>
	  <div id="message" class="error"><p>Spider Product Rating Not Deleted</p></div>
      <?php
	 
 }
 else{
 ?>
 <div class="updated"><p><strong><?php _e('Items Deleted.' ); ?></strong></p></div>
 <?php
 }
	 
	 
 }















function update_s_c_rating($id){
global $wpdb;
$rows=$wpdb->get_col("SELECT `id` FROM ".$wpdb->prefix."spidercatalog_product_votes WHERE product_id=".$id);
if($rows[0]){
foreach($rows as $row)
{
	if(isset($_POST['vote_'.$row]))
	$wpdb->update($wpdb->prefix.'spidercatalog_product_votes', 
			  array('vote_value'    =>$_POST['vote_'.$row]), 
              array('id'=>$row),
			  array(  '%d' )
			  );
}
}
?>
 <div class="updated"><p><strong><?php _e('Items Saved.' ); ?></strong></p></div>
 <?php


}








