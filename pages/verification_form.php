<?php
    $header = "MIME-Version:1.0\r\nContent-Type: text/html;charset=ISO-8859-1\r\n";
    session_start();
    $message = '<html>
    <head>
    <body style="display: flex; align-items: center; justify-content: center; padding: 10px;">
        <div style="padding: 10px; margin: 10px; box-shadow: 0 0 5px 5px #ccc; border-radius: 5px;">
            <strong style="font-family: cursive; font-size: 17px;">Hi! Thanks for being registered in <span style="color: #1F00F2">smartstockbook</span>... <br> Your user verification Key to <span style="color: #1F00F2">smartstockbook</span> is :</strong>
            <h4 style="font-family: cursive; text-align: center; color: #000066; letter-spacing: 2px; padding: 0; margin: 4px; ">'.$_POST['code'].'</h4>
        </div>
    </body>
    </html>';
    if(mail($_POST["email"], "SSB user verification", $message, $header)){
        ?>
        <div class="animate__animated animate__fadeIn animate_faster d-flex justify-content-center align-items-center w-100 position-absolute verification-content" style="width: 100%; min-height: 100vh; z-index: 20000; background-color: #130202DE;">
            <form class="verification-form bg-transparent text-center">
        <h4 class="text-white mb-1">Account Verification</h4>
        <span class="text-center d-block text-white mb-3">Verification code  has been sent to the email ( <?php echo $_POST["email"] ?> ) you've registered.. <br>Enter that code below.</span>
        <div class="card  border-white rounded mx-auto" style="max-width: 315px;">
            <div class="card-body border-white">
                <div class="form-control">
                    <label for="code-text" class="text-1"><b>Enter the account verification code below</b></label>
                    <input type="number" id="code-text" class="form-control text-1 mb-2 shadow-none border bor-1" name="code">
                    <button type="submit" class="btn verify-btn bg-1 text-white cp" disabled><b>Verify Now!</b></button>
                </div>
            </div>
        </div>
    </form>
        </div>

        <script>
            $(".verification-form").submit(function(e){
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "./php/verification.php",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    beforeSend: function(){
                        $(".verify-btn").attr("disabled", "true");
                        $("#code-text").attr("disabled", "true");
                        $(".verification-msg").html("<b><i class='fa fa-spinner fa-spin'></i> &nbsp; Please Wait! Signing Up....</b>");
                    },
                    success: function(response){
                        if(response["status"] == "error"){
                            $(".verification-msg").html("<span class='text-3'><i class='fa fa-close'></i> &nbsp; "+response["message"].trim()+"</span>");
                            $("#code-text").removeAttr("disabled");
                        }
                        else if(response["status"] == "success"){
                            $(".verification-msg").html("<span class='text-2'><i class='fa fa-check'></i> &nbsp; "+response["message"].trim()+". You can now log in to your account</span>");
                            setTimeout(function(){
                                login_form();
                            },2000)
                        }
                        else
                            $(".verification-msg").html("<span class='text-3'><i class='fa fa-close'></i> &nbsp; OOPPSS!!  seems like something went wrong!!</span>") && console.log(response);
                    }
                })
            })

            $("#code-text").on("input", function(){
                (this.value.length == 6 ) ? $(".verify-btn").removeAttr("disabled") : $(".verify-btn").attr("disabled", "true")
            })
        </script>`;
        <?php
    }
 ?>