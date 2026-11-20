<?php
$page_title = "All Students";
require_once 'db.php';

// Check for feedback messages via URL query parameter
$alert_msg = "";
$alert_type = "";

if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'added') {
        $alert_msg = "Student record added successfully!";
        $alert_type = "success";
    } elseif ($_GET['msg'] == 'updated') {
        $alert_msg = "Student details updated successfully!";
        $alert_type = "success";
    } elseif ($_GET['msg'] == 'deleted') {
        $alert_msg = "Student record deleted successfully!";
        $alert_type = "success";
    } elseif ($_GET['msg'] == 'notfound') {
        $alert_msg = "Student record not found!";
        $alert_type = "warning";
    } elseif ($_GET['msg'] == 'error') {
        $alert_msg = "An error occurred while processing the request.";
        $alert_type = "danger";
    }
}

// Fetch all students using MySQL prepared statement
$students = [];
$query = "SELECT * FROM students ORDER BY id DESC";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    $stmt->close();
}

include 'includes/header.php';
?>

<!-- Header & Actions -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
    <div>
        <h4 class="fw-bold mb-1 text-dark">
            <i class="bi bi-people text-primary me-2"></i>Students Directory
        </h4>
        <p class="text-muted small mb-0">Total Enrolled: <strong class="text-primary"><?php echo count($students); ?></strong> student(s)</p>
    </div>
    <div>
        <a href="add_student.php" class="btn btn-primary fw-semibold shadow-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Add New Student
        </a>
    </div>
</div>

<!-- Notification Alert -->
<?php if (!empty($alert_msg)): ?>
    <div class="alert alert-<?php echo $alert_type; ?> alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi <?php echo ($alert_type == 'success') ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'; ?> me-2"></i>
        <?php echo htmlspecialchars($alert_msg); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Students Table Card -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <?php if (!empty($students)): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Register No</th>
                            <th>Student Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Department</th>
                            <th>Year</th>
                            <th class="text-center pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $serial = 1;
                        foreach ($students as $student): 
                        ?>
                            <tr>
                                <td class="ps-4 text-muted small"><?php echo $serial++; ?></td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary fw-semibold">
                                        <?php echo htmlspecialchars($student['reg_no']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?php echo htmlspecialchars($student['name']); ?></div>
                                </td>
                                <td>
                                    <a href="mailto:<?php echo htmlspecialchars($student['email']); ?>" class="text-decoration-none text-muted">
                                        <?php echo htmlspecialchars($student['email']); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($student['phone']); ?></td>
                                <td>
                                    <span class="badge bg-info-subtle text-info-emphasis">
                                        <?php echo htmlspecialchars($student['department']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary">
                                        <?php echo htmlspecialchars($student['year']); ?>
                                    </span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <!-- Edit Button -->
                                        <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="btn btn-outline-primary" title="Edit Student">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <!-- Delete Button with JavaScript confirmation -->
                                        <a href="delete_student.php?id=<?php echo $student['id']; ?>" 
                                           class="btn btn-outline-danger" 
                                           title="Delete Student" 
                                           onclick="return confirm('Are you sure you want to delete student: <?php echo htmlspecialchars(addslashes($student['name'])); ?> (<?php echo htmlspecialchars($student['reg_no']); ?>)?\n\nThis action cannot be undone.');">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-folder2-open display-4 d-block mb-3 opacity-50"></i>
                <h5 class="fw-semibold text-dark">No Students Found</h5>
                <p class="mb-3">Get started by enrolling a new student into the system.</p>
                <a href="add_student.php" class="btn btn-primary btn-sm">
                    <i class="bi bi-person-plus-fill me-1"></i> Add Student
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
