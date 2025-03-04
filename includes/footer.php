<?php
include('theme.php')
?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.2/dist/chart.min.js"></script>
    <script src="../js/dataTables.bootstrap5.min.js"></script>
    <script src="../js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


    <script>
        //View the Customer details
     document.addEventListener("DOMContentLoaded", function () { 
        document.querySelectorAll(".viewCustomer").forEach(button => {
            button.addEventListener("click", function () {
                let customerId = this.getAttribute("data-id");
                fetch("fetch_customer_for_view.php?id=" + customerId)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("customerDetails").innerHTML = data;
                    })
                    .catch(error => console.error("Error fetching customer details:", error));
            });
        });

       
    });

   

    // edit customer
    document.addEventListener("DOMContentLoaded", function () {
        // Load customer details into Edit Modal
        document.querySelectorAll(".editCustomer").forEach(button => {
            button.addEventListener("click", function () {
                let customerId = this.getAttribute("data-id");
                fetch("fetch_customer.php?id=" + customerId)
                    .then(response => response.json())  // Expecting JSON response
                    .then(data => {
                        document.getElementById("edit_c_id").value = data.c_id;
                        document.getElementById("edit_c_name").value = data.c_name;
                        document.getElementById("edit_c_email").value = data.c_email;
                        document.getElementById("edit_c_phone").value = data.c_phone;
                        document.getElementById("edit_c_role").value = data.c_role;
                    })
                    .catch(error => console.error("Error loading customer data:", error));
            });
        });

        // Submit updated data to server
        document.getElementById("editCustomerForm").addEventListener("submit", function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            fetch("update_customer.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);  // Show success/error message
                location.reload();  // Refresh the page
            })
            .catch(error => console.error("Error updating customer:", error));
        });
    });


        // View the User details
         document.addEventListener("DOMContentLoaded", function () { 
        document.querySelectorAll(".viewUser").forEach(button => {
            button.addEventListener("click", function () {
                let userId = this.getAttribute("data-id");
                fetch("fetch_user_for_view.php?id=" + userId)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("userDetails").innerHTML = data;
                    })
                    .catch(error => console.error("Error fetching user details:", error));
            });
        });

       
    });

   // edit user details
    document.addEventListener("DOMContentLoaded", function () {
        // Load user details into Edit Modal
        document.querySelectorAll(".editUser").forEach(button => {
            button.addEventListener("click", function () {
                let userId = this.getAttribute("data-id");
                fetch("fetch_user.php?id=" + userId)
                    .then(response => response.json())  // Expecting JSON response
                    .then(data => {
                        document.getElementById("edit_u_id").value = data.u_id;
                        document.getElementById("edit_u_name").value = data.u_name;
                        document.getElementById("edit_u_email").value = data.u_email;
                        document.getElementById("edit_u_phone").value = data.u_phone;
                        document.getElementById("edit_password").value = data.password;
                        document.getElementById("edit_role").value = data.role;
                    })
                    .catch(error => console.error("Error loading user data:", error));
            });
        });

        // Submit updated data to server
        document.getElementById("editUserForm").addEventListener("submit", function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            fetch("update_user.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);  // Show success/error message
                location.reload();  // Refresh the page
            })
            .catch(error => console.error("Error updating user:", error));
        });
    });









document.addEventListener('DOMContentLoaded', function() {
    var current = new URL(location.href).pathname.split('/').pop().split('?')[0];
    var navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(function(link) {
        if (link.href.endsWith(current)) {
            link.parentElement.classList.add('active');
        }
    });
});

$(document).ready(function() {
    $('#userForm').on('submit', function(e){
        e.preventDefault();
        console.log('working');

        $.ajax({
            url: '../signup_check.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response){
                // console.log(response);
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

    </script>




</body>

</html>
