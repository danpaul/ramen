<form action="<?php echo $GLOBALS['config']['site_root_url'].'/admin/upload' ?>"
      class="dropzone"
      id="my-awesome-dropzone">
</form>

<script src="<?php echo $GLOBALS['config']['assets_root_url']. '/js/vendor/dropzone.js' ?>"></script>

<script type="text/javascript">
	
	Dropzone.options.myAwesomeDropzone = {
	  init: function() {
	    this.on("addedfile", function(file) {

	    });
	    this.on("success", function(file, response){
	    	response = $.parseJSON(response);
	    	if(response !== false){
		    	$('#add_product_form').append(
		    		'<input type="hidden" name=uploads[] value="' + response + '">'
		    	);	    		
	    	}else{
	    		console.log('ERROR uploading image.')
	    	}
	    });
	  }
	};

</script>