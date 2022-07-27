    <div class="animate__animated animate__fadeIn animate_faster d-flex justify-content-center align-items-center employee-form-content w-100 position-absolute" style="width: 100%; min-height: 100vh; z-index: 20000; background-color: #130202DD;">
        <div>
            <div class="text-white text-center mb-3">
                <i class="mdi mdi-close-circle-outline cp form-close-icn" style="font-size: 30px" ></i>
                <h5 class="mb-4 mt-3">Create a new Employee Account</h5>
            </div>
            <!-- create employee card -->
            <div class="card rounded sh text-start text-1 px-0 " style="max-width:  450px">
                <form class="card-body pt-0 employee-create-form" autocomplete="off">
                    <div class="form-floating my-1">
                        <input type="text" required name="name" id="name" class="form-control shadow-none border-0 border-bottom bor-2 rounded-0 text-1" placeholder="organization name here">
                        <label for="name" class="text-1"> <i class="mdi mdi-account"></i> &nbsp; Name of an employee </label>
                    </div>
                    <div class="row">
                        <div class="col-12 my-1">
                            <div class="form-floating form-group">
                                <input type="email" required class="form-control shadow-none border-0 border-bottom bor-2 rounded-0 text-1" id="email" name="email" placeholder="Organization mail here">
                                <label for="email" class="text-1"><i class="mdi mdi-mail"></i> &nbsp; Email</label>
                            </div>
                        </div>
                        <div class="col-md-6 my-1">
                            <div class="form-floating form-group">
                                <input type="phone" required class="form-control shadow-none border-0 border-bottom bor-2 rounded-0 text-1" id="phone" name="phone" placeholder="Employee contact ">
                                <label for="phone" class="text-1"><i class="mdi mdi-phone"></i> &nbsp; Phone</label>
                            </div>
                        </div>
                        <div class="col-md-6 position-relative">
                            <span class="show-pw position-absolute cp " style="top: 10px; right: 10px; z-index: 100; font-size: 70%;">Show Password</span>
                            <div class="form-floating my-1 float-left">
                                <input type="password" required class="form-control shadow-none border-0 border-bottom bor-2 rounded-0 text-1" id="pw" name="password" placeholder="Organization mail here">
                                <label for="pw" class="text-1"><i class="mdi mdi-key"></i> &nbsp; Password</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating my-1">
                        <input type="text" required class="form-control shadow-none border-0 border-bottom bor-2 rounded-0 text-1" name="address" id="address" placeholder="Address">
                        <label for="name" class="text-1"> <i class="mdi mdi-office-building-outline"></i> &nbsp; Address </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input shadow-none" type="checkbox" id="mySwitch" value="yes" checked name="activation">
                        <label class="form-check-label cp" for="mySwitch" style="user-select: none;">Activate</label>
                    </div>
                    <p class="message"></p>
                    <div class="text-end">
                        <button id="create-emp-btn" type="submit" class="bg-1 bor-1 text-white mb-1 py-1 px-4 btn-sm float-right border-0"><b><i class="mdi mdi-account-plus"></i> &nbsp;Create Now</b></button>
                    </div>
                    <style>
                    .form-check-input:checked {
                        background-color: #0C0061;
                        border: none;
                    }

                    </style>
                </form>
                <script>
                    var form_submit = false;
                    $(".employee-create-form").submit(function(e) {
                        e.preventDefault();
                        if (form_submit)
                            return;
                        $.ajax({
                            type: "POST",
                            url: root + "merchant/php/save_employee.php",
                            data: new FormData(this),
                            processData: false,
                            contentType: false,
                            cache: false,
                            beforeSend: function() {
                                form_submit = true;
                                $("p.message").html(" <span class='text-1'><i class='fa fa-spinner fa-spin'></i> Creating.. Please Wait.... </span>");
                                $("#create-emp-btn").attr("disabled", true);
                            },
                            success: function(response) {
                                console.log(response);
                                $("#create-emp-btn").removeAttr("disabled");
                                if (response["status"] == "success")
                                    $("p.message").html(" <span class='text-1'><i class='fa fa-check'></i> " + response["message"] + " </span>");
                                else if (response["status"] == "error")
                                    $("p.message").html(" <span class='text-3'><i class='fa fa-times'></i> " + response["message"] + " </span>");
                                else
                                    alert("what");
                                form_submit = false;
                                $(".employee-btn").click();
                                setTimeout(function() { $("p.message").html("") }, 5000);
                            }
                        })
                    })

                    $(".form-close-icn").click(function(){
                        $(".employee-form-content").addClass("animate__fadeOut");
                        setTimeout(function(){
                            $(".main-content").removeClass("position-fixed");
                            $(".employee-form-content").remove();},
                        500);
                    })
            </script>
        </div>
    </div>
</div>