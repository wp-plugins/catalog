	<?php	
	
	if(!current_user_can('manage_options')) {
	die('Access Denied');
}	
 //////////////////////////////////////////////////////                                             /////////////////////////////////////////////////////// 
 //////////////////////////////////////////////////////      Html functions for categories          ///////////////////////////////////////////////////////
 //////////////////////////////////////////////////////                                             ///////////////////////////////////////////////////////
 //////////////////////////////////////////////////////                                             ///////////////////////////////////////////////////////



















function html_showcategories($option, $rows, $controller, $lists, $pageNav,$sort){
	global $wpdb;
	?>
    <script language="javascript">
	function ordering(name,as_or_desc)
	{
		document.getElementById('asc_or_desc').value=as_or_desc;		
		document.getElementById('order_by').value=name;
		document.getElementById('admin_form').submit();
	}
	function saveorder()
	{
		document.getElementById('saveorder').value="save";
		document.getElementById('admin_form').submit();
		
	}
	function listItemTask(this_id,replace_id)
	{
		document.getElementById('oreder_move').value=this_id+","+replace_id;
		document.getElementById('admin_form').submit();
	}
				 	function doNothing() {  
var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
    if( keyCode == 13 ) {


        if(!e) var e = window.event;

        e.cancelBubble = true;
        e.returnValue = false;

        if (e.stopPropagation) {
                e.stopPropagation();
                e.preventDefault();
        }
}
}
	</script>
    <form method="post"  onkeypress="doNothing()" action="admin.php?page=Categories_Spider_Catalog" id="admin_form" name="admin_form">
	<table cellspacing="10" width="100%">
              <tr>   
<td width="100%" style="font-size:14px; font-weight:bold"><a href="http://web-dorado.com/spider-catalog-wordpress-guide-step-2.html" target="_blank" style="color:blue; text-decoration:none;">User Manual</a><br />
This section allows you to create categories of products. <a href="http://web-dorado.com/spider-catalog-wordpress-guide-step-2.html" target="_blank" style="color:blue; text-decoration:none;">More...</a></td>   
	 <td colspan="7" align="right" style="font-size:16px;">
  <a href="http://web-dorado.com/files/fromSpiderCatalog.php" target="_blank" style="color:red; text-decoration:none;">
<img src="<?php echo plugins_url("images/header.png",__FILE__) ?>" border="0" alt="http://web-dorado.com/files/fromSpiderCatalog.php" width="215"><br>
Get the full version&nbsp;&nbsp;&nbsp;&nbsp;
</a>
  </td>
        </tr>
    <tr>
    <td style="width:80px">
    <?php echo "<h2>".'Categories'. "</h2>"; ?>
    </td>
    <td  style="width:90px; text-align:right;"><p class="submit" style="padding:0px; text-align:left"><input type="button" value="Add a Category" name="custom_parametrs" onclick="window.location.href='admin.php?page=Categories_Spider_Catalog&task=add_cat'" /></p></td>
<td style="text-align:right;font-size:16px;padding:20px; padding-right:50px">

    </tr>
    </table>
    <?php
	if(isset($_POST['serch_or_not'])) {if($_POST['serch_or_not']=="search"){ $serch_value=$_POST['search_events_by_title']; }else{$serch_value="";}} 
	$serch_fields='<div class="alignleft actions" style="width:180px;">
    	<label for="search_events_by_title" style="font-size:14px">Filter: </label>
        <input type="text" name="search_events_by_title" value="'.$serch_value.'" id="search_events_by_title" onchange="clear_serch_texts()">
    </div>
	<div class="alignleft actions">
   		<input type="button" value="Search" onclick="document.getElementById(\'page_number\').value=\'1\'; document.getElementById(\'serch_or_not\').value=\'search\';
		 document.getElementById(\'admin_form\').submit();" class="button-secondary action">
		 <input type="button" value="Reset" onclick="window.location.href=\'admin.php?page=Categories_Spider_Catalog\'" class="button-secondary action">
    </div>';
	 print_html_nav($pageNav['total'],$pageNav['limit'],$serch_fields);	
	
	?>
  <table class="wp-list-table widefat fixed pages" style="width:95%">
 <thead>
 <TR>
   <th scope="col" id="id" class="<?php if($sort["sortid_by"]=="id") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="width:30px" ><a href="javascript:ordering('id',<?php if($sort["sortid_by"]=="id") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>ID</span><span class="sorting-indicator"></span></a></th>
 <th scope="col" id="name" class="<?php if($sort["sortid_by"]=="name") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="" ><a href="javascript:ordering('name',<?php if($sort["sortid_by"]=="name") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>Name</span><span class="sorting-indicator"></span></a></th>
<th scope="col" id="description" class="<?php if($sort["sortid_by"]=="description") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="" ><a href="javascript:ordering('description',<?php if($sort["sortid_by"]=="description") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>Description</span><span class="sorting-indicator"></span></a></th>
 <th scope="col" id="ordering" class="<?php if($sort["sortid_by"]=="ordering") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="width:95px" ><a style="display:inline" href="javascript:ordering('ordering',<?php if($sort["sortid_by"]=="ordering") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>Order</span><span class="sorting-indicator"></span></a><div><a style="display:inline" href="javascript:saveorder(1, 'saveorder')" title="Save Order"><img onclick="saveorder(1, 'saveorder')" src="<?php echo plugins_url("images/filesave.png",__FILE__) ?>" alt="Save Order"></a></div></th>
  <th scope="col" id="published"  class="<?php if($sort["sortid_by"]=="published") echo $sort["custom_style"]; else echo $sort["default_style"]; ?>" style="width:100px" ><a href="javascript:ordering('published',<?php if($sort["sortid_by"]=="published") echo $sort["1_or_2"]; else echo "1"; ?>)"><span>Published</span><span class="sorting-indicator"></span></a></th>
 <th style="width:80px">Edit</th>
 <th style="width:80px">Delete</th>
 </TR>
 </thead>
 <tbody>
 <?php 
  for($i=0; $i<count($rows);$i++){ 
	  if(isset($rows[$i-1]->id))
		  {
		  $move_up='<span><a href="#reorder" onclick="return listItemTask(\''.$rows[$i]->id.'\',\''.$rows[$i-1]->id.'\')" title="Move Up">   <img src="'.plugins_url('images/uparrow.png',__FILE__).'" width="16" height="16" border="0" alt="Move Up"></a></span>';
		  }
	  else
	  	{
			$move_up="";
	  	}
		if(isset($rows[$i+1]->id))
  		$move_down='<span><a href="#reorder" onclick="return listItemTask(\''.$rows[$i]->id.'\',\''.$rows[$i+1]->id.'\')" title="Move Down">  <img src="'.plugins_url('images/downarrow.png',__FILE__).'" width="16" height="16" border="0" alt="Move Down"></a></span>';
  		else
  		$move_down="";
  		
  ?>
 <tr>
         <td><?php echo $rows[$i]->id; ?></td>
         <td><a  href="admin.php?page=Categories_Spider_Catalog&task=edit_cat&id=<?php echo $rows[$i]->id?>"><?php echo $rows[$i]->name; ?></a></td>
         <td><?php echo $rows[$i]->description; ?></td>
         <td ><?php echo  $move_up.$move_down; ?><input type="text" name="order_<?php echo $rows[$i]->id; ?>" style="width:40px" value="<?php echo $rows[$i]->ordering; ?>" /></td>
         <td><a  href="admin.php?page=Categories_Spider_Catalog&task=unpublish_cat&id=<?php echo $rows[$i]->id?>"<?php if(!$rows[$i]->published){ ?> style="color:#C00;" <?php }?> ><?php if($rows[$i]->published)echo "Yes"; else echo "No"; ?></a></td>
         <td ><a  href="admin.php?page=Categories_Spider_Catalog&task=edit_cat&id=<?php echo $rows[$i]->id?>">Edit</a></td>
         <td><a  href="admin.php?page=Categories_Spider_Catalog&task=remove_cat&id=<?php echo $rows[$i]->id?>">Delete</a></td>
  </tr> 
 <?php } ?>
 </tbody>
 </table>
 <input type="hidden" name="oreder_move" id="oreder_move" value="" />
 <input type="hidden" name="asc_or_desc" id="asc_or_desc" value="<?php if(isset($_POST['asc_or_desc'])) echo $_POST['asc_or_desc'];?>"  />
 <input type="hidden" name="order_by" id="order_by" value="<?php if(isset($_POST['order_by'])) echo $_POST['order_by'];?>"  />
 <input type="hidden" name="saveorder" id="saveorder" value="" />

 <?php
?>
    
    
   
 </form>
    <?php

}

























/////////////////////////////////////////////// 







function Html_editCategory($ord_elem, $count_ord,$images,$row)

{
	
	
		
	
?>
<script type="text/javascript">
function submitbutton(pressbutton) 
{
	if(!document.getElementById('name').value){
	alert("Name is required.");
	return;
	
	}
	referesh_created_tags();
	document.getElementById("adminForm").action=document.getElementById("adminForm").action+"&task="+pressbutton;
	document.getElementById("adminForm").submit();
	
}
</script>




<table width="95%">
  <tbody>
              <tr>   
<td width="100%" style="font-size:14px; font-weight:bold"><a href="http://web-dorado.com/spider-catalog-wordpress-guide-step-2.html" target="_blank" style="color:blue; text-decoration:none;">User Manual</a><br />
This section allows you to create categories of products. <a href="http://web-dorado.com/spider-catalog-wordpress-guide-step-2.html" target="_blank" style="color:blue; text-decoration:none;">More...</a></td>   
	 <td colspan="7" align="right" style="font-size:16px;">
  <a href="http://web-dorado.com/files/fromSpiderCatalog.php" target="_blank" style="color:red; text-decoration:none;">
<img src="<?php echo plugins_url("images/header.png",__FILE__) ?>" border="0" alt="http://web-dorado.com/files/fromSpiderCatalog.php" width="215"><br>
Get the full version&nbsp;&nbsp;&nbsp;&nbsp;
</a>
  </td>
        </tr>
  <tr>
  <td width="100%"><h2>Category - <?php echo stripslashes($row->name) ?></h2></td>
  <td align="right"><input type="button" onclick="submitbutton('save')" value="Save" class="button-secondary action"> </td>  
  <td align="right"><input type="button" onclick="submitbutton('apply')" value="Apply" class="button-secondary action"> </td> 
  <td align="right"><input type="button" onclick="window.location.href='admin.php?page=Categories_Spider_Catalog'" value="Cancel" class="button-secondary action"> </td> 
  </tr>
  </tbody></table>
  <br />
  <br />
<form action="admin.php?page=Categories_Spider_Catalog&id=<?php echo $row->id; ?>" method="post" name="adminForm" id="adminForm">

<table class="admintable">
<tr>
<td width="100" align="right" class="key">
Name:
</td>
<td>
<input class="text_area" type="text" name="name" id="name" size="50" maxlength="250" value="<?php echo $row->name;?>" />
</td>
</tr>





<tr>
<td width="100" align="right" >
Images:
</td>
<td>


<script>
var count_of_elements=<?php 

if(!$images[0] && $count_ord==1)
{
	echo 1;
}
else{
echo $count_ord+1; 
}
?>;
function add_upload(id_upload){
	
					if(id_upload>=count_of_elements)	
					{		
					count_of_elements++;
				var upload_button_texnod=document.createTextNode("Select")
	
	
				var div_element=document.createElement('div');
				div_element.setAttribute('id',"upload_div_"+count_of_elements);
				
				
				var a_element=document.createElement('a');
				a_element.setAttribute("class","button lu_upload_button");
				a_element.setAttribute("id","upload_href_"+count_of_elements);
				a_element.setAttribute("onclick","narek('"+count_of_elements+"')");
				a_element.appendChild(upload_button_texnod);
				
				
				

				var inpElement = document.createElement('input');
				inpElement.setAttribute('type','text');
				inpElement.setAttribute('style','width:200px;');
				inpElement.setAttribute('id','image_no_'+count_of_elements);
				inpElement.setAttribute('value','');
				inpElement.setAttribute('onchange','add_upload('+count_of_elements+')');
				inpElement.setAttribute('class','text_input');
				
				var btnElement = document.createElement('input');
				btnElement.setAttribute('type','button');
				btnElement.setAttribute('value','X');
				btnElement.setAttribute('onclick',"remov_upload("+count_of_elements+")");
				btnElement.setAttribute('title',"Delete");
					
					
					
					
				div_element.appendChild(inpElement);
				div_element.appendChild(a_element);
				div_element.appendChild(btnElement);
				div_element.appendChild(document.createElement('br'));
					
				document.getElementById("img__uploads").appendChild(div_element);
	

	
				document.getElementById('image_no_'+count_of_elements).focus();

					}
					create_images_tags();
	
								
}


function remov_upload(id_upload){
	if(document.getElementById('img__uploads').getElementsByTagName('div').length!=1 && id_upload!=count_of_elements)
	{
	var div = document.getElementById("upload_div_" + id_upload);
	div.parentNode.removeChild(div);
	create_images_tags();
	}
	
	
}
	
	
   var formfield=null;
   var upload_id_x_for=null;
	 window.original_send_to_editor = window.send_to_editor;
   	 window.send_to_editor = function(html){
        if (formfield) {
            var fileurl = jQuery('img',html).attr('src');
			if(!fileurl){
			var if_url_not_set;
			if_url_not_set="<a>"+html+"</a>"
			fileurl=jQuery('img',if_url_not_set).attr('src');
			}
            formfield.val(fileurl);
			add_upload(upload_id_x_for);
            tb_remove();
        } else {
            window.original_send_to_editor(html);
        }
        formfield=null;
		
    };
    
			

function narek(upload_id_x)
{
		upload_id_x_for=upload_id_x;
        formfield = jQuery("#upload_href_"+upload_id_x).parent().find(".text_input");
        tb_show('', 'media-upload.php?type=image&TB_iframe=true');
        jQuery('#TB_overlay,#TB_closeWindowButton').bind("click",function(){formfield=null; upload_id_x_for=null;});
		
        return false;

	
}

function referesh_created_tags()
{
	var lists="";
	document.getElementById('uploadded_images_list').value='';
	for(i=0;i<count_of_elements;i++)
	{
		if(document.getElementById('image_no_'+i)){
		if(document.getElementById('uploadded_images_list').value)
		document.getElementById('uploadded_images_list').value=document.getElementById('uploadded_images_list').value+";;;"+document.getElementById('image_no_'+i).value;
		else
		document.getElementById('uploadded_images_list').value=document.getElementById('image_no_'+i).value;
		}
	}
}



function create_images_tags()
{
	document.getElementById("added_images").innerHTML="";
	for(i=0;i<count_of_elements;i++)
	{
		
		if(document.getElementById('image_no_'+i)){					
			var a_for_image = document.createElement('a');
			a_for_image.setAttribute('href',document.getElementById('image_no_'+i).value);
			a_for_image.setAttribute('target',"_blank");
			var img_for_image=document.createElement('img');
			img_for_image.setAttribute('src',document.getElementById('image_no_'+i).value);
			img_for_image.setAttribute('style','max-height: 50px; max-width: 50px; margin: 8px');
			a_for_image.appendChild(img_for_image);
			document.getElementById('added_images').appendChild(a_for_image);
		}
	
	}
}

</script>
<table>
<tr>
<td id="img__uploads">
<?php 

$images_with_id=$images;
$counnt_image=count($images_with_id);
for($i=0;$i<$counnt_image;$i++){
	$ffff=explode('******',$images_with_id[$i]);
	$images[$i]=$ffff[0];
}
$count_ord=count($images);

if($images[0] || $count_ord!=1)
for($i=0;$i<$count_ord;$i++){ ?>
<div id="upload_div_<?php echo $i+1; ?>">
<input type="text"  id="image_no_<?php echo $i+1; ?>" value="<?php echo stripslashes($images[$i]) ?>" onchange="add_upload('<?php echo $i+1; ?>');" class="text_input" style="width:200px;"><a id="upload_href_<?php echo $i+1; ?>" class="button lu_upload_button" onclick="narek('<?php echo $i+1; ?>')" >Select</a><input type="button" value="X" title="Delete" onclick="remov_upload('<?php echo $i+1; ?>')" /><br>
</div>

<?php }?>

<div id="upload_div_<?php echo $i+1; ?>">
<input type="text"  id="image_no_<?php echo $i+1; ?>" value="<?php echo stripslashes($images[$i]) ?>" onchange="add_upload('<?php echo $i+1; ?>');" class="text_input" style="width:200px;"><a id="upload_href_<?php echo $i+1; ?>" class="button lu_upload_button" onclick="narek('<?php echo $i+1; ?>')">Select</a><input type="button" value="X" title="Delete" onclick="remov_upload('<?php echo $i+1; ?>')" /><br>
</div>
</td>
<tr>
<td id="added_images">
<?php if($images[0] || $count_ord!=1){ ?>
<?php for($i=0;$i<$count_ord;$i++){ ?>
<a href="<?php echo $images[$i]; ?>" target="_blank"><img src="<?php echo stripslashes($images[$i]); ?>" style="max-height: 50px; max-width: 50px; margin: 8px"></a>
<?php } }?>
</td>
</tr>

</table>

<input type="hidden" name="uploadded_images_list" id="uploadded_images_list" value="<?php stripslashes($row->category_image_url); ?>" />
<td>

</tr>
<tr><td colspan="2" style="width:500px;">
<?php echo $image_url_list; ?>
</td></tr>






<tr>
<td width="100" align="right" class="key">
Description:
</td>
<td>

<div id="main_editor"><div  style=" width:600px; text-align:left" id="poststuff">
<div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea"><?php the_editor(stripslashes($row->description),"content","title" ); ?>
</div>
</div>
</div>

</td>
</tr>
<tr>
<tr>
<td width="100px" align="right" class="key">
Parameters:
</td>
<td>


<script type="text/javascript">

parameters0['sel1']=new Array(<?php

$par=explode("	",$row->param);

for($k=0;$k<=count($par);$k++)
{
if(isset($par[$k]) and $par[$k]!='')
echo "'".stripslashes(str_replace('
','',htmlspecialchars($par[$k])))."',";
}

?>'');

</script>

<div id="sel1">
<?php
$k=0;
while($k<1000)
{
if(isset($par[$k]) and $par[$k]!=''){
echo '<input type="text" style="width:200px;" id="inp_sel1_'.$k.'" value="'.stripslashes(htmlspecialchars($par[$k])).'" 

onChange="Add(\'sel1\')" /><input type="button" value="X" 

onClick="Remove('.$k.',\'sel1\');" /><br />';
$k++;
}
else{
echo '<input type="text" style="width:200px;" id="inp_sel1_'.$k.'" value="" onChange="Add(\'sel1\')" /><input 

type="button" value="X" onClick="Remove('.$k.',\'sel1\');" /><br 

/>';
$k=1000;
}
}
?>
</div><input type="hidden" name="param" id="hid_sel1" value="<?php echo stripslashes($row->param);?>" />
</td>
</tr>

<tr>
<td width="100" align="right" class="key">
Order:
</td>
<td>
<select name="ordering" >
<?php
$count_ord=count($ord_elem);
for($i=0;$i<$count_ord;$i++)
{
?>
<option value="<?php echo $ord_elem[$i]->ordering  ?>"<?php if($ord_elem[$i]->ordering==$row->ordering) echo 'selected="selected"'; ?> > <?php echo  $ord_elem[$i]->ordering." "; echo $ord_elem[$i]->name; ?></option>

<?php 
}
?>
<option value="<?php echo  $ord_elem[$i-1]->ordering+1; ?>" if()><?php echo  $ord_elem[$i-1]->ordering+1; ?> Last</option>
</select>


</td>
</tr>
<tr>
<td width="100" align="right" class="key">Published:</td>
<td>
	<input type="radio" name="published" id="published0" value="0" <?php if($row->published==0) echo 'checked="checked"'; ?> class="inputbox">
	<label for="published0">No</label>
	<input type="radio" name="published" id="published1" value="1" <?php if($row->published==1) echo 'checked="checked"'; ?> class="inputbox">
	<label for="published1">Yes</label>
</td>
<?php

?>
</td>
</tr>
</table>
<input type="hidden" name="task" value="" />
</form>
<?php






	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}

    
   





function html_add_category($ord_elem)
{
	
	
	
?>
<script type="text/javascript">
function submitbutton(pressbutton) 
{
	if(!document.getElementById('name').value){
	alert("Name is required.");
	return;
	
	}
	referesh_created_tags();
	document.getElementById("adminForm").action=document.getElementById("adminForm").action+"&task="+pressbutton;
	document.getElementById("adminForm").submit();
	
}
</script>




<table width="95%">
  <tbody>
              <tr>   
<td width="100%" style="font-size:14px; font-weight:bold"><a href="http://web-dorado.com/spider-catalog-wordpress-guide-step-2.html" target="_blank" style="color:blue; text-decoration:none;">User Manual</a><br />
This section allows you to create categories of products. <a href="http://web-dorado.com/spider-catalog-wordpress-guide-step-2.html" target="_blank" style="color:blue; text-decoration:none;">More...</a></td>   
	 <td colspan="7" align="right" style="font-size:16px;">
  <a href="http://web-dorado.com/files/fromSpiderCatalog.php" target="_blank" style="color:red; text-decoration:none;">
<img src="<?php echo plugins_url("images/header.png",__FILE__) ?>" border="0" alt="http://web-dorado.com/files/fromSpiderCatalog.php" width="215"><br>
Get the full version&nbsp;&nbsp;&nbsp;&nbsp;
</a>
  </td>
        </tr>
  <tr>
  <td width="100%"><h2>Add Category</h2></td>
  <td align="right"><input type="button" onclick="submitbutton('save')" value="Save" class="button-secondary action"> </td>  
  <td align="right"><input type="button" onclick="submitbutton('apply')" value="Apply" class="button-secondary action"> </td> 
  <td align="right"><input type="button" onclick="window.location.href='admin.php?page=Categories_Spider_Catalog'" value="Cancel" class="button-secondary action"> </td> 
  </tr>
  </tbody></table>
  <br />
  <br />
<form action="admin.php?page=Categories_Spider_Catalog" method="post" name="adminForm" id="adminForm">

<table class="admintable">
<tr>
<td width="100" align="right" class="key">
Name:
</td>
<td>
<input class="text_area" type="text" name="name" id="name" size="50" maxlength="250" value="<?php echo $row->name;?>" />
</td>
</tr>





<tr>
<td width="100" align="right" >
Images:
</td>
<td>


<script>
var count_of_elements=1;
function add_upload(id_upload){
	
					if(id_upload>=count_of_elements)	
					{		
					count_of_elements++;
				var upload_button_texnod=document.createTextNode("Select")
	
	
				var div_element=document.createElement('div');
				div_element.setAttribute('id',"upload_div_"+count_of_elements);
				
				
				var a_element=document.createElement('a');
				a_element.setAttribute("class","button lu_upload_button");
				a_element.setAttribute("id","upload_href_"+count_of_elements);
				a_element.setAttribute("onclick","narek('"+count_of_elements+"')");
				a_element.appendChild(upload_button_texnod);
				
				
				

				var inpElement = document.createElement('input');
				inpElement.setAttribute('type','text');
				inpElement.setAttribute('style','width:200px;');
				inpElement.setAttribute('id','image_no_'+count_of_elements);
				inpElement.setAttribute('value','');
				inpElement.setAttribute('onchange','add_upload('+count_of_elements+')');
				inpElement.setAttribute('class','text_input');
				
				var btnElement = document.createElement('input');
				btnElement.setAttribute('type','button');
				btnElement.setAttribute('value','X');
				btnElement.setAttribute('onclick',"remov_upload("+count_of_elements+")");
				btnElement.setAttribute('title',"Delete");
					
					
					
					
				div_element.appendChild(inpElement);
				div_element.appendChild(a_element);
				div_element.appendChild(btnElement);
				div_element.appendChild(document.createElement('br'));
					
				document.getElementById("img__uploads").appendChild(div_element);
	

	
				document.getElementById('image_no_'+count_of_elements).focus();

					}
					create_images_tags();
	
								
}


function remov_upload(id_upload){
	
	if(document.getElementById('img__uploads').getElementsByTagName('div').length!=1 && id_upload!=count_of_elements)
	{
	var div = document.getElementById("upload_div_" + id_upload);
	div.parentNode.removeChild(div);
		create_images_tags();
	}

	
}
	
	
   var formfield=null;
   var upload_id_x_for=null;
	 window.original_send_to_editor = window.send_to_editor;
   	 window.send_to_editor = function(html){
        if (formfield) {
            var fileurl = jQuery('img',html).attr('src');
			if(!fileurl){
			var if_url_not_set;
			if_url_not_set="<a>"+html+"</a>"
			fileurl=jQuery('img',if_url_not_set).attr('src');
			}
            formfield.val(fileurl);
			add_upload(upload_id_x_for);
            tb_remove();
        } else {
            window.original_send_to_editor(html);
        }
        formfield=null;
		
    };
    
			

function narek(upload_id_x)
{
		upload_id_x_for=upload_id_x;
        formfield = jQuery("#upload_href_"+upload_id_x).parent().find(".text_input");
        tb_show('', 'media-upload.php?type=image&TB_iframe=true');
        jQuery('#TB_overlay,#TB_closeWindowButton').bind("click",function(){formfield=null; upload_id_x_for=null;});
		
        return false;

	
}

function referesh_created_tags()
{
	var lists="";
	document.getElementById('uploadded_images_list').value='';
	for(i=0;i<count_of_elements;i++)
	{
		if(document.getElementById('image_no_'+i)){
		if(document.getElementById('uploadded_images_list').value)
		document.getElementById('uploadded_images_list').value=document.getElementById('uploadded_images_list').value+";;;"+document.getElementById('image_no_'+i).value;
		else
		document.getElementById('uploadded_images_list').value=document.getElementById('image_no_'+i).value;
		}
	}
}



function create_images_tags()
{
	document.getElementById("added_images").innerHTML="";
	for(i=0;i<count_of_elements;i++)
	{
		
		if(document.getElementById('image_no_'+i)){					
			var a_for_image = document.createElement('a');
			a_for_image.setAttribute('href',document.getElementById('image_no_'+i).value);
			a_for_image.setAttribute('target',"_blank");
			var img_for_image=document.createElement('img');
			img_for_image.setAttribute('src',document.getElementById('image_no_'+i).value);
			img_for_image.setAttribute('style','max-height: 50px; max-width: 50px; margin: 8px');
			a_for_image.appendChild(img_for_image);
			document.getElementById('added_images').appendChild(a_for_image);
		}
	
	}
}

</script>
<table>
<tr>
<td id="img__uploads">
<div id="upload_div_1">
<input type="text"  id="image_no_1" onchange="add_upload('1');" class="text_input" style="width:200px;"><a id="upload_href_1" class="button lu_upload_button" onclick="narek('<?php echo $i+1; ?>')">Select</a><input type="button" value="X" title="Delete" onclick="remov_upload('1')" /><br>
</div>
</td>
<tr>
<td id="added_images">

</td>
</tr>

</table>

<input type="hidden" name="uploadded_images_list" id="uploadded_images_list" value="" />
<td>

</tr>
<tr><td colspan="2" style="width:500px;">
<?php echo $image_url_list; ?>
</td></tr>






<tr>
<td width="100" align="right" class="key">
Description:
</td>
<td>

<div id="main_editor"><div  style=" width:600px; text-align:left" id="poststuff">
<div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea"><?php the_editor

("","content","title" ); ?>
</div>
</div>
</div>

</td>
</tr>
<tr>
<tr>
<td width="100" align="right" class="key">
Parameters:</td>
<td>
<?php
?>

<script type="text/javascript">

parameters0['sel1']=new Array(<?php

$par=explode("	",$row->param);

for($k=0;$k<=count($par);$k++)
{
if(isset($par[$k]) and $par[$k]!='')
echo "'".addslashes(htmlspecialchars($par[$k]))."',";
}

?>'');

</script>

<div id="sel1">
<?php
$k=0;
while($k<1000)
{
if(isset($par[$k]) and $par[$k]!=''){
echo '<input type="text" style="width:200px;" id="inp_sel1_'.$k.'" value="'.htmlspecialchars($par[$k]).'" 

onChange="Add(\'sel1\')" /><input type="button" value="X" 

onClick="Remove('.$k.',\'sel1\');" /><br />';
$k++;
}
else{
echo '<input type="text" style="width:200px;" id="inp_sel1_'.$k.'" value="" onChange="Add(\'sel1\')" /><input 

type="button" value="X" onClick="Remove('.$k.',\'sel1\');" /><br 

/>';
$k=1000;
}
}
?>
</div><input type="hidden" name="param" id="hid_sel1" value="" />
</td>
</tr>

<tr>
<td width="100" align="right" class="key">
Order:
</td>
<td>
<select name="ordering" >
<?php
$count_ord=count($ord_elem);
for($i=0;$i<$count_ord;$i++)
{
?>
<option value="<?php echo $ord_elem[$i]->ordering  ?>"> <?php echo  $ord_elem[$i]->ordering." "; echo $ord_elem[$i]->name; ?></option>

<?php 
}
?>
<option value="<?php echo  $ord_elem[$i-1]->ordering+1; ?>"><?php echo  $ord_elem[$i-1]->ordering+1; ?> Last</option>
</select>


</td>
</tr>
<tr>
<td width="100" align="right" class="key">Published:</td>
<td>
	<input type="radio" name="published" id="published0" value="0" class="inputbox">
	<label for="published0">No</label>
	<input type="radio" name="published" id="published1" value="1" checked="checked" class="inputbox">
	<label for="published1">Yes</label>
</td>
<?php

?>
</td>
</tr>
</table>
<input type="hidden" name="id"
value="<?php echo $row->id; ?>" />
<input type="hidden" name="task" value="" />
</form>
<?php








	
	
	
	
}









?>