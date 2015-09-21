<?php

?>
<div class="row">
	<div class="large-4 columns text-left">
	<div class="panel dashboard-panel ">
	<h3>Backup Database</h3>
	<form method='post' id='' action=''>
	<label><input type='radio' name='backup_database_type' class="backup-type" value='full' checked> Backup Full Database</label>
	<label><input type='radio' name='backup_database_type' class="backup-type" value='partial'> Backup Partially</label>

	<div class="tables-list">
		<?php foreach($data['tables'] as $table): ?>
		<label><input type='checkbox' name='backup_tables_list[]' value='<?php echo $table; ?>' /> <?php echo $table; ?></label>
		<?php endforeach; ?>
		
	<label><input type='checkbox' name='backup_tables_type[]' value='sql' checked/> Export Tables in SQL format</label>
	<label><input type='checkbox' name='backup_tables_type[]' value='csv' /> Export Tables in CSV format</label>	
	</div>

	<label><input type='checkbox' name='backup_archive' value='1' checked /> Archive Backup</label>


	<input type='submit' class='button switch small round' name="submit_backup_database" value="Backup Database Data"> 
	</form>		
	</div>
	</div>
	<div class="large-4 columns text-left">
	<div class="panel dashboard-panel ">
	<h3>Existing Database backup</h3>
	    <?php if(isset($data['backups']) && isset($data['backups']['database']) && count($data['backups']['database']) > 0): ?>
	    <div class="row">
			<div class="columns text-left large-12">
				<h5>Full Database Backups</h5>
				<?php foreach($data['backups']['database'] as $file_backup): ?>
				<a href="<?php echo $data['script_url']; ?>&download=database&filename=<?php echo basename($file_backup); ?>" target="_blank"><?php echo basename($file_backup); ?></a><br/><br/>
				 <?php endforeach; ?>
			</div>
		</div>
		<?php endif; ?>		
	    <?php if(isset($data['backups']) && isset($data['backups']['tables']) && count($data['backups']['tables']) > 0): ?>
	    <div class="row">
			<div class="columns text-left large-12">
				<h5>Partial / Table Backups in sql format</h5>
				<?php foreach($data['backups']['tables'] as $file_backup): ?>
				<a href="<?php echo $data['script_url']; ?>&download=database&filename=<?php echo basename($file_backup); ?>" target="_blank"><?php echo basename($file_backup); ?></a><br/>
				 <?php endforeach; ?>
			</div>
		</div>
		<?php endif; ?>	
					    <?php if(isset($data['backups']) && isset($data['backups']['csv']) && count($data['backups']['csv']) > 0): ?>
	    <div class="row">
			<div class="columns text-left large-12">
				<h5>Partial / Table Backups in csv format</h5>
				<?php foreach($data['backups']['csv'] as $file_backup): ?>
				<a href="<?php echo $data['script_url']; ?>&download=database&filename=<?php echo basename($file_backup); ?>" target="_blank"><?php echo basename($file_backup); ?></a><br/>
				 <?php endforeach; ?>
			</div>
		</div>
		<?php endif; ?>	
		
	</div>
	</div>
	<div class="large-4 columns text-left">
	<!--<div class="panel dashboard-panel ">	
	</div>-->
	</div>
</div>




