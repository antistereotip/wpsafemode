        <?php

        if(defined('WP_DEBUG') && WP_DEBUG == true){
            $wp_debug_checked = 'checked="checked"';
        }else{
            $wp_debug_checked = '';
        }
        if(defined('AUTOMATIC_UPDATER_DISABLED') && AUTOMATIC_UPDATER_DISABLED == true){
            $automatic_updater = 'checked="checked"';
        }else{
            $automatic_updater = '';
        }
        if(defined('WP_AUTO_UPDATE_CORE') && WP_AUTO_UPDATE_CORE == true){
            $automatic_updater_core = 'checked="checked"';
        }else{
            $automatic_updater_core = '';
        }

        ?>
<div class="row">
	<div class="columns text-left large-4">
    <div class="panel dashboard-panel ">
      <h3>WP Config</h3>
<form action="<?php echo $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']; ?>" method="post">
    <fieldset class="switch medium round" tabindex="0">

      
        <div class="row">
        <div class="columns small-6 item">
        <span data-tooltip aria-haspopup="true" class="has-tip" title="Turn on/off WP DEBUG"> WP Debug</span>
         </div>
         <div class="columns small-6 item text-right">
        <input id="checkbox-wpconfig-4" type="checkbox" value="on" name="wpdebug" <?php echo $wp_debug_checked; ?>>
        <label for="checkbox-wpconfig-4"></label>
        </div>
        </div>
         <div class="row">
        <div class="columns small-6 item">
        <span data-tooltip aria-haspopup="true" class="has-tip" title="Enable/Disable Automatic updates for plugins and themes">Automatic Updater</span>
        </div>
        <div class="columns small-6 item text-right">
        <input id="checkbox-wpconfig-5" type="checkbox" value="on" name="automatic_updater" <?php echo $automatic_updater; ?>>
        <label for="checkbox-wpconfig-5"></label>
        </div>
        </div>
         <div class="row">
         <div class="columns small-6 item">
        <span data-tooltip aria-haspopup="true" class="has-tip" title="Enable/Disable Automatic update for WordPress Core"> Core Automatic Updater</span>
         </div>
         <div class="columns small-6 item text-right">
        <input id="checkbox-wpconfig-6" type="checkbox" value="on" name="automatic_updater_core" <?php echo $automatic_updater_core; ?>>
       
        <label for="checkbox-wpconfig-6"></label>
        </div></div>
    </fieldset>

    <!-- ---------------- SUBMIT SAVE CONFIG ------------------------------->
    <input type=submit value="Save Config" name="saveconfig" class="button switch small round"/>
</form>
</div>
</div>
<div class="columns text-left large-4">
<!--<div class="panel dashboard-panel ">
</div>-->
</div>
<div class="columns text-left large-4">
<!--<div class="panel dashboard-panel ">
</div>-->
</div>
</div>