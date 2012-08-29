<?php 



function front_end_single_product($id)
{
		global $wpdb;
		  $params=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."spidercatalog_params");
	  $new_param=array();
	  foreach( $params as $param)
	  {
		  $new_param[$param->name]=$param->value;
	  }
	   $params=$new_param;

	$product_id=$id;
	if($_GET['rev_page'])
	$rev_page=$_GET['rev_page'];
	else
	$rev_page=1;
	


	$query = "SELECT ".$wpdb->prefix."spidercatalog_products.*, ".$wpdb->prefix."spidercatalog_product_categories.name as cat_name FROM ".$wpdb->prefix."spidercatalog_products left join ".$wpdb->prefix."spidercatalog_product_categories on  ".$wpdb->prefix."spidercatalog_products.category_id=".$wpdb->prefix."spidercatalog_product_categories.id where
	".$wpdb->prefix."spidercatalog_products.id='".$product_id."' and ".$wpdb->prefix."spidercatalog_products.published = '1' ";
	$rows = $wpdb->get_results($query);
	

	foreach($rows as $row)
		{
			$category_id=$row->category_id;
		}

		$query= "SELECT * FROM ".$wpdb->prefix."spidercatalog_product_categories WHERE id = '".$category_id."' ";

		$row1 = $wpdb->get_row($query);
		$category_name=$row1->name;
		$full_name=$_POST['full_name'];
		$message_text=$_POST['message_text'];



		@session_start();

		$code=$_POST['code'];

		

				if($code!='' and $full_name!='' and $code==$_SESSION['captcha_code'] )
					{
						
						
						 $save_or_no= $wpdb->insert($wpdb->prefix.'spidercatalog_product_reviews', array(
							'id'	=> NULL,
								'name'   		 =>$full_name,
								'content'	    =>$message_text,
								'product_id'    =>$product_id,
								'remote_ip'   	 =>$_SERVER['REMOTE_ADDR'],
									),
									array(
									'%d',
									'%s',
									'%s',
									'%d',
									'%s',
											
									)
									);
									
						if (! $save_or_no)
							{
								echo "<script> alert('lav cheq pahum dzez');
								window.history.go(-1); </script>\n";
								exit();
							}
					}

				
	$reviews_perpage=$params['reviews_perpage'];
					$query = "SELECT name,content FROM ".$wpdb->prefix."spidercatalog_product_reviews where product_id='$product_id' order by id desc  limit ".(($rev_page-1)*$reviews_perpage).",$reviews_perpage ";
					
					$reviews_rows = $wpdb->get_results($query);
					

	$query_count = "SELECT count(".$wpdb->prefix."spidercatalog_product_reviews.id) as reviews_count FROM ".$wpdb->prefix."spidercatalog_product_reviews  WHERE product_id='".$product_id."' ";

	$row = $wpdb->get_row($query_count);
	//print_r($row);
	$reviews_count=$row->reviews_count;



	$query= "SELECT AVG(vote_value) as rating FROM ".$wpdb->prefix."spidercatalog_product_votes  WHERE product_id = '$product_id' ";



		$row1 = $wpdb->get_var($query);

		$rating=substr($row1,0,3);

		$query= "SELECT vote_value FROM ".$wpdb->prefix."spidercatalog_product_votes  WHERE product_id = '$product_id' and remote_ip='".$_SERVER['REMOTE_ADDR']."' ";
		$voted=count($wpdb->get_col($query));

		return html_front_end_single_product($rows,$reviews_rows, $option, $params,$category_name,$rev_page,$reviews_count,$rating,$voted);

	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	



















































//////////////////////////////////////////////////////////////////////										//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////										//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////     		 Front End Catalog			//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////										//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////										//////////////////////////////////////////////////////////////////////









