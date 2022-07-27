<div class="animate__animated animate__fadeIn animate_faster d-flex justify-content-center align-items-center login-form-content w-100 position-absolute" style="width: 100%; min-height: 100vh; z-index: 20000; background-color: #130202DD;">
	<style>
			.form-check-input[type=checkbox]{
				border-color: #0C0061;
			}
			.form-check-input:checked[type=checkbox] {
				background-color: #0C0061;
				border-color: #0C0061;
			}
	</style>
	<form class="login-form bg-transparent text-center">
		<div class="text-white mb-3">
			<i class="mdi mdi-close-circle-outline cp form-close-icn" style="font-size: 30px" ></i>
		</div>
		<h4 class="text-white mb-3">Account Login</h4>
		<div class="card rounded-0" style="border-radius:  25px !important; max-width: 300px;">
			<div class="card-body">
				<div class="input-group border-bottom bor-1 text-1 mb-3">
					<div class="input-group-prepend">
						<label class="input-group-text bg-white border-0">
							<i class="mdi mdi-account text-1"></i>
						</label>
					</div>
					<input type="text" name="email" class="form-control border-0 shadow-none text-1" placeholder="Email">
				</div>
				<div class="input-group border-bottom bor-1 text-1 mb-2">
					<div class="input-group-prepend">
						<label class="input-group-text bg-white border-0">
							<i class="mdi mdi-lock text-1"></i>
						</label>
					</div>
					<input type="password" name="password" class="form-control border-0 shadow-none pw-field text-1" placeholder="Enter Password">
					<div class="input-group-append">
						<span class="input-group-text px-0 border-0 bg-white">
							<i class="mdi mdi-eye cp text-1 show-pw "></i>
							<i class="mdi mdi-eye-off cp text-1 hide-pw d-none"></i>
						</span>
					</div>
				</div>
				<!-- <div class="form-check form-switch my-3 text-start">
					<input class="form-check-input shadow-none" type="checkbox" id="remember" name="remember" value="yes" checked>
					<label class="form-check-label mx-2" for="remember">Remember Me</label>
				</div> -->
				<button id="login-now-btn" type="submit" class="bg-white bor-1 text-1 my-2 py-1 px-4 btn-sm" style="border-radius: 18px"><b><i class="mdi mdi-login"></i> &nbsp;Login Now</b></button>
				<div class="login-caption"></div>
			</div>
		</div>
		<div class="py-3">
			<b class="text-white text-decoration-none cp" onclick="switch_form('signup')">Don't Have an account? <br> Sign Up Now</b>
		</div>
	</form>
	<script>
		$(".login-form").submit(function(e){
			e.preventDefault();
			$.ajax({
				type:"POST",
				url: root+"php/login.php",
				processData: false,
				contentType: false,
				cache: false,
				data: new FormData(this),
				beforeSend: function(){
					$("#login-now-btn").attr("disabled",true);
					$(".login-caption").html(`<b class='text-1'><i class="fas fa-spinner fa-spin"></i> &nbsp; Loading..... Please Wait</b>`);
				},
				success: function(response){
					$("#login-now-btn").removeAttr("disabled");
                    console.log(response);
                    if(response["status"].trim() == "verify")
                        show_form(response["message"]);
                    else if(response["status"].trim() == "error")
                        $(".login-caption").html("<span class='text-3'><i class='fa fa-close'></i> &nbsp; "+response["message"].trim()+"</span>");
                    else if(response["message"] == "logged in"){
                    	location.reload();
                    }
					else
                        $(".login-caption").html("<span class='text-3'><i class='fa fa-close'></i> &nbsp; Failed</span>");
				}
			})
		})
		$(".form-close-icn").click(function(){
			$(".login-form-content").addClass("animate__fadeOut");
			setTimeout(function(){
				$(".main-content").removeClass("position-fixed");
				$(".login-form-content").remove();}, 500);
		})
		$(".show-pw").click(function(){
			$(".pw-field").attr("type","text");
			$(this).addClass("d-none");
			$(".hide-pw").removeClass("d-none");
		})
		$(".show-pw").click(function(){
			$(".pw-field").attr("type","text");
			$(this).addClass("d-none");
			$(".hide-pw").removeClass("d-none");
		})
		$(".hide-pw").click(function(){
			$(".pw-field").attr("type","password");
			$(this).addClass("d-none");
			$(".show-pw").removeClass("d-none");
		})
	</script>
</div>