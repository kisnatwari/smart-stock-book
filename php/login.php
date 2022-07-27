<?php 
    require_once "../common/db.php";
    require_once "../common/imp_functions.php";
    header("Content-type: application/json");

   //check if all data are available
    die_on_post_miss(array("Email address" => "email", "Password field" => "password"));

   //checking if email is valid
    if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))     show_error("Invalid email");

    //getting user data of provided email address
    $data = $db -> where("users", "email", "=", $_POST["email"], false);
    if($data){
        $status = $data["status"];
        $u_password = $_POST["password"];
        if(password_verify($u_password, $data["password"])){
            $role = $data["role"];
            if($role == "merchant"){
                if($status == "unverified"){
                    $rand_code = rand(100000, 999999);
                    $_SESSION['email'] = $_POST["email"];
                    $_SESSION['code'] = $rand_code;
                    $data = array("email" => $_POST["email"], "code" => $rand_code);
                    $url = $root."pages/verification_form.php";
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    show_message("verify",curl_exec($curl));
                    curl_close($curl);
                }
                else if($status == "verified"){
                    $_SESSION["logged_in"] = true;
                    $_SESSION["logged_in_email"] = $_POST["email"];
                    $_SESSION["logged_in_role"] = $role;
                    $user_data = $db -> where("users","email", "=", $_SESSION['logged_in_email'], false);
                    $_SESSION["logged_in_merchant"] = $user_data["merchant_id"];
                    $_SESSION["logged_in_id"] = $user_data["id"];
                    show_success("logged in");
                }
            }
            else if($role == "admin"){
                    $_SESSION["logged_in"] = true;
                    $_SESSION["logged_in_email"] = $_POST["email"];
                    $_SESSION["logged_in_role"] = "admin";
                    $_SESSION["logged_in_id"] = $db -> where("users","email", "=", $_SESSION['logged_in_email'], false)["id"];
                    show_success("logged in");
            }
            else if($role == "employee"){
                if($status == "activated"){
                    $_SESSION["logged_in"] = true;
                    $_SESSION["logged_in_email"] = $_POST["email"];
                    $_SESSION["logged_in_role"] = $role;
                    $user_data = $db -> where("users","email", "=", $_SESSION['logged_in_email'], false);
                    $_SESSION["logged_in_merchant"] = $user_data["merchant_id"];
                    $_SESSION["logged_in_id"] = $user_data["id"];
                    show_success("logged in");
                }
                else if($status == "deactivated"){
                    show_error("Account has been deactivated by the merchant. Ask your merchant to activate the account");
                }
            }
        }
        else
            show_error("Wrong password");
    } 
    else
        show_error("user not found");
 ?>
