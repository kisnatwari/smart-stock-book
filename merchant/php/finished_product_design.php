<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	$merchant_id = $_SESSION["logged_in_merchant"];
	$query = $pdo -> prepare("SELECT products.id, categories.category, products.category_id, products.name, products.marked_price, products.photo, products.available_stock FROM products 	INNER JOIN categories on products.category_id = categories.id
		WHERE products.merchant_id = $merchant_id AND available_stock = 0 ORDER BY id DESC");
	$query -> execute();
 ?>

<div class="row animate__animated text-center animate__fadeIn">
	<div class="d-flex justify-content-start align-items-end" style="max-width: 1200px;">
		<?php 
			if($query -> rowCount() == 0){
				?>
				<h4>No finished Products Available</h4>
				<?php
			}

			else{
				$products = $query -> fetchAll();
				foreach($products as $p){
			  ?>
				<div class="card product-card m-3 sh pt-3" style="width: 250px; height: fit-content;">
					<div class="img-conainer" style="height: 200px; background-image: url(<?php echo $root."merchant/stocks/".$p["photo"] ?>); background-size: contain; background-position: center; background-repeat: no-repeat;"></div>
					<h5 class="mt-2 text-muted"><strong><?php echo $p["name"] ?></strong></h5>
					<h6><?php echo $p["category"] ?></h6>
					<button class="bg-1 text-white" onclick="purchase('<?php echo $p['id'] ?>')">Purchase</button>
				</div>
			<?php
			 }
		}
		 ?>
	</div>
	<script>
		function purchase(id){
			sessionStorage.setItem("purchase_existing_id", id);
			$(".purchase-btn").click();
		}
	</script>
</div>