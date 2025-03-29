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


//////////// USER Ends ////////////

//////////// ACCOUNT Starts ////////////
    //modal toggle
   $(document).ready(function () {
            function toggleFields() {
                let isBank = $("#bank").is(":checked");
                
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
//////////// ACCOUNT Ends ////////////

    </script>




</body>

</html>