function showPublishedProducts_1($cat_id=1,$show_cat_det=1,$cels_or_list='')
{
			global $wpdb;
		  $params=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."spidercatalog_params");
	  $new_param=array();
	  foreach( $params as $param)
	  {
		  $new_param[$param->name]=$param->value;
	  }
	   $params=$new_param;

		$params1['display_type']=$cels_or_list;
		$params1['show_category_details']=$show_cat_det;
		$params1['categories']=$cat_id;
		if($params1['display_type']=="list")
		$prod_in_page=$params['count_of_products_in_the_page'];
		else
		$prod_in_page=$params['count_of_product_in_the_row']*$params['count_of_rows_in_the_page'];
		if(isset($_GET['page_num']))
		$page_num=$_GET['page_num'];
		else
		$page_num=1;
		if(isset($_POST['cat_id']))
		$cat_id=$_POST['cat_id'];
		else
		$cat_id=0;

		if(isset($_POST['prod_name']))
			$prod_name=$_POST['prod_name'];
		else
			$prod_name=0;
		
		if($params1['categories']>0)
		{
		
		$query_count = "SELECT count(".$wpdb->prefix."spidercatalog_products.id) as prod_count FROM ".$wpdb->prefix."spidercatalog_products left join ".$wpdb->prefix."spidercatalog_product_categories on ".$wpdb->prefix."spidercatalog_products.category_id=".$wpdb->prefix."spidercatalog_product_categories.id WHERE 
		".$wpdb->prefix."spidercatalog_products.published = '1'  and ".$wpdb->prefix."spidercatalog_products.category_id='".$params1['categories']."' ";
		
		$query = "SELECT ".$wpdb->prefix."spidercatalog_products.*, ".$wpdb->prefix."spidercatalog_product_categories.name as cat_name,".$wpdb->prefix."spidercatalog_product_categories.category_image_url as cat_image_url,".$wpdb->prefix."spidercatalog_product_categories.description as cat_description FROM ".$wpdb->prefix."spidercatalog_products left join ".$wpdb->prefix."spidercatalog_product_categories on ".$wpdb->prefix."spidercatalog_products.category_id=".$wpdb->prefix."spidercatalog_product_categories.id WHERE
		
		".$wpdb->prefix."spidercatalog_products.published = '1'  and ".$wpdb->prefix."spidercatalog_products.category_id='".$params1['categories']."' ";
		
		$cat_query= "SELECT ".$wpdb->prefix."spidercatalog_product_categories.name as cat_name,".$wpdb->prefix."spidercatalog_product_categories.category_image_url as cat_image_url,".$wpdb->prefix."spidercatalog_product_categories.description as cat_description FROM ".$wpdb->prefix."spidercatalog_product_categories WHERE published = '1' and id='".$params1['categories']."'  ";
		
		}
		
		else
		{
		$query_count = "SELECT count(".$wpdb->prefix."spidercatalog_products.id) as prod_count FROM ".$wpdb->prefix."spidercatalog_products  WHERE 
		".$wpdb->prefix."spidercatalog_products.published = '1' ";
		
		$query= "SELECT  ".$wpdb->prefix."spidercatalog_products.*, ".$wpdb->prefix."spidercatalog_product_categories.name as cat_name,".$wpdb->prefix."spidercatalog_product_categories.category_image_url as cat_image_url,".$wpdb->prefix."spidercatalog_product_categories.description as cat_description FROM ".$wpdb->prefix."spidercatalog_products left join ".$wpdb->prefix."spidercatalog_product_categories on ".$wpdb->prefix."spidercatalog_products.category_id=".$wpdb->prefix."spidercatalog_product_categories.id WHERE 
		".$wpdb->prefix."spidercatalog_products.published = '1' ";
		
		$cat_query= "SELECT ".$wpdb->prefix."spidercatalog_product_categories.name as cat_name,".$wpdb->prefix."spidercatalog_product_categories.category_image_url as cat_image_url,".$wpdb->prefix."spidercatalog_product_categories.description as cat_description FROM ".$wpdb->prefix."spidercatalog_product_categories WHERE published = '1' ";	
			
		if($cat_id!=0)
		{
		$query_count .= " and ".$wpdb->prefix."spidercatalog_products.category_id='".$cat_id."' ";
		$query .= " and ".$wpdb->prefix."spidercatalog_products.category_id='".$cat_id."' ";
		$cat_query.=" and id='".$cat_id."' ";
		}
		}
		
		if($prod_name!="")
		{
		$query_count .= " and ".$wpdb->prefix."spidercatalog_products.name like '%".$prod_name."%' ";
		$query .= " and ".$wpdb->prefix."spidercatalog_products.name like '%".$prod_name."%' ";
		}
		
		$query .= "order by ".$wpdb->prefix."spidercatalog_products.ordering limit ".(($page_num-1)*$prod_in_page).",".$prod_in_page."  ";
		
		$row = $wpdb->get_var($query_count);
		
		$prod_count=$row;
		
		

		
		$rows = $wpdb->get_results($query);

		
		
		


		$cat_rows = $wpdb->get_results($cat_query);

		
		foreach($rows as $row)
		{
			$id=$row->id;
			$query= "SELECT AVG(vote_value) as rating FROM ".$wpdb->prefix."spidercatalog_product_votes  WHERE product_id = '".$id."' ";
		
			$row1 = $wpdb->get_var($query);
			$ratings[$id]=substr($row1,0,3);
			$query= "SELECT vote_value FROM ".$wpdb->prefix."spidercatalog_product_votes  WHERE product_id = '".$id."' and remote_ip='".$_SERVER['REMOTE_ADDR']."' ";

		
			$num_rows = $wpdb->get_var($query);
			$voted[$id]=$num_rows;
		
			$query= "SELECT * FROM ".$wpdb->prefix."spidercatalog_product_categories WHERE id = '".$row->category_id."' ";	
			$row2 = $wpdb->get_row($query);	
			$categories[$row2->id]=$row2->name;			
			}
		
			$query= "SELECT * FROM ".$wpdb->prefix."spidercatalog_product_categories WHERE `published`=1 ";		$category_list = $wpdb->get_results($query);
		if($params1['display_type']=="list")
		return front_end_catalog_list($rows, $option,$params,$page_num,$prod_count,$prod_in_page,$ratings,$voted,$categories,$category_list,$params1,$cat_rows,$cat_id);
		else
		return front_end_catalog_cells($rows, $option,$params,$page_num,$prod_count,$prod_in_page,$ratings,$voted,$categories,$category_list,$params1,$cat_rows,$cat_id);

}






































	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
?>






