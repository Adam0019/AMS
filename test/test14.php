// Function to fetch and display account balance (same as credit.php)
function fetchAccountBalance(accId, displayElementId) {
    if (!accId || accId === '') {
        document.getElementById(displayElementId).textContent = '';
        return;
    }

    // Show loading indicator
    document.getElementById(displayElementId).textContent = 'Loading...';

    // Create FormData for POST request
    const formData = new FormData();
    formData.append('acc_id', accId);

    fetch('../Credit/get_account_balance.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const balance = parseFloat(data.balance);
            document.getElementById(displayElementId).textContent = '₹ ' + balance.toFixed(2);
            // Store balance for validation
            document.getElementById(displayElementId).setAttribute('data-balance', balance);
        } else {
            document.getElementById(displayElementId).textContent = 'Error: ' + (data.error || 'Unknown error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById(displayElementId).textContent = 'Network error';
    });
}

document.addEventListener('DOMContentLoaded', function(){
    // Account selection handler for Add Debit Modal
    document.getElementById('dbt_acc_id').addEventListener('change', function() {
        fetchAccountBalance(this.value, 'debit_account_balance_display');
    });

    // Account selection handler for Edit Debit Modal
    document.getElementById('edit_dbt_acc_id').addEventListener('change', function() {
        fetchAccountBalance(this.value, 'edit_debit_account_balance_display');
    });

    // Purpose selection handler
    document.getElementById('dbt_gl_id').addEventListener('change', function () {
        const customField1 = document.getElementById('customGLIDField');
        if (this.value === 'other') {
            customField1.classList.remove('d-none');
            document.getElementById('gl_name').required = true;
        } else {
            customField1.classList.add('d-none');
            document.getElementById('gl_name').required = false;
        }
    });

    // Add Debit Form Submission
    document.getElementById('debitForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Validate amount
        const amount = parseFloat(document.getElementById('amount').value);
        if (isNaN(amount) || amount <= 0) {
            alert('Please enter a valid amount greater than 0');
            return;
        }

        // Check account balance
        const balanceElement = document.getElementById('debit_account_balance_display');
        const currentBalance = parseFloat(balanceElement.getAttribute('data-balance') || '0');
        
        if (amount > currentBalance) {
            alert(`Insufficient balance! Current balance: ₹${currentBalance.toFixed(2)}, Debit amount: ₹${amount.toFixed(2)}`);
            return;
        }

        // Validate customer selection
        const customerId = document.getElementById('dbt_c_id').value;

        if (customerId === 'other') {
            const customerName = document.querySelector('input[name="c_name"]').value;
            const customerEmail = document.querySelector('input[name="c_email"]').value;
            const customerPhone = document.querySelector('input[name="c_phone"]').value;
            const customerAddress = document.querySelector('input[name="c_address"]').value;
            const customerRole = document.querySelector('select[name="c_role"]').value;
            
            if (!customerName.trim() || !customerEmail.trim() || !customerPhone.trim() || !customerAddress.trim() || !customerRole.trim()) {
                alert('Please fill all customer details');
                return;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(customerEmail)) {
                alert('Please enter a valid email address');
                return;
            }
            
            // Phone validation (basic)
            const phoneRegex = /^[0-9]{10,}$/;
            if (!phoneRegex.test(customerPhone.replace(/\D/g, ''))) {
                alert('Please enter a valid phone number');
                return;
            }
        }
        
        // Validate purpose selection
        const GLId = document.getElementById('dbt_gl_id').value;
        const GLName = document.getElementById('gl_name').value;
        
        if (GLId === 'other' && GLName.trim() === '') {
            alert('Please enter valid name for new purpose');
            return;
        }

        // Validate cheque details if cheque is selected
        const debitMode = document.getElementById('debit_mode').value;
        if (debitMode === 'Cheque') {
            const chequeNumber = document.querySelector('input[name="cheque_number"]').value;
            const bankName = document.querySelector('input[name="bank_name"]').value;
            const chequeDate = document.querySelector('input[name="cheque_date"]').value;
            
            if (!chequeNumber.trim() || !bankName.trim() || !chequeDate) {
                alert('Please fill all cheque details');
                return;
            }
        }

        // Proceed with form submission
        const form = e.target;
        const formData = new FormData(form);

        fetch('store_debit.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data.includes('Debit added successfully')) {
                alert('Debit added successfully!');
                window.location.href = "debit.php";
            } else {
                alert("Error: " + data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Network error occurred. Please try again.");
        });
    });

    // Show Cheque Modal when Cheque is selected in Add Debit
    document.getElementById('debit_mode').addEventListener('change', function () {
        if (this.value === 'Cheque') {
            const DchequeModal = new bootstrap.Modal(document.getElementById('DchequeModal'));
            DchequeModal.show();
        }
    });

    // Show Customer Modal when 'other' is selected in Add Debit
    document.getElementById('dbt_c_id').addEventListener('change', function () {
        if (this.value === 'other') {
            const DcustomerModal = new bootstrap.Modal(document.getElementById('DcustomerModal'));
            DcustomerModal.show();
        }
    });

    // View Debit Modal
    const viewButtons = document.querySelectorAll('.viewDebit');
    viewButtons.forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('view_debit_id').value = button.getAttribute('data-id');
            document.getElementById('view_debit_id_display').textContent = button.getAttribute('data-id');
            document.getElementById('view_d_date').textContent = button.getAttribute('data-d_date');
            document.getElementById('view_amount').textContent = button.getAttribute('data-amount');
            document.getElementById('view_debit_mode').textContent = button.getAttribute('data-debit_mode');
            document.getElementById('view_c_name').textContent = button.getAttribute('data-c_name');
            document.getElementById('view_gl_name').textContent = button.getAttribute('data-gl_name');
            document.getElementById('view_acc_num').textContent = button.getAttribute('data-acc_num');
            document.getElementById('view_cheque_number').textContent = button.getAttribute('data-cheque_number') || 'N/A';
            document.getElementById('view_bank_name').textContent = button.getAttribute('data-bank_name') || 'N/A';
            document.getElementById('view_cheque_date').textContent = button.getAttribute('data-cheque_date') || 'N/A';
        });
    });

    // Edit Debit Modal
    document.querySelectorAll('.editDebit').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('edit_debit_id').value = button.getAttribute('data-id');
            document.getElementById('edit_d_date').value = button.getAttribute('data-d_date');
            document.getElementById('edit_amount').value = button.getAttribute('data-amount');
            
            const debitMode = button.getAttribute('data-debit_mode');
            document.getElementById('edit_debit_mode').value = debitMode;
            document.getElementById('edit_dbt_c_id').value = button.getAttribute('data-dbt_c_id');
            document.getElementById('edit_dbt_gl_id').value = button.getAttribute('data-dbt_gl_id');
            
            // Set account and fetch its balance
            const accountId = button.getAttribute('data-dbt_acc_id');
            document.getElementById('edit_dbt_acc_id').value = accountId;
            
            // Fetch account balance for the selected account immediately
            if (accountId) {
                fetchAccountBalance(accountId, 'edit_debit_account_balance_display');
            }

            // Set cheque details
            document.getElementById('edit_cheque_number').value = button.getAttribute('data-cheque_number') || '';
            document.getElementById('edit_bank_name').value = button.getAttribute('data-bank_name') || '';
            document.getElementById('edit_cheque_date').value = button.getAttribute('data-cheque_date') || '';
            
            // Show/hide cheque details based on debit mode
            const chequeDetails = document.getElementById('editChequeDetails');
            if (debitMode === 'Cheque') {
                chequeDetails.style.display = 'block';
            } else {
                chequeDetails.style.display = 'none';
            }
        });
    });

    // Show/hide cheque details when debit mode changes in edit modal
    document.getElementById('edit_debit_mode').addEventListener('change', function () {
        const chequeDetails = document.getElementById('editChequeDetails');
        if (this.value === 'Cheque') {
            chequeDetails.style.display = 'block';
        } else {
            chequeDetails.style.display = 'none';
            // Clear cheque fields when not needed
            document.getElementById('edit_cheque_number').value = '';
            document.getElementById('edit_bank_name').value = '';
            document.getElementById('edit_cheque_date').value = '';
        }
    });

    // Edit Debit Form Submission
    document.getElementById('editDebitForm').addEventListener('submit', function(e){
        e.preventDefault();
        
        // Validate amount
        const amount = parseFloat(document.getElementById('edit_amount').value);
        if (isNaN(amount) || amount <= 0) {
            alert('Please enter a valid amount greater than 0');
            return;
        }

        // Check account balance for edit form
        const balanceElement = document.getElementById('edit_debit_account_balance_display');
        const currentBalance = parseFloat(balanceElement.getAttribute('data-balance') || '0');
        
        // Get the original amount to calculate the difference
        const originalAmount = parseFloat(document.getElementById('edit_amount').getAttribute('data-original') || '0');
        const amountDifference = amount - originalAmount;
        
        // Only check balance if we're increasing the debit amount
        if (amountDifference > 0 && amountDifference > currentBalance) {
            alert(`Insufficient balance for additional debit! Current balance: ₹${currentBalance.toFixed(2)}, Additional debit required: ₹${amountDifference.toFixed(2)}`);
            return;
        }
        
        const form = e.target;
        const formData = new FormData(form);
        
        fetch('updateDebit.php', {
            method:'POST',
            body:formData
        }) 
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            if(data.includes('Debit updated successfully')){
                alert('Debit updated successfully!');
                window.location.href = "debit.php";
            } else {
                alert("Error: " + data);
            }
        }) 
        .catch(error => {
            console.error('Error:', error);
            alert("Network error occurred. Please try again.");
        });
    });

    // Delete Debit Modal
    document.querySelectorAll('.deleteDebit').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('delete_debit_id').value = button.getAttribute('data-id');
        });
    });

    // Confirm Delete
    document.getElementById('confirmDelete').addEventListener('click', function() {
        const debitId = document.getElementById('delete_debit_id').value;
        
        if (debitId) {
            fetch('delete_debit.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'debit_id=' + encodeURIComponent(debitId) + '&csrf_token=' + encodeURIComponent('<?php echo $_SESSION['csrf_token']; ?>')
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(data => {
                if(data.includes('Debit deleted successfully')){
                    alert('Debit deleted successfully!');
                    window.location.href = "debit.php";
                } else {
                    alert("Error: " + data);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Network error occurred. Please try again.");
            });
        }
    });
});