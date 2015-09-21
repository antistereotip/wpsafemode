<?php

?>


<div class="row">
	<div class="large-4 columns  text-left">
	<div class="panel dashboard-panel ">
	<h3>Backup Site Directory</h3>
	<form method='post' id='' action=''>
	
	<input type='submit' class='button switch small round backup-files-submit' name="submit_backup_files" value="Backup Files"> 
	</form>		
	</div>
	</div>
	<div class="large-4 columns text-left">
	   <?php if(isset($data['backups']) && isset($data['backups']['full']) && count($data['backups']['full']) > 0): ?>
	<div class="panel dashboard-panel ">
	
	<h3>Existing Files backup</h3>
	 
	    <div class="row">
			<div class="columns text-left large-12">
				<h5>Full Site Backups</h5>
				<?php foreach($data['backups']['full'] as $file_backup): ?>
				<a href="<?php echo $data['script_url']; ?>&download=sitefiles&filename=<?php echo basename($file_backup); ?>" target="_blank"><?php echo basename($file_backup); ?></a><br/><br/>
				 <?php endforeach; ?>
			</div>
		</div>
		
	</div>
	<?php endif; ?>	
	</div>
	<div class="large-4 columns text-left">
	<!--<div class="panel dashboard-panel ">
	</div>-->
	</div>
</div>