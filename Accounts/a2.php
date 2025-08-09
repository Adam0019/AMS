document.addEventListener('DOMContentLoaded', function(){
    // Fix the event listener ID
    document.getElementById('a_type').addEventListener('change', function () {
        if (this.value === 'Cash') {
            const CashModal = new bootstrap.Modal(document.getElementById('CashModal'));
            CashModal.show();
        } else if (this.value === 'Bank') {
            const BankModal = new bootstrap.Modal(document.getElementById('BankModal'));
            BankModal.show();
        }
    });

     document.getElementById('accountForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const aType = document.getElementById('a_type').value;
        
        if(aType === 'Cash'){
            const accAmmo = document.querySelector('input[name="acc_ammo"]').value;
            const accType = document.querySelector('select[name="acc_type"]').value;
            if(!accAmmo.trim() || !accType.trim()){
                alert('Please fill all the details');
                return;
            }
        }
        
        if(aType === 'Bank'){
            const accAmmo = document.querySelector('input[name="acc_ammo"]').value;
            const accType = document.querySelector('select[name="acc_type"]').value;
            const accNum = document.querySelector('input[name="acc_num"]').value;
            const abName = document.querySelector('input[name="ab_name"]').value;
            
            if(!accAmmo.trim() || !accType.trim() || !accNum.trim() || !abName.trim()){
                alert('Please fill all the details');
                return;
            }
        }
         // document.addEventListener('DOMContentLoaded', function(){

  // document.getElementById('acc_type').addEventListener('change', function () {
  //   const customField = document.getElementById('customAccTypeField');
  //   if (this.value === 'other') {
  //     customField.classList.remove('hidden');
  //   } else {
  //     customField.classList.add('hidden');
  //   }
  // });

  // const aType = document.getElementById('a_type').value;
  // if(aType === 'Cash'){
  //   const accAmmo = document.querySelector('input[name="acc_ammo"}').value;
  //   const accType = document.querySelector('input[name="acc_type"}').value;
  //   if(!accAmmo.trim()||!accType.trim()){
  //     alert('Please fill all the details');
  //           return;
  //   }
  // }
  // if(aType === 'Bank'){
  //   const accAmmo = document.querySelector('input[name="acc_ammo"}').value;
  //   const accType = document.querySelector('input[name="acc_type"}').value;
  //   const accNum = document.querySelector('input[name="acc_num"}').value;
  //   const abName = document.querySelector('input[name="ab_num"}').value;
  //     if(!accAmmo.trim()||!accType.trim()||!accNum.trim()||abName.trim()){
  //     alert('Please fill all the details');
  //           return;
  //   }
  // }
//  const form = e.target;
//     const formData = new FormData(form);

//     fetch('store_account.php', {
//         method: 'POST',
//         body: formData
//     })
//     .then(response => response.text())
//     .then(data => {
//         if (data.includes('account added successfully')) {
//             alert('account added successfully!');
//             window.location.href = "account.php";
//         } else {
//             alert("Error: " + data);
//         }
//     })
//     .catch(error => {
//         console.error('Error:', error);
//         alert("Network error occurred. Please try again.");
//     });
// });