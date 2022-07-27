<div class="supplier-details">
	<style>
		.action-icon{
			border-radius: 5px;
			color: var(--color1);
		}
		.action-icon:hover{
			color: white;
			background-color: var(--color1);
		}
	</style>
	<div class="supplier-container bg-white text-center p-3 my-3 sh-lg">
		<div class=" text-end px-3 d-flex justify-content-between flex-wrap">
			<button class="btn btn-sm border bor-2 text-2 m-0 shadow-none new-supplier-btn" style="border-radius: 20px;"><b> <i class="fa  fa-plus"></i> &nbsp; Create New Supplier</b></button>
			<i class="close-icon fa fa-times text-1 cp" style="font-size: 20px;"></i>
		</div>
		<br>
		<?php 
		require_once "../../common/db.php";
		require_once "../../common/imp_functions.php";
		$merchant_id = $_SESSION["logged_in_merchant"];
		$stmt = $pdo -> prepare("SELECT * FROM suppliers WHERE `merchant_id` = $merchant_id");
		$stmt -> execute();
		$suppliers = $stmt -> fetchAll();
		if($stmt -> rowCount() > 0){
		 ?>
		<h3 class="p-0 m-0 mb-3">Available Suppliers</h3>
		<table class="table">
			<thead class="border-0 border-bottom bor-2">
				<tr>
					<th class="border-0">Supplier Name</th>
					<th class="border-0">Supplier Email</th>
					<th class="border-0">Supplier Contact</th>
					<th class="border-0">Address</th>
					<th class="border-0">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					foreach($suppliers as $supplier){
						?>
						<tr data-id = "<?php echo $supplier["id"]; ?>" data-edit-mode="false">
							<td>
								<h6 class="py-1"><?php echo $supplier["name"] ?></h6>
							</td>
							<td>
								<h6 class="py-1"><?php echo $supplier["email"] ?></h6>
							</td>
							<td>
								<h6 class="py-1"><?php echo $supplier["contact"] ?></h6>
							</td>
							<td>
								<h6 class="py-1"><?php echo $supplier["address"] ?></h6>
							</td>
							<td>
								<div class="action-box d-flex justify-content-center align-items-center">
									<i class="fa fa-save cp p-2 action-icon d-none" title="Save Supplier"> </i>
									<i class="fa fa-edit cp p-2 action-icon" title="Edit Supplier" onclick="edit_supplier(this)"> </i>
									<i class="fa fa-trash cp p-2 action-icon" title="Delete Supplier" onclick="delete_supplier(this)"> </i>
								</div>
							</td>
						</tr>
						<?php
					}
				 ?>
			</tbody>
		</table>
	<?php } ?>
	</div>
	<script>
		$(".close-icon").click(function(){
			$('.dashboard-button').click();
		});
		$(".new-supplier-btn").click(function(){
			$.ajax({
				type: "POST",
				url: root+"merchant/php/add_supplier_design.php",
				data:{
					reload_on_success : true
				},
				success: function(response){
					$(".main-content").addClass("position-fixed");
					$(".external-content").html(response);
				}
			})
		})

		//edit and save supplier's detail
		function edit_supplier(edit_icon){
			var data_saved = false;
			var save_icon = edit_icon.previousElementSibling;
			var row = edit_icon.parentElement.parentElement.parentElement;
			if($(row).attr("data-edit-mode") == "true")		return;
			$(row).attr("data-edit-mode", "true");
			$(edit_icon).addClass("d-none");
			$(save_icon).removeClass("d-none");
			var elements = row.querySelectorAll("td h6");
			var row_id = $(row).attr("data-id");
			$(elements).each(function(){
				$(this).attr("contenteditable", true);
				$(this).addClass("border border-1");
				$(this).removeClass("border-0");
				$(this).addClass("bor-1");
				$(this).addClass("text-1");
			})
			elements[0].focus();
			$(save_icon).click(function(){
				if(data_saved) return;
				$(save_icon).addClass("d-none");
				$(edit_icon).removeClass("d-none");
				$(elements).each(function(){
					$(this).removeAttr("contenteditable");
					$(this).removeClass("border border-1");
					$(this).addClass("border-0");
					$(this).removeClass("bor-1");
					$(this).removeClass("text-1");
				})
				$.ajax({
					type: "POST",
					url: root+"merchant/php/edit_supplier.php",
					data:{
						id: row_id,
						supplier_name: elements[0].innerText,
						supplier_email: elements[1].innerText,
						supplier_contact: elements[2].innerText,
						supplier_address: elements[3].innerText
					},
					beforeSend: function(){
						$(edit_icon).removeClass("fa-edit");
						$(edit_icon).addClass("fa-spinner fa-spin");
					},
					success: function(response){
						console.log(response);
						$(edit_icon).removeClass("fa-spinner fa-spin");
						$(edit_icon).addClass("fa-check");
						data_saved = true;
						setTimeout(function(){
							$(edit_icon).removeClass("fa-check");
							$(edit_icon).addClass("fa-edit");
							$(row).attr("data-edit-mode", "false");
						},2000);
					}
				})
				return false;
			})
			return false;
		}


		function delete_supplier(del_icon){
			var row = del_icon.parentElement.parentElement.parentElement;
			$.ajax({
				type: "POST",
				url: root+"merchant/php/delete_supplier.php",
				data: {
					id: $(row).attr("data-id")
				},
				beforeSend: function(){
					$(del_icon).removeClass("fa-trash");
					$(del_icon).addClass("fa-spinner fa-spin");
				},
				success: function(response){
					row.remove();
					console.log(response);
				}
			})
		}
	</script>
</div>