<div class="animate__animated animate__fadeIn">
    <?php
		require_once "../../common/db.php";
		require_once "../../common/imp_functions.php";
	?>
			<style>

			@media print{
				aside, .top-content, .add-row-btn, .remove-row-btn, .discount-option-card, .sales-history-header{
					display: none !important;
				}
				.content, body{
					padding: 0 !important;
					margin: 0 !important;
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
    <div class="sales-history">
        <div class="d-flex justify-content-start">
            <div class="btn btn-sm view-purchase-history-btn border bor-2 text-2 my-3 shadow-none px-4" style="border-radius: 20px;"><b> <i class="mdi mdi-home-import-outline" style="font-size: 18px"></i> &nbsp; View Purchase History</b></div>
        </div>
        <div class="bg-white p-2 m-2 card rounded-0 sh-lg" style="max-width: 900px;">
            <div class="card-header bg-white border-0 border-bottom bor-1 text-1 pb-0">
                <strong class="border-0">Sales History</strong>
            </div>
            <div class="card-body">
                <?php
								$stmt = $pdo -> prepare("SELECT
									sales_history.id, sales_history.date, sales_history.invoice_no,
									sales_history.product_details, sales_history.paid_price, users.name, users.role
									FROM sales_history INNER JOIN users ON sales_history.user_id = users.id
									WHERE sales_history.merchant_id = ? ORDER BY id desc
									");
								$stmt -> execute([$_SESSION["logged_in_merchant"]]);
								$sales = $stmt -> fetchAll();
				?>
                <ul class="list-group">
                    <?php
									$current_date = "";
										foreach($sales as $sale){
											if($current_date != $sale["date"]){
												$current_date = $sale["date"];
												$date = new DateTime($current_date);
												echo "<li class='list-group-item list-group-item-dark'>". $date -> format("Y-F-d l") . "</li>";
											}
											echo "<li class='list-group-item list-group-item-action position-relative'>";
												echo "<p class='d-block m-0 p-0 text-1'>Voucher No. ${sale["invoice_no"]}</p>";
												$seller = ($sale["role"] == "merchant") ? "Admin" : $sale["name"];
												$message = "$seller sold ";
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
												$message .= ($product_count == 1) ? "$product_count unit of product for Rs $total_price " : "$product_count units of product for Rs $total_price ";
												if($total_price == $sale["paid_price"])
													$message .= " and received full amount";
												else
													$message .= " and received Rs ".$sale["paid_price"];
												echo "<p>$message</p>";
												echo "<button class='btn shadow-none border-0 p-0 m-0 text-1 rounded-0 position-absolute get-bill-btn' data-sale='${sale["id"]}' style='top: 10px; right: 11px; font-size: 12px'><b><i class='fa fa-file-invoice text-1'></i> View Bill</b></button>";
											echo "</li>";
										}
					?>
                </ul>
            </div>
        </div>
    </div>
    <div class="bill-section">
    	
    </div>
    <script>
    $(document).ready(function() {
        $(".view-purchase-history-btn").click(function() {
            $.ajax({
                type: "POST",
                url: root + "merchant/php/purchase_history.php",
                beforeSend: function() {},
                success: function(response) {
                    $(".inner-content").html(response);
                }
            })
        })
        $(".get-bill-btn").click(function() {
            var sale = $(this).attr("data-sale");
            var btn = this;
            $.ajax({
                type: "POST",
                data: {
                    sale: sale
                },
                url: root + "merchant/php/get_bill.php",
                beforeSend: function() {
                    $(btn).html("<i class='fa fa-spinner fa-spin text-1'></i>");
                },
                success: function(response) {
                    $(".sales-history").addClass("d-none");
                    $(".bill-section").html(response);
                    $(btn).html("<b><i class='fa fa-file-invoice text-1'></i> View Bill</b>");
                }
            })
        })
    })
    </script>
</div>