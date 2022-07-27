	<?php 
		require_once "../../common/db.php";
		require_once "../../common/imp_functions.php";
		$merchant_id = $_SESSION['logged_in_merchant'];
		$all_stocks = $pdo -> prepare("
			SELECT products.id, categories.category, products.category_id, products.name, products.marked_price, products.photo, products.available_stock FROM products
			INNER JOIN categories on products.category_id = categories.id WHERE products.merchant_id = $merchant_id AND products.available_stock > 0 ORDER BY id DESC");
		$all_stocks -> execute();
		$all_stocks = $all_stocks -> fetchAll();
		$keys = array_keys($all_stocks);
		for($i = 0; $i < count($keys); $i++){
			$all_stocks[$keys[$i]]["brand_id"] = $db -> where("products", "id", "=", $all_stocks[$keys[$i]]["id"], false)["brand_id"];
		}
		$stocks = $all_stocks;
		?>
<div class="animate__animated text-center animate__fadeIn products-main-container" >
<?php 
echo "<script> var all_stocks = ".json_encode($all_stocks).";</script>";
if(count($stocks) > 0){
	echo "<script> var stocks = ".json_encode($stocks).";</script>";
 ?>
	<style>
		.product-card:hover{
			transform: scale(1.01);
		}
		.img{
			background-size: contain;
			background-repeat: no-repeat;
			height: 220px;
			background-position: center;
		}
		.product-header-buttons i.active{
			background-color: #EEF;
			box-shadow: 0 0 3px #666;
		}
	</style>
	<div class="text-start position-relative">
		<button class="ms-auto bg-transparent border bor-2 text-2 btn px-5 my-3 show-filter" style="border-radius: 20px;"><strong> <i class="fa fa-filter me-2"></i>  Filter Products</strong></button>
		<div class="filter-ext-con position-absolute" style="max-height: 100%; max-width: 310px; z-index: 10; top: 0;">
			<div class="filter-con animate__animated animate__fadeIn animate-faster border p-2 bg-light sh-lg" style="display: none; overflow: auto;">
				<div class="text-end pe-1 pt-1"></div>
				<h4 class="p-0 m-0 ps-1 text-start d-flex justify-content-between">
					<span>Filter Products</span>
					<i class="fa fa-times filter-close cp" style="font-size: 18px;"></i>
				</h4>
				<div class="accordion mt-2">

					<!-- filter by price -->
					<div class="card sh-sm rounded-0 my-2" style="width: 270px;">
						<div class="card-header py-0 bg-white border-0 border-bottom bor-1">
							<a href="#priceFilter" class="btn shadow-none" data-bs-toggle="collapse">
							<strong style="font-size: 18px;">Filter By Price</strong>
							</a>
						</div>
						<div class="card-body collapse show" id="priceFilter" data-bs-parent="#accordion">
							<input type="number" class="form-control shadow-none border my-1 mb-2 min-price" placeholder="Minimum Price" min="1">
							<input type="number" class="form-control shadow-none border my-2 max-price" placeholder="Maximum Price" min="1">
							<p class="price-filter-message"></p>
							<button class="border bor-2 bg-1 text-white form-control shadow-none filter-price">Filter Now</button>
						</div>
					</div>

					<!-- filter by categories -->
					<div class="card sh-sm rounded-0 my-2" style="width: 270px;">
						<div class="card-header py-0 bg-white border-0 border-bottom bor-1">
							<a href="#catFilter" class="btn shadow-none" data-bs-toggle="collapse">
							<strong style="font-size: 18px;">Filter By Categories</strong>
							</a>
						</div>
						<div class="card-body collapse show p-0 text-start bg-info" id="catFilter" data-bs-parent="#accordion">
							<ul class="list-group" id="cat-accordion">
								<?php 
								$categories = $db -> where("categories", "merchant_id", "=", $_SESSION['logged_in_merchant']);
								foreach($categories as $category){ ?>
									<li class='list-group-item'>
										<a class='btn shadow-none border-0 p-0 text-start' href='#c_<?php echo $category["id"]?>' data-bs-toggle="collapse"><?php echo $category["category"]?></a>
										<div id = "c_<?php echo $category["id"] ?>" class='collapse' data-bs-parent='#cat-accordion'>
											<?php 
											$brands = $pdo -> prepare("SELECT * FROM brands WHERE category_id = ? AND merchant_id = ?");
											$brands -> execute([$category["id"], $_SESSION['logged_in_merchant']]);
											$brands = $brands -> fetchAll();
											?>
											<ul class="list-group list-group-flush">
												<li class="list-group-item list-group-item-action cp" onclick="filter_categories(['category', <?php echo $category["id"] ?>])">All Products</li>
												<?php 
													foreach($brands as $brand){
														echo '<li class="list-group-item list-group-item-action cp" onclick="filter_categories('.$brand["id"].')">'.$brand["brand"].'</li>';
													}
												 ?>
											</ul>
										</div>
									</li>
								<?php
							}
								 ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
	
	

	<div class="py-3 sh rounded mx-auto overflow-auto position-relative" style="background-color: #FFFFFE;">
		
		<div class="product-header w-100 text-start">
			<h3 class="text-1 text-center">Available Stocks</h3>
			<div class="product-header-buttons d-flex justify-content-between flex-wrap">
				<div class="sold-btn-con">
					<button class="btn text-2 bor-2  px-5 sold-many-btn d-none" onclick="sold_many()" style="border-radius: 20px"> <i class="fa fa-money-bill"></i> <b> &nbsp; Sold Now </b></button>
				</div>
				
				<div class="btn-group mx-2" style="height: fit-content;">
					<i class="btn view-switch-btn fa fa-th mx-2 p-1 rounded active" onclick = "switch_view(this)" data-view-type="grid" style="font-size: 20px;"></i>
					<i class="btn view-switch-btn fa fa-table mx-2 p-1 rounded" onclick = "switch_view(this)" data-view-type="table" style="font-size: 20px;"></i>
				</div>
			</div>
			
		</div>
		
		<div class="products-container p-2 d-flex justify-content-start align-items-end flex-wrap">
		</div>
	</div>
<?php }
	else
		echo '	<div class="text-center py-5 animate__animated animate__fadeIn animate_faster">
		<div class="mt-5 bg-white sh-lg rounded p-5 pb-3 d-inline-block text-1">
			<p class="border-0 border-bottom bor-1 pb-3" style="border-bottom-style: dashed !important;"><b>No Products has been Found!! <br> Please purchase some products <br> in order to see the list of products</b> <br><p>
		</div>
	</div>';
?>

<script>

	function filter_categories(brand){
		if(typeof(brand) == "object"){
			if(brand.length == 2 && brand[0] == "category" && !isNaN(Number(brand[1]))){
				stocks = [];
				for(var s in all_stocks){
					if(all_stocks[s]["category_id"] == brand[1])
						stocks.push(all_stocks[s]);
				}
				show_products();
				$(".filter-close").click();
			}
			return;
		}
		console.log(brand);
		stocks = [];
		for(var s in all_stocks){
			console.log(all_stocks[s]["brand_id"] + " is not equals to " + brand);
			if(all_stocks[s]["brand_id"] == brand)
				stocks.push(all_stocks[s]);
		}
		show_products();
		$(".filter-close").click();
	}


	$(".filter-price").click(function(){
		var min = Number($(".min-price").val());
		var max = Number($(".max-price").val());
		if(isNaN(min) || isNaN(max)){
			$(".price-filter-message").html("<b class='text-4'>Error in mentioned price.</b>");
			setTimeout(function(){
				$(".price-filter-message").html("");
			}, 5000)
			return;
		}
		if(min < 1 || max < 1){
			$(".price-filter-message").html("<b class='text-4'>Mentioned price should be greater than 0.</b>");
			setTimeout(function(){
				$(".price-filter-message").html("");
			}, 5000)
			return;
		}
		if(min > max){
			$(".price-filter-message").html("<b class='text-4'>Minimum Price should not be greater than maximum price.</b>");
			setTimeout(function(){
				$(".price-filter-message").html("");
			}, 5000)
			return;
		}
		console.log(all_stocks);
		stocks = [];
		for(var s in all_stocks){
			if(all_stocks[s]["marked_price"] >= min && all_stocks[s]["marked_price"] <= max)
				stocks.push(all_stocks[s]);
		}
		console.log(stocks);
		show_products();
		$(".filter-close").click();
	})

	$(".show-filter").click(function(){
		$(".filter-con").css("display", "block");
	})

	$(".filter-close").click(function(){
		$(".filter-con").fadeOut(100);
	})


	function product_check(){
		if(document.querySelectorAll(".product-checkbox:checked").length > 0)
			$(".sold-many-btn").removeClass("d-none");
		else
			$(".sold-many-btn").addClass("d-none");
	}

		function sold_many(){
			var products = document.querySelectorAll(".product-checkbox:checked");
			var values = [];
			$(products).each(function(){
				values[values.length] = $(this).val();
			})
			sessionStorage.setItem("products_to_sold", values);
			$(".sale-btn").click();
		}



		function sold_one(element){
			sessionStorage.setItem("products_to_sold", $(element).attr("data-value"));
			$(".sale-btn").click();
		}



		function product_view(element){
			$.ajax({
				type: "POST",
				url: root+"merchant/php/product_view.php",
				data: {				id: $(element).attr("data-value")			},
				beforeSend: function(){},
				success: function(response){
					$(".products-main-container").html(response);
				}
			})
		}

		function show_products(){
			var mode = $(".product-header-buttons i.active").attr("data-view-type");
			$(".products-container").html("");
			if(mode == "grid"){
				for(var i = 0; i<stocks.length; i++){
					document.querySelector(".products-container").innerHTML += `
					<div class="product-card  border `+

					//applying border to finished products
					((stocks[i]["available_stock"] < 1) ? "bor-4" : "")
					//applying border finished
					
					+` m-2 p-2 pb-3 text-center sh position-relative" style="width: 240px; overflow: hidden">
					<input type="checkbox" class="product-checkbox position-absolute form-check-input" value ="${stocks[i]['id']}" style="right: 20px;" oninput="product_check()">
	                        <div class="product-image w-100" style="height: 250px; background-image:url('<?php echo $root ?>merchant/stocks/${stocks[i]['photo']}'); background-size: contain; background-position: center; background-repeat: no-repeat"></div>
	                        <strong>${stocks[i]['name']}</strong><br>
	                        <strong style="font-size: 13px;">${stocks[i]['category']}</strong><br> `+


	                        // checking available stocks and highlighting finished
	                        ((stocks[i]["available_stock"] > 0) ?
	                         `<strong style="font-size: 13px;">On Stock : ${stocks[i]['available_stock']}</strong>` : 
	                         `<strong class='text-4' style="font-size: 13px;">On Stock : ${stocks[i]['available_stock']}</strong>`)+
	                        // checking ends

	                        `<br>`+
	                        `<i>Price : Rs ${stocks[i]['marked_price']}</i><br>
	                        <div class="d-flex justify-content-around">` + 
	                        ((stocks[i]["available_stock"] > 0) ? 
	                        `<button class="btn btn-sm bg-1 text-light buy-now-btn my-1 px-3 rounded-0" onclick="sold_one(this)" data-value="${stocks[i]['id']}"> <i class="fa fa-money-bill-alt"></i> &nbsp; Sold</button>` : "" )+
	                        `<button class="btn btn-sm bg-1 text-light product-view-btn my-1 px-3 rounded-0" onclick="product_view(this)" data-value="${stocks[i]['id']}"> <i class="fa fa-eye"></i> &nbsp; View</button>
	                        </div>
	                    </div>
					`;
				}
			}
			else if(mode == "table"){

			var table = `
			<table class="table table-hover table-sm">
				<thead>
					<tr>
						<th></th>
						<th>Image</th>
						<th>Name</th>
						<th>Category</th>
						<th>Marked Price</th>
						<th>Available Stocks</th>
						<th>Actions</th>
					</tr>
				</thead>`;
				for(var i = 0; i<stocks.length; i++){
				table +=  `<tr>
					<td class="p-1" valign="middle">
						<input type="checkbox" class="product-checkbox form-check-input" value ="${stocks[i]['id']}" oninput="product_check()">
					</td>
					<td class="p-2" valign="middle">
						  <img src=" <?php echo $root ?>merchant/stocks/${stocks[i]['photo']}" alt="${stocks[i]['photo']}" style="max-width: 60px; max-height: 60px;"/>
					</td>
					<td class="p-2" valign="middle">${stocks[i]['name']}</td>
                    <td class="p-2" valign="middle">${stocks[i]['category']}</td>
                    <td class="p-2" valign="middle"> Rs ${stocks[i]['marked_price']}</td>
                    <td class="p-2" valign="middle">${stocks[i]['available_stock']}</td>
					<td class="p-2" valign="middle">
						<div class="btn-group">` + 
	                        ((stocks[i]["available_stock"] > 0) ? 
	                        `<button class="btn border btn-sm bg-1 text-light buy-now-btn my-1 rounded-0" onclick="sold_one(this)" data-value="${stocks[i]['id']}"> <i class="fa fa-money-bill-alt"></i> &nbsp; Sold</button>` : `` )+
							`<button class="btn border btn-sm bg-1 text-light product-view-btn my-1 rounded-0"  onclick="product_view(this)" data-value="${stocks[i]['id']}"> <i class="fa fa-eye"></i> &nbsp; View</button>
						</div>
					</td>
				</tr>`;
				}
				table += `	</table>`;
				document.querySelector(".products-container").innerHTML = table;
			}
		}
		show_products();
	//switch_view(document.querySelectorAll(".view-switch-btn")[1]);
	function switch_view(element){
		$(".product-header-buttons i.active").removeClass("active");
		$(element).addClass("active");
		show_products();
	}
</script>
</div>