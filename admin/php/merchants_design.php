<div class="row animate__animated animate__fadeIn">
	<?php 
		require_once "../../common/db.php";
		require_once "../../common/imp_functions.php";
	 ?>
	<div class="container p-3 sh-lg" style="background-color: rgb(248, 248, 248); min-height: 550px;">
		<?php 
			$merchants = $pdo -> prepare("SELECT count('id') as num_merchants FROM merchants");
			$merchants -> execute();
			$num_m = $merchants -> fetch()["num_merchants"];

			$products = $pdo -> prepare("SELECT count('id') as num_merchants FROM products");
			$products -> execute();
			$num_p = $products -> fetch()["num_merchants"];
		 ?>
		 <div class="number-summary d-flex justify-content-start flex-wrap">
			 <div class="merchants-num bg-white px-2 py-2 d-flex justify-content-center m-3 sh" style="width: fit-content; border-radius: 50px;">
			 	<div class="image text-white bg-2 p-2 rounded-circle d-flex justify-content-center align-items-center" style="width: 60px; height: 60px;">
			 		<i class="fa fa-users" style="font-size:  30px"></i>
			 	</div>
			 	<div class="merchants-num-con  px-2 d-flex align-items-center text-2">
			 		<h3 class='m-0 p-0'><strong> <?php echo $num_m ?> <?php echo $num_m > 1 ? " Merchants " : " Merchant "?> Registered</strong></h3>
			 	</div>
			 </div>


			  <div class="products-num bg-2 px-2 py-2 d-flex justify-content-center m-3 sh" style="width: fit-content; border-radius: 50px;">
			 	<div class="image text-2 bg-white p-2 rounded-circle d-flex justify-content-center align-items-center" style="width: 60px; height: 60px;">
			 		<i class="fa fa-users" style="font-size:  30px"></i>
			 	</div>
			 	<div class="products-num-con  px-2 d-flex align-items-center text-white">
			 		<h3 class='m-0 p-0'><strong> <?php echo $num_p ?> <?php echo $num_p > 1 ? " Products " : " Product "?> Registered</strong></h3>
			 	</div>
			 </div>
		 </div>

		<div class="merchants bg-white p-2" style="width: fit-content;">
			<?php 
				$merchants = $db -> select_all("merchants");
				if(count($merchants) > 0){
					?>
					<ul class="list-group bg-2 border bor-2">
						<li class="list-group-item list-group-item-header bg-2 text-white"><h5 class="p-0 m-0"><strong>Registered Merchants</strong></h5></li>
						<?php 
							foreach($merchants as $m){
								?>
								<li class="d-flex border p-3 list-group-item list-group-item-action">
									<img src="<?php echo $root.'merchant/logos/'.$m["logo"] ?>" alt="<?php echo $m["name"] ?>"
										class="flex-shrink-0 me-3 mt-3" style="max-width:60px;max-height:60px; object-fit: contain;">
									<div>
										<?php
											echo "<h5><strong>${m["name"]}</strong></h5>";
											$stmt = $pdo -> prepare("SELECT count(id) FROM products WHERE merchant_id = ?");
											$stmt -> execute([$m["id"]]);
											$p = $stmt -> fetch()["count(id)"];
											echo "<strong>$p Products Assigned</strong>";
										?>
									</div>
								</li>
								<?php
							}
						 ?>
					</ul>
					<?php
				}
			 ?>
		</div>

	</div>

</div>