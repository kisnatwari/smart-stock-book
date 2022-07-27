<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
 ?>
<div class="row animate__animated animate__fadeIn">
	<style>
		.category-list-item .category-config{
			transform: translateX(110%);
		}
		.category-list-item:hover .category-config{
			transform: translateX(0);
		}
		.create-category-card{
			min-width: 300px;
		}
		.show-category-card{
			min-width: 260px;
		}
		@media only screen and (min-width: 992px) {
			.create-category-card{
				min-width: 300px;
			}
			.show-category-card{
				min-width: 350px;
			}
		}
	</style>
    <div class="category-design-conainer d-flex flex-wrap justify-content-start ">

    	<!-- category create card -->
        <div class="card create-category-card p-0 my-4 sh mx-sm-2 mx-lg-3" style="height: fit-content;">
            <div class="card-header bg-transparent border-bottom bor-1 text-dark"><b>Create a new Category</b></div>
            <div class="card-body py-0">
                <div class="new-category-field">
                    <div class="input-group border-0 border-bottom bor-2 my-2">
                        <input type="text" name="category" class="category-field form-control shadow-none border-0 rounded-0" placeholder="Mobile Devices" style="border-width: 1px !important;">
                    </div>
                </div>
                <button class="btn btn-sm add-new-btn my-2 text-1 shadow-none"><b><i class="fas fa-plus-circle"></i> Add New</b></button>
            </div>
            <div class="card-footer bg-transparent border-0 text-end pt-0">
                <button class="btn category-submit-btn text-white bg-1 shadow-none border"> <i class="fa fa-save"></i> &nbsp;Save Categories</button>
                <div class="categories-save-message"></div>
            </div>
        </div> 


        <!-- card Showing Categories  -->
        <div class="card show-category-card p-0 sh my-4 mx-sm-2 mx-lg-3">
            <div class="card-header bg-transparent border-bottom bor-1 text-dark"><b>Created Categories</b></div>
            <div class="card-body p-0">
                <ul class="list-group category-list">
                	<?php 
                	$categories = $db -> where("categories", "merchant_id", "=", $_SESSION["logged_in_merchant"]);
                		foreach($categories as $category){
                		?>
                	<li class="list-group-item category-list-item  border-0 p-0 mb-1 mt-0 d-flex align-items-center overflow-hidden" data-category-id='<?php echo $category["id"] ?>'>
                		<p class="cat-name px-2 pt-2 m-0"><?php echo $category["category"]	 ?></p>
                		<div class="rounded category-config h-100 bg-1 position-absolute border px-1" style="top: 0; right: 0;">
                			<span class="fa fa-edit edit-category h-100 d-inline-flex align-items-center justify-content-start px-2 bg-1 text-white cp" onclick="edit_category(this)"> </span>
                			<span class="fa fa-save d-none save-category h-100 d-inline-flex align-items-center justify-content-start px-2 bg-1 text-white cp"> </span>
                			<span class="fa fa-trash delete-category h-100 d-inline-flex align-items-center justify-content-start px-2 bg-1 text-white cp" data-name="<?php echo $category["category"]	 ?>" onclick="delete_category(this)" ></span>
                		</div>
                	</li>
                	<p class="category-list-message p-0 m-0 border-bottom"></p>
                	<?php
                	}
                	 ?>
                </ul>
            </div>
        </div>
    </div>
    <script>

    $(".add-new-btn").click(function() {
        var btn_code = `
       			<div class="input-group border-0 border-bottom bor-2 my-2">
					<input type="text" name="category" class="category-field form-control shadow-none border-0 rounded-0" placeholder="Mobile Devices" style="border-width: 1px !important;">
					<div class="input-group-append">
						<button class="input-group-text bg-transparent border-0 btn" onclick='del_category_field(this.parentElement.parentElement)'><span class="fas fa-times"></span></button>
					</div>
				</div>`;
        $(".new-category-field").append(btn_code);
        document.getElementsByClassName("category-field")[document.getElementsByClassName("category-field").length - 1].focus();
    })

    function del_category_field(inp_elem) {
        inp_elem.remove();
    }

	//submitting new categories
    $(".category-submit-btn").click(function(){
    	var categories = [];
    	$(".category-field").each(function(){
    		if($(this).val())
    			categories.push($(this).val());
    		if(categories.length == 0)
    			alert("No Categories");
    	})

		//sending new categories as ajax request
    	$.ajax({
    		type: "POST",
    		data: {
    			categories: categories
    		},
    		url: root+"merchant/php/save_categories.php",
    		beforeSend: function(){
				$(".category-submit-btn").attr("disabled", "true");
    			$(".categories-save-message").html("<p class='m-0 py-2'> <i class='fas fa-spin fa-spinner'></i> Saving Categories! Please Wait!!</p>")
    		},
    		success: function(response){
    			console.log(response);
				$(".category-submit-btn").removeAttr("disabled");

				//if categories added successfully
    			if(response["status"] == "data-updated"){
					$(".new-category-field").html(`<div class="input-group border-0 border-bottom bor-2 my-2">
                        <input type="text" name="category" class="category-field form-control shadow-none border-0 rounded-0" placeholder="Mobile Devices" style="border-width: 1px !important;">
                    </div>`);
    				var categories = JSON.parse(response["message"]);
    				$("ul.list-group.category-list").html("");
    				$(categories).each(function(){
    					$("ul.list-group.category-list").append(` 
    						<li class="list-group-item category-list-item border-0 p-0 mb-1 mt-0 d-flex align-items-center overflow-hidden" data-category-id='${this["id"]}'>
		                		<p class="cat-name p-2 m-0">${this["category"]}</p>
		                		<div class="rounded category-config h-100 bg-1 position-absolute border px-1" style="top: 0; right: 0;">
		                			<span class="fa fa-edit edit-category h-100 d-inline-flex align-items-center justify-content-start px-2 bg-1 text-white cp" onclick="edit_category(this)"> </span>
		                			<span class="fa fa-save d-none save-category h-100 d-inline-flex align-items-center justify-content-start px-2 bg-1 text-white cp"> </span>
		                			<span class="fa fa-trash delete-category h-100 d-inline-flex align-items-center justify-content-start px-2 bg-1 text-white cp" onclick="delete_category(this)"  data-name=${this["category"]}></span>
		                		</div>
		                	</li>
		                	<p class="category-list-message p-0 m-0 border-bottom"></p>
    					 `);
    				})
    				$(".categories-save-message").html("<p class='m-0 py-2 text-2'> <b><i class='fas fa-check'></i> New categories saved successfully....</b></p>");
    			}
    			else if(response["status"] == "error")
    				$(".categories-save-message").html("<p class='m-0 py-2 text-3'> <b> <i class='fas fa-times'></i> "+response["message"]+" </b></p>");
    			setTimeout(function(){
    				$(".categories-save-message").html("");
    			},5000);
    		}
    	})
    })

    function edit_category(edit_icon){
    	var category_id = edit_icon.parentElement.parentElement.getAttribute("data-category-id");
    	$(edit_icon).addClass("d-none");
    	var save_icon = edit_icon.nextElementSibling;
    	$(save_icon).removeClass("d-none");
    	var cat_name_con = edit_icon.parentElement.previousElementSibling;
    	var old_category = cat_name_con.innerText;
    	$(cat_name_con).attr("contenteditable", true);
    	cat_name_con.focus();
    	$(cat_name_con).attr("data-edit-mode", true);
    	cat_name_con.onkeypress = function(event){
    		if(event.keyCode == 13){
    			if($(cat_name_con).attr("data-edit-mode"))
    				$(save_icon).click();
    			return false;
    		}
    	}
    	$(save_icon).click(function(){
			if($(cat_name_con).attr("data-edit-mode")){
				$(cat_name_con).removeAttr("data-edit-mode");
				$(cat_name_con).removeAttr("contenteditable");
				var new_category = $(cat_name_con).text();
				$(save_icon).addClass("d-none");
				$(edit_icon).removeClass("d-none");
				$.ajax({
					type: "POST",
					url: root+"merchant/php/update_categories.php",
					data:{
						id: category_id,
						category: new_category,
						old_category: old_category
					},
					success: function(response){
						console.log(response);
						if(response["status"] == "success"){
							edit_icon.parentElement.parentElement.nextElementSibling.innerHTML = `<div class='message-${category_id}'><strong class='text-1'> <i class='fas fa-check'></i> ${response["message"]} </strong>	</div>`;
							setTimeout(function(){
								$(`.message-${category_id}`).remove();
							},2000);
						}
						else if(response["status"] == "error"){
							edit_icon.parentElement.parentElement.nextElementSibling.innerHTML = `<div class='message-${category_id}'><strong class='text-3'> <i class='fas fa-times'></i> ${response["message"]} </strong>	</div>`;
							setTimeout(function(){
								$(`.message-${category_id}`).remove();
							},2000);
						}
					}
				})
				return false;
			}
		})
    }


    function delete_category(delete_icon){
    	var category_con = delete_icon.parentElement.parentElement;
    	var category_id = $(category_con).attr("data-category-id");
    	var category_name = delete_icon.getAttribute("data-name");
    	ssb_dialog({
    		message: `<h5><b>Are You Sure to delete the category  "${category_name}"?</b></h5>
                <h6>Deleting this will also delete all the associated brands, products and history that depends on this category</h6>`,
            okay: function(){
            	$.ajax({
		    		type: "POST",
		    		url : root+"merchant/php/delete_categories.php",
		    		data: {
		    			id: category_id
		    		},
		    		success: function(response){
		    			$(category_con).html(response["message"]);
		    			setTimeout(function(){$(category_con).remove()},2500);
		    		}
		    	})
            },
            cancel: function(){}
    	})
    	return;
    }
    </script>
</div>