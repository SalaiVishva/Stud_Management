<?php
$page_title = "Edit Student";
require_once 'db.php';

$message = "";
$message_type = "";

// Check if student ID is provided in query string or POST data
$student_id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : 0);

if ($student_id <= 0) {
    header("Location: view_students.php?msg=notfound");
    exit;
}

// Handle Form Submission for Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name       = trim($_POST['name'] ?? '');
    $reg_no     = trim($_POST['reg_no'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $phone      = trim($_POST['phone'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $year       = trim($_POST['year'] ?? '');

    // Validation
    if (empty($name) || empty($reg_no) || empty($email) || empty($phone) || empty($department) || empty($year)) {
        $message = "Please fill in all required fields.";
        $message_type = "danger";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
        $message_type = "danger";
    } else {
        // Check if register number is already used by another student
        $check_stmt = $conn->prepare("SELECT id FROM students WHERE reg_no = ? AND id != ?");
        $check_stmt->bind_param("si", $reg_no, $student_id);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $message = "Register Number '{$reg_no}' is already assigned to another student!";
            $message_type = "warning";
            $check_stmt->close();
        } else {
            $check_stmt->close();

            // Update record using MySQL prepared statement
            $update_query = "UPDATE students SET name = ?, reg_no = ?, email = ?, phone = ?, department = ?, year = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);

            if ($stmt) {
                $stmt->bind_param("ssssssi", $name, $reg_no, $email, $phone, $department, $year, $student_id);

                if ($stmt->execute()) {
                    $stmt->close();
                    // Redirect back to students list with success notification
                    header("Location: view_students.php?msg=updated");
                    exit;
                } else {
                    $message = "Error updating student record: " . $stmt->error;
                    $message_type = "danger";
                    $stmt->close();
                }
            } else {
                $message = "Failed to prepare update statement.";
                $message_type = "danger";
            }
        }
    }
}

// Fetch current student details for the form
$select_stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$select_stmt->bind_param("i", $student_id);
$select_stmt->execute();
$result = $select_stmt->get_result();

if ($result->num_rows === 0) {
    $select_stmt->close();
    header("Location: view_students.php?msg=notfound");
    exit;
}

$student = $result->fetch_assoc();
$select_stmt->close();

include 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Top Navigation -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0 text-dark">
                <i class="bi bi-pencil-square text-primary me-2"></i>Edit Student Details
            </h4>
            <a href="view_students.php" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Students
            </a>
        </div>

        <!-- Alert Notification -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi <?php echo ($message_type == 'success') ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'; ?> me-2"></i>
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Edit Form Card -->
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="edit_student.php?id=<?php echo $student_id; ?>" method="POST" autocomplete="off">
                    <!-- Hidden student ID -->
                    <input type="hidden" name="id" value="<?php echo $student['id']; ?>">

                    <div class="row g-3">
                        <!-- Student Name -->
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">Student Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo htmlspecialchars($student['name']); ?>" required>
                        </div>

                        <!-- Register Number -->
                        <div class="col-md-6">
                            <label for="reg_no" class="form-label fw-semibold">Register Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="reg_no" name="reg_no" 
                                   value="<?php echo htmlspecialchars($student['reg_no']); ?>" required>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($student['email']); ?>" required>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?php echo htmlspecialchars($student['phone']); ?>" required>
                        </div>

                        <!-- Department -->
                        <div class="col-md-6">
                            <label for="department" class="form-label fw-semibold">Department <span class="text-danger">*</span></label>
                            <select class="form-select" id="department" name="department" required>
                                <option value="" disabled>-- Select Department --</option>
                                <?php
                                $departments = [
                                    "Computer Science",
                                    "Information Technology",
                                    "Electronics & Communication",
                                    "Electrical Engineering",
                                    "Mechanical Engineering",
                                    "Civil Engineering"
                                ];
                                foreach ($departments as $dept) {
                                    $selected = ($student['department'] == $dept) ? 'selected' : '';
                                    echo "<option value=\"$dept\" $selected>$dept</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Academic Year -->
                        <div class="col-md-6">
                            <label for="year" class="form-label fw-semibold">Academic Year <span class="text-danger">*</span></label>
                            <select class="form-select" id="year" name="year" required>
                                <option value="" disabled>-- Select Year --</option>
                                <?php
                                $years = ["1st Year", "2nd Year", "3rd Year", "4th Year"];
                                foreach ($years as $yr) {
                                    $selected = ($student['year'] == $yr) ? 'selected' : '';
                                    echo "<option value=\"$yr\" $selected>$yr</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="col-12 mt-4 pt-2 border-top d-flex gap-2 justify-content-end">
                            <a href="view_students.php" class="btn btn-light px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4 fw-semibold">
                                <i class="bi bi-arrow-repeat me-1"></i> Update Student
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
