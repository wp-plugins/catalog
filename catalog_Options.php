<?php

if (function_exists('current_user_can'))
    if (!current_user_can('manage_options')) {
        die('Access Denied');
    }
if (!function_exists('current_user_can')) {
    die('Access Denied');
}


function wdcat_showGloballll($op_type = "0")
{
    global $wpdb;
    $lists = array();

    $query = "SELECT *  from " . $wpdb->prefix . "spidercatalog_params ";
    $rows = $wpdb->get_results($query);
    $param_values = array();
    foreach ($rows as $row) {
        $key = $row->name;
        $value = $row->value;
        $param_values[$key] = $value;
    }

    wdcat_html_showGlobal($param_values, $op_type);
}


function wdcat_showStyles($op_type = "0")
{
    global $wpdb;
    $query = "SELECT *  from " . $wpdb->prefix . "spidercatalog_params ";

    $rows = $wpdb->get_results($query);

    $param_values = array();
    foreach ($rows as $row) {
        $key = $row->name;
        $value = $row->value;
        $param_values[$key] = $value;
    }
    wdcat_html_showStyles($param_values, $op_type);
}


function wdcat_save_styles_options()
{

    global $wpdb;
    if (isset($_POST['params'])) {
      $params = $_POST['params'];
      foreach ($params as $key => $value) {
          $wpdb->update($wpdb->prefix . 'spidercatalog_params',
              array('value' => $value),
              array('name' => $key),
              array('%s')
          );
      }
      ?>
      <div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
      <?php
    }
}

function wdcat_save_global_options()
{

    global $wpdb;
    if (isset($_POST['params']))
        $params = $_POST['params'];
    foreach ($params as $key => $value) {
        $wpdb->update($wpdb->prefix . 'spidercatalog_params',
            array('value' => $value),
            array('name' => $key),
            array('%s')
        );
    }
    ?>
    <div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
<?php

}


?>
  