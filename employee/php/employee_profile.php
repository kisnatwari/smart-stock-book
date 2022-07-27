<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
 ?>
 	<?php 
 	$stmt = $pdo -> prepare("
 	SELECT users.id, users.name, users.email, employees.phone, employees.address
 	 FROM users INNER JOIN employees ON users.id = employees.user_id WHERE users.id = ?");
	$stmt -> execute([$_SESSION["logged_in_id"]]);
	$info = $stmt -> fetch();

	$stmt = $pdo -> prepare("SELECT logo FROM merchants WHERE id = ?");
	$stmt -> execute([$_SESSION["logged_in_merchant"]]);
	$logo = $stmt -> fetch()["logo"];
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
			<div class="img-logo position-absolute rounded-circle border sh-lg overflow-hidden" style="background-image: url('<?php echo $logo ?>'); background-color: white; height: 150px; width: 150px; background-size: contain; background-repeat: no-repeat; background-position: center; top: 100%; left: 50px; transform: translatey(-50%);">
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
					<form class="info">
						<h4 class="d-flex justify-content-between"><span>Employee Details</span> <button class="btn edit-details-btn" type="button"> <i class="fa fa-edit"></i> &nbsp; Edit</button></h4>
						<table class="table table-borderless" style="max-width: 600px;">
							<tr>
								<td class="p-1 py-2">Email</td>
								<td class="p-1"><p class=" shadow-none m-0" disabled required name="email" ><?php echo $info["email"] ?> </p></td>
							</tr>
							<tr>
								<td class="p-1">Name</td>
								<td class="p-1"><input type="text" class="form-control shadow-none border merchant-name" disabled required name="name" value="<?php echo $info["name"] ?>"></td>
							</tr>
							<tr>
								<td class="p-1">Phone</td>
								<td class="p-1"><input type="text" class="form-control shadow-none border" disabled required name="phone" value="<?php echo $info["phone"] ?>"></td>
							</tr>
							<tr>
								<td class="p-1">Address</td>
								<td class="p-1"><input type="text" class="form-control shadow-none border" disabled required name="address" value="<?php echo $info["address"] ?>"></td>
							</tr>
						</table>
						<p class="details-info"></p>
						<div class="text-end">
							<button class="btn text-white bg-1" type="submit" disabled><b>Update Information</b></button>
						</div>
					</form>
					<div class="account-section d-none">
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
						
					</div>
				</div>
			</div>
		</div>
	</div>	
	<script>


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
				url: root+"employee/php/edit_employee_details.php",
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
        	password_changing = true;
			$.ajax({
				type: "POST",
				url: root+"employee/php/change_employee_password.php",
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

		$(".profile-close-btn").click(function(){
			$(".main-content").removeClass("position-fixed");
			$(".main-content").removeClass("d-none");
			$(".external-content").html("");
		})
	</script>
</div>