<div class="purchase-page">
	<?php require_once "purchase_new_form.php" ?>

</div>
<script>
	function switch_purchase_form(new_form){

		if(new_form == "purchase_new"){
			$.ajax({
				type: "POST",
				url: root+"merchant/php/purchase_new_form.php",
				beforeSend: function(){
					$(".purchase-page").html(inner_content_loading);
				},
				success: function(response){
					$(".purchase-page").html(response);
				}
			})
		}
		if(new_form == "purchase_existing"){
			$.ajax({
				type: "POST",
				url: root+"merchant/php/purchase_existing_form.php",
				beforeSend: function(){
					$(".purchase-page").html(inner_content_loading);
				},
				success: function(response){
					$(".purchase-page").html(response);
				}
			})
		}
	}
</script>