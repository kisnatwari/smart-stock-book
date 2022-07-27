<?php 
	require_once "../../common/db.php";
	$table = $db -> select_all("branding");
	$brand_name = "smartstockbook";
	$domain_name = "ssb.krishnat.com.np";
	$email = "admin@krishnat.com.np";
	$phone = "9816439892";
	$address = "Kawasoti-5, Nawalpur";
	$fb = "https://www.facebook.com/profile.php?id=100008883133980";
	$twitter = "https://www.twitter.com/@kisnatwari";
	$about_us = "This is about your brand";
	$privacy_policy = "This is about the privacy_policy of website";
	$cookies_Policy = "Cookies policy will be added";
	$terms_conditions = "If you've seen this there is no any terms and conditions";
	$logo = "";
	if(is_array($table) && count($table) > 0){
		$table = $table[0];
		$brand_name = $table["brand_name"];
		$domain_name = $table["domain_name"];
		$email = $table["email"];
		$phone = $table["phone"];
		$address = $table["address"];
		$fb = $table["fb"];
		$twitter = $table["twitter"];
		$about_us = $table["about_us"];
		$privacy_policy = $table["privacy_policy"];
		$cookies_Policy = $table["cookies_Policy"];
		$terms_conditions = $table["terms_conditions"];
		$logo =  "data:image/png;base64,".base64_encode($table["logo"]);
	}
 ?>
<div class="row animate__animated animate__fadeIn">
	<div class="col-md-1"></div>
	<div class="col-md-10 px-md-3">
		<form class="branding-form bg-white shadow-lg rounded p-3 border border-3 mx-auto" style="max-width: 700px;">
			<div class="text-1 border-bottom bor-1 d-flex justify-content-between align-items-center">
				<h5 class="p-0 m-0">Brand your site professionally</h5>
				<span class="btn bg-1 text-white px-2 py-1 mb-1 branding-edit-btn"><i class="fa fa-edit"></i> <b>Edit</b></span>
			</div>
			<!-- Logo -->
			<div class="text-center my-3">
				<div class="logo border bor-2 mx-auto d-flex justify-content-center align-items-center rounded-circle position-relative overflow-hidden" style="width: 150px; height: 150px; border-style: dashed !important; background-image: url('<?php  echo $logo?>'); background-position: center; background-size: cover; background-repeat: no-repeat;">
					<h6 class="text-1 logo-caption"><b>Brand <br> Logo <br> Here</b></h6>
					<input type="file" accept="image/*" name="logo" class="position-absolute w-100 h-100 cp" disabled style="top: 0; left: 0; opacity: 0;">
				</div>
			</div>
			<!-- Brand Name -->
			<div class="input-group bg-white  border-bottom bor-1  my-4 rounded shadow-sm" style="border-width: 1px !important;">
				<div class="input-group-prepend">
					<label class="input-group-text bg-light border-0 text-1" for="brand-name">Brand Name </label>
				</div>
				<input type="text" class="form-control border-0 shadow-none" id="brand-name" name="brand-name"  placeholder="smartstockbook" disabled value="<?php echo $brand_name ?>">
			</div>
			<!-- Domain Name -->
			<div class="input-group bg-white  border-bottom bor-1  my-4 rounded shadow-sm" style="border-width: 1px !important;">
				<div class="input-group-prepend">
					<label class="input-group-text bg-light border-0 text-1" for="domain-name">Domain Name </label>
				</div>
				<input type="text" class="form-control border-0 shadow-none" id="domain-name" name="domain-name"  placeholder="www.example.com" disabled value="<?php echo $domain_name ?>">
			</div>
			<!-- Email -->
			<div class="input-group bg-white  border-bottom bor-1  my-4 rounded shadow-sm" style="border-width: 1px !important;">
				<div class="input-group-prepend">
					<label class="input-group-text bg-light border-0 text-1" for="email">Email </label>
				</div>
				<input type="email" class="form-control border-0 shadow-none" id="email" name="email"  placeholder="Enter Email Address" disabled value="<?php echo $email ?>">
			</div>
			<!-- Phone No. -->
			<div class="input-group bg-white  border-bottom bor-1  my-4 rounded shadow-sm" style="border-width: 1px !important;">
				<div class="input-group-prepend">
					<label class="input-group-text bg-light border-0 text-1" for="phone">Phone No. </label>
				</div>
				<input type="text" class="form-control  border-0 shadow-none" id="phone" name="phone"  placeholder="Enter Phone Number" disabled value="<?php echo $phone ?>">
			</div>
			<!-- Address. -->
			<div class="input-group bg-white  border-bottom bor-1  my-4 rounded shadow-sm" style="border-width: 1px !important;">
				<div class="input-group-prepend">
					<label class="input-group-text bg-light border-0 text-1" for="address">Address </label>
				</div>
				<textarea type="text" rows="1" class="form-control  border-0 shadow-none" id="address" name="address" placeholder="Enter Your Address" disabled><?php echo $address ?></textarea>
			</div>
			<hr>
			<!-- Social Media -->
			<h5 class="text-1">Social Handle</h5>
			<div class="input-group bg-white  border-bottom bor-1  my-4 rounded shadow-sm" style="border-width: 1px !important;">
				<div class="input-group-prepend">
					<label class="input-group-text bg-light border-0 text-1" for="fb"><i class="mdi mdi-facebook"></i> </label>
				</div>
				<input type="url" name="fb" id="fb" class="form-control border-0 shadow-none" placeholder="Facebook Profile Link" disabled value="<?php echo $fb ?>">
			</div>
			<div class="input-group bg-white  border-bottom bor-1  my-4 rounded shadow-sm" style="border-width: 1px !important;">
				<div class="input-group-prepend">
					<label class="input-group-text bg-light border-0 text-1" for="twitter"><i class="mdi mdi-twitter"></i> </label>
				</div>
				<input type="url" name="twitter" id="twitter" class="form-control border-0 shadow-none" placeholder="Twitter profile link" disabled value="<?php echo $twitter ?>">
			</div>
			<hr>
			<!-- About us -->
			<div class="form-group my-3">
				<label class="text-1" for="about-us" style="font-size: 16px;">About Brand <sub>(Shown as about us)</sub></label>
				<textarea name="about-us" id="about-us" rows="10" class="form-control border bor-2 shadow-none" style="border-width: 1px !important;" disabled><?php echo $about_us ?></textarea>
			</div>
			<hr>
			<!-- Privacy Policy -->
			<div class="form-group">
				<label for="privacy-policy" class="text-1" style="font-size: 16px;">Privacy Policy</label>
				<textarea name="privacy-policy" id="privacy-policy" rows="10" class="form-control shadow-none border bor-2" style="border-width: 1px !important;" disabled><?php echo $privacy_policy ?></textarea>
			</div>
			<hr>
			<!-- Cookies Policy -->
			<div class="form-group">
				<label for="cookies_Policy" class="text-1" style="font-size: 16px;">Cookies Policy</label>
				<textarea name="cookies_Policy" id="cookies_Policy" rows="10" class="form-control shadow-none border bor-2" style="border-width: 1px !important;" disabled><?php echo $cookies_Policy ?></textarea>
			</div>
			<hr>
			<!-- Terms and Conditions -->
			<div class="form-group">
				<label for="terms-conditions" class="text-1" style="font-size: 16px;">Terms And Conditions</label>
				<textarea name="terms-conditions" id="terms-conditions" rows="10" class="form-control shadow-none border bor-2" style="border-width: 1px !important;" disabled><?php echo $terms_conditions ?></textarea>
			</div>
			<button type="submit" class="btn bg-2 text-white mt-3 branding-submit-btn" disabled><b>Save branding Details</button><b></button>
		</form>
	</div>
	<div class="col-md-1"></div>
