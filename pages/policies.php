<?php 
	require_once "../common/db.php";
	$policy = $_POST['policy'];
	$policies = ["terms" => "terms_conditions", "about" => "about_us", "privacy" => "privacy_policy", "cookies"=>"cookies_Policy"];
	if(empty($policy))
		die;
	$branding = $db -> select_all("branding")[0];
 ?>
<div class="animate__animated animate__fadeIn animate_faster d-flex flex-column policies-con justify-content-center align-items-center w-100 position-absolute pt-5" style="width: 100%; min-height: 100vh; z-index: 20000; background-color: #130202DD;">
	<div class="text-white mb-3">
		<i class="mdi mdi-close-circle-outline cp policies-close-icn" style="font-size: 30px" ></i>
	</div>
	<h4 class="text-white mb-1">
		<b>
			<?php 
			echo (($policy == "terms") ? "Terms and Conditions" : (($policy == "about") ? "About Us" : (($policy == "privacy")? "Privacy Policy" : (($policy == "cookies") ? "Cookies Policy" : $branding["brand_name"]))));
			 ?>
		</b>
	</h4>
	<div class="card my-3 animate__animated animate__fadeInDown" style="max-width: 800px;">
		<div class="card-body">
			<?php 
				echo (($policy == "terms") ? $branding["terms_conditions"] : (($policy == "about") ? $branding["about_us"] : (($policy == "privacy")? $branding["privacy_policy"] : (($policy == "cookies") ?$branding["cookies_Policy"] : ""))));
			?>
		</div>
	</div>
	<script>
		$(".policies-close-icn").click(function(){
			$(".policies-con").addClass("animate__fadeOut");
			setTimeout(function(){
				$(".main-content").removeClass("position-fixed");
				$(".policies-con").remove();}, 500);
		})
	</script>
</div>