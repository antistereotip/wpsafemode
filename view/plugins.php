<?php

$active_plugins = array();
if(isset($data['plugins']['active_plugins']) && is_array($data['plugins']['active_plugins'])){
    $active_plugins = $data['plugins']['active_plugins'];
}

//echo '<pre>'.print_r($data['plugins']['all_plugins'],true).'</pre>';
?>

            <form method="post" action="">
            <div class="row">
              <div class="large-4 columns text-left">
              <div class="panel dashboard-panel ">
              <h3>Active WP Plugins From Database</h3>
                <?php
                if(isset($data['plugins']['all_plugins'])) {
                    $all_plugins = $data['plugins']['all_plugins'];
                   // $p =  unserialize($data['result']['active_plugins'][0]['option_value']);//print_r($p);
                    foreach($all_plugins as $key => $value): ?>
                        <?php
                        $checked = '';
                        if(in_array($key,$active_plugins)){
                        $checked = 'checked';

                        }
                        ?>
                   
                <label><input type="checkbox" class="plugins-checkbox" name="plugins[]" value="<?php echo $key; ?>" <?php echo $checked; ?>/> <span data-tooltip aria-haspopup="true" class="has-tip" data-options="disable_for_touch:true"  title="<?php echo $value['info']; ?>"> <?php echo $value['name']; ?> </span></label>
                    <?php endforeach; ?><br />
                 </div>
                 </div>
                 <div class="large-4 columns   text-left">
                 <div class="panel dashboard-panel ">
                    <label><input class="submit-plugins-action" type="radio" name="submit_plugins_action" value="enable_selected" checked/> Enable Selected</label>
                    <label><input class="submit-plugins-action"  type="radio" name="submit_plugins_action" value="enable_all" /> Enable All</label>
                    <label><input class="submit-plugins-action"  type="radio" name="submit_plugins_action" value="disable_all" /> Disable All</label>
                    <label><input  class="submit-plugins-action"  type="radio" name="submit_plugins_action" value="revert" /> Revert to initial state</label>
                    <label>
						<input type="checkbox" name="rebuild_plugins_backup" value="rebuild" /> Rebuild Plugins Backup Data
						<span class="error">warning: rebuilding plugins backup data will remove initial state of active plugins</span>
					</label>
                    <input type="submit" name="submit_plugins" value="Save Plugins Data"  class="button switch small round"/>
                  </div>
                  </div>
                   <div class="large-4 columns text-left">
                   	<!--<div class="panel dashboard-panel ">
                   </div>-->
                   </div>
           
                 </div>
                <?php }
                ?>
</form>

<?php /* ?>
<h3>WP Plugins From ScanDir</h3>
<form method="post" action="">
    <?php
    if(isset($data['result']['all_plugins_info'])) {
        $p =  $data['result']['all_plugins_info'];
        print_r($p);
        foreach($p as $key => $value): ?>
            <input type="checkbox" name="plugins[]" value="<?php echo $key; ?>" <?php echo (in_array($key , $p))?'checked':''; ?>/> <?php echo $value; ?><br />
        <?php endforeach; ?><br />

        <input id="exampleCheckboxSwitch4" type="submit" name="revert" value="revert"  class="button switch small round"/>
    <?php } else if(!isset($data['result']['all_plugins_info']))
    {
        print($msg);
    } ?>
</form>
<?php */ ?>

