  <li class="<?php echo (isset($data['current_page']) && $data['current_page'] == 'wpconfig')?'active':''; ?>">
  <a class="icons icon-config" href="?view=wpconfig">Configuration File</a>
  </li>
  <li class="<?php echo (isset($data['current_page']) && $data['current_page'] == 'themes')?'active':''; ?>">
  <a class="icons icon-themes" href="?view=themes">Themes</a></li>
  <li class="<?php echo (isset($data['current_page']) && $data['current_page'] == 'plugins')?'active':''; ?>">
  <a class="icons icon-plugins" href="?view=plugins">Plugins</a></li>
  <li class="<?php echo (isset($data['current_page']) && $data['current_page'] == 'backup_files')?'active':''; ?>">
  <a  class="icons icon-backup-files" href="?view=backup_files">Backup Files</a></li>
  <li class="<?php echo (isset($data['current_page']) && $data['current_page'] == 'backup_database')?'active':''; ?>">
  <a  class="icons icon-backup-database" href="?view=backup_database">Backup Database</a></li>
 <li class="inactive"><a  class="icons icon-config" href="#"><span data-tooltip aria-haspopup="true" class="has-tip" data-options="disable_for_touch:true"  title="Coming Soon">.Htaccess Settings</span></a></li>
 <li class="inactive"><a  class="icons icon-themes" href="#"><span data-tooltip aria-haspopup="true" class="has-tip" data-options="disable_for_touch:true"  title="Coming Soon">Website Migration</span></a></li>
 <li class="inactive"><a  class="icons icon-backup-files" href="#"><span data-tooltip aria-haspopup="true" class="has-tip" data-options="disable_for_touch:true"  title="Coming Soon">Autobackup</span></a></li>
 <li class="inactive"><a  class="icons icon-plugins" href="#"><span data-tooltip aria-haspopup="true" class="has-tip" data-options="disable_for_touch:true"  title="Coming Soon">Search and Replace</span></a></li>