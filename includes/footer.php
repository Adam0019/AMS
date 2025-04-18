<?php
include('theme.php')
?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script> -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.2/dist/chart.min.js"></script>
    <script src="../js/dataTables.bootstrap5.min.js"></script>
    <script src="../js/theme_script.js"></script>
    <script src="../js/script.js"></script>


<script>

// Setting paths for the active links
document.addEventListener('DOMContentLoaded', function() {
    var current = new URL(location.href).pathname.split('/').pop().split('?')[0];
    var navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(function(link) {
        if (link.href.endsWith(current)) {
            link.parentElement.classList.add('active');
        }
    });
});
////////CUSTOMER Starts//////////
// View the Customer details
$(document).ready(function () {
    $(".viewCustomer").on("click", function () {
        let customerId = $(this).data("id");

        $.ajax({
            url: "fetch_customer_for_view.php",
            type: "GET",
            data: { id: customerId },
            dataType: "json", // Expect JSON response
            success: function (response) {
                if (response.success) {
                    let customerDetails = `
                        <p><strong>ID:</strong> ${response.id}</p>
                        <p><strong>Name:</strong> ${response.name}</p>
                        <p><strong>Email:</strong> ${response.email}</p>
                        <p><strong>Phone:</strong> ${response.phone}</p>
                        <p><strong>Role:</strong> ${response.role}</p>
                        <p><strong>Status:</strong> ${response.status}</p>
                    `;
                    $("#customerDetails").html(customerDetails);
                } else {
                    $("#customerDetails").html(`<p style="color: red;">${response.message}</p>`);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching customer details:", error);
                $("#customerDetails").html(`<p style="color: red;">An error occurred while fetching customer data.</p>`);
            }
        });
    });
});

////////// Edit the Customer details
$(document).ready(function () {
    // Load customer details into Edit Modal
    $(".editCustomer").on("click", function () {
        let customerId = $(this).data("id");
        
        $.ajax({
            url: "fetch_customer.php",
            type: "GET",
            data: { id: customerId },
            dataType: "json",
            success: function (data) {
                // console.log('working');
                $("#edit_c_id").val(data.c_id);
                $("#edit_c_name").val(data.c_name);
                $("#edit_c_email").val(data.c_email);
                $("#edit_c_phone").val(data.c_phone);
                $("#edit_c_role").val(data.c_role);
            },
            error: function (xhr, status, error) {
                console.error("Error loading customer data:", error);
            }
        });
    });

    // Submit updated data to server
    $("#editCustomerForm").on("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            url: "update_customer.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                alert( "Customer updated successfully!"); // Show success/error message
                location.reload(); // Refresh the page
            },
            error: function (xhr, status, error) {
                console.error("Error updating customer:", error);
            }
        });
    });
});

////Delete Customer

$(document).ready(function() {
    
    $('.deleteCustomer').on('click', function() {
        const c_Id = $(this).data('id');
        $('#deleteCUSId').val(c_Id);
    });

    // Confirm delete
    $('#confirmDeleteCus').on('click', function() {
        const c_Id = $('#deleteCUSId').val();

        $.ajax({
            url: 'delete_customer.php',
            type: 'POST',
            data: { c_id: c_Id },
            success: function(response) {
                // Handle success response
                $('#deleteCustomerModal').modal('hide');
                 alert("Customer deleted successfully!");
                window.location.reload();
                
            },
            error: function() {
                alert('Error deleting customer.');
            }
        });
    });
});


///////////CUSTOMER Ends//////////

//////////// USER Starts ////////////

// ADD NEW USER
// Storing user data from add user modal to signup_check
$(document).ready(function() {
    $('#userForm').on('submit', function(e){
        e.preventDefault();
        console.log('working');

        $.ajax({
            url: '../signup_check.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response){
                // console.log('response');
                $('#userModal').modal('hide');
                alert('User registrated successfully!');
                window.location.reload();

            },
            error: function(xhr, status, error){
                alert('An error occurred: ' + error);
            }
        });
    });
    
    });



    // View the User details
