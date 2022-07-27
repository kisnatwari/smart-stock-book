<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	$products = $db -> where("products","merchant_id", "=", $_SESSION['logged_in_merchant']);
	$array = [];
	foreach($products as $product){
		$key = $product["id"];
		$array[$product["id"]] = [];
		$array[$key]["id"] = $product["id"];
		$array[$key]["name"] = $product["name"];
		$array[$key]["marked_price"] = $product["marked_price"];
		$array[$key]["available_stock"] = $product["available_stock"];
	}
	$data = json_encode($array);
	$customers = $db -> where("customers", "merchant_id", "=", $_SESSION["logged_in_merchant"]);
	$customer_array = [];
	foreach($customers as $customer)
		$customer_array[$customer["id"]] = $customer;
	echo "<script> var customers = ".json_encode($customer_array)." </script>";
	$stmt = $pdo -> prepare("SELECT * FROM merchants WHERE id = ?");
	$stmt -> execute([$_SESSION['logged_in_merchant']]);
	$merchant_data = $stmt -> fetch();
	?>
	<script>
		var products = <?php echo $data ?>;
		var counter = 1;
	</script>
	<div class="animate__animated animate__fadeIn animate_faster">
		<style>
			/* Chrome, Safari, Edge, Opera */
			select.sales-product.form-control,
			input::-webkit-outer-spin-button,
			input::-webkit-inner-spin-button {
			  -webkit-appearance: none !important;
			  margin: 0;
			  background:  white;
			}

			/* Firefox */
			input[type=number] {
			  -moz-appearance: textfield;
			}
			@media print{
				aside, .top-content, .add-row-btn, .remove-row-btn, .discount-option-card, .sales-header{
					display: none !important;
				}
				.content, body{
					padding: 0 !important;
					background-color: white;
					box-shadow: none !important;
				}
				section[data-sidebar-size="max"] .content {
					margin-left: 0;
				}
				section[data-sidebar-size="min"] .content {
					margin-left: 0;
				}
				section[data-sidebar-hide="true"] .content {
					margin-left: 0;
				}
				.sales-voucher{
					margin: 20px auto !important;
					width: 90% !important;
					box-shadow: none !important;
					border: solid black 2px;
					padding-bottom: 5px !important;
					display: block !important;
				}
				.remarks::placeholder{
					color: transparent;
				}
			}
		</style>
		<div class="sales-header d-none">
			<button class="btn btn-sm text-2 border bor-2 px-5 mx-3 mb-2 mt-3 back-btn" style="border-radius: 20px"><i class="fa fa-arrow-left" style="font-size: 18px"></i> &nbsp; <strong> Go Back </strong></button>
			<button class="btn btn-sm text-2 border bor-2 px-5 mx-3 mb-2 mt-3" onclick="print()" style="border-radius: 20px"><i class="mdi mdi-printer" style="font-size: 18px"></i> &nbsp; <strong> Print Bill </strong></button>
		</div>
		
		<div class="sales-voucher bg-white sh-lg p-3 m-3 d-inline-block">
			<form id="sales-voucher-form">
			<div class="row">
				<div class="col-8"></div>
				<div class="col-4 mb-4">
					 <div class="position-relative">Billing Date: <span class="bill-date cp"><?php echo date("Y-m-d") ?></span> <input type="date" name="bill-date" max="<?php echo date("Y-m-d") ?>" min="2022-01-01" value="<?php echo date("Y-m-d") ?>"  class="date d-none form-control position-absolute px-1" style="top: 0; width: 100%; "> </div>
				</div>
			</div>

			<?php if(count($customers) > 0){	?>
				<div class="form-group pb-2 customer-group">
					<label for="">Sold to saved customer?</label>
					<select name="saved-customer" id="saved-customer" class="form-control border shadow-none p-1">
						<option value="null">Choose Customer</option>
						<?php foreach($customers as $customer){
							echo "<option value='".$customer["id"]."'>".$customer["name"]."</option>";
						} ?>
					</select>
				</div>
			<?php  } ?>
			<div class="customer-info mb-3">
				<div class="input-group">
					<div class="input-group-prepend p-0">
						<label for="customer-name" class="input-group-text border-0 bg-transparent p-0 m-0">Customer Name</label>
					</div>
					<input type="text" id="customer-name" name="customer-name" class="form-control shadow-none border-0 border rounded-0 py-0 ms-1" style="border-bottom: #000 1px dotted !important">
				</div>
				<div class="row">
					<div class="col-sm-6 my-1">
						<div class="input-group">
							<div class="input-group-prepend">
								<label for="customer-phone" class="input-group-text bg-transparent border-0 p-0 m-0">Phone</label>
							</div>
							<input type="text" id="customer-phone" name="customer-phone" class="form-control shadow-none border-0 border rounded-0 py-0 ms-1" style="border-bottom: #000 1px dotted !important">
						</div>
					</div>
					<div class="col-sm-6 my-1">
						<div class="input-group">
							<div class="input-group-prepend">
								<label for="customer-email" class="input-group-text bg-transparent border-0 p-0 m-0">Email</label>
							</div>
							<input type="text" id="customer-email" name="customer-email" class="form-control shadow-none border-0 border rounded-0 py-0 ms-1" style="border-bottom: #000 1px dotted !important">
						</div>
					</div>
				</div>
				<div class="input-group my-1">
					<div class="input-group-prepend">
						<label for="customer-address" class="input-group-text bg-transparent border-0 p-0 m-0">Address</label>
					</div>
					<input id="customer-address" name="customer-address" type="text" class="form-control border-0 shadow-none py-0 ms-1" style="border-bottom: #000 1px dotted !important">
				</div>
				<div class="input-group save-details">
					<div class="input-group-prepend">
						<span class="input-group-text bg-white border-0 ps-0">
							<input type="checkbox" name="save-customer" id="save-customer" class="cp"> &nbsp;&nbsp; 
							<label for="save-customer" class="cp">Save Customer Details</label>
						</span>
					</div>
				</div>
			</div>

			<table class="w-100 table text-center" cellpadding="5" cellspacing="0" id="products-table">
				<thead class="w-100">
					<tr class="w-100">
						<th width="50px" class="p-0">S.No</th>
						<th width="300px" class="p-0 text-start">Products</th>
						<th width="50px" class="p-0">Qty</th>
						<th width="90px" class="p-0">Rate</th>
						<th width="65px" class="disc-col p-0">Total Discount</th>
						<th width="120px" class="p-0">Amount</th>
					</tr>
				</thead>

				<!-- Voucher table body starts here -->
				<tbody class="voucher-table-body">
				</tbody>
				<!-- voucher table body ends here -->
			</table>
			<div class="bg-white text-1 btn btn-sm add-row-btn"><b> <i class="fa fa-plus-circle"></i> Add</b></div>
			<div class="bg-white text-1 btn btn-sm remove-row-btn"><b> <i class="fa fa-times-circle"></i> Remove</b></div>
			<div class="row">
				<div class="col">
					<textarea maxlength="140" name="remarks" class="remarks form-control shadow-none border h-100 w-100" placeholder="remarks to customer (Will be printed in bill)" style="resize:  none;overflow: hidden; height: 40px;"></textarea>
				</div>
				<div class="col">
					<table class="table table-borderless footer-table ms-auto" style="width: fit-content; max-width: 90%;">
						<tr>
							<td width="130px">Total Amount</td>
							<td>
								<h6 class="form-control border-0 total_amt_field p-0 m-0">0</h6>
							</td>
			 			</tr>
						<tr class="">
							<td>Paid Amount</td>
							<td><input type="number" name="paid-amt" min="0" value="0" data-edited="0" class="form-control p-0 border-0 shadow-none border-bottom rounded-0 paid_amt_field" style="border-bottom: dotted 2px !important;"></td>
						</tr>
						<tr class="due-amt"></tr>
					</table>
				</div>
				<div class="col-12 text-end">
					<button type="submit" class="btn bg-1 text-white rounded-0 border-0 prepare-bill-btn">Prepare Bill <sub>(confirm sale)</sub></button>
				</div>
			</div>
			
			<p class="server-message"></p>
			</form>
		</div>


		<!-- Advanced discount options -->
		<div class="discount-option-card card border-0 sh m-3 d-inline-block" style="width: 350px;">
				<div class="card-header bg-white border-bottom bor-1 pb-0">
					<h5 class="text-1"><b>Advanced Discount Options <br> <sub>(use after finalizing product and quantity)</sub></b></h5>

				</div>
				<div class="card-body">
					<div class="input-group my-2">
						<div class="input-group-prepend">
							<b class="input-group-text border-0 bg-white">Product No. (S.No):</b>
						</div>
						<input type="number" class="form-control shadow-none border discount-product-no" min="1" style="width: 100px;" value="1">
					</div>
					<div class="input-group my-2">
						<div class="input-group-prepend">
							<b class="input-group-text border-0 bg-white">Discount Of</b>
						</div>
						<input type="number" min="0" class="form-control shadow-none border discount-num">
						<div class="input-group-append">
							<select class="form-control border-0 cp shadow-none discount-method">
								<option value="percent">Percent</option>
								<option value="amount">Amount</option>
							</select>
						</div>
					</div>
					<div class="input-group d-none discount-for-group">
						<div class="input-group-prepend">
							<label class="input-group-text bg-white border-0">Discount For</label>
						</div>
						<select class="form-control border-0 cp shadow-none discount-for">
							<option value="overall">Overall Quantity</option>
							<option value="each">Each Quantity</option>
						</select>
					</div>
				</div>
				<div class="card-footer bg-white border-0 text-end p-0">
					<button class="btn rounded-0 bg-1 text-white discount-apply-btn"><i class="fa fa-check"></i> <b style="letter-spacing: 3px;"> &nbsp;Set</b></button>
				</div>
			</div>

			<script>
				$(".discount-method").on("change", function(){
					if($(this).val() == "percent")
						$(".discount-for-group").addClass("d-none");
					else
						$(".discount-for-group").removeClass("d-none");
				})

				$(".discount-apply-btn").click(function(){
					var product_num = $(".discount-product-no").val();
					var table = document.querySelector(".voucher-table-body");
					var row = table.querySelectorAll("tr")[Number(product_num)-1];
					var product = row.querySelector(".sales-product").value;
					var rate = products[product]["marked_price"];
					var qty = row.querySelector(".qty-field").value;
					var method = $(".discount-method").val();
					var disc_amt = 0;
					var number = Number($(".discount-num").val());
					if(method == "percent" && number <= 100 && number > 0){
						disc_amt = (qty * rate) * number / 100;
					}
					else if(method == "amount" && number){
						if($(".discount-for").val() == "overall")
							disc_amt = number;
						else if($(".discount-for").val() == "each")
							disc_amt = qty * number;
					}
					row.querySelector(".disc-field").value = disc_amt;
					assign_price(row.querySelector(".disc-field"));
				})
			</script>
			<!-- Advanced discount options end -->


		<script>

			$(".back-btn").click(function(){
				$(".sale-btn").click();
			})
			$(document).ready(function(){
				if(sessionStorage.getItem("products_to_sold") != null){
					var data = sessionStorage.getItem("products_to_sold").split(",");
					for(var i = 0; i<data.length; i++){
						$(".voucher-table-body").append(
							 `<tr class="p-0 pt-1">
						<td class="text-center p-0" valign="bottom">${counter++}</td>
						<td class="p-0" valign="bottom">
							<select class="sales-product form-control shadow-none border-0 rounded-0 p-0" onchange="assign_product(this)">
								<option value="null">Choose Product</option>
							</select>
						</td>
						<td class="p-0 pt-1" valign="bottom">
							<input type="number" class="form-control text-center shadow-none border rounded-0 p-0 px-1 qty-field" placeholder="Qty" min="1" value="1" oninput="qty_input_validation(this); assign_price(this)">
						</td>
						<td class="p-0 pt-1" valign="bottom">
							<h6 class="form-control text-center rate-field border-0 p-0 m-0">0</h6>
						</td>

						<td class="disc-col p-0 pt-1" valign="bottom">
							<input type="number" class="form-control text-center shadow-none border rounded-0 p-0 px-1 disc-field w-100 m-0" placeholder="Disc" min="0" value="0" oninput="disc_input_validation(this);">
						</td>

						<td class="p-0 pt-1" valign="bottom">
							<h6 class="form-control text-center total-field p-0 m-0 border-0">0</h6>
						</td>
					</tr>`	);
					remove_btn_visiblity();
					load_products();
					var select_elem = document.querySelectorAll("select.sales-product");
					select_elem[select_elem.length - 1].value = data[i];
					assign_product(select_elem[select_elem.length - 1]);
					}
					sessionStorage.removeItem("products_to_sold");
				}
				else{
					$(".voucher-table-body").append(
							 `<tr class="p-0 pt-1">
						<td class="text-center p-0" valign="bottom">${counter++}</td>
						<td class="p-0" valign="bottom">
							<select class="sales-product form-control shadow-none border-0 rounded-0 p-0" onchange="assign_product(this)">
								<option value="null">Choose Product</option>
							</select>
						</td>
						<td class="p-0 pt-1" valign="bottom">
							<input type="number" class="form-control text-center shadow-none border rounded-0 p-0 px-1 qty-field" placeholder="Qty" min="1" value="1" oninput="qty_input_validation(this); assign_price(this)">
						</td>
						<td class="p-0 pt-1" valign="bottom">
							<h6 class="form-control text-center rate-field border-0 p-0 m-0">0</h6>
						</td>

						<!-- discount column -->
						<td class="disc-col p-0 pt-1" valign="bottom">
							<input type="number" class="form-control text-center shadow-none border rounded-0 p-0 px-1 disc-field w-100 m-0" placeholder="Disc" min="0" value="0" oninput="disc_input_validation(this);">
						</td>
						<!-- discount column ends -->

						<td class="p-0 pt-1" valign="bottom">
							<h6 class="form-control text-center total-field p-0 m-0 border-0">0</h6>
						</td>
					</tr>`
							);
					remove_btn_visiblity();
					load_products();var select_elem = document.querySelectorAll("select.sales-product");
				}
			})

			/*saved customer on change starts*/



			$("#saved-customer").on("change", function(){
				if($(this).val() != "null" && !isNaN(Number($(this).val()))){
					var customer_id = $(this).val();
					$("#customer-name").val(customers[customer_id]["name"]);
					$("#customer-phone").val(customers[customer_id]["contact"]);
					$("#customer-email").val(customers[customer_id]["email"]);
					$("#customer-address").val(customers[customer_id]["address"]);
					$("#customer-name").attr("disabled", true);
					$("#customer-phone").attr("disabled", true);
					$("#customer-email").attr("disabled", true);
					$("#customer-address").attr("disabled", true);
					$(".save-details").addClass("d-none");
				}
				else{
					$("#customer-name").val("");
					$("#customer-phone").val("");
					$("#customer-email").val("");
					$("#customer-address").val("");
					$("#customer-name").removeAttr("disabled");
					$("#customer-phone").removeAttr("disabled");
					$("#customer-email").removeAttr("disabled");
					$("#customer-address").removeAttr("disabled");
					$(".save-details").removeClass("d-none");
				}
			})
			/*saved customer on change ends*/

			$(".add-row-btn").click(function(){
				var row = `<tr class="p-0 pt-1">
						<td class="text-center p-0" valign="bottom">${counter++}</td>
						<td class="p-0" valign="bottom">
							<select class="sales-product form-control shadow-none border-0 rounded-0 p-0" onchange="assign_product(this)">
								<option value="null">Choose Product</option>
							</select>
						</td>
						<td class="p-0 pt-1" valign="bottom">
							<input type="number" class="form-control text-center shadow-none border rounded-0 p-0 px-1 qty-field" placeholder="Qty" min="1" value="1" oninput="qty_input_validation(this); assign_price(this)">
						</td>
						<td class="p-0 pt-1" valign="bottom">
							<h6 class="form-control text-center rate-field border-0 p-0 m-0">0</h6>
						</td>

						<!-- discount column -->
						<td class="disc-col p-0 pt-1" valign="bottom">
							<input type="number" class="form-control text-center shadow-none border rounded-0 p-0 px-1 disc-field w-100 m-0" placeholder="Disc" min="0" value="0" oninput="disc_input_validation(this);">
						</td>
						<!-- discount column ends -->

						<td class="p-0 pt-1" valign="bottom">
							<h6 class="form-control text-center total-field p-0 m-0 border-0">0</h6>
						</td>
					</tr>`;
					$(".voucher-table-body").append(row);
					remove_btn_visiblity();
					load_products();
			})



			$(".remove-row-btn").click(function(){
				var rows = document.querySelectorAll(".voucher-table-body tr");
				if(rows.length > 1){
					rows[rows.length-1].remove();
					counter--;
				}
				remove_btn_visiblity();
				assign_total();
			})

			remove_btn_visiblity()
			function remove_btn_visiblity(){
				var remove_btn = document.querySelector(".remove-row-btn");
				var rows = document.querySelectorAll(".voucher-table-body tr");
				if(rows.length > 1)
					$(remove_btn).removeClass("d-none");
				else
					$(remove_btn).addClass("d-none");
			}
			$(".bill-date").click(function(){
				$(".date").removeClass("d-none");
				setTimeout(function(){$(".date").focus();},100);
			})

			$(".date").on("blur", function(){
				$(".bill-date").text($(this).val());
				$(this).addClass("d-none");
			})


			//only adds options in the select option from the product list
			function load_products(){
				var rows =  document.querySelectorAll(".voucher-table-body tr");
				var last_row = rows[rows.length - 1];
				var select = last_row.querySelectorAll("select")[0];
				for(var product in products){
					select.innerHTML += `<option value='${product}'>${products[product]["name"]}</option>`;
				}
				assign_total();
			}

			//assign quantity and rate of each products and calculate amt = qty*rate-disc
			function assign_product(element){
				var val = Number.parseInt(element.value);
				var row = element.parentElement.parentElement;
				var rate_field = row.querySelector(".rate-field");
				var qty_field = row.querySelector(".qty-field");
				var total_field = row.querySelector(".total-field");
				var disc_field = row.querySelector(".disc-field");
				if(!isNaN(val) && products[val] != undefined){
					//if product is choosen correctly
					var product = products[val];
					console.log(product);
					rate_field.innerText = product["marked_price"];
					var qty = Number($(".qty-field").val());
					if(isNaN(qty) || qty > product["available_stock"]){
						qty_field.value = product["available_stock"];
					}
					var total = 0;
					if(!isNaN(Number(rate_field.innerText))  &&  !isNaN(Number(qty_field.value)) &&  !isNaN(Number(disc_field.value)))
						total = Number(rate_field.innerText) * Number(qty_field.value) -  Number(disc_field.value);
					total_field.innerText = total;
				}
				else{
					//if product option is "Choose Product" being choosed
					rate_field.innerText = 0;
					total_field.innerText = 0;
				}
				assign_total();
			}


			//assign amount of each product
			function assign_price(element){
				var row = element.parentElement.parentElement;
				var rate_field = row.querySelector(".rate-field");
				var qty_field = row.querySelector(".qty-field");
				var total_field = row.querySelector(".total-field");
				var disc_field = row.querySelector(".disc-field");
				var total = 0;
				if(!isNaN(Number(rate_field.innerText))  &&  !isNaN(Number(qty_field.value)) &&  !isNaN(Number(disc_field.value)))
					total = Number(rate_field.innerText) * Number(qty_field.value) -  Number(disc_field.value);
				total_field.innerText = total;
				assign_total();
			}


			//calculate final total amont
			function assign_total(){
				var table = document.querySelector("#products-table");
				var total_fields = $(".total-field");
				var total = 0;
				$(total_fields).each(function(){
					total += Number($(this).text());
				})
				if($(".paid_amt_field").val() > total)
					$(".paid_amt_field").val(total);

				$(".total_amt_field").text(total);
				if($(".paid_amt_field").attr("data-edited") == "0")
					$(".paid_amt_field").val(total);
				if($(".paid_amt_field").val() < total){
					$(".due-amt").html(`
						<td>Due Amount</td>
						<td>
							<h6 class="form-control border-0 due_amt_field p-0 m-0">${total - Number($(".paid_amt_field").val())}</h6>
						</td>
					`)
				}
				else{
					$(".due-amt").html("");
				}
			}


			//stop dynamically change on paid amount after once edited by user
			$(".paid_amt_field").on("input", function(){
				$(this).attr("data-edited", "1");
				if(!$(this).val())
					$(this).val("0");
				else
					$(this).val(Number($(this).val()));
				assign_total();
			})

			//product quantity should not be greater than available quantity
			function qty_input_validation(qty_field){
				if($(qty_field).val() < 1)
					$(qty_field).val('');
				var row = qty_field.parentElement.parentElement;
				var val = Number(row.querySelectorAll("select.sales-product")[0].value);
				if(!isNaN(val) && products[val] != undefined){
					var product_details = products[val];
					if($(qty_field).val() > product_details["available_stock"]){
						$(qty_field).val(product_details["available_stock"]);
						assign_price(qty_field);
					}
				}
				else{
					console.log("failed");
				}
			}

			$("#sales-voucher-form").submit(function(e){
				e.preventDefault();
				var formdata = new FormData(this);
				var products_data = document.querySelectorAll(".voucher-table-body tr");
				var data = {};
				for(var i = 0; i<products_data.length; i++){
					data[i] = {};
					data[i]["product"] = products_data[i].querySelector(".sales-product").value;
					data[i]["qty"] = products_data[i].querySelector(".qty-field").value;
					data[i]["disc"] = products_data[i].querySelector(".disc-field").value;
				}
				formdata.append("products_data", JSON.stringify(data));
				$.ajax({
					type: "POST",
					url: root+"merchant/php/make_sales.php",
					data: formdata,
					processData: false,
					contentType: false,
					cache: false,
					beforeSend: function(){
						$(".server-message").html("<b class='text-1'> <i class='fa fa-spinner fa-spin'></i> &nbsp; Loading Please Wait..... </b>")
					},
					success: function(response){
						response = response.trim();
						if(response.indexOf("Error") == 0)
							$(".server-message").html(`<span class='text-4'> <i class='fa fa-times'></i> &nbsp; ${response.replace("Error:", "")}</span>`);
						else{
							$(".sales-voucher").html(response);
							$(".discount-option-card").remove();
							$(".sales-header").removeClass("d-none");
						}
					}
				})
			})

			function disc_input_validation(disc_field){
				if($(disc_field).val() < 1)
					$(disc_field).val('');
				var row = disc_field.parentElement.parentElement;
				var val = Number(row.querySelectorAll("select.sales-product")[0].value);
				if(!isNaN(val) && products[val] != undefined){
					var product_details = products[val];
					if($(disc_field).val() > product_details["marked_price"])
						$(disc_field).val(product_details["marked_price"]);
				}
				else{
					$(disc_field).val("");
				}
				assign_price(disc_field);
			}
		</script>
	</div>