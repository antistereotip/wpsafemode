<?php
/**
 * Created by PhpStorm.
 * User: hightech
 * Date: 8/20/15
 * Time: 1:49 PM
 */
?>
<form method='post' id='' action=''>
<input type='checkbox' name='backup_database' value='1'> Backup Database <br>
<input type='checkbox' name='backup_all_files' value='1'> Backup All WP Files <br>
<input type='submit' class='buttons' name="submit_backup" value="Backup"> </form>
<?php
if (isset($_POST['backup_all_files'])) {

    print_r(glob("/home/devcloud/public_html/hightech/wordpress-safe-mode-script/sfstore/*.zip"));

}
