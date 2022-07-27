<div class="row animate__animated animate__fadeIn">
	<div class="d-flex justify-content-start">
		<div class="btn btn-sm view-sales-history-btn border bor-2 text-2 my-3 shadow-none px-4"
			style="border-radius: 20px;"><b> <i class="mdi mdi-home-export-outline" style="font-size: 18px"></i> &nbsp;
				View Sales History</b></div>
	</div>
	<div class="bg-white p-2 m-2 card rounded-0 sh-lg" style="max-width: 900px; min-width: 500px;">
		<?php 
			require_once "../../common/db.php";
			require_once "../../common/imp_functions.php";
			$query = $pdo -> prepare("SELECT 
				purchase_history.id, purchase_history.date, purchase_history.supplier_id, purchase_history.unit_price, purchase_history.stock,
				purchase_history.total_price, purchase_history.paid_price, purchase_history.remarks, products.name, products.photo, users.name as user_name, users.role
				FROM purchase_history
				INNER JOIN products ON purchase_history.product_id = products.id
				INNER JOIN users ON purchase_history.user_id = users.id
				WHERE purchase_history.merchant_id = ?
				ORDER BY `date` DESC
				");
			$query -> execute([$_SESSION["logged_in_merchant"]]);
			$history = $query -> fetchAll();
		 ?>
		<div class="card-header bg-white border-0 border-bottom bor-1 text-1 pb-0">
			<strong class="border-0">Purchase History</strong>
		</div>
		<div class="card-body">
			<ul class="history list-group">
				<?php 
						$last_date = "";
							foreach($history as $h){
								$date = new DateTime($h["date"]);
								$current_date = $date -> format("Y-m-d");
								if($current_date != $last_date){
									$last_date = $current_date;
									echo "<li class='list-group-item list-group-item-dark'>". $date -> format("Y-F-d l") . "</li>";
								}
								?>
				<li class="d-flex border p-3 list-group-item list-group-item-action">
					<img src="<?php echo $root.'merchant/stocks/'.$h["photo"] ?>" alt="John Doe"
						class="flex-shrink-0 me-3 mt-3" style="max-width:60px;max-height:60px; object-fit: contain;">
					<div>
						<?php
							echo "<small class='text-1'><strong>".$date->format('h:i A')."</strong></small>";
							$message = ($h["role"] == "merchant") ? "<strong>Admin</strong> " : "<strong>".$h["user_name"]."</strong>";
							$message .= "purchased ";
							$unit = ($h["stock"] > 2) ? $h["stock"]." units" : $h["stock"]." unit";
							$message .= $unit;
							$message .= " of <strong>".$h["name"]."</strong>";
							$supplier = $h["supplier_id"];
							$supplier_msg = " from random supplier.";
							if($h["supplier_id"]){
								$supplier = $pdo -> prepare("SELECT name FROM suppliers WHERE id = ?");
								$supplier -> execute([$h["supplier_id"]]);
								$supplier = $supplier -> fetch()["name"];
								$supplier_msg = " from $supplier .";
							}
							echo "<p class='d-block p-0 m-0'>".$message . $supplier_msg."</p>";
							echo "<strong class='d-block text-muted'>Price : Rs ".$h["unit_price"]." per unit for ".$unit ." = Rs ".$h["total_price"]."</strong>";
							if($h["total_price"] > $h["paid_price"])
								echo "<strong class='text-muted'>Paid : Rs ".$h["paid_price"]." (Partial Payment) </strong>";
							if($h["total_price"] = $h["paid_price"])
								echo "<strong class='text-muted d-block'>Paid : Rs ".$h["paid_price"]." (Full Payment) </strong>";
							if($h["remarks"]){
								echo "<a class=' cp bor-2 toggle-remarks' data-visibility='hidden'>show remarks</a>
											<pre class='p-0 m-0 remarks' style='display: none'><p class='bg-light p-2 border'>".$h["remarks"]."</p></pre>";
							}
						?>
					</div>
				</li>
				<?php
				}
				?>
			</ul>
		</div>
	</div>
	<script>
		$(document).ready(function () {
			$(".view-sales-history-btn").click(function () {
				$.ajax({
					type: "POST",
					url: root + "merchant/php/sales_history.php",
					beforeSend: function () {},
					success: function (response) {
						$(".inner-content").html(response);
					}
				})
			})
		})

		$(".toggle-remarks").click(function () {
			var remarks = this.parentElement.querySelector(".remarks");
			if ($(this).attr("data-visibility") == "hidden") {
				$(this).text("hide remarks");
				$(this).attr("data-visibility", "shown");
				$(remarks).show();
			} else if ($(this).attr("data-visibility") == "shown") {
				$(this).text("view remarks");
				$(this).attr("data-visibility", "hidden");
				$(remarks).hide();
			}
		})
	</script>
</div>