$(document).ready(function () {
    $(".viewUser").on("click", function () {
        let userId = $(this).data("id");

        $.ajax({
            url: "fetch_user_for_view.php",
            type: "GET",
            data: { id: userId },
            dataType: "json", // Expect JSON response
            success: function (response) {
                if (response.success) {
                    let userDetails = `
                        <p><strong>ID:</strong> ${response.id}</p>
                        <p><strong>Name:</strong> ${response.name}</p>
                        <p><strong>Email:</strong> ${response.email}</p>
                        <p><strong>Password:</strong> ${response.password}</p>
                        <p><strong>Username:</strong> ${response.username}</p>
                        <p><strong>Phone:</strong> ${response.phone}</p>
                        <p><strong>Role:</strong> ${response.role}</p>
                        <p><strong>Status:</strong> ${response.status}</p>
                    `;
                    $("#userDetails").html(userDetails);
                } else {
                    $("#userDetails").html(`<p style="color: red;">${response.message}</p>`);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching user details:", error);
                $("#userDetails").html(`<p style="color: red;">An error occurred while fetching user data.</p>`);
            }
        });
    });
});


// Edit the User details
$(document).ready(function () {
    $(".editUser").on("click", function () {
        let userId = $(this).data("id");

        $.ajax({
            url: "fetch_user.php",
            type: "GET",
            data: { id: userId },
            dataType: "json",
            success: function (data) {
                $("#edit_u_id").val(data.u_id);
                $("#edit_u_name").val(data.u_name);
                $("#edit_u_email").val(data.u_email);
                $("#edit_u_phone").val(data.u_phone);
                $("#edit_password").val(data.password);
                $("#edit_role").val(data.role);
            },
            error: function (xhr, status, error) {
                console.error("Error loading user data:", error);
            }
        });
    });
});

$(document).ready(function () {
    $("#editUserForm").on("submit", function (e) {
        e.preventDefault();
        
        let formData = new FormData(this);

        $.ajax({
            url: "update_user.php",
            type: "POST",
            data: formData,
            processData: false, // Prevent jQuery from converting the data
            contentType: false, // Prevent jQuery from adding a content-type header
            success: function (response) {
                alert("User updated successfully!"); // Show success/error message
                location.reload(); // Refresh the page
            },
            error: function (xhr, status, error) {
                console.error("Error updating user:", error);
            }
        });
    });
});

////Delete User

$(document).ready(function() {
    
    $('.deleteUser').on('click', function() {
        const u_Id = $(this).data('id');
        $('#deleteUserId').val(u_Id);
    });

    // Confirm delete
    $('#confirmDeleteUser').on('click', function() {
        const u_Id = $('#deleteUserId').val();

        $.ajax({
            url: 'delete_user.php',
            type: 'POST',
            data: { u_id: u_Id },
            success: function(response) {
                // Handle success response
                $('#deleteUserModal').modal('hide');
                 alert("User deleted successfully!");
                window.location.reload();
                
            },
            error: function() {
                alert('Error deleting user.');
            }
        });
    });
});


//////////// USER Ends ////////////

//////////// ACCOUNT Starts ////////////
    //modal toggle
   $(document).ready(function () {
            function toggleFields() {
                let isBank = $("#Bank").is(":checked");
                
                $("#ab_nameField").toggle(isBank);
                $("#acc_numField").toggle(isBank);
                $("#acc_typeField").toggle(isBank);
            }

            // Run when radio button is clicked
            $('input[name="a_type"]').change(toggleFields);

            // Run on page load to ensure proper visibility
            toggleFields();
        });

    // ADD NEW ACCOUNT

    $(document).ready(function(){
        $('#accountForm').on('submit', function(e){
            e.preventDefault();
            console.log('account working');

            $.ajax({
                url:'../Accounts/store_account.php',
                type:'POST',
                data: $(this).serialize(),
                success:function(response){
                    // console.log('response');
                    $('#accountModal').modal('hide');
                    alert('Account created successfully!');
                    window.location.reload();
                },
                error:function(xhr,status,error){
                    alert('An error occurred: ' + error);
                }
            })
        });
    });

    //fetch user
     $('#u_id').on('change', function() {
        var cat_id = $(this).val();  
        console.log('hello');
        if(u_id){
            $.ajax({
                url:'../Accounts/fetchUser.php',
                type:'POST',
                data:{u_id:u_id},
                success:function(response){
                    $('#u_id').html(response);},
                error:function(xhr,status,error){
                    console.error("Error fetching user data:", error);
                }
            });
        }else{
            $('#u_id').html('<option value="">Select User</option>');
        }
    });

    // View the Account details
$(document).ready(function(){
    $(".viewAcc").on("click", function(){
        let accId=$(this).data("id");

        $.ajax({
            url:"view_account.php",
            type: "GET",
            data: {id:accId},
            dataType: "json", // Expect JSON response
            success: function(response){
                if(response.success){
                    let accountDetails = `
                        <p><strong>ID:</strong> ${response.id}</p>
                        <p><strong>Name:</strong> ${response.name}</p>
                        <p><strong>Account Number:</strong> ${response.account_number}</p>
                        <p><strong>Account Name:</strong> ${response.account_name}</p>
                        <p><strong>Account Type:</strong> ${response.account_type}</p>
                        <p><strong>Account Amount:</strong> ${response.account_ammo}</p>
                        <p><strong>Status:</strong> ${response.account_status}</p>
                    `;
                    $("#accountDetails").html(accountDetails);
                }else{
                    $("#accountDetails").html(`<p style="color: red;">${response.message}</p>`);
                }
            },
            error: function(xhr, status, error){
                console.error("Error fetching account details:", error);
                $("#accountDetails").html(`<p style="color: red;">An error occurred while fetching account data.</p>`);
            }
        })
    })
})

// Edit the Account details
$(document).ready(function(){
    $(".editAcc").on("click", function(){
        let accId=$(this).data("id");

        $.ajax({
            url:"fetch_account.php",
            type: "GET",
            data: {id:accId},
            dataType: "json",
            success: function(data){
                $("#edit_acc_id").val(data.acc_id);
                $("#edit_u_id").val(data.u_id);
                $("#edit_acc_num").val(data.acc_num);
                $("#edit_ab_name").val(data.ab_name);
                $("#edit_acc_ammo").val(data.acc_ammo);
                $("#edit_acc_type").val(data.acc_type);
            },
            error: function(xhr, status, error){
                console.error("Error loading account data:", error);
            }
        });
    
    });
});

$(document).ready(function(){
    $("#editAccForm").on("submit", function(e){
        e.preventDefault();
        
        let formData = new FormData(this);

        $.ajax({
            url:"update_account.php",
            type: "POST",
            data: formData,
            processData: false, // Prevent jQuery from converting the data
            contentType: false, // Prevent jQuery from adding a content-type header
            success: function(response){
                alert("Account updated successfully!"); // Show success/error message
                location.reload(); // Refresh the page
            },
            error: function(xhr, status, error){
                console.error("Error updating account:", error);
            }
        });
    });
});

////Delete Account

$(document).ready(function() {
    
    $('.deleteAcc').on('click', function() {
        const acc_Id = $(this).data('id');
        $('#deleteACCId').val(acc_Id);
    });

    // Confirm delete
    $('#confirmDeleteAcc').on('click', function() {
        const acc_Id = $('#deleteACCId').val();

        $.ajax({
            url: 'delete_account.php',
            type: 'POST',
            data: { acc_id: acc_Id },
            success: function(response) {
                // Handle success response
                $('#deleteAccModal').modal('hide');
                 alert("Account deleted successfully!");
                window.location.reload();
                
            },
            error: function() {
                alert('Error deleting user.');
            }
        });
    });
});
//////////// ACCOUNT Ends ////////////

//////////// GL Starts ////////////

// ADD NEW GL
$(document).ready(function(){
    $('#addGLForm').on('submit', function(e){
        e.preventDefault();
        console.log('GL working');

        $.ajax({
            url:'../General_Ledger/store_gl.php',
            type:'POST',
            data: $(this).serialize(),
            success:function(response){
                //console.log('response');
                $('#addGLModal').modal('hide');
                 alert('GL Title created successfully!');
                 window.location.reload();
            },
            error:function(xhr,status,error){
                alert('An error occurred: ' + error);
            }
        })
    });
});

////Delete GL Titles Modal

$(document).ready(function() {
    
    $('.deleteGL').on('click', function() {
        const gl_Id = $(this).data('id');
        $('#deleteGLId').val(gl_Id);
        // $('#deleteGLModal').modal('show');
    });

    // Confirm delete
    $('#confirmDelete').on('click', function() {
        const gl_Id = $('#deleteGLId').val();

        $.ajax({
            url: 'gl_delete.php',
            type: 'POST',
            data: { gl_id: gl_Id },
            success: function(response) {
                // Handle success response
                $('#deleteGLModal').modal('hide');
                 alert("GL Title deleted successfully!");
                window.location.reload();
                
            },
            error: function() {
                alert('Error deleting user.');
            }
        });
    });
// });

///Edit GL Modal
// $(document).ready(function () {
    // Load gl details into Edit Modal
    $(".editGL").on("click", function () {
        let glID = $(this).data("id");
        console.log(glID);
        
        $.ajax({
            url: "gl_fetch.php",
            type: "GET",
            data: { id: glID },
            dataType: "json",
            success: function (data) {
                // console.log('working');
                $("#edit_gl_id").val(data.gl_id);
                $("#edit_gl_name").val(data.gl_name);
                $("#edit_gl_descript").val(data.gl_descript);
                $("#edit_gl_type").val(data.gl_type);
            },
            error: function (xhr, status, error) {
                console.error("Error loading gl data:", error);
            }
        });
    });

    // Submit updated data to server
    $("#editGLForm").on("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            url: "gl_update.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                alert( "GL updated successfully!"); // Show success/error message
                location.reload(); // Refresh the page
            },
            error: function (xhr, status, error) {
                console.error("Error updating GL:", error);
            }
        });
    });
});


//////////// GL Ends ////////////

    </script>




</body>

</html>
