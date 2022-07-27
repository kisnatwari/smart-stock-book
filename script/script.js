var login_content = null;
var signup_content = null;
var desktop_nav = `            	<!-- desktop nav starts -->
            	<ul class="nav navbar nav-pills d-none d-lg-flex">
                <li class="nav-item mx-lg-1">
                    <a class="nav-link text-0 px-1 active" href="#home">Home</a>
                </li>
                <li class="nav-item mx-lg-1">
                    <a class="nav-link text-0 px-1" href="#service">Services</a>
                </li>
                <li class="nav-item mx-lg-1">
                    <a class="nav-link text-0 px-1" href="#feature">Features</a>
                </li>
                <button class="login-btn bor-4 bg-white text-4 px-4 mx-1" style="border-radius: 30px; border-style: solid;" onclick="login_form()"><b class="mx-3">Login</b></button>
                <button class="signup-btn bor-4 bg-4 text-white px-4 mx-1" style="border-radius: 30px; border-style: solid;" onclick = "signup_form()"><b class="mx-3">Sign Up</b></button>
            </ul>
            	<!-- desktop nav ends -->`;

var mobile_nav = `            	<!-- mobile nav starts -->
            	<div class="mobile-nav px-2 py-3 d-block d-lg-none">
        <div class="text-center"><span class="mobile-nav-close mdi mdi-close-circle-outline text-3 text-end cp" style="font-size:25px"></span></div>
        <ul class="flex-column text-center m-0 p-0" type="none">
            <li class="nav-item">
                <a class="d-inline-block nav-link text-0 text-center my-1" href="#home"><b>Home</b></a>
            </li>
            <li class="nav-item">
                <a class="d-inline-block nav-link text-0 text-center my-1" href="#service"><b>Services</b></a>
            </li>
            <li class="nav-item">
                <a class="d-inline-block nav-link text-0 text-center my-1" href="#feature"><b>Features</b></a>
            </li>
            <hr class='bg-1' style="height: 5px;">
            <button class="login-btn d-block bor-4 bg-white text-4 px-4 mx-1 mb-2" style="border-radius: 30px; border-style: solid;" onclick="login_form()"><b class="mx-3">Login</b></button>
            <button class="signup-btn d-block bor-4 bg-4 text-white px-4 mx-1 mt-2" style="border-radius: 30px; border-style: solid;" onclick = "signup_form()"><b class="mx-3">SIgn Up</b></button>
        </ul>
    </div>
    <script>
    $(document).ready(function(){
	$(".mobile-nav-open").click(function(){
		$(".mobile-nav").css("transform","translateX(0)");
	})
	$(".mobile-nav-close").click(function(){
		$(".mobile-nav").css("transform","translateX(105%)");
	})
})
</script>
            	<!-- mobile nav ends -->`;


set_nav();

function set_nav() {
    var width = Number(window.innerWidth);
    if (width >= 992) {
        $(".nav-con").html(desktop_nav);
    } else {
        $(".nav-con").html(mobile_nav);
    }
}

window.onresize = function() {
    set_nav();
}


$(document).ready(function() {
    window.onscroll = function() {
        if (this.scrollY > 20) {
            $(".nav-container").css({
                "padding-top": "0px",
                "padding-bottom": "0px",
                "box-shadow": "0 0 10px 10px #11111125"
            })
        } else {
            $(".nav-container").css({
                "padding-top": "10px",
                "padding-bottom": "10px",
                "box-shadow": "none"
            })
        }
    }
})

/*login code */

function login_form() {
    if (login_content)
        $(".main-content").addClass("position-fixed") && $(".external-content").html(login_content);
    else {
        $.ajax({
            type: "POST",
            url: root + "pages/login_form.php",
            success: function(response) {
                $(".main-content").addClass("position-fixed");
                login_content = response.trim();
                $(".external-content").html(login_content);
            }
        })
    }
}

function signup_form() {
    if (signup_content)
        $(".main-content").addClass("position-fixed") && $(".external-content").html(signup_content);
    else {
        $.ajax({
            type: "POST",
            url: root + "pages/signup_form.php",
            success: function(response) {
                $(".main-content").addClass("position-fixed");
                signup_content = response.trim();
                $(".external-content").html(signup_content);
            }
        })
    }
}

function show_form(form) {
    $(".form-close-icn").click();
    setTimeout(function() {
        $(".main-content").addClass("position-fixed");
        $(".external-content").html(form);
    }, 505)
}

function switch_form(switch_to) {
    $(".form-close-icn").click();
    setTimeout(function() {
        ((switch_to == "login") && login_form()) || ((switch_to == "signup") && signup_form());
    }, 700);
}

function policy(policy) {
    $.ajax({
        type: "POST",
        data: { policy: policy },
        url: "pages/policies.php",
        beforeSend: function() {
            console.log("sending");
        },
        success: function(response) {
            $(".main-content").addClass("position-fixed");
            $(".external-content").html(response.trim());
        }
    })
}


$("#contact-form").submit(function(e){
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: root+"php/sendmessage.php",
        data: new FormData(this),
        processData: false,
        contentType: false,
        beforeSend: function(){
            $("#contact-submit").attr("disabled", "true");
            $("#contact-submit").html("<i class='fa fa-spinner fa-spin'></i> &nbsp;Sending....")
        },
        success: function(response){
            console.log(response);
            if(response.status == "success")
                $("#contact-submit").html("<strong class='text-1'> <i class='fa fa-check'></i> "+response.message+"</strong>");
            else
                $("#contact-submit").html("<strong class='text-3'> <i class='fa fa-times'></i> "+response.message+"</strong>");
            setTimeout(function(){
                $("#contact-submit").html("Send Mail To Admin");
                $("#contact-submit").removeAttr("disabled");
            }, 4000)
        }
    })
})