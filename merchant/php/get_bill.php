<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	$id = $_POST['sale'];
	$stmt = $pdo -> prepare("SELECT
		sales_history.date, sales_history.invoice_no, sales_history.customer_id, sales_history.unsaved_customer,
		sales_history.product_details, sales_history.paid_price, sales_history.remarks, users.name, users.role
		FROM sales_history INNER JOIN users ON sales_history.merchant_id = users.merchant_id
		WHERE sales_history.merchant_id = ? AND sales_history.id = ? ORDER BY sales_history.id desc
		 ");
	$stmt -> execute([$_SESSION["logged_in_merchant"], $id]);
	$sales = $stmt -> fetch();
	$customer_data = [];
	$invoice_no = $sales["invoice_no"];
	$data = [];
	$data["bill_date"] = $sales["date"];
	if(!empty($sales["customer_id"])){
		$customer_data = $db -> where("customers", "id", "=", $sales["customer_id"], false);
		$data["name"] = $customer_data["name"];
		$data["email"] = $customer_data["email"];
		$data["phone"] = $customer_data["contact"];
		$data["address"] = $customer_data["address"];
	}
	else{
		$customer_data = json_decode($sales["unsaved_customer"], true);
		$data["name"] = $customer_data["name"];
		$data["email"] = $customer_data["email"];
		$data["phone"] = $customer_data["contact"];
		$data["address"] = $customer_data["address"];
	}
	$data["remarks"] = $sales["remarks"];
	$data["paid"] = $sales["paid_price"];
	$products = json_decode($sales["product_details"], true);
	$total_price = 0;
	foreach($products as $p){
		$product = $db -> where("products", "id", "=", $p["product"], false);
		$rate = $product["marked_price"];
		$qty = $p["qty"];
		$disc = $p["disc"];
		$price = $qty * $rate - $disc;
		$total_price += $price;
	}

		//generating logo
	$logo = $pdo -> prepare("SELECT logo FROM branding");
	$logo -> execute();
	$logo = $logo -> fetch()["logo"];


	$stmt = $pdo -> prepare("SELECT * FROM merchants WHERE id = ?");
	$stmt -> execute([$_SESSION['logged_in_merchant']]);
	$merchant_data = $stmt -> fetch();

 ?>
<div class="sales-history-header">
    <button class="btn btn-sm text-2 border bor-2 px-5 mx-3 mb-2 mt-3 back-btn" style="border-radius: 20px"><i class="fa fa-arrow-left" style="font-size: 18px"></i> &nbsp; <strong> Go Back </strong></button>
    <button class="btn btn-sm text-2 border bor-2 px-5 mx-3 mb-2 mt-3" onclick="print()" style="border-radius: 20px"><i class="mdi mdi-printer" style="font-size: 18px"></i> &nbsp; <strong> Print Bill </strong></button>
</div>
<div class="sales-voucher bg-white sh-lg p-3 m-3 d-inline-block">
	<div id="sales-voucher-form" class="bg-white">
	    <div class="row">
	        <div class="col-2 my-2 text-center">
	            <img src="<?php echo (empty($merchant_data[" logo"])) ? "data:image/png;base64," .base64_encode($logo) : $root.'merchant/logos/'.$merchant_data["logo"] ?>" alt="" style="max-width: 70px; max-height: 70px;">
	        </div>
	        <div class="col-1"></div>
	        <div class="col-6">
	            <h3 class="text-center">
	                <?php echo $merchant_data["name"] ?>
	            </h3>
	            <p class="text-center">
	                <?php echo $merchant_data["phone"] ?> <br>
	                <?php echo $merchant_data["address"] ?>
	            </p>
	        </div>
	        <div class="col-3">
	            <div class="position-relative">
	                Date: <span class="bill-date">
	                    <?php echo date("Y-m-d") ?></span> <input type="date" name="bill-date" max="<?php echo date(" Y-m-d") ?>" min="2022-01-01" value="
	                <?php echo date("Y-m-d") ?>" class="date d-none form-control position-absolute px-1" style="top: 0; width: 100%; "> <br>
	                Voucher No:.
	                <?php echo $invoice_no; ?>
	            </div>
	        </div>
	    </div>
	    <div class="customer-info mb-3">
	        <h5>Customer Name:
	            <?php echo $data["name"] ?>
	        </h5>
	        <div class="row">
	            <div class="col-sm-6 my-1">
	                <h6>Phone:
	                    <?php echo $data["phone"]; ?>
	                </h6>
	            </div>
	            <div class="col-sm-6 my-1">
	                <h6>Email:
	                    <?php echo $data["email"]; ?>
	                </h6>
	            </div>
	        </div>
	        <div class="input-group my-1">
	            <h6>Address:
	                <?php echo $data["address"]; ?>
	            </h6>
	        </div>
	    </div>
	    <table class="w-100 table text-center" cellpadding="5" cellspacing="0" id="products-table">
	        <thead class="w-100">
	            <tr class="w-100">
	                <th width="50px" class="p-0">S.No</th>
	                <th width="300px" class="p-0 text-start">Product Name</th>
	                <th width="50px" class="p-0">Qty</th>
	                <th width="90px" class="p-0">Rate</th>
	                <th width="65px" class="disc-col p-0">Disc</th>
	                <th width="120px" class="p-0">Amount</th>
	            </tr>
	        </thead>
	        <!-- Voucher table body starts here -->
	        <tbody class="voucher-table-body">
	            <?php $counter = 1;
						foreach ($products as $product) { 
							$stmt = $pdo -> prepare("SELECT * FROM products WHERE id= ? AND merchant_id = ?");
							$stmt -> execute([$product["product"], $_SESSION["logged_in_merchant"]]);
							$product_data = $stmt -> fetch();
							?>
	            <tr class="p-0 pt-1">
	                <td class="text-center p-0" valign="center">
	                	 <h6 class="p-0 py-1 m-0">
	                        <?php echo $counter++ ?>
	                    </h6>
	                </td>
	                <td class="p-0 text-start" valign="center">
	                    <h6 class="p-0 py-1 m-0">
	                        <?php echo $product_data["name"]; ?>
	                    </h6>
	                </td>
	                <td class="p-0 pt-1" valign="center">
	                    <h6 class="p-0 py-1 m-0">
	                        <?php echo $product["qty"]; ?>
	                    </h6>
	                </td>
	                <td class="p-0 pt-1" valign="center">
	                    <h6 class="p-0 py-1 m-0">
	                        <?php echo $product_data["marked_price"]; ?>
	                    </h6>
	                </td>
	                <!-- discount column -->
	                <td class="disc-col p-0 pt-1" valign="center">
	                    <h6 class="p-0 py-1 m-0">
	                        <?php echo $product["disc"]; ?>
	                    </h6>
	                </td>
	                <!-- discount column ends -->
	                <td class="p-0 pt-1" valign="center">
	                    <h6 class="p-0 py-1 m-0">
	                        <?php echo (intval($product["qty"]) * intval($product_data["marked_price"]) - intval($product["disc"])) ?>
	                    </h6>
	                </td>
	            </tr>
	            <?php
						}
				 	?>
	        </tbody>
	        <!-- voucher table body ends here -->
	    </table>
	    <div class="row">
	        <div class="col-sm-6">
	            <?php echo "<pre>".$data["remarks"]."</pre>" ?>
	        </div>
	        <div class="col-sm-6">
	            <table class="table table-borderless footer-table ms-auto" style="width: fit-content; max-width: 90%;">
	                <tr>
	                    <td width="130px">Total Amount</td>
	                    <td>
	                        <h6 class="form-control border-0 total_amt_field p-0 m-0">Rs
	                            <?php echo $total_price; ?>
	                        </h6>
	                    </td>
	                </tr>
	                <tr>
	                    <td>Paid Amount</td>
	                    <td>
	                        <h6 class="form-control border-0 total_amt_field p-0 m-0">Rs
	                            <?php echo $data["paid"]; ?>
	                        </h6>
	                    </td>
	                </tr>
	                <?php if($total_price > $data["paid"]) { ?>
	                <tr>
	                    <td>Due Amount</td>
	                    <td>
	                        <h6 class="form-control border-0 total_amt_field p-0 m-0">Rs
	                            <?php echo($total_price - $data["paid"]) ?>
	                        </h6>
	                    </td>
	                </tr>
	                <?php } ?>
	            </table>
	        </div>
	    </div>
	    <h6 class=" border-top pt-2 border-dark text-center mt-2 b-0 bill-footer"><sub><big>*****</big></sub> Thank You for visiting. Hope to see you soon <sub><big>*****</big></sub></h6>
	</div>
	<script>
		$(".back-btn").click(function(){
			$(".sales-history").removeClass("d-none");
            $(".bill-section").html("");
		})
	</script>
</div>