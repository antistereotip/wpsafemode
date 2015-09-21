<?php
$title = 'DashBoard';
include_once 'header.php';
?>
    <div class="row">


        <!------------------------------- grid half screen ------------------------------------------------------------------->
        <div class="large-6 columns padd">







            <fieldset class="switch" tabindex="0">
                <form action="<?php echo $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']; ?>" method="post">
                    <?php
                    if(WP_DEBUG == true){
                        $wp_debug_checked = 'checked="checked"';
                    }else{
                        $wp_debug_checked = '';
                    }
                    if(AUTOMATIC_UPDATER_DISABLED == false){
                        $automatic_updater = 'checked="checked"';
                    }else{
                        $automatic_updater = '';
                    }
                    if(WP_AUTO_UPDATE_CORE == true){
                        $automatic_updater_core = 'checked="checked"';
                    }else{
                        $automatic_updater_core = '';
                    }
                    ?>
                    <h3>WP Debug</h3>
                    <input id="exampleCheckboxSwitch4" type="checkbox" value="on" name="wpdebug" <?php echo $wp_debug_checked; ?>>
                    <label for="exampleCheckboxSwitch4"></label> WP Debug<br />
                    <input id="exampleCheckboxSwitch5" type="checkbox" value="on" name="automatic_updater" <?php echo $automatic_updater; ?>>
                    <label for="exampleCheckboxSwitch5"></label> Automatic Updater<br />
                    <input id="exampleCheckboxSwitch6" type="checkbox" value="on" name="automatic_updater_core" <?php echo $automatic_updater_core; ?>>
                    <label for="exampleCheckboxSwitch6"></label> Automatic Updater Core<br />
            </fieldset>





            <fieldset>





                <?php
                if(isset($data['result']['active_plugins'])){
                    //echo $data['result']['active_plugins'][0]['option_value'];
                    $list = unserialize($data['result']['active_plugins'][0]['option_value']);
                    //print_r($list);
                    echo '<h3>WP Plugins</h3>';

                    foreach($list as $key=>$value){
                        echo '- '. $value ."<br/>";
                    }
                    echo '</p>';
                }
                ?>

                <form method="post" action="">
                    <?php $p =  unserialize($data['result']['active_plugins'][0]['option_value']);//print_r($p);
                    foreach($p as $key => $value): ?>
                        <input type="checkbox" name="plugins[]" value="<?php echo $key; ?>" <?php echo (in_array($key , $p))?'checked':''; ?>/> <?php echo $value; ?><br />
                    <?php endforeach; ?>
                    <input type="submit" name="update" value="update"  class="button"/>
                    <input type="submit" name="enableall" value="enable all"  class="button"/>
                    <input type="submit" name="disableall" value="disable all" class="button alert"/>
                    <input type="submit" name="revert" value="revert"  class="button"/>
                </form>




                <?php if(isset($data['result']['active_themes'])) {
                    $themes = $data['result']['active_themes'];
                    //print_r($themes);
                    echo '<h3>WP Theme</h3>';
                    foreach($themes as $theme){
                        echo '<label for="' . $theme['option_name'] . '"> Option Name: ' . $theme['option_name']. '</label>';
                        echo '<input type="text"  id="' . $theme['option_name'] . '" name="themes[' . $theme['option_name'] . ']" value="' . $theme['option_value'] . '"/> ' . "<br/>";
                    }
                    //print_r($list);
                } ?>




            </fieldset>







            <!-- ---------------- SUBMIT SAVE CONFIG ------------------------------->
            <input type=submit value="Save Config" name="saveconfig" class="button"/>


            </form>




        </div>
        <!------------------------------- end grid------------------------------------------------------------------->






        <!------------------------------- grid half screen ------------------------------------------------------------------->
        <div class="large-6 columns padd">
            <form>


                <fieldset>
                    <h3>General Info / Power  Bar</h3><p>
                        <img src="view/ui/assets/form/img/matrix-button2.png" width="100"></p>
                    <!--
	<div class="alert-box small" style="background-color: #12ACDF;border-color: #2BA6CF;padding:0.5%;">
	<strong><?php echo 'PHP ';?>
	</strong>
	<a href="#" class="close" style="color: #FFFFFF;">⊗</a>
	</div> -->
                    <div class="large-2 columns">
                        <div class="range-slider vertical-range" data-slider data-options="vertical: true;">
                            <span class="range-slider-handle" role="slider" tabindex="0"></span>
                            <span class="range-slider-active-segment"></span>
                            <input type="hidden">
                        </div>
                    </div>

                    <nav class="breadcrumbs" role="menubar" aria-label="breadcrumbs">
                        <li role="menuitem"><a href="#"><?php echo 'PHP ';?></a></li>
                        <li role="menuitem" class="unavailable" role="button" aria-disabled="true"><a href="#"><?php echo phpversion();?></a></li>

                    </nav><br />

                    <nav class="breadcrumbs" role="menubar" aria-label="breadcrumbs">
                        <li role="menuitem"><a href="#"><?php echo 'PTH ';?></a></li>
                        <li role="menuitem" class="unavailable" role="button" aria-disabled="true"><a href="#" style="text-transform: lowercase;"><?php echo $_SERVER['PHP_SELF'];?></a></li>

                    </nav><br />

                    <nav class="breadcrumbs" role="menubar" aria-label="breadcrumbs">
                        <li role="menuitem"><a href="#"><?php echo 'YIP ';?></a></li>
                        <li role="menuitem" class="unavailable" role="button" aria-disabled="true"><a href="#"><?php echo $_SERVER['REMOTE_ADDR'];?></a></li>

                    </nav><br />

                    <nav class="breadcrumbs" role="menubar" aria-label="breadcrumbs">
                        <li role="menuitem"><a href="#"><?php echo 'HIP ';?></a></li>
                        <li role="menuitem" class="unavailable" role="button" aria-disabled="true"><a href="#"><?php echo $_SERVER['SERVER_ADDR'];?></a></li>

                    </nav><br />

                    <nav class="breadcrumbs" role="menubar" aria-label="breadcrumbs">
                        <li role="menuitem"><a href="#"><?php echo 'HT ';?></a></li>
                        <li role="menuitem" class="unavailable" role="button" aria-disabled="true"><a href="#" style="text-transform: lowercase;"><?php echo $_SERVER['SERVER_NAME'];?></a></li>

                    </nav><br />
                    <nav class="breadcrumbs" role="menubar" aria-label="breadcrumbs">
                        <li role="menuitem"><a href="#"><?php echo 'SRV ';?></a></li>
                        <li role="menuitem" class="unavailable" role="button" aria-disabled="true"><a href="#" style="text-transform: lowercase;"><?php echo $_SERVER['SERVER_SOFTWARE'];?></a></li>

                    </nav><br />


                    <nav class="breadcrumbs" role="menubar" aria-label="breadcrumbs">
                        <li role="menuitem"><a href="#">RP</a></li>
                        <li role="menuitem" class="unavailable" role="button" aria-disabled="true"><a href="#" style="text-transform: lowercase;"><?php echo $_SERVER['REMOTE_PORT'];;?></a></li>

                    </nav><br />

                    <nav class="breadcrumbs" role="menubar" aria-label="breadcrumbs">
                        <li role="menuitem"><a href="#">PR</a></li>
                        <li role="menuitem" class="unavailable" role="button" aria-disabled="true"><a href="#" style="text-transform: lowercase;"><?php echo $_SERVER['SERVER_PORT'];;?></a></li>

                    </nav><br />




                    <?php /*
$indicesServer = array('PHP_SELF',
'argv',
'argc',
'GATEWAY_INTERFACE',
'SERVER_ADDR',
'SERVER_NAME',
'SERVER_SOFTWARE',
'SERVER_PROTOCOL',
'REQUEST_METHOD',
'REQUEST_TIME',
'REQUEST_TIME_FLOAT',
'QUERY_STRING',
'DOCUMENT_ROOT',
'HTTP_ACCEPT',
'HTTP_ACCEPT_CHARSET',
'HTTP_ACCEPT_ENCODING',
'HTTP_ACCEPT_LANGUAGE',
'HTTP_CONNECTION',
'HTTP_HOST',
'HTTP_REFERER',
'HTTP_USER_AGENT',
'HTTPS',
'REMOTE_ADDR',
'REMOTE_HOST',
'REMOTE_PORT',
'REMOTE_USER',
'REDIRECT_REMOTE_USER',
'SCRIPT_FILENAME',
'SERVER_ADMIN',
'SERVER_PORT',
'SERVER_SIGNATURE',
'PATH_TRANSLATED',
'SCRIPT_NAME',
'REQUEST_URI',
'PHP_AUTH_DIGEST',
'PHP_AUTH_USER',
'PHP_AUTH_PW',
'AUTH_TYPE',
'PATH_INFO',
'ORIG_PATH_INFO') ;

echo '<table cellpadding="10">' ;
foreach ($indicesServer as $arg) {
    if (isset($_SERVER[$arg])) {
        echo '<tr><td>'.$arg.'</td><td>' . $_SERVER[$arg] . '</td></tr>' ;
    }
    else {
        echo '<tr><td>'.$arg.'</td><td>-</td></tr>' ;
    }
}
echo '</table>' ; */
                    ?>

                </fieldset>

                <fieldset>

                    <h3><img src="view/ui/assets/form/img/lock.png" width="50"  height="50"></h3>
                    <p>It's not a Bug. It's a Feature !</p>
                    <p>Security As It's Best.<br />Take Full Control Of Your WordPress Installation  With SafeMode Own Tool.</p>
                </fieldset>


                <fieldset>
                    <h3><img src="view/ui/assets/form/img/wifi.png" width="50"></h3>

                    <p><blockquote>Software vulnerabilities that malware exploits usually already have fixes available by the time the virus reaches a computer.</p>
                    <p>The problem? The user simply failed to install the latest updates that would have prevented the infection in the first place.</blockquote></p>

                    <p>I've heard that instant messages through AOL/Yahoo/MSN can be read by hackers that "sniff" the messages leaving my network. Is this true?
                    </p>
                    <p><blockquote>
                        Yes.

                        It's actually true for all the data that comes and goes on your internet connection: web pages, emails, instant messaging conversations and more.

                        Most of the time it simply doesn't matter. Honest.

                        On the other hand, there are definitely times and situations when you really do need to be careful.

                        Data traveling on a network such as the internet can be seen by many other machines. Local machines connected via a hub, for example, all see the data being sent to and from all the other machines connected to the same hub. As the data travels across the internet, it actually travels across many devices each of which can "see" the data.

                        Sounds scary.

                        The good news is that's actually pretty hard to find data transmitted to and from a specific machine unless you're on the same network segment. For example, if you're connected to the internet via DSL, other machines sharing that DSL connection might watch your traffic, but random machines out on the internet would have an extremely difficult time tracking it down.

                        It's not something I worry about much at home.

                        However, there are scenarios that you should be very aware of.

                        "... if you're on the road you might simply wait until you're home to access sensitive sites like online banking or others."
                        Wireless access points operate much like a hub. Any wireless adapter within range can see all of the network traffic in the area. Visited any open (meaning not WPA-encrypted) wireless hotspots lately? Anyone in the coffee shop or library, or even just outside on the street or a nearby building, could be sniffing your traffic.
                        Hotel or other third-party provided internet connections are also vulnerable, since you have no idea what, or who, is sharing or watching your connection. It's possible that you're on a hub, and the room next door or down the hall could be watching your traffic, or it's possible that the hotel staff themselves are tapped into the internet traffic to and from all the rooms.
                        Landlord-provided internet connections, or those provided by or shared with a roommate or housemate fall into the same category: whomever set it up could very easily be watching the internet traffic going to and from the connection(s) that they provide you.
                        Your connection at work can also easily be monitored by your employer. In fact, the only difference between your employer and a hotel or landlord provided connection is that in most places the employer snooping on your use of their connection is legal, whereas the others typically are not.</blockquote></p>
                </fieldset>







            </form>
        </div>












        <!------------------------------- grid half screen ------------------------------------------------------------------->
        <div class="large-6 columns padd">

            <?php
            if(isset($data['result']['wp_options'])) {
                $options = $data['result']['wp_options'];
                echo '<h3>All WP Options</h3>';
                print_r($options);
            }
            ?>

        </div>


        <!------------------------------- grid half screen ------------------------------------------------------------------->
        <div class="large-6 columns padd">
            <form>

                <fieldset><h3><img src="view/ui/assets/form/img/network.png" width="50"  height="50"></h3><p><blockquote>
                        I know that backing up doesn’t feel like a “security” measure, but ultimately, it can be one of the most powerful ways to recover if you even encounter a security related issue.
                        Having a recent backup to restore to can quickly undo the damage done by almost any form of malware.
                        Having a back copy of your data (all your data) can help you recover after computer is lost or stolen (not to mention when a hard disk dies).
                        Backing up your email and contacts can be a critical way to restore your world should your online account ever be compromised.
                        Backups truly are the silver bullet of the computing world: a proper and recent backup can help save you from just about any disaster, including security issues.</blockquote></p>
                </fieldset>



            </form>
        </div>
    </div>














<?php include_once 'footer.php'; ?>
<?php
/**
 * Created by PhpStorm.
 * User: hightech
 * Date: 8/14/15
 * Time: 12:03 PM
 */