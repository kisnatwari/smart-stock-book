sidebar = () => ( innerWidth >= 769) ? $("section[data-sidebar-size]").attr("data-sidebar-size", "max"): $("section[data-sidebar-size]").attr("data-sidebar-size", "min");
sidebar();


$(".nav-item").click(function() {
    if (!this.className.match("active")) {
        $(".nav-item.active").removeClass("active");
        $(this).addClass("active");
    }
})


$(document).ready(function() {
    $(".sidebar-toggle").click(function() {
        var visiblity = $("section[data-sidebar-hide]").attr("data-sidebar-hide");
        (visiblity == "true") ?   $("section[data-sidebar-hide]").attr("data-sidebar-hide", "false")  :    $("section[data-sidebar-hide]").attr("data-sidebar-hide", "true");
    })
})
window.onresize = sidebar;


//dynamic design
$(document).ready(function(){
	$(".sidebar-nav .nav-item").click(function(){
		$.ajax({
			type: "POST",
			url : `./php/${$(this).attr("data-url")}.php`,
			beforeSend: function(){

			},
			success: function(response){
				$(".inner-content").html(response);
			}
		})
	})
	
	$(".sidebar-nav .nav-item.active").click()
})

$(".profile-btn").click(function(){
    $.ajax({
        type: "POST",
        url: root+"admin/php/admin_profile.php",
        beforeSend: function(){},
        success: function(response){
            $(".main-content").addClass("d-none");
            $(".external-content").html(response);
        }
    })
})