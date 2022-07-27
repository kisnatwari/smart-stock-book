<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
 ?>
<div class="animate__animated animate__fadeIn animate_faster" style="width: fit-content;">
	<div class="d-flex justify-content-end">
		<button class="btn btn-sm border bor-2 text-2 my-3 shadow-none" style="border-radius: 20px;" onclick="switch_purchase_form('purchase_new')"><b> <i class="fa  fa-plus"></i> &nbsp; Create New Product</b></button>
	</div>
	<?php 
	$merchant_id = $_SESSION['logged_in_merchant'];
	//$products = $db -> where("products", "user_id", "=", $_SESSION['logged_in_id']);
	$stmt = $pdo -> prepare("SELECT `id`, `name` FROM products WHERE merchant_id = $merchant_id");
	$stmt -> execute();
	$products = $stmt -> fetchAll();
	if($stmt -> rowCount() > 0){
	 ?>
	<form class="update-stock-form">
		<div class="purchase-container bg-white p-3 sh rounded" style="max-width: 960px;">

			 <h4><label for="product-name">Choose Product to be purchased</label></h4>
			<select name="product-name" id="product-name" class="form-control mb-4 shadow-none border" onchange="get_product_data(this.value)">
				<?php 
					foreach($products as $product){
						echo "<option value = '".$product["id"]."' >".$product["name"]."</option>";
					}
				 ?>
			</select>
			<h3 class="product-loading text-center"><i class="fa fa-spinner fa-spin"></i> <br>Getting Product data <br> Please wait<br>-----</h3>
			<div class="row update-content d-none">
				<div class="col-12 py-3">
					<div class="img-con rounded mx-auto sh-lg border d-flex overflow-hidden justify-content-center align-items-center position-relative" style="width: 160px; height: 160px;">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group my-2">
						<div class="form-group my-2">
							<label for="qty">Quantity</label>
							<input type="number" id="qty" class="form-control shadow-none border" min="1"  name="qty">
						</div>	
					</div>
				</div>
				<div class="col-md-8">
					<div class="form-group my-2">
						<label for="supplier-name">Name of a Supplier </label>
						<div class="input-group">
							<select name="supplier-name" id="supplier-name" class="form-control shadow-none">
								<?php  ?>
								<option value="random">Default Supplier</option>
							</select>
							<div class="input-group-append">
								<span for="supplier-name" class="input-group-text add-supplier-icn cp bg-transparent border-0 ">
									&nbsp;<i class="fa fa-plus"></i>&nbsp; 
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-lg-4 col-xl-3">
					<div class="form-group my-2">
						<label for="unit-price">Cost Price <sub>(per unit)</sub> </label>
						<input type="number" id="unit-price" class="form-control shadow-none border" min="1" value="1000" name="unit-price">
					</div>
				</div>
				<div class="col-sm-6 col-lg-4 col-xl-3">
					<div class="form-group my-2">
						<label for="total-price">Total Price</label>
						<input type="number" id="total-price" class="form-control shadow-none border disabled" disabled min="1" name="total-price">
					</div>
				</div>
				<div class="col-sm-6 col-lg-4 col-xl-3">
					<div class="form-group my-2">
						<label for="paid-price">Paid Amount</label>
						<input id="paid-price" type="number" class="form-control shadow-none border" min="1" name="paid-price">
					</div>
				</div>
				<div class="col-sm-6 col-lg-4 col-xl-3">
					<div class="form-group my-2">
						<label for="marked-price">Marked Price <sub>(per unit)</sub></label>
						<input type="number" id="marked-price" class="form-control shadow-none border" min="0" name="marked-price" placeholder="Marked price per unit">
					</div>
				</div>
				<div class="col-12">
					<div class="form-group my-2">
						<label for="remarks-area">Remarks <sub>(If any)</sub></label>
						<textarea id="remarks" class="form-control shadow-none border" style="height: 80px; overflow: auto;" name="remarks" placeholder="If you have any remarks regarding product, Write here"></textarea>
					</div>
				</div>
				<div class="col-12">
					<h4 class="stock-update-caption"></h4>
					<button type="submit" class="btn bg-1 text-white"> <i class="fa fa-plus-circle"></i> &nbsp;Update Stock</button>
				</div>
			</div>
		</div>
	</form>
	<script>

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
						$("#supplier-name option")[document.querySelectorAll("#supplier-name option").length-2].setAttribute("selected", "true");
					}
				}
			})
		}

		var product = $("#product-name").val();
		get_product_data(product);
		function get_product_data(product){
			$.ajax({
				type: "POST",
				data: {product: product},
				url: root+"merchant/php/get_product_details.php",
				success: function(response){
					console.log(response);
					if(response["status"] != "success")
						return;
					$(".product-loading").addClass("d-none");
					$(".update-content").removeClass("d-none");
					$(".img-con").css({
						'background-image': "url("+root+"merchant/stocks/"+response["message"]["photo"]+")",
						'background-size': "90% 90%",
						'background-position': "center",
						'background-repeat': 'no-repeat'
					})
					$("#supplier-name option[selected]").removeAttr("selected");
					$("#supplier-name option[value="+response["message"]["supplier_id"]+"]").attr("selected", true);
					$("#unit-price").val(response["message"]["unit_price"]);
					$("#marked-price").val(response["message"]["marked_price"]);
					if($("#qty").val() && $("#unit-price").val()){
						var unit_price = Number($("#unit-price").val());
						var qty = Number($("#qty").val());
						$("#total-price").val(qty * unit_price);
						$("#paid-price").val(qty * unit_price);
					}
				}
			})
		}

		var form_submit = false;
		$(".update-stock-form").submit(function(e){
			e.preventDefault();
			if(form_submit)		return;
			form_submit = true;
			$.ajax({
				type: "POST",
				url: root+"merchant/php/update_stock.php",
				data: new FormData(this),
				processData: false,
				contentType: false,
				cache: false,
				beforeSend: function(){
					$(".stock-update-caption").html("<i class='fa fa-spinner fa-spin text-0'></i> Updating product... Please wait..");
				},
				success: function(response){
					console.log(response);
					form_submit = false;
					if(response["status"] == "success")
						$(".stock-update-caption").html("<i class='fa fa-check text-1'></i> Stocks updated successfully");
					else
						$(".stock-update-caption").html("<i class='fa fa-times text-3'></i> Failed to update stocks...");
					$(".update-stock-form").trigger("reset");
					setTimeout(function(){
						$(".stock-update-caption").html("");
					},4000)
				}
			})
		})
	</script>
<?php } 
else{
	echo '<div class="bg-white px-3 sh-lg rounded">
	<h3 class=" py-2  product-loading text-center text-3 pb-3"><i class="fa fa-times m-3"></i> <br> No Products Found <br>Please add products firstly</h3> 
	</div> ';
}
?>
</div>