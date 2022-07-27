<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
 ?>
 	<?php 
 	$user_info = $pdo -> prepare("SELECT * FROM users WHERE id = ?");
	$user_info -> execute([$_SESSION["logged_in_id"]]);
	$email = $user_info -> fetch()["email"];
	$merchant_info = $pdo -> prepare("SELECT * FROM merchants WHERE id = ?");
	$merchant_info -> execute([$_SESSION['logged_in_merchant']]);
	$merchant_info = $merchant_info -> fetch();
	$logo = $merchant_info["logo"];
	if(!empty($logo))
		$logo = $root."merchant/logos/".$logo;
	else
		$logo = $root."images/logo.png";
	 ?>
<div class="container my-3 px-0 overflow-hidden">
	<div class="close-container text-end"><i class="fa fa-times my-3 mx-2 cp profile-close-btn" style="font-size: 20px;"></i></div>
	<div class="container-content bg-white">
		<div class="img-section position-relative sh-sm" style="margin-bottom: 100px;">
			<div class="img-cover" style="background-image: url('<?php echo $logo ?>'); height: 200px; background-size: cover; background-repeat: no-repeat; background-position: center; filter: blur(5px);"></div>
			<div class="img-logo cp position-absolute rounded-circle border sh-lg overflow-hidden" style="background-image: url('<?php echo $logo ?>'); background-color: white; height: 150px; width: 150px; background-size: contain; background-repeat: no-repeat; background-position: center; top: 100%; left: 50px; transform: translatey(-50%);">
				<input type="file" accept="image/*" class="w-100 h-100 cp logo-input" style="opacity: 0;">
				<span class="img-caption position-absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%);"></span>
			</div>
		</div>
		<p class="logo-msg ps-3"></p>
		<div class="profile-content w-100">
			<div class="row w-100">
				<div class="col-md-3 bg-white py-3 px-1">
					<ul class="list-groups">
						<li class="list-group-item cp info-menu">General Informations</li>
						<li class="list-group-item cp account-menu">Account Details</li>
					</ul>
				</div>
				<div class="col-md-9 bg-white p-3">
					<form class="info d-none">
						<h4 class="d-flex justify-content-between"><span>Business Details</span> <button class="btn edit-details-btn" type="button"> <i class="fa fa-edit"></i> &nbsp; Edit</button></h4>
						<table class="table table-borderless" style="max-width: 600px;">
							<tr>
								<td class="p-1">Email</td>
								<td class="p-1"><p class=" shadow-none m-0" disabled required name="email" ><?php echo $email ?> </p></td>
							</tr>
							<tr>
								<td class="p-1" style="width: 150px;">Merchant Name</td>
								<td class="p-1"><input type="text" class="form-control shadow-none border merchant-name" disabled required name="name" value="<?php echo $merchant_info["name"] ?>"></td>
							</tr>
							<tr>
								<td class="p-1">Phone</td>
								<td class="p-1"><input type="text" class="form-control shadow-none border" disabled required name="phone" value="<?php echo $merchant_info["phone"] ?>"></td>
							</tr>
							<tr>
								<td class="p-1">Address</td>
								<td class="p-1"><input type="text" class="form-control shadow-none border" disabled required name="address" value="<?php echo $merchant_info["address"] ?>"></td>
							</tr>
						</table>
						<p class="details-info"></p>
						<div class="text-end">
							<button class="btn text-white bg-1" type="submit" disabled><b>Update Information</b></button>
						</div>
					</form>
					<div class="account-section">
						<form class="password-form asdf">
							<h4>Change password</h4>
							<table class="table table-borderless" style="max-width: 600px;">
							<tr>
								<td class="p-1" style="width: 150px;">Old Password</td>
								<td class="p-1"><input type="password" class="form-control shadow-none border" required name="o_pw"></td>
							</tr>
							<tr>
								<td class="p-1">New Password</td>
								<td class="p-1"><input type="password" class="form-control shadow-none border" id="new_pw" required name="n_pw"></td>
							</tr>
							<tr>
								<td class="p-1">Re: new Password</td>
								<td class="p-1"><input type="password" class="form-control shadow-none border"  id="confirm_pw" required name="c_pw"></td>
							</tr>
						</table>
						<div class="password-info"></div>
						<div class="password-change-btn text-end">
							<button class="btn bg-1 text-white rounded-0">Change</button>
						</div>
							
						</form>
						
						<p class="account-delete text-1 cp"><span class="text-decoration-underline">Delete An Account</span></p>
					</div>
				</div>
			</div>
		</div>
	</div>	
	<script>
		$(".account-delete").click(function(){
			ssb_dialog({
				message: `<h5><b>Are You Sure to delete the merchant account?</b></h5>
                <h6>Deleting the merchant account wil completely <b>delete</b> all the <b>categories</b>, <b>brands</b>, <b>products</b> <b>employees</b> and all that are associated with the account</h6>`,
				okay: function(){
					ssb_dialog({
						initial_script: function(){
							var okay_btn = document.querySelector(".dialog-content .conf-card .conf-card-footer .conf-card-okay");
							document.querySelector(".dialog-content .conf-card .conf-card-footer .conf-card-cancel").remove();
							$(okay_btn).removeClass(".conf-card-okay");
							$(okay_btn).addClass("bg-3 account-delete-btn");
							$(okay_btn).html("<i class='fa fa-trash'></i>&nbsp Delete Now")
							$(okay_btn).click(function(){
								var pw = $("#delete-account-pw").val();
								$.ajax({
									type: "POST",
									data: {pw: pw},
									url: root+"merchant/php/delete_merchant.php",
									beforeSend: function(){
										$(".account-delete").html("<strong><i class='fa fa-spinner fa-spin'></i> &nbsp; Deleting Account...  Please Wait</strong>");
									},
									success: function(response){
										console.log(response);
										if(response.status == "success")
											window.location = location.href;
										else if(response.status == "error")
											$(".account-delete").html("<strong class='text-4> <i class='fa fa-times'></i>&nbsp; "+response.message+"</strong>");
										setTimeout(function(){
											$(".account-delete").html('<span class="text-decoration-underline">Delete An Account</span>');
										}, 4000)
									}
								})
							})
						},
						message: `
								<div class="form-group">
									<label for="delete-account-pw" class="text-1"><b>Enter current Password just before deleting the account</b></label>
									<input type="password" required id="delete-account-pw" class="form-control shadow-none border mt-1" placeholder="**********">
								</div>`,
						okay: function(){},
						cancel: function(){}
					})
				},
				cancel: function(){}
			})
		})
		$(".dlt-form").submit(function(e){
			e.preventDefault();
			alert();
		})

		$(".info-menu").click(function(){
			$(".account-section").addClass("d-none");
			$(".info").removeClass("d-none");
		})

		$(".account-menu").click(function(){
			$(".info").addClass("d-none");
			$(".account-section").removeClass("d-none");
		})

		$(".edit-details-btn").click(function(){
			$(".info input, .info button").removeAttr("disabled");
			$(".merchant-name").focus();
		})

		$(".info").submit(function(e){
			e.preventDefault();
			$.ajax({
				type: "POST",
				url: root+"merchant/php/edit_merchant_details.php",
				data: new FormData(this),
				processData: false,
				contentType: false,
				cache: false,
				beforeSend: function(){
					$(".details-info").html("<strong class='text-1'><i class='fa fa-spinner fa-spin'></i> Changing details... Please wait..</strong>");
				},
				success: function(response){
					console.log(response);
					if(response["status"] == "success")
						$(".details-info").html("<strong class='text-1'><i class='fa fa-check'></i> "+response["message"]+"</strong>");
					else if(response["status"] == "error")
						$(".details-info").html("<strong class='text-3'><i class='fa fa-times'></i> "+response["message"]+"</strong>");
					setTimeout(function(){$(".details-info").html("")}, 4000);
				}
			})
		})
        	var password_changing = false;
		$(".password-form").submit(function(e){
			e.preventDefault();
			if(password_changing)
				return;
			password_changing = true;
        	var pw = $(".password-form input[name=n_pw").val();
        	if(pw.length < 8){
        		$(".password-info").html("<strong class='text-3'><i class='fa fa-times'></i> Password should be of at least 8 character long</strong>");
        		return;
        	}
        	else if(!pw.match(/[A-Z]/g)){
        		$(".password-info").html("<strong class='text-3'><i class='fa fa-times'></i> Password should contain at least one capital letter</strong>");
        		return;
        	}
        	else if(!pw.match(/[a-z]/g)){
        		$(".password-info").html("<strong class='text-3'><i class='fa fa-times'></i> Password should contain at least one small letter</strong>");
        		return;
        	}

        	else if(!pw.match(/[0-9]/g)){
        		$(".password-info").html("<strong class='text-3'><i class='fa fa-times'></i> Password should contain at least one number</strong>");
        		return;
        	}

        	else if(!pw.match(/[~|`|!|@|#|$|%|^|&|*|(|_|-|+|=]/g)){
        		$(".password-info").html("<strong class='text-3'><i class='fa fa-times'></i> Password should contain at least one special character i.e.: [[~|`|!|@|#|$|%|^|&|*|(|_|-|+|=]]</strong>");
        		return;
        	}

        	else if(pw != $(".password-form input[name=c_pw").val()){
        		$(".password-info").html("<strong class='text-3'><i class='fa fa-times'></i> Password and confirm password should have same value</strong>");
        		return;
        	}

			$.ajax({
				type: "POST",
				url: root+"merchant/php/change_merchant_password.php",
				data: new FormData(this),
				processData: false,
				contentType: false,
				cache: false,
				beforeSend: function(){
				$(".password-info").html("<strong class='text-1'> <i class='fa fa-spinner fa-spin'></i> Changing password.. Please wait</strong>");
			},
				success: function(response){
					console.log(response);
					if(response["status"] == "success")
						$(".password-info").html("<strong class='text-1'><i class='fa fa-check'></i> "+response["message"]+"</strong>");
					else if(response["status"] == "error")
						$(".password-info").html("<strong class='text-3'><i class='fa fa-times'></i> "+response["message"]+"</strong>");
					password_changing = false;
					setTimeout(function(){$(".password-info").html("")}, 4000);
				}
			})
		})

		//set product image as background image
		var logo_set = false;
		$(".logo-input").on("change", function (){
			var file = this.files[0];
			if(file == undefined){
		    	logo_set =  false;
		    	$(".img-logo").css("background", "none");
		    	$(".img-caption").html("<b>Upload <br> Product <br> Image</b>");
		    	return;
		    }
			var size = file.size/1024;
			if(size <= 1024){
				$(".img-caption").html("");
				var reader = new FileReader();
		        reader.addEventListener("load", function() {
		            $(".img-logo").css({
		                "background-image": `URL("` + reader.result + `")`,
		                "background-repeat": "no-repeat",
		                "background-position": "center",
		                "background-size": "contain"
		            })
		            $(".img-cover").css({
		            	  "background-image": `URL("` + reader.result + `")`
		            })
		            logo_set = true;
		        }, false);
		        reader.readAsDataURL(file);
		        var formdata = new FormData();
		        formdata.append("logo", file)
		        $.ajax({
		        	type: "POST",
		        	url: root+"merchant/php/change_logo.php",
		        	data: formdata,
		        	processData: false,
		        	contentType: false,
		        	cache: false,
		        	beforeSend: function(){
		        		$(".logo-msg").html("<strong class='text-1'> <i class='fa fa-spinner fa-spin'></i> Uploading.. Please wait</strong>");
		        	},
		        	success: function(response){
		        		if(response["status"] == "success")
		        			$(".logo-msg").html("<strong class='text-1'> <i class='fa fa-check'></i> "+response["message"]+"</strong>");
		        		else
		        			$(".logo-msg").html("<strong class='text-3'> <i class='fa fa-times'></i> "+response["message"]+"</strong>");
		        		setTimeout(function(){$(".logo-msg").html("")}, 4000);
		        	}
		        })
		    }
		    else{
		    	logo_set =  false;
		    	$(".img-logo").css("background", "none");
		    	$(".img-caption").html("<span class='text-4 animate__animated animate__fadeInUp'><b>Upload <br> photo <br> Less than <br> 1 MB</b></span>");
		    	setTimeout(() =>{
		    		if(!logo_set)
		    			(".img-caption").html("<b>Upload <br> Product <br> Image</b>")
		    		} , 5000);
		    }
		})

		$(".profile-close-btn").click(function(){
			$(".main-content").removeClass("position-fixed");
			$(".main-content").removeClass("d-none");
			$(".external-content").html("");
		})
	</script>
</div>