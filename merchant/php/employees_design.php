<div class="row animate__animated text-center animate__fadeIn">
<?php 
    require_once "../../common/db.php";
    require_once "../../common/imp_functions.php";
    $stmt = $pdo -> prepare("SELECT * FROM users
    INNER JOIN employees ON users.id = employees.user_id WHERE role = ? AND merchant_id = ?");
    $stmt -> execute(["employee", $_SESSION["logged_in_merchant"]]);
    if($stmt -> rowCount() == 0){
 ?>
    <div class="bg-white p-3 w-auto sh text-1 no-emp-msg">
        <i class="fa fa-times-circle d-block m-2" style="font-size: 35px;"></i>
        <h5>No Employees account Found</h5>
        <button class="btn btn-sm text-1 px-0 create-emp-btn shadow-none border-0"><strong> Click Here</strong></button> to create one
    </div>
<?php }
    else{
        ?>
        <div class="text-start">
            <button class="btn btn-sm px-5 py-2 border bor-2 text-2 my-3 mx-0 shadow-none create-emp-btn" style="border-radius: 30px;">
                <strong> <i class="fa fa-user-plus"></i> &nbsp;Create a new Employee</strong>
            </button>
        </div>
        
        <div class="card sh" style="max-width: 1000px">
            <style>
                .action-icon{
                    border-radius: 5px;
                    color: var(--color1);
                }
                .action-icon:hover{
                    color: white;
                    background-color: var(--color1);
                }
            </style>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Activation</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody> 
                <?php 
                    $employees = $stmt -> fetchAll();
                    foreach($employees as $emp){
                        echo "<tr data-id='${emp['user_id']}'>";
                        echo "<td valign='center'><h6 class=' editable'>${emp['name']}</h6></td>";
                        echo "<td valign='center'><h6>${emp['email']}</h6></td>";
                        echo "<td valign='center'><h6 class=' editable'>${emp['phone']}</h6></td>";
                        echo "<td valign='center'><h6 class=' editable'>${emp['address']}</h6></td>";
                        if($emp["status"] == "deactivated")
                            echo "<td valign='center'><h6>Deactivated</h6></td>";
                        else if($emp["status"] == "activated")
                            echo "<td valign='center'><h6 class=''>Activated</h6></td>";
                            echo '<td valign="center">
                                <div class="action-box d-flex justify-content-center align-items-center">' ;
                                if($emp["status"] == "activated")
                                    echo '<i class="fa fa-user-times  cp px-2 py-1  action-icon" onclick="deactivate_emp(this)" title="Deactivate Employee"></i>';
                                else if($emp["status"] == "deactivated")
                                    echo '<i class="fa fa-user-check  cp px-2 py-1  action-icon" onclick="activate_emp(this)" title="Activate Employee"></i>';
                                echo '<i class="fa fa-save cp px-2 py-1  action-icon d-none" title="Save Employee"> </i>
                                    <i class="fa fa-edit cp px-2 py-1  action-icon" title="Edit Employee" onclick="edit_employee(this)"> </i>
                                    <i class="fa fa-trash cp px-2 py-1  action-icon" title="Delete Employee" onclick="delete_employee(this)"> </i>
                                </div>
                            </td>';
                        echo "</tr>";

                    }
                ?>
                </tbody>
            </table>
        </div>
        <?php
    }
 ?>
<script>
    $(".create-emp-btn").click(function(){
        $.ajax({
            type: "POST",
            url: root+"merchant/php/employee_form.php",
            beforeSend: function(){
                $(this).html("<i class='fa fa-spinner fa-spin'></i>");
            },
            success: function(response){
                $(this).html("<strong> Click Here</strong>");
                $(".main-content").addClass("position-fixed");
                $(".external-content").html(response);
            }
        })
    })


        //edit and save employee's details
        function edit_employee(edit_icon){
            var data_saved = false;
            var save_icon = edit_icon.previousElementSibling;
            var row = edit_icon.parentElement.parentElement.parentElement;
            if($(row).attr("data-edit-mode") == "true")     return;
            $(row).attr("data-edit-mode", "true");
            $(edit_icon).addClass("d-none");
            $(save_icon).removeClass("d-none");
            var elements = row.querySelectorAll("td h6.editable");
            var row_id = $(row).attr("data-id");
            $(elements).each(function(){
                $(this).attr("contenteditable", true);
                $(this).addClass("border border-1");
                $(this).removeClass("border-0");
                $(this).addClass("bor-1");
                $(this).addClass("text-1");
            })
            elements[0].focus();
            $(save_icon).click(function(){
                if(data_saved) return;
                $(save_icon).addClass("d-none");
                $(edit_icon).removeClass("d-none");
                $(elements).each(function(){
                    $(this).removeAttr("contenteditable");
                    $(this).removeClass("border border-1");
                    $(this).addClass("border-0");
                    $(this).removeClass("bor-1");
                    $(this).removeClass("text-1");
                })
                $.ajax({
                    type: "POST",
                    url: root+"merchant/php/employee_action.php",
                    data:{
                        id: row_id,
                        employee_name: elements[0].innerText,
                        employee_contact: elements[1].innerText,
                        employee_address: elements[2].innerText,
                        action: "edit"
                    },
                    beforeSend: function(){
                        $(edit_icon).removeClass("fa-edit");
                        $(edit_icon).addClass("fa-spinner fa-spin");
                    },
                    success: function(response){
                        console.log(response);
                        $(edit_icon).removeClass("fa-spinner fa-spin");
                        if(response["status"] == "success")
                            $(edit_icon).addClass("fa-check");
                        

                        else if(response["status"] == "error")
                            $(edit_icon).addClass("fa-times");
                        
                        
                        data_saved = true;
                        setTimeout(function(){
                            $(edit_icon).removeClass("fa-check");
                            $(edit_icon).removeClass("fa-times");
                            $(edit_icon).addClass("fa-edit");
                            $(row).attr("data-edit-mode", "false");
                        },2000);
                    }
                })
                return false;
            })
            return false;
        }


            function delete_employee(del_icon){
            var row = del_icon.parentElement.parentElement.parentElement;
            $.ajax({
                type: "POST",
                url: root+"merchant/php/employee_action.php",
                data: {
                    id: $(row).attr("data-id"),
                    action: "delete"
                },
                beforeSend: function(){
                    $(del_icon).removeClass("fa-trash");
                    $(del_icon).addClass("fa-spinner fa-spin");
                },
                success: function(response){
                    $(".employee-btn").click();
                    console.log(response);
                }
            })
        }


        function activate_emp(del_icon){
            var row = del_icon.parentElement.parentElement.parentElement;
            $.ajax({
                type: "POST",
                url: root+"merchant/php/employee_action.php",
                data: {
                    id: $(row).attr("data-id"),
                    action: "activate"
                },
                beforeSend: function(){
                    $(del_icon).removeClass("fa-trash");
                    $(del_icon).addClass("fa-spinner fa-spin");
                },
                success: function(response){
                    $(".employee-btn").click();
                    console.log(response);
                }
            })
        }

        function deactivate_emp(del_icon){
            var row = del_icon.parentElement.parentElement.parentElement;
            $.ajax({
                type: "POST",
                url: root+"merchant/php/employee_action.php",
                data: {
                    id: $(row).attr("data-id"),
                    action: "deactivate"
                },
                beforeSend: function(){
                    $(del_icon).removeClass("fa-trash");
                    $(del_icon).addClass("fa-spinner fa-spin");
                },
                success: function(response){
                    $(".employee-btn").click();
                    console.log(response);
                }
            })
        }

</script>
</div>