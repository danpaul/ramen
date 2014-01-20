<form action="<?php echo $GLOBALS['config']['site_root_url'].'/admin/upload' ?>"
      class="dropzone"
      id="my-awesome-dropzone">
</form>

<script src="<?php echo $GLOBALS['config']['assets_root_url']. '/js/vendor/dropzone.js' ?>"></script>

<script type="text/javascript">
	
	Dropzone.options.myAwesomeDropzone = {
	  init: function() {
	    this.on("addedfile", function(file) {
	    	$('#add_product_form').append(
	    		'<input type="hidden" name=uploads[] value="' + file.name + '">'
	    	);
	    });
	  }
	};

</script>