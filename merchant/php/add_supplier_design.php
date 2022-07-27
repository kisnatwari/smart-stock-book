<div class="animate__animated animate__fadeIn animate_faster d-flex justify-content-center align-items-center supplier-form-content w-100 position-absolute" style="width: 100%; min-height: 100vh; z-index: 20000; background-color: #130202DD;">

	<form class="supplier-form bg-transparent text-center">
		<div class="text-white mb-3">
			<i class="mdi mdi-close-circle-outline cp form-close-icn" style="font-size: 30px" ></i>
		</div>
		<h5 class="text-white mb-3">Add new Supplier</h5>
		<div class="card rounded-0" style="border-radius:  25px !important; max-width: 315px;">
			<div class="card-body">
				<div class="input-group border-bottom bor-1 text-1 mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text pe-0 bg-white border-0">
							<i class="mdi mdi-account text-1"></i>
						</span>
					</div>
					<input type="text" name="supplier_name" class="form-control border-0 shadow-none text-1" placeholder="Supplier Name Here">
				</div>
				<div class="input-group border-bottom bor-1 text-1 mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text pe-0 bg-white border-0">
							<i class="mdi mdi-email text-1"></i>
						</span>
					</div>
					<input type="email" name="supplier_email" class="form-control border-0 shadow-none text-1" placeholder="Supplier Email Here">
				</div>
				<div class="input-group border-bottom bor-1 text-1 mb-2">
					<div class="input-group-prepend">
						<span class="input-group-text pe-0 bg-white border-0">
							<i class="mdi mdi-phone text-1"></i>
						</span>
					</div>
					<input type="text" name="supplier_contact" class="form-control border-0 shadow-none pw-field text-1" placeholder="Supplier Contact Here">
				</div>
				<div class="input-group border-bottom bor-1 text-1 mb-2">
					<div class="input-group-prepend">
						<span class="input-group-text pe-0 bg-white border-0">
							<i class="mdi mdi-map-marker text-1"></i>
						</span>
					</div>
					<input type="text" name="supplier_address" class="form-control border-0 shadow-none pw-field text-1" placeholder="Supplier Address">
				</div>
				<button id="save-supplier-btn" type="submit" class="bg-white bor-1 text-1 my-2 py-1 px-4 btn-sm" style="border-radius: 18px"><b><i class="mdi mdi-account-plus"></i> &nbsp;Save Supplier</b></button>
				<div class="supplier-caption"></div>
			</div>
		</div>
	</form>
	<script>
		
		$(".form-close-icn").click(function(){
			$(".supplier-form-content").addClass("animate__fadeOut");
			setTimeout(function(){
				$(".main-content").removeClass("position-fixed");
				$(".supplier-form-content").remove();}, 500);
		})
		var form_submitted = false;
		$(".supplier-form").submit(function(e){
			e.preventDefault();
			if(form_submitted)
				return;
			$.ajax({
				type: "POST",
				url: root+"merchant/php/save_supplier.php",
				data: new FormData(this),
				processData: false,
				contentType: false,
				beforeSend: function(){
					form_submitted = true;
					$(".supplier-caption").html("<p class='text-1'><i class='fa fa-spin fa-spinner'></i> &nbsp;Saving supplier... <br> Please Wait!!!</p>");
				},
				success: function(response){
					console.log(response);
					form_submitted = false;
					if(response["status"] == "success"){
						$(".supplier-caption").html("<p class='text-1'><i class='fa fa-check'></i> &nbsp;Supplier saved successfully</p>");
						<?php 
							if(!empty($_POST) && !empty($_POST["reload_on_success"])){
								echo "show_suppliers();";
							}
							else{
								echo "load_suppliers_purchse();";
							}
						 ?>
						setTimeout(function(){
							$(".supplier-caption").html("");
						},3000);
					}
					else if(response["status"] == "error"){
						$(".supplier-caption").html("<p class='text-3'><i class='fas fa-times'></i> &nbsp;"+response["message"]+"..</p>");
						setTimeout(function(){
							$(".supplier-caption").html("");
						},4500);
					}
					else
						$(".supplier-caption").html("");
				}
			})
		})
	</script>
</div>