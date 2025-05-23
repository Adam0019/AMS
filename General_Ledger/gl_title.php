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

        <?php
        // Database connection using PDO
        require_once('../config/dbcon.php');
        ?>

        <!-- Add Acc Type modal button -->
        <div class="mt-4 px-4">
          <button type="button" class="btn btn-light float-end" data-bs-toggle="modal" data-bs-target="#addGLModal">
            <i class="bi bi-person-fill-add"></i>
            ADD GL Titles
          </button>
        </div>
      </div>

      <!-- Acc Type Table -->
      <?php
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
                    data-name="<?php echo $row['gl_name']; ?>" data-description="<?php echo $row['gl_descript']; ?>"
                    data-type="<?php echo $row['gl_type']; ?>" data-bs-toggle="modal" data-bs-target="#editGLModal">
                    <i class="bi bi-tools"></i>
                  </button>


                  <button class="btn btn-danger btn-sm deleteGL" data-id="<?php echo $row['gl_id']; ?>"
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
          <form id="addGLForm">
            <div class="form-group">
              <label for="gl_name">GL Titles</label>
              <input type="text" class="form-control" id="gl_name" name="gl_name" placeholder="GL Titles" required>
            </div>
            <div class="form-group mt-4">
              <label for="gl_descript">Description</label>
              <select class="form-control" id="gl_descript" name="gl_descript" placeholder="Description">
                <option value="Assets" selected>Assets</option>
                <option value="Liabilities">Liabilities</option>
                <option value="Income">Income</option>
                <option value="Expenses">Expenses</option>
              </select>
            </div>
            <div class="form-group mt-4">
              <label for="gl_type">Type</label>
              <select class="form-select" id="gl_type" name="gl_type" required>
                <option value="Credit" selected>Credit</option>
                <option value="Debit">Debit</option>
              </select>
            </div>
            <input type="hidden" name="submit" value="submit">
            <input type="hidden" name="fromGL" value="fromGL">
            <div class="modal-footer">
              <a href="gl_title.php">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button></a>
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
    <form action="gl_update.php" method="POST">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editGLLabel">Edit GL Title</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="gl_id" id="edit_gl_id">
          <div class="mb-3">
              <label for="edit_gl_name" class="form-label">Name</label>
              <input type="text" name="gl_name" id="edit_gl_name" class="form-control" required>
            </div>
          
          <!-- GL Description Dropdown -->
          <div class="form-group mt-4">
            <label for="gl_descript">Description</label>
            <select class="form-control" id="edit_gl_descript" name="gl_descript" required>
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
              <option value="Credit">Credit</option>
              <option value="Debit">Debit</option>
            </select>
          </div>
          
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </div>
    </form>
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
          <a href="gl_title.php">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button></a>
          <button type="button" id="confirmDelete" class="btn btn-danger">Delete</button>
        </div>
      </div>
    </div>
  </div>

  <?php
  include('../includes/footer.php'); ?>

  <script>
  document.addEventListener('DOMContentLoaded', function () {
  const editButtons = document.querySelectorAll('.editGL');
  editButtons.forEach(button => {
    button.addEventListener('click', () => {
      // Set the values for the modal
      document.getElementById('edit_gl_id').value = button.getAttribute('data-id');
      
      // Set Name input value
      const name = button.getAttribute('data-name');
      document.getElementById('edit_gl_name').value = name;

      // Set Description dropdown value
      const description = button.getAttribute('data-description');
      document.getElementById('edit_gl_descript').value = description;
      
      // Set Type dropdown value
      const type = button.getAttribute('data-type');
      document.getElementById('edit_gl_type').value = type;
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