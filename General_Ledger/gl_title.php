<?php

include('../includes/header.php');
if (isset($_SESSION['userAuth']) && $_SESSION['userAuth'] != "") {
  include('../includes/sidebar.php');
  ?>

  <main class="mt-3 pt-3">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8">
          <h4>General Ledger Titles</h4>
        </div>
        <!-- Add Acc Type modal button -->
        <div class="mt-4 px-4">
          <button type="button" class="btn btn-light float-end" data-bs-toggle="modal" data-bs-target="#addGLModal">
            <i class="bi bi-person-fill-add"></i>
            ADD GL Titles
          </button>
          <!-- <button id="deleteSelectedBtn" class="btn btn-danger mb-3">Delete Selected</button> -->
        </div>
      </div>

      <!-- Acc Type Table -->
      <?php
       require_once('../config/dbcon.php');
      try {
        $query = "SELECT * FROM gl_tbl ORDER BY gl_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      } catch (PDOException $e) {
        echo "Error fetching records: " . $e->getMessage();
        $result = [];
      }
      ?>
      <div class="mt-3">
        <table id="example" class="table table-striped data-table" style="width:100%">
          <thead>
            <tr>
              <!-- <th><input type="checkbox" id="selectAllVisible" /></th> -->
              <th>GL_ID</th>
              <th>Date Created</th>
              <th>Name</th>
              <th>Description</th>
              <th>Type</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($result as $row) { ?>
              <tr>
                <!-- <td><input type="checkbox" class="selectBox" name="gl_ids[]" value="<?php echo $row['gl_id']; ?>"></td> -->
                <td><?php echo $row['gl_id']; ?></td>
                <td><?php echo htmlspecialchars( $row['created_at']); ?></td>
                <td><?php echo htmlspecialchars( $row['gl_name']); ?></td>
                <td><?php echo htmlspecialchars( $row['gl_descript']); ?></td>
                <td><?php echo htmlspecialchars( $row['gl_type']); ?></td>
                <td>
                  
                  <a href="gl_toggle.php?id=<?php echo htmlspecialchars($row['gl_id']); ?>" class="btn btn-sm <?php echo ($row['gl_status'] == 'active') ? 'btn-success' : 'btn-secondary'; ?>">
                                    <?php echo ($row['gl_status'] == 'active') ? 'Active' : 'Inactive'; ?>
                                </a>
                </td>
                <td>
                  <button class="btn btn-warning btn-sm editGL" data-id="<?php echo $row['gl_id']; ?>"
                    data-name="<?php echo htmlspecialchars ($row['gl_name']); ?>" data-description="<?php echo htmlspecialchars ($row['gl_descript']); ?>"
                    data-type="<?php echo htmlspecialchars ($row['gl_type']); ?>" 
                    data-bs-toggle="modal" data-bs-target="#editGLModal">
                    <i class="bi bi-tools"></i>
                  </button>


                  <button class="btn btn-danger btn-sm deleteGL" data-id="<?php echo htmlspecialchars ($row['gl_id']); ?>"
                    data-bs-toggle="modal" data-bs-target="#deleteGLModal"><i class="bi bi-trash"></i></button>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- Add GL modal -->
  <div class="modal fade" id="addGLModal" tabindex="-1" aria-labelledby="addGLModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="addGLModalLabel">Create GL Titles</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addGLForm" method="POST" action="store_gl.php">
            <div class="form-group">
              <label for="gl_name">GL Titles</label>
              <input type="text" class="form-control" id="gl_name" name="gl_name" placeholder="GL Titles" required>
            </div>
            <div class="form-group mt-4">
              <label for="gl_descript">Description</label>
              <select class="form-control" id="gl_descript" name="gl_descript" placeholder="Description">
                <option value=""disabled selected>Description</option>
                <option value="Assets">Assets</option>
                <option value="Liabilities">Liabilities</option>
                <option value="Income">Income</option>
                <option value="Expenses">Expenses</option>
              </select>
            </div>
            <div class="form-group mt-4">
              <label for="gl_type">Type</label>
              <select class="form-select" id="gl_type" name="gl_type" required>
                <option value=""disabled selected>Select Type</option>
                <option value="Credit">Credit</option>
                <option value="Debit">Debit</option>
              </select>
            </div>
            <input type="hidden" name="submit" value="submit">
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit GL Modal -->

<div class="modal fade" id="editGLModal" tabindex="-1" aria-labelledby="editGLLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editGLLabel">Edit GL Title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
          <form id="editGLForm" action="gl_update.php" method="POST">
          <input type="hidden" name="gl_id" id="edit_gl_id">
          <div class="mb-3">
              <label for="edit_gl_name" class="form-label">Name</label>
              <input type="text" name="gl_name" id="edit_gl_name" class="form-control" required>
            </div>
          
          <!-- GL Description Dropdown -->
          <div class="form-group mt-4">
            <label for="gl_descript">Description</label>
            <select class="form-control" id="edit_gl_descript" name="gl_descript" required>
              <option value=""disabled selected>Description</option>
              <option value="Assets">Assets</option>
              <option value="Liabilities">Liabilities</option>
              <option value="Income">Income</option>
              <option value="Expenses">Expenses</option>
            </select>
          </div>

          <!-- GL Type Dropdown -->
          <div class="form-group mt-4">
            <label for="gl_type">Type</label>
            <select class="form-select" id="edit_gl_type" name="gl_type" required>
              <option value=""disabled selected>Type</option>
              <option value="Credit">Credit</option>
              <option value="Debit">Debit</option>
            </select>
          </div>
          
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
    </div>
  </div>
</div>





  <!-- Delete Gl Modal -->
  <div class="modal fade" id="deleteGLModal" tabindex="-1" aria-labelledby="deleteGLModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title" id="deleteGLModalLabel">Delete GL</h1>
          <button type="button" class="btn-close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this GL?
          <input type="hidden" id="deleteGLId">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" id="confirmDelete" class="btn btn-danger">Delete</button>
        </div>
      </div>
    </div>
  </div>

  <?php
  include('../includes/footer.php'); ?>

  <script>
  document.addEventListener('DOMContentLoaded', function () {
  
   document.getElementById('addGLForm').addEventListener('submit', function(e) {
    e.preventDefault();
 const form = e.target;
    const formData = new FormData(form);

    fetch('store_gl.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes('gl added successfully')) {
            alert('gl added successfully!');
            window.location.href = "gl_title.php";
        } else {
            alert("Error: " + data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Network error occurred. Please try again.");
    });
});


  document.querySelectorAll('.editGL').forEach(button => {
    button.addEventListener('click', () => {
      // Set the values for the modal
      document.getElementById('edit_gl_id').value = button.getAttribute('data-id');
      
      // Set Name input value 
      document.getElementById('edit_gl_name').value = button.getAttribute('data-name');

      // Set Description dropdown value
      document.getElementById('edit_gl_descript').value = button.getAttribute('data-description');
      
      // Set Type dropdown value
      document.getElementById('edit_gl_type').value = button.getAttribute('data-type');
    });
  });
     // Edit Credit Form Submission
    document.getElementById('editGLForm').addEventListener('submit', function(e){
        e.preventDefault();
        
         const form = e.target;
        const formData = new FormData(form);
        
        fetch('gl_update.php', {
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
            if(data.includes('gl updated successfully')){
                alert('gl updated successfully!');
                window.location.href = "gl_title.php";
            } else {
                alert("Error: " + data);
            }
        }) 
        .catch(error => {
            console.error('Error:', error);
            alert("Network error occurred. Please try again.");
        });
    });
     // Delete button functionality
    const deleteButtons = document.querySelectorAll('.deleteAcc');
    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('deleteACCId').value = button.getAttribute('data-id');
        });
    });
    
    // Confirm delete
    document.getElementById('deleteGLId').addEventListener('click', function() {
        const glId = document.getElementById('deleteGLId').value;
        
        fetch('gl_delete.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'gl_id=' + glId
        })
        .then(response => response.text())
        .then(data => {
            if (data.includes('success')) {
                alert('gl deleted successfully!');
                window.location.href = "gl_title.php";
            } else {
                alert("Error: " + data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Network error occurred. Please try again.");
        });
    });
    
  });
 


  </script>

  <?php
} else {
  echo '<script>
    alert("Not Authorised!");
    window.location.href = "../index.php";
    </script>';
} ?>