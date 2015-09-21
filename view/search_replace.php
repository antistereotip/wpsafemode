<?php

?>
<form action="" method="post">
 <div class="row">
  <div class="large-4 columns text-left">
    <div class="panel dashboard-panel ">
     <h3>Search and Replace in your database</h3>
    
      <div class="row">
   	    <div class="columns large-12">
     	 <label for="term">Search Term</label>
   		 <input type="text" name="term" id="term"/>   		
   	    </div>
      </div>
      <div class="row">
   	    <div class="columns large-12">     	
   		 <input type="submit" name="submit_search_replace" class="button switch small round" value="Search"/>   		
   	    </div>
      </div>
      <a href="#" class="advanced-toggle" rel="advanced-search">Advanced</a> 
      <div id="advanced-search" class="advanced-panel">
      <div class="row">
   	    <div class="columns large-12">   
   	    <label for="search-criteria-term"><b>Find values: </b></label>  	
   	     <label for="search-criteria-term-1">
   		 <input type="radio" name="search_criteria_term" id="search-criteria-term-1" value="contains" checked/>   	
   		   That contain term</label>  	
   		  <label for="search-criteria-term-2">
   		 <input type="radio" name="search_criteria_term" id="search-criteria-term-2" value="exact"/>   	
   		  That have exact term </label>  	
   		 <label for="search-criteria-term-3">
   		<input type="radio" name="search_criteria_term" id="search-criteria-term-3" value="any"/>   	
   		 That have any of the terms 
   		 </label>  	
   	    </div>
      </div> 
    <div class="row">
     <div class="columns large-12"> 
      <label for="search-criteria-1"><b>Search through:</b></label>  
         <label><input type='radio' name='search_criteria_db' class="search-criteria-db" value='full' checked> Full Database</label>
	     <label><input type='radio' name='search_criteria_db' class="search-criteria-db" value='partial'> Only selected tables</label>
       </div>
    </div>
       <div class="row">
   	    <div class="columns large-11 large-offset-1">        
          <div class="tables-list">
		<?php foreach($data['tables'] as $table): ?>
		    <label><input type='checkbox' name='search_tables_list[]' value='<?php echo $table; ?>' /> <?php echo $table; ?></label>
		<?php endforeach; ?>
   	    </div>
      </div> 		
	</div>
     	
      </div>
    </div>
  </div>
 </div>
</form>
