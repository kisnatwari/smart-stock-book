sidebar = () => (innerWidth >= 769) ? $("section[data-sidebar-size]").attr("data-sidebar-size", "max") : $("section[data-sidebar-size]").attr("data-sidebar-size", "min");
sidebar();

var inner_content_loading = `<div class="animate__animated animate__fadeIn animate_faster bg-white p-3 text-center mt-5 mx-lg-5 sh-lg" style="width: fit-content;">
                            <h4> <i class="fa fa-spin fa-spinner m-3" style="font-size: 30px;"></i> <br> Loading Content.. <br> Please Wait  <br> ________________</h4>
                        </div>`;

$(".nav-item").click(function() {
    if (!this.className.match("active")) {
        $(".nav-item.active").removeClass("active");
        $(this).addClass("active");
    }
})


$(document).ready(function() {
    $(".sidebar-toggle").click(function() {
        var visiblity = $("section[data-sidebar-hide]").attr("data-sidebar-hide");
        (visiblity == "true") ? $("section[data-sidebar-hide]").attr("data-sidebar-hide", "false"): $("section[data-sidebar-hide]").attr("data-sidebar-hide", "true");
    })
})
window.onresize = sidebar;


//dynamic design
$(document).ready(function() {
    $(".sidebar-nav .nav-item").click(function() {
        $.ajax({
            type: "POST",
            url: `./php/${$(this).attr("data-url")}.php`,
            beforeSend: function() {
                $(".inner-content").html(inner_content_loading);
            },
            success: function(response) {
                $(".inner-content").html(response);
            }
        })
    })

    $(".sidebar-nav .nav-item.active").click()
})

$(".profile-btn").click(function(){
    $.ajax({
        type: "POST",
        url: root+"employee/php/employee_profile.php",
        beforeSend: function(){},
        success: function(response){
            $(".main-content").addClass("d-none");
            $(".external-content").html(response);
        }
    })
})



/*$(document).ready(function(){
    $.ajax({
                type: "POST",
                url: root+"merchant/php/add_supplier_design.php",
                success: function(response){
                    $(".main-content").addClass("position-fixed");
                    $(".external-content").html(response);
                }
            })
})
*/
function ssb_dialog(data){
    $(".inner-body").addClass("position-fixed");
    if(!data.message)
        return;
    var html = `
    <div class="animate__animated animate__fadeIn animate_faster d-flex justify-content-center align-items-center supplier-form-content w-100 position-absolute" style="width: 100%; min-height: 100vh; z-index: 20000; background-color: #130202DD;">
        <div class="card conf-card m-3" style="max-width: 500px; min-width: 300px;">
            <div class="card-body">
                ${data.message}
            </div>
            <div class="card-footer conf-card-footer text-end bg-white">
                <strong class="btn conf-card-okay bg-2 text-white me-1 shadow-none border-0"><strong> <i class="fa fa-check-circle"></i>  Okay</strong></strong>
                <strong class="btn conf-card-cancel bg-4 text-white ms-1 shadow-none border-0"><strong> <i class="fa fa-times-circle"></i> Cancel</strong></strong>
            </div>
        </div>
    </div>`;

    $(".dialog-content").html(html);
    if(data.initial_script)
        data.initial_script()
    //onsuccess
    $(".dialog-content .conf-card .conf-card-footer .conf-card-okay").click(function(){
        $(".dialog-content").html("");
        $(".inner-body").removeClass("position-fixed");
        data.okay();
    })

    //onfailed
    $(".dialog-content .conf-card .conf-card-footer .conf-card-cancel").click(function(){
        $(".dialog-content").html("");
        $(".inner-body").removeClass("position-fixed");
        data.cancel();
    })
}

/*ssb_confirm({
    message: `<h5><b>Are You Sure to delete this category?</b></h5>
                <h6>Deleting this will also delete all the brands and products that depends upon this category</h6>`,
    success: function(){alert("success")},
    fail: function(){alert("Fail")}
});*/