</div>
<script>
	$(".branding-edit-btn").click(function(){
		$(".branding-form input, .branding-form textarea, .branding-form button").removeAttr("disabled");
		$("#brand-name").focus();
	})
</script>
<script>
	$(".branding-form").submit(function(e){
		e.preventDefault();
		$.ajax({
			type: "POST",
			url : "./php/save_branding.php",
			data: new FormData(this),
			processData: false,
			contentType: false,
			beforeSend: function(){
				$(".branding-submit-btn").disabled;
				$(".branding-submit-btn").html('<b><i className="fa fa-spinner fa-spin"></i> &nbsp; Saving... Please Wait</b>');
			},
			success: function(response){
				$(".branding-form input, .branding-form textarea, .branding-form button").removeAttr("disabled");
				if(response.trim() == "success"){
					$(".branding-submit-btn").html('<b><i className="fa fa-check"></i> &nbsp; Data saved Successfully</b>');
					$(".branding-form input, .branding-form textarea, .branding-form button").attr("disabled","true");
				}
				else{
					$(".branding-submit-btn").html('<b><i className="fa fa-close"></i> &nbsp; File saving Failed</b>');
					$(".branding-submit-btn").addClass("bg-3");
					$(".branding-submit-btn").removeClass("bg-2");
					console.log(response);
				}
			}
		})
	})
</script>

<script>
	$(".logo input[name='logo']").on("change", function(){
		var file = this.files[0];
		var size = file.size/1024;
		if(size <= 200){
			console.log(file);
			var reader = new FileReader();
	        reader.addEventListener("load", function() {
	            $(".logo").css({
	                "background-image": `URL("` + reader.result + `")`,
	                "background-repeat": "no-repeat",
	                "background-position": "center",
	                "background-size": "cover"
	            })
	        }, false);
	        reader.readAsDataURL(file);
	    }
	    else{
	    	this.files[0] = null;
	    	$(".logo").css("background", "none");
	    	$(".logo-caption").html("<span class='text-4 animate__animated animate__fadeInUp'><b>Upload <br> photo <br> Less than <br> 200 KB</b></span>");
	    	setTimeout(() =>$(".logo-caption").html("<b>Brand <br> Logo <br> Here</b>") , 8000);
	    }
	})

</script>
<?php 
	if($table == "table not found" || !$table){
	?>
	<script>$(".branding-edit-btn").click();</script>
	<?php	
	}
 ?>


 