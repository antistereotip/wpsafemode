<?php


//echo '<pre>'.print_r($data,true).'</pre>';
foreach($data['themes']['active_theme'] as $active_theme){
   if($active_theme['option_name'] == 'stylesheet'){
       $current_theme = $active_theme['option_value'];
   }
    if($active_theme['option_name'] == 'template'){
        $theme_template = $active_theme['option_value'];
    }

}

?>
    <div class="row">
        <!------------------------------- grid half screen ------------------------------------------------------------------->
        <div class="large-4 columns">
        <div class="panel dashboard-panel ">
   
            <h3> Set Current Theme</h3>
              <p>This App does not have Super Cow Powers.</p> 
            <form method="post" action="">  
                <?php
                //print_r($data['themes']['all_themes']);
                foreach($data['themes']['all_themes'] as $key => $value):
                    $checked = ($key == $current_theme)?'checked':'';
                    $current = ($key == $current_theme)?' (current theme)':'';
                    echo '<label><input type="radio" name="active_theme" value="'.$key.'" '.$checked .'/> '.$value['theme_name']. $current . '</label>'; //close your tags!!
                endforeach;
                echo '<label><input type="radio" name="active_theme" value="downloadsafe"/> Download Twenty Fifteen (this will download and activate clean theme from wordpress.org)</label>'; //close your tags!!
                ?>
             <input type="submit" name="submit_themes" class="button switch small round" value="Save Current Theme"/>
            </form>
        </div>
        </div>
        <div class="large-4 columns">
       <!--<div class="panel dashboard-panel ">
        </div>-->
        </div>
        <div class="large-4 columns">
       <!-- <div class="panel dashboard-panel ">
        </div>-->
        </div>
    </div>














