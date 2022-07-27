<div class="animate__animated animate__fadeIn animate_faster py-5 d-flex justify-content-center align-items-center signup-form-content w-100 position-absolute" style="width: 100%; min-height: 100vh; z-index: 20000; background-color: #130202DD;">
    <form class="signup-form text-center">
        <div class="text-white mb-3">
            <i class="mdi mdi-close-circle-outline cp form-close-icn" style="font-size: 30px"></i>
        </div>
        <h4 class="text-white mb-4">Merchant Registeration</h4>
        <div class="card text-start text-1 p-3 mx-3 rounded-0 shadow animate__animated animate__fadeInUp" style="border-radius:  25px !important; background-color: #fff; max-width: 497px;">
            <div class="card-body">
                <div class="form-floating my-2">
                    <input type="text" required name="name" id="name" class="form-control shadow-none border-0 border-bottom bor-1 rounded-0 text-1" placeholder="organization name here">
                    <label for="name" class="text-1"> <i class="mdi mdi-office-building-outline"></i> &nbsp; Name of your Business </label>
                </div>
                <div class="row">
                    <div class="col-md-6 my-2">
                        <div class="img-con mx-auto sh-sm border d-flex overflow-hidden justify-content-center align-items-center position-relative" style="width: 140px; height: 140px; ">
                            <b class="text-center text-1 img-caption">Upload <br> Business <br> Logo</b>
                            <input type="file" name="logo-img" class="position-absolute w-100 h-100 cp product-img" onchange="change_img(this)" style="opacity: 0; top: 0; left: 0;">
                        </div>
                    </div>
                    <div class="col-md-6 my-2">

                        <div class="form-floating">
                            <input type="text" required class="form-control shadow-none border-0 border-bottom bor-1 rounded-0 text-1" id="phone" name="phone" placeholder="Organization contact number" maxlength="15" >
                            <label for="phone" class="text-1"><i class="mdi mdi-phone"></i> &nbsp; Phone</label>
                        </div>

                        <div class="form-floating form-group">
                            <input type="email" required class="form-control shadow-none border-0 border-bottom bor-1 rounded-0 text-1" id="email" name="email" placeholder="Organization mail here">
                            <label for="email" class="text-1"><i class="mdi mdi-mail"></i> &nbsp; Email</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 position-relative">
                        <span class="show-pw position-absolute cp " style="top: 10px; right: 10px; z-index: 100; font-size: 70%;">Show Password</span>
                        <div class="form-floating my-2 float-left">
                            <input type="password" required class="form-control shadow-none border-0 border-bottom bor-1 rounded-0 text-1" id="pw" name="pw" placeholder="Organization mail here" >
                            <label for="pw" class="text-1"><i class="mdi mdi-key"></i> &nbsp; Password</label>
                        </div>
                    </div>
                    <div class="col-md-6 position-relative">
                        <span class="show-pw position-absolute cp " style="top: 10px; right: 10px; z-index: 100; font-size: 70%;">Show Password</span>
                        <div class="form-floating my-2">
                            <input type="password" required class="form-control shadow-none border-0 border-bottom bor-1 rounded-0 text-1" name="c_pw" id="c_pw" placeholder="Organization mail here">
                            <label for="c_pw" class="text-1"><i class="fas fa-key"></i> &nbsp; Confirm Password </label>
                        </div>
                    </div>
                </div>
                <div class="form-floating my-2">
                    <input type="text" required class="form-control shadow-none border-0 border-bottom bor-1 rounded-0 text-1" name="address" id="address" placeholder="Address">
                    <label for="name" class="text-1"> <i class="mdi mdi-office-building-outline"></i> &nbsp; Address </label>
                </div>
                <p class="message text-3"></p>
                <button id="signup-now-btn" type="submit" class="bg-white bor-1 text-1 mb-1 py-1 px-4 btn-sm" style="border-radius: 18px"><b><i class="mdi mdi-account-plus"></i> &nbsp;SIgn Up Now</b></button>
            </div>
        </div>
        <div class="py-3">
            <b class="text-white text-decoration-none cp" onclick="switch_form('login')">Already Have an account? <br> Login Now</b>
        </div>
    </form>
    <script>

       //set product image as background image
        var img_set = false;
        function change_img(event){
            var file = event.files[0];
            if(file == undefined){
                img_set =  false;
                $(".img-con").css("background", "none");
                $(".img-caption").html("<b>Upload <br> Product <br> Image</b>");
                return;
            }
            var size = file.size/1024;
            if(size <= 1024){
                $(".img-caption").html("");
                var reader = new FileReader();
                reader.addEventListener("load", function() {
                    $(".img-con").css({
                        "background-image": `URL("` + reader.result + `")`,
                        "background-repeat": "no-repeat",
                        "background-position": "center",
                        "background-size": "contain"
                    })
                    img_set = true;
                }, false);
                reader.readAsDataURL(file);
            }
            else{
                img_set =  false;
                $(".img-con").css("background", "none");
                $(".img-caption").html("<span class='text-4 animate__animated animate__fadeInUp'><b>Upload <br> Business logo <br> Less than <br> 1 MB</b></span>");
                setTimeout(() =>{
                    if(!img_set)
                        (".img-caption").html("<b>Upload <br> Business <br> Logo</b>")
                    } , 5000);
            }
        }


    //close icon
    $(".form-close-icn").click(function() {
        $(".signup-form-content").addClass("animate__fadeOut");
        setTimeout(function() {
            $(".main-content").removeClass("position-fixed");
            $(".signup-form-content").remove();
        }, 500);
    })
    </script>


    <script>
    $(".show-pw").click(function() {
        //show or hide the password
        var form_floating = this.nextSibling.nextSibling;
        var input_field = form_floating.querySelector("#pw") ? form_floating.querySelector("#pw") : form_floating.querySelector("#c_pw");
        if ($(input_field).attr("type") == "password") {
            $(input_field).attr("type", "text");
            $(this).html("Hide password");
        } else {
            $(input_field).attr("type", "password");
            $(this).html("Show password");
        }
    })
    </script>
    <script>
    //.password validation
    var p_over_status = null;
    $("#pw").popover({
        title: "password should contain:-",
        content: `<ul type="none">
	<li class="pw-8 text-3">minimum 8 characters &nbsp; &nbsp; <i class="fa fa-times pw-i-8"></i> </li>
	<li class="pw-cap text-3">at least one capital letter &nbsp; &nbsp; <i class="fa fa-times pw-i-cap"></i> </li>
	<li class="pw-sm text-3">at least one small letter &nbsp; &nbsp; <i class="fa fa-times pw-i-sm"></i> </li>
	<li class="pw-num text-3">at least one number &nbsp; &nbsp; <i class="fa fa-times pw-i-num"></i> </li>
	<li class="pw-spcl text-3">at least one special character !@#$%^&*()-=+_[]\\?/.>,< &nbsp; &nbsp; <i class="fa fa-times pw-i-spcl"></i> </li>
</ul>`,
        html: true,
        trigger: "focus"
    })
    $("#pw").on("input", function() {
        pw_validate($(this).val());
    })
    var validation;

    function pw_validate(pw) {
        validation = true;
        pw.length >= 8 ? success(".pw-8", ".pw-i-8") : failed(".pw-8", ".pw-i-8");
        pw.match(/[A-Z]/g) ? success(".pw-cap", ".pw-i-cap") : failed(".pw-cap", ".pw-i-cap");
        pw.match(/[a-z]/g) ? success(".pw-sm", ".pw-i-sm") : failed(".pw-sm", ".pw-i-sm");
        pw.match(/[0-9]/g) ? success(".pw-num", ".pw-i-num") : failed(".pw-num", ".pw-i-num");
        pw.match(/[~|`|!|@|#|$|%|^|&|*|(|_|-|+|=]/g) ? success(".pw-spcl", ".pw-i-spcl") : failed(".pw-spcl", ".pw-i-spcl");
        if (validation) {
            $("#pw").popover("hide");
            console.log("validated");
            p_over_status = "hidden";
        } else {
            $("#pw").popover("show");
            console.log("not validated");
            if (p_over_status == "hidden") {
                p_over_status = "default";
                pw_validate($("#pw").val());
            }
        }
    }

    function success(item, icon) {
        $(item).removeClass("text-3");
        $(item).addClass("text-1");
        $(icon).addClass("fa-check");
        $(icon).removeClass("fa-times");
    }

    function failed(item, icon) {
        validation = false;
        $(item).addClass("text-3");
        $(item).removeClass("text-1");
        $(icon).removeClass("fa-check");
        $(icon).addClass("fa-times");
    }
    $("#c_pw").on("input", function(){
    	var pw = $("#pw").val();
    	if($(this).val() == pw){
    		$(this).addClass("bor-1");
    		$(this).removeClass("bor-3");
    	}
    	else{
    		$(this).addClass("bor-3");
    		$(this).removeClass("bor-1");
    	}
    })
    </script>
    <script>
    	//signup a new business
    	$(".signup-form").submit(function(e){
    		e.preventDefault();
            var form = this;
    		var formdata = new FormData(this);
    		if(!$("#name").val()){
    			$(".message").html("Please give the name of your business organization")
    			return false;
    		}
    		if(!$("#phone").val()){
    			$(".message").html("Please give the Phone Number!! It is compulsory")
    			return false;
    		}
    		if(!$("#email").val()){
    			$(".message").html("Please give the email id of your business organization")
    			return false;
    		}
    		if(!$("#pw").val()){
    			$(".message").html("Password field missing")
    			return false;
    		}
    		if(!$("#c_pw").val()){
    			$(".message").html("Password confirmation field is missing")
    			return false;
    		}
            if(!$("#address").val()){
    			$(".message").html("Please provide your address");
    			return false;
    		}
            $.ajax({
                type:"POST",
                data: formdata,
                url: root+"php/signup.php",
                processData: false,
                contentType: false,
                beforeSend: function(){
                    console.log("Sending");
                    $(".message").html("<span class='text-2'><i class='fa fa-spinner fa-spin'></i> &nbsp; Please Wait! Signing Up....</span>");
                    $("#signup-now-btn").attr("disabled","true");
                },
                success: function(response){
                    $("#signup-now-btn").removeAttr("disabled");
                    console.log(response);
                    if(response["status"].trim() == "registered")
                        show_form(response["message"]);
                    else if(response["status"].trim() == "error")
                        $(".message").html("<span class='text-3'><i class='fa fa-close'></i> &nbsp; "+response["message"].trim()+"</span>");
                    else
                        $(".message").html("<span class='text-3'><i class='fa fa-close'></i> &nbsp; Failed</span>");
                }
            })
    	})
    </script>
</div>