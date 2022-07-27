<?php 
	require "../../common/db.php";
	require "../../common/imp_functions.php";
	if(empty($_POST['id']))
		show_error_string("No Product Found");
	$id  = sanitize_var($_POST['id']);
	$products = $pdo -> prepare("SELECT `id` FROM `products` WHERE `merchant_id` = ?");
	$products -> execute([$_SESSION["logged_in_merchant"]]);
	if($products -> rowCount() < 1)
		show_error_string("No products found");
	$products =$products -> fetchAll();
	$items = [];
	foreach($products as $product)
		array_push($items, $product["id"]);
	if(!in_array($id, $items))
		show_error_string("No products found");

	$product = $pdo -> prepare("SELECT * FROM products INNER JOIN categories ON products.category_id = categories.id WHERE products.id = ? AND products.merchant_id = ?");
	$product -> execute([$id, $_SESSION["logged_in_merchant"]]);
	if($product -> rowCount() < 1)
		show_error_string("No products found");
	$product = $product -> fetch();
	$brand = "NO BRAND";
	if(!empty($product["brand_id"])){
		$brand = $pdo -> prepare("SELECT brand FROM brands WHERE id = ?");
		$brand  -> execute([$product["brand_id"]]);
		$brand = $brand -> fetch()["brand"];
	}
 ?>
  <div class="p-3" style="max-width: 1000px;">
 	<div class="row bg-white sh">
 		<div class="col-md-5 p-3">
 			<img src="<?php echo $root."merchant/stocks/".$product["photo"] ?>" alt="" class="w-100"></div>
 		<div class="bg-white col-md-7 p-3 text-start">
 			<br>
 			<h3><?php echo $product["name"] ?></h3>
 			<h5>Category: <?php echo $product["category"] ?></h5>
 			<h5>Brand: <?php echo $brand ?></h5>
 			<h5>Available Stocks: <?php echo $product["available_stock"] ?></h5>
 			<h5>Product Description:</h5>
 			<h6><?php echo $product["description"] ?></h6>
 			
 			<?php if($product["available_stock"] > 0)
 				echo '<button class="btn bg-1 text-white rounded-0 btn-sm sold-now-btn" data-value="'.$id.'">Assign to bill</button>'; ?>
 		</div>
 	</div>
 	<script>
 	$(".sold-now-btn").click(function(){
		sessionStorage.setItem("products_to_sold", $(this).attr("data-value"));
		$(".sale-btn").click();
	})
 	</script>
 </div>