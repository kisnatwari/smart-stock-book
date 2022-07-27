<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	$categories = $db -> where("categories", "merchant_id", "=", $_SESSION['logged_in_merchant']);
	if(count($categories) == 0){
	?>

	<div class="text-center py-5 animate__animated animate__fadeIn animate_faster">
		<div class="mt-5 bg-white sh-lg rounded p-5 pb-3 d-inline-block text-1">
			<p class="border-0 border-bottom bor-1 pb-3" style="border-bottom-style: dashed !important;"><b>No categories has been Found!! <br> Please create a list of product categories <br> in order to get started with Products</b> <br><p>
		</div>
	</div>
	<?php
	die;
	}
 ?>
<!-- If at least one category has been found, than continue -->
<div class="animate__animated animate__fadeIn animate_faster" style="width: fit-content;">
	<div class="d-flex justify-content-end">
		<button class="btn btn-sm border bor-2 text-2 my-3 shadow-none" style="border-radius: 20px;" onclick="switch_purchase_form('purchase_existing')"><b> <i class="mdi mdi-update"></i> Update Existing Product</b></button>
	</div>
	<form class="add-stock-form w-100">
		<div class="row purchase-container bg-white p-3 sh rounded" style="max-width: 960px;">
			<div class="col-md-4 product-img-section">
				<div class="img-con mx-auto rounded-circle sh-lg border d-flex overflow-hidden justify-content-center align-items-center position-relative" style="width: 140px; height: 140px;">
					<b class="text-center text-1 img-caption">Upload <br> Product <br> Image</b>
					<input type="file" name="product-img" class="position-absolute w-100 h-100 cp product-img" onchange="change_img(this)" style="opacity: 0; top: 0; left: 0;">
				</div>
			</div>
			<div class="col-md-8">
				<div class="form-group my-2">
					<label for="supplier-name">Name of a Supplier </label>
					<div class="input-group">
						<select name="supplier-name" id="supplier-name" class="form-control shadow-none">
							<option value="random">Random Supplier</option>
						</select>
						<div class="input-group-append">
							<span for="supplier-name" class="input-group-text add-supplier-icn cp bg-transparent border-0 ">
								&nbsp;<i class="fa fa-plus"></i>&nbsp; 
							</span>
						</div>
					</div>
				</div>
				<div class="form-group my-2">
					<label for="product-name">Name of a poduct <span class="text-4">*</span></label>
						<input type="text" class="form-control shadow-none border" required name="product-name" id="product-name" placeholder="New Product Name here">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group my-2">
					<label for="">Product Category</label>
					<select type="text" class="form-control shadow-none border purchase-category" name="category">
						<?php 
						foreach($categories as $category){
							echo "<option value='".$category["id"]."'>".$category["category"]."</option>";
						}
						 ?>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group my-2">
					<label for="">Product Brand</label>
					<select type="text" class="form-control shadow-none border purchase-brand" name="brand">
						<option value="default">No Brand</option>
					</select>
				</div>
			</div>
			<div class="col-sm-6 col-lg-4">
				<div class="form-group my-2">
					<label for="qty">Quantity</label>
					<input type="number" id="qty" class="form-control shadow-none border" min="1" value="1" name="qty">
				</div>
			</div>
			<div class="col-sm-6 col-lg-4">
				<div class="form-group my-2">
					<label for="unit-price">Cost Price <sub>(per unit)</sub></label>
					<input type="number" id="unit-price" class="form-control shadow-none border" name="unit-price" min = "1">
				</div>
			</div>
			<div class="col-sm-6 col-lg-4">
				<div class="form-group my-2">
					<label for="total-price">Total Price</label>
					<input type="number" id="total-price" class="form-control shadow-none border disabled" disabled min="1" name="total-price">
				</div>
			</div>
			<div class="col-sm-6 col-lg-4">
				<div class="form-group my-2">
					<label for="paid-price">Paid Amount</label>
					<input id="paid-price" type="number" class="form-control shadow-none border" min="1" name="paid-price">
				</div>
			</div>
			<div class="col-sm-6 col-lg-4">
				<div class="form-group my-2">
					<label for="marked-price">Marked Price <sub>(per unit)</sub></label>
					<input type="number" id="marked-price" class="form-control shadow-none border" min="0" name="marked-price" placeholder="Marked price per unit">
				</div>
			</div>
			
			<div class="col-12">
				<div class="form-group my-2">
					<label for="description-area">Product Description</label>
					<textarea id="description-area" class="form-control shadow-none border" style="height: 100px; overflow: auto;" name="description" placeholder="Write product description here if you need it later"></textarea>
				</div>
			</div>
			<div class="col-12">
				<div class="form-group my-2">
					<label for="remarks-area">Remarks <sub>(If any)</sub></label>
					<textarea id="remarks-area" class="form-control shadow-none border" style="height: 80px; overflow: auto;" name="remarks" placeholder="If you have any remarks regarding product, Write here"></textarea>
				</div>
			</div>
			<div class="col-12">
				<h4 class="stock-create-caption"></h4>
				<button type="submit" class="new-stock-submit btn bg-1 text-white"> <i class="fa fa-plus-circle"></i> &nbsp;Add Stock</button>
			</div>
		</div>
	</form>
	<script>

		//set product image as background image
		var img_set = false;
		function change_img(event){
			var file = event.files[0];
			if(file == undefined){
		    	img_set =  false;
		    	$(".img-con").css("background", "none");
		    	$(".img-caption").html("<b>Upload <br> Product <br> Image</b>");
		    	return;
		    }
			var size = file.size/1024;
			if(size <= 1024){
				var filename = file.name.split('.').slice(0, -1).join('.');
				if($("#product-name").val().trim() == "")
					$("#product-name").val(filename);
				$(".img-caption").html("");
				var reader = new FileReader();
		        reader.addEventListener("load", function() {
		            $(".img-con").css({
		                "background-image": `URL("` + reader.result + `")`,
		                "background-repeat": "no-repeat",
		                "background-position": "center",
		                "background-size": "contain"
		            })
		            img_set = true;
		        }, false);
		        reader.readAsDataURL(file);
		    }
		    else{
		    	img_set =  false;
		    	$(".img-con").css("background", "none");
		    	$(".img-caption").html("<span class='text-4 animate__animated animate__fadeInUp'><b>Upload <br> photo <br> Less than <br> 1 MB</b></span>");
		    	setTimeout(() =>{
		    		if(!img_set)
		    			(".img-caption").html("<b>Upload <br> Product <br> Image</b>")
		    		} , 5000);
		    }
		}

		//load brands on change of category
		get_brands();
		document.querySelector(".purchase-category").onchange = get_brands;
		function get_brands(){
			var category = $(".purchase-category").val();
			$.ajax({
				type: "POST",
				data: {
					category : category
				},
				url: root+"merchant/php/get_brands.php",
				success: function(response){
					$(".purchase-brand").html('<option value="default">No Brand</option>');
					if(response.status == "brands"){
						$(response["message"]).each(function(){
							$(".purchase-brand").append(`<option value="${this["id"]}">${this["brand"]}</option>`);
						})
						document.querySelector(".purchase-brand").selectedIndex = document.querySelectorAll(".purchase-brand option").length-1;
					}
				}
			})
		}


		//calculate total price on change of qty and unit price
		$(document).ready(function(){
			$("#qty").on("input", function(){
				if($("#unit-price").val()){
					var qty = Number($(this).val());
					var unit_price = Number($("#unit-price").val());
					$("#total-price").val(qty * unit_price);
					$("#paid-price").val(qty * unit_price);
				}
			})
			$("#unit-price").on("input", function(){
				if($("#qty").val()){
					var unit_price = Number($(this).val());
					var qty = Number($("#qty").val());
					$("#total-price").val(qty * unit_price);
					$("#paid-price").val(qty * unit_price);
				}
			})
		})

		//submitting form
		var form_submit = false;
		$(document).ready(function(){
			$(".add-stock-form").submit(function(e){
				e.preventDefault();
				if(form_submit)
					return;
				form_submit = true;
				$.ajax({
					type: "POST",
					url: root+"merchant/php/add_new_stock.php",
					data: new FormData(this),
					processData: false,
					contentType: false,
					beforeSend: function(){
						$(".new-stock-submit").attr("disabled", true);
						$(".stock-create-caption").html(" <i class='fa fa-spinner fa-spin'></i>  Adding Stock... Please Wait");
					},
					success: function(response){
						console.log(response);
						form_submit = false;
						$(".new-stock-submit").removeAttr("disabled");
						if(response["status"] == "success")
							$(".stock-create-caption").html(' <i class="fa fa-check"></i> Product Added Successfully');
						else if(response["status"] == "error")
							$(".stock-create-caption").html(' <span class="text-3"><i class="fa fa-times"></i> '+response["message"]+' </span>');
						setTimeout(() => {
							$(".stock-create-caption").html('');
							get_brands();
						}, 7000);
						$(".add-stock-form").trigger("reset");
						$(".product-img-section").html(`
								<div class="img-con mx-auto rounded-circle sh-lg border d-flex overflow-hidden justify-content-center align-items-center position-relative" style="width: 140px; height: 140px;">
									<b class="text-center text-1 img-caption">Upload <br> Product <br> Image</b>
									<input type="file" name="product-img" class="position-absolute w-100 h-100 cp product-img" onchange="change_img(this)" style="opacity: 0; top: 0; left: 0;">
								</div>
						`);
					}
				})
			})
		})

		//add supplier on clicking on icon
		$(".add-supplier-icn").click(function(){
			$.ajax({
				type: "POST",
				url: root+"merchant/php/add_supplier_design.php",
				success: function(response){
					$(".main-content").addClass("position-fixed");
					$(".external-content").html(response);
				}
			})
		})

		load_suppliers_purchse();
		function load_suppliers_purchse(){
			$.ajax({
				type: "POST",
				url: root+"merchant/php/load_suppliers.php",
				success: function(response){
					if(response["status"] == "success"){
						$("#supplier-name").html(" ");
						var suppliers = response["message"];
						$(suppliers).each(function(){
							$("#supplier-name").append('<option value="'+this['id']+'">'+this['name']+'</option>');
						})
						$("#supplier-name").append('<option value="random">Random Supplier</option>');
						document.querySelector("#supplier-name").selectedIndex = document.querySelectorAll("#supplier-name option").length-2;
					}
				}
			})
		}

	</script>
</div>