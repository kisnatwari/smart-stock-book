<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
 ?>
<div class="animate__animated animate__fadeIn animate_faster">
	<div class="p-3 ps-4 d-flex flex-column flex-wrap dashboard-inner-content" >
		<?php 
		//count suppliers
		$merchant_id = $_SESSION['logged_in_merchant'];
		$stmt = $pdo -> prepare("SELECT COUNT(id) AS sup_count FROM suppliers WHERE merchant_id = $merchant_id");
		$stmt -> execute();
		$no_supplier = $stmt -> fetch()["sup_count"];

		//count customers
		$stmt = $pdo -> prepare("SELECT COUNT(id) AS customer_count FROM customers WHERE merchant_id = $merchant_id");
		$stmt -> execute();
		$no_customer = $stmt -> fetch()["customer_count"];

		//count products
		$stmt = $pdo -> prepare("SELECT COUNT(id) AS product_count FROM products WHERE merchant_id = $merchant_id");
		$stmt -> execute();
		$no_product = $stmt -> fetch()["product_count"];


				//counting finished products
		$finished_count = $pdo -> prepare("SELECT * FROM products WHERE merchant_id = $merchant_id AND available_stock = 0");
		$finished_count -> execute();
		$no_finished = $finished_count -> rowCount();
		 ?>
		 <div class="dashboard-summary d-flex flex-wrap">

			 <!-- products count -->
			 <div class="text-white row overflow-hidden m-3 bg-2 cp position-relative products-count" style="width: 250px; height: 80px; border-radius: 10px; box-shadow: 8px 7px 17px 0 #1f00f230;">
			 	<div class="col-4 h-100 d-flex justify-content-center align-items-center p-0">
			 		<span class="bg-white text-2 d-flex justify-content-center align-items-center sh-3 d-inline-block rounded-circle" style="min-height: 50px; width: 50px;">
			 			<i class="fa fa-shopping-basket" style="font-size: 30px;"></i>
			 		</span>
			 	</div>
			 	<div class="col-8 d-flex justify-content-start align-items-center h-100 p-0">
			 		<h3 class="supplier-number"><b><?php echo  ($no_product > 1) ? "$no_product products" : "$no_product Product" ?> </b></h3>
			 	</div>
			 	<div class="products-caption position-absolute bg-2 w-100 h-100 d-flex justify-content-center align-items-center d-none" style="top: 0; left: 0;">
			 		<h4><b><i class="fa fa-spinner fa-spin"></i> &nbsp; Loading... </b></h4>
			 	</div>
			 </div>
		 	
		 	 	<!-- suppliers count -->
		 	 <div class="text-2 row overflow-hidden m-3 bg-white cp supplier-count position-relative" style="width: 250px; height: 80px; border-radius: 10px; box-shadow: 8px 7px 17px 0 #1f00f230;">
			 	<div class="col-4 h-100 d-flex justify-content-center align-items-center p-0">
			 		<span class="bg-2 text-white d-flex justify-content-center align-items-center sh-3 d-inline-block rounded-circle" style="min-height: 50px; width: 50px;">
			 			<i class="fas fa-users" style="font-size: 30px;"></i>
			 		</span>
			 	</div>
			 	<div class="col-8 d-flex justify-content-start align-items-center h-100 p-0">
			 		<h3 class="supplier-number"><b><?php echo  ($no_supplier > 1) ? "$no_supplier Suppliers" : "$no_supplier Supplier" ?> </b></h3>
			 	</div>
			 	<div class="supplier-caption position-absolute bg-white w-100 h-100 d-flex justify-content-center align-items-center d-none" style="top: 0; left: 0;">
			 		<h4><b><i class="fa fa-spinner fa-spin"></i> &nbsp; Loading... </b></h4>
			 	</div>
			 </div>

			 			 	<!-- customers count -->
		 	 <div class="text-2 row overflow-hidden m-3 bg-white cp customer-count position-relative" style="width: 280px; height: 80px; border-radius: 10px; box-shadow: 8px 7px 17px 0 #1f00f230;">
			 	<div class="col-4 h-100 d-flex justify-content-center align-items-center p-0">
			 		<span class="bg-2 text-white d-flex justify-content-center align-items-center sh-3 d-inline-block rounded-circle" style="min-height: 50px; width: 50px;">
			 			<i class="fas fa-users" style="font-size: 30px;"></i>
			 		</span>
			 	</div>
			 	<div class="col-8 d-flex justify-content-start align-items-center h-100 p-0">
			 		<h3 class="customer-number"><b><?php echo  ($no_customer > 1) ? "$no_customer Customers" : "$no_customer Customer" ?> </b></h3>
			 	</div>
			 	<div class="customer-caption position-absolute bg-white w-100 h-100 d-flex justify-content-center align-items-center d-none" style="top: 0; left: 0;">
			 		<h4><b><i class="fa fa-spinner fa-spin"></i> &nbsp; Loading... </b></h4>
			 	</div>
			 </div>

			 			 			 			 	<!-- Finished count -->
		 	 <div class="text-white row overflow-hidden m-3 bg-2 cp finished-count position-relative" style="min-width: 370px; height: 80px; border-radius: 10px; box-shadow: 8px 7px 17px 0 #1f00f230;">
			 	<div class="col-4 h-100 d-flex justify-content-center align-items-center p-0" style="width: 75px;">
			 		<span class="bg-white text-2 d-flex justify-content-center align-items-center sh-3 d-inline-block rounded-circle" style="min-height: 50px; width: 50px;">
			 			<i class="mdi mdi-package-down" style="font-size: 30px;"></i>
			 		</span>
			 	</div>
			 	<div class="col-8 d-flex justify-content-start align-items-center h-100 p-0" style="width: 295px;">
			 		<h3 class="finished-number"><b><?php echo  ($no_finished > 1) ? "$no_finished Finished Products" : "$no_finished Finished Product" ?> </b></h3>
			 	</div>
			 	<div class="finished-caption position-absolute bg-2 w-100 h-100 d-flex justify-content-center align-items-center d-none" style="top: 0; left: 0;">
			 		<h4><b><i class="fa fa-spinner fa-spin"></i> &nbsp; Loading... </b></h4>
			 	</div>
			 </div>
		 </div>


		 <div class="recents d-flex flex-wrap">
		 	<div class="card sh bg-white bor-2 mx-2" style="max-width: 500px; height: fit-content;">
		 		<div class="card-header bg-white rounded-0 px-3 bg-2  text-white pb-1 border-bottom bor-2 d-flex justify-content-between"><strong>Recent Purchases</strong> <small class="cp" onclick="see_all('purchases')">See All</small></div>
		 		<div class="card-body p-0">
		 			<?php 
						require_once "../../common/db.php";
						require_once "../../common/imp_functions.php";
						$query = $pdo -> prepare("SELECT 
							purchase_history.stock, products.name, products.photo
							FROM purchase_history
							INNER JOIN products ON purchase_history.product_id = products.id
							WHERE purchase_history.merchant_id = ?
							ORDER BY `date` DESC limit 8
							");
						$query -> execute([$_SESSION["logged_in_merchant"]]);
						$history = $query -> fetchAll();

						if(count($history) > 0){
							echo "<ul class='list-group'>";
							foreach($history as $h){
								?>
								<li class="d-flex align-items-center border p-2 list-group-item text-1">
									<img src="<?php echo $root.'merchant/stocks/'.$h["photo"] ?>" alt="John Doe" class="flex-shrink-0 me-3" style="max-width:30px; max-height:30px; object-fit: contain;">
									<div>
										<?php 
											$message = ($h["stock"] > 2) ? $h["stock"]." units" : $h["stock"]." unit";
											$message .= " of <strong>".$h["name"]."</strong>";
											echo $message;
										 ?>
									</div>
								</li>

								<?php
							}
							echo "</ul>";
						}
					 ?>
		 		</div>
		 	</div>

		 	<!-- sales history -->
		 	<div class="card mx-2 bor-2" style="max-width: 500px; height: fit-content;">
	            <div class="card-header bg-white rounded-0 px-3 bg-2  text-white pb-1 border-bottom bor-2 d-flex justify-content-between">
	            	<strong>Recent Sales</strong>
	            	<small class="cp" onclick="see_all('sales')">See All</small>
	            </div>
	            <div class="card-body p-0">
	                <?php
									$stmt = $pdo -> prepare("SELECT
										sales_history.id, sales_history.date, sales_history.invoice_no,
										sales_history.product_details, sales_history.paid_price, users.name, users.role
										FROM sales_history INNER JOIN users ON sales_history.user_id = users.id
										WHERE sales_history.merchant_id = ? ORDER BY id desc limit 8
										");
									$stmt -> execute([$_SESSION["logged_in_merchant"]]);
									$sales = $stmt -> fetchAll();
					?>
	                <ul class="list-group">
	                    <?php
							foreach($sales as $sale){
								echo "<li class='d-flex align-items-center border p-2 list-group-item text-1'>";
									$seller = ($sale["role"] == "merchant") ? "Admin" : $sale["name"];
									$message = "<strong>Voucher No. ${sale["invoice_no"]} : </strong> ";
									//counting products
									$products = json_decode($sale["product_details"], true);
									$total_price = 0;
									$product_count = 0;
									foreach($products as $p){
										$product = $db -> where("products", "id", "=", $p["product"], false);
										$rate = $product["marked_price"];
										$qty = $p["qty"];
										$disc = $p["disc"];
										$price = $qty * $rate - $disc;
										$total_price += $price;
										$product_count += $qty;
									}
									$message .= ($product_count == 1) ? "$product_count unit of product for <strong> &nbsp; Rs $total_price </strong>" : "$product_count units of product for <strong> &nbsp; Rs $total_price </strong>";
									echo "$message";
								echo "</li>";
							}
						?>
	                </ul>
	            </div>
	        </div>

		 </div>
	</div>
	<div class="dashboard-external-content d-none"></div>
	<script>

		function see_all(type){
			if(type == "sales" || type == "purchases"){
				sessionStorage.setItem("history_type", type);
				$(".history-btn").click();
			}
		}

		document.querySelector(".supplier-count").onclick = show_suppliers;
		document.querySelector(".customer-count").onclick = show_customers;
		function show_suppliers(){
			$.ajax({
				url: root+"merchant/php/suppliers_details.php",
				beforeSend: function(){
					$(".supplier-caption").removeClass("d-none");
				},
				success: function(response){
					$(".dashboard-inner-content").addClass("d-none");
					$(".dashboard-external-content").removeClass("d-none");
					$(".dashboard-external-content").html(response);
				}
			})
		}

		function show_customers(){
			$.ajax({
				url: root+"merchant/php/customers_details.php",
				beforeSend: function(){
					$(".customer-caption").removeClass("d-none");
				},
				success: function(response){
					$(".dashboard-inner-content").addClass("d-none");
					$(".dashboard-external-content").removeClass("d-none");
					$(".dashboard-external-content").html(response);
				}
			})
		}

		$(".products-count").click(function(){
			$(".products-button").click();
		})

				$(".finished-count").click(function(){
			$.ajax({
				type: "get",
				url: root+"merchant/php/finished_product_design.php",
				success: function(response){
					$(".dashboard-inner-content").addClass("d-none");
					$(".dashboard-external-content").removeClass("d-none");
					$(".dashboard-external-content").html(response);
				}
			})
		})
	</script>
</div>