<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	$categories = $db -> where("categories", "merchant_id", "=", $_SESSION["logged_in_merchant"]);
 ?>
<div class="row animate__animated animate__fadeIn">
	<?php 
	if(count($categories) > 0){
	 ?>
	<style>
		.brand-list-item .brand-config{
			transform: translateX(110%);
		}
		.brand-list-item:hover .brand-config{
			transform: translateX(0);
		}
		.create-brand-card{
			min-width: 300px;
		}
		.show-brand-card{
			min-width: 260px;
		}
		@media only screen and (min-width: 992px) {
			.create-brand-card{
				min-width: 300px;
			}
			.show-brand-card{
				min-width: 350px;
			}
		}
	</style>
    <div class="brand-design-conainer d-flex flex-wrap justify-content-start ">
    	<!-- brand create card -->
        <div class="card create-brand-card p-0 my-4 sh mx-sm-2 mx-lg-3" style="height: fit-content;">
            <div class="card-header bg-transparent border-bottom bor-1 text-dark"><b>Create a new Brand From Here</b></div>
            <div class="card-body py-0">
                <div class="new-brand-field my-2">
                	<div class="form-group">
                		<label for="choose-cat">Choose Category</label>
                		<select name="category" id="set-brand-choose-cat" class="shadow-none border form-control">
                			<?php 
                				foreach($categories as $category)
                					echo '<option value="'.$category["id"].'">'.$category["category"].'</option>';
                			 ?>
                		</select>
                	</div>
                	<div class="form-group mt-2 brand-name-field">
                		<p class="m-0 p-0">Brand Name</p>
                		<input type="text" class="form-control border-0 border-bottom bor-2 rounded-0 shadow-none brand-field" placeholder="brand name here (Nokia)">
                	</div>
                </div>
                <button class="btn btn-sm add-new-btn my-2 text-1 shadow-none"><b><i class="fas fa-plus-circle"></i> Add New</b></button>
            </div>
            <div class="card-footer bg-transparent border-0 text-end pt-0">
                <button class="btn brand-submit-btn text-white bg-1 shadow-none border"> <i class="fa fa-save"></i> &nbsp;Save Brands</button>
                <div class="brands-save-message"></div>
            </div>
        </div>


        <!-- card Showing Brands  -->
        <div class="card show-brand-card p-0 sh my-4 mx-sm-2 mx-lg-3" style="height: fit-content;">
            <div class="card-header bg-transparent border-bottom bor-1 text-dark"><b>Assigned Brands</b></div>
            <div class="card-body p-2">
            	<div class="input-group">
            		<div class="input-group-prepend">
            			<label for="list-cat-select" class="input-group-text bg-transparent border-0">Show Brands Under : </label>
            		</div>
            		<select name="category" class="shadow-none border form-control" id="list-cat-select" onchange="get_brands()">
            			<?php 
            				foreach($categories as $category)
            					echo '<option value="'.$category["id"].'">'.$category["category"].'</option>';
            			 ?>
            		</select>
            	</div>
            	<ul class="list-group brands-list">
            		
            	</ul>
            </div>
        </div>
    </div>
    <script>
    $(".add-new-btn").click(function() {
        var input_code = `<div class="input-group border-bottom bor-2 mt-2">
                			<input type="text" class="form-control border-0 rounded-0 shadow-none brand-field" placeholder="brand name here">
                			<div class="input-group-append">
                				<span class="input-group-text bg-transparent fa fa-times p-0 border-0 cp" onclick="del_brand_field(this.parentElement.parentElement)"></span>
                			</div>
                		</div>`;
        $(".brand-name-field").append(input_code);
        document.getElementsByClassName("brand-field")[document.getElementsByClassName("brand-field").length - 1].focus();
    })

    function del_brand_field(inp_elem) {
        inp_elem.remove();
    }



    $(".brand-submit-btn").click(function(){
    	var category = $("#set-brand-choose-cat").val();
    	var brands = document.querySelectorAll(".create-brand-card .brand-field");
    	var brand_list = [];
    	$(brands).each(function(){
    		if($(this).val().length > 0)
    			brand_list.push($(this).val());
    	})
    	if(brand_list.length == 0) return;
    	$.ajax({
    		type: "POST",
    		url: root+"merchant/php/save_brands.php",
    		data: {
    			category : category,
    			brands: brand_list
    		},
    		beforeSend: function(){
				$(".brands-save-message").html("<p class='m-0 py-2'> <i class='fas fa-spin fa-spinner'></i> Saving Brands! Please Wait!!</p>");
				$(".brand-submit-btn").attr("disabled", "true");
			},
    		success: function(response){
    			console.log(response);
				if(response["status"] == "success"){
					$(".brands-save-message").html("<p class='m-0 py-2 text-1'> <i class='fas fa-check'></i> Brands Saved Successfully!!</p>");
					$(".brand-submit-btn").removeAttr("disabled");
				}
				else if(response["status"] == "error"){
					$(".brands-save-message").html("<p class='m-0 py-2 text-3'> <i class='fas fa-times'></i> "+response["message"]+"</p>");
					$(".brand-submit-btn").removeAttr("disabled");
				}
				$(".brand-name-field").html(`
					<p class="m-0 p-0">Brand Name</p>
            		<input type="text" class="form-control border-0 border-bottom bor-2 rounded-0 shadow-none brand-field" placeholder="brand name here (Nokia)">
				`);
				setTimeout(function(){
					$(".brands-save-message").html("");
				},2500)
				get_brands();
    		}
    	})
    })

    get_brands();
    function get_brands(){
    	var category = $("#list-cat-select").val();
    	if(category){
    		$.ajax({
    			type: "POST",
    			url: root+"merchant/php/get_brands.php",
    			data: {category:category},
    			beforeSend: function(){
    				$(".brands-list").html('<p class="text-center py-2 text-1"><i class="fa fa-spinner fa-spin my-3" style="font-size:30px"></i><br> Getting Brands list.. <br> Please wait...</p>');
    			},
    			success: function(response){
    				console.log(response);
    				$(".brands-list").html("");
    				if(response["status"]=="brands"){
    					$(response["message"]).each(function(){
    						$(".brands-list").append(`
	    					<li class="list-group-item brand-list-item border-0 p-0 mb-1 mt-0 d-flex align-items-center overflow-hidden" data-brand-id='${this["id"]}'>
			                		<p class="brand-name p-2 m-0">${this["brand"]}</p>
			                		<div class="rounded brand-config h-100 bg-1 position-absolute border px-1" style="top: 0; right: 0;">
			                			<span class="fa fa-edit edit-brand h-100 d-inline-flex align-items-center justify-content-start px-2 bg-1 text-white cp" onclick="edit_brand(this)"> </span>
			                			<span class="fa fa-save d-none save-brand h-100 d-inline-flex align-items-center justify-content-start px-2 bg-1 text-white cp"> </span>
			                			<span class="fa fa-trash delete-brand h-100 d-inline-flex align-items-center justify-content-start px-2 bg-1 text-white cp" onclick="delete_brand(this)"  data-name="${this["brand"]}"></span>
			                		</div>
			                	</li>
			                	<p class="brand-list-message p-0 m-0 border-bottom"></p>
	    					`)
    					})
    				}
    			}
    		})
    	}
    }

        function edit_brand(edit_icon){
    	var brand_id = edit_icon.parentElement.parentElement.getAttribute("data-brand-id");
    	$(edit_icon).addClass("d-none");
    	var save_icon = edit_icon.nextElementSibling;
    	$(save_icon).removeClass("d-none");
    	var brand_name_con = edit_icon.parentElement.previousElementSibling;
    	var old_brand = brand_name_con.innerText;
    	$(brand_name_con).attr("contenteditable", true);
    	brand_name_con.focus();
    	$(brand_name_con).attr("data-edit-mode", true);
    	brand_name_con.onkeypress = function(event){
    		if(event.keyCode == 13){
    			if($(brand_name_con).attr("data-edit-mode"))
    				$(save_icon).click();
    			return false;
    		}
    	}
    	$(save_icon).click(function(){
			if($(brand_name_con).attr("data-edit-mode")){
				$(brand_name_con).removeAttr("data-edit-mode");
				$(brand_name_con).removeAttr("contenteditable");
				var new_brand = $(brand_name_con).text();
				$(save_icon).addClass("d-none");
				$(edit_icon).removeClass("d-none");
				$.ajax({
					type: "POST",
					url: root+"merchant/php/update_brands.php",
					data:{
						id: brand_id,
						brand: new_brand,
						old_brand: old_brand,
						category: $("#list-cat-select").val()
					},
					success: function(response){
						if(response["status"] == "success"){
							edit_icon.parentElement.parentElement.nextElementSibling.innerHTML = `<div class='message-${brand_id}'><strong class='text-1'> <i class='fas fa-check'></i> ${response["message"]} </strong>	</div>`;
							setTimeout(function(){
								$(`.message-${brand_id}`).remove();
							},2000);
						}
						else if(response["status"] == "error"){
							edit_icon.parentElement.parentElement.nextElementSibling.innerHTML = `<div class='message-${brand_id}'><strong class='text-3'> <i class='fas fa-times'></i> ${response["message"]} </strong>	</div>`;
							setTimeout(function(){
								$(`.message-${brand_id}`).remove();
							},2000);
						}
					}
				})
				return false;
			}
		})
    }

    function delete_brand(delete_icon){
    	var brand_con = delete_icon.parentElement.parentElement;
    	var brand_id = $(brand_con).attr("data-brand-id");
    	var brand_name = delete_icon.getAttribute("data-name");
    	ssb_dialog({
    		message: `<h5><b>Are You Sure to delete the brand  "${brand_name}"?</b></h5>
                <h6>The products under this brand will be assigned under "no brand".</h6>`,
            okay: function(){
            	$.ajax({
		    		type: "POST",
		    		url : root+"merchant/php/delete_brands.php",
		    		data: {
		    			id: brand_id
		    		},
		    		success: function(response){
		    			$(brand_con).html(response["message"]);
		    			setTimeout(function(){$(brand_con).remove()},2500);
		    		}
		    	})
            },
            cancel: function(){}
    	})
    	return;
    }

    </script>
<?php }
else{
	?>

	<div class="text-center py-5">
		<div class="mt-5 bg-white sh-lg rounded p-5 pb-3 d-inline-block text-1">
			<p class="border-0 border-bottom bor-1 pb-3" style="border-bottom-style: dashed !important;"><b>No categories has been Found!! <br> Please create a list of product categories <br> To get started with brands</b> <br><p>
		</div>
	</div>

	<?php
}

 ?>
</div>