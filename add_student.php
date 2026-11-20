<?php
$page_title = "Add Student";
require_once 'db.php';

$message = "";
$message_type = "";

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize user inputs
    $name       = trim($_POST['name'] ?? '');
    $reg_no     = trim($_POST['reg_no'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $phone      = trim($_POST['phone'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $year       = trim($_POST['year'] ?? '');

    // Basic Validation
    if (empty($name) || empty($reg_no) || empty($email) || empty($phone) || empty($department) || empty($year)) {
        $message = "Please fill in all required fields.";
        $message_type = "danger";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
        $message_type = "danger";
    } else {
        // Check if Register Number already exists using prepared statement
        $check_query = "SELECT id FROM students WHERE reg_no = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("s", $reg_no);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $message = "A student with Register Number '{$reg_no}' already exists!";
            $message_type = "warning";
            $check_stmt->close();
        } else {
            $check_stmt->close();

            // Insert new student using MySQL prepared statement
            $insert_query = "INSERT INTO students (name, reg_no, email, phone, department, year) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);

            if ($stmt) {
                // "ssssss" indicates 6 string parameters
                $stmt->bind_param("ssssss", $name, $reg_no, $email, $phone, $department, $year);

                if ($stmt->execute()) {
                    $message = "Student record added successfully!";
                    $message_type = "success";

                    // Clear form fields after successful insert
                    $name = $reg_no = $email = $phone = $department = $year = "";
                } else {
                    $message = "Database error: Unable to add student. " . $stmt->error;
                    $message_type = "danger";
                }
                $stmt->close();
            } else {
                $message = "Failed to prepare query statement.";
                $message_type = "danger";
            }
        }
    }
}

include 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Breadcrumb / Back button -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0 text-dark">
                <i class="bi bi-person-plus text-primary me-2"></i>Add New Student
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

        <!-- Student Registration Form Card -->
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="add_student.php" method="POST" autocomplete="off">
                    <div class="row g-3">
                        <!-- Student Name -->
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">Student Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="e.g. Alex Johnson" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                        </div>

                        <!-- Register Number -->
                        <div class="col-md-6">
                            <label for="reg_no" class="form-label fw-semibold">Register Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="reg_no" name="reg_no" placeholder="e.g. REG2024101" value="<?php echo htmlspecialchars($reg_no ?? ''); ?>" required>
                        </div>

                        <!-- Email Address -->
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="e.g. alex@example.com" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                        </div>

                        <!-- Phone Number -->
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="e.g. 9876543210" value="<?php echo htmlspecialchars($phone ?? ''); ?>" required>
                        </div>

                        <!-- Department -->
                        <div class="col-md-6">
                            <label for="department" class="form-label fw-semibold">Department <span class="text-danger">*</span></label>
                            <select class="form-select" id="department" name="department" required>
                                <option value="" disabled <?php echo empty($department) ? 'selected' : ''; ?>>-- Select Department --</option>
                                <option value="Computer Science" <?php echo (isset($department) && $department == 'Computer Science') ? 'selected' : ''; ?>>Computer Science</option>
                                <option value="Information Technology" <?php echo (isset($department) && $department == 'Information Technology') ? 'selected' : ''; ?>>Information Technology</option>
                                <option value="Electronics & Communication" <?php echo (isset($department) && $department == 'Electronics & Communication') ? 'selected' : ''; ?>>Electronics & Communication</option>
                                <option value="Electrical Engineering" <?php echo (isset($department) && $department == 'Electrical Engineering') ? 'selected' : ''; ?>>Electrical Engineering</option>
                                <option value="Mechanical Engineering" <?php echo (isset($department) && $department == 'Mechanical Engineering') ? 'selected' : ''; ?>>Mechanical Engineering</option>
                                <option value="Civil Engineering" <?php echo (isset($department) && $department == 'Civil Engineering') ? 'selected' : ''; ?>>Civil Engineering</option>
                            </select>
                        </div>

                        <!-- Year -->
                        <div class="col-md-6">
                            <label for="year" class="form-label fw-semibold">Academic Year <span class="text-danger">*</span></label>
                            <select class="form-select" id="year" name="year" required>
                                <option value="" disabled <?php echo empty($year) ? 'selected' : ''; ?>>-- Select Year --</option>
                                <option value="1st Year" <?php echo (isset($year) && $year == '1st Year') ? 'selected' : ''; ?>>1st Year</option>
                                <option value="2nd Year" <?php echo (isset($year) && $year == '2nd Year') ? 'selected' : ''; ?>>2nd Year</option>
                                <option value="3rd Year" <?php echo (isset($year) && $year == '3rd Year') ? 'selected' : ''; ?>>3rd Year</option>
                                <option value="4th Year" <?php echo (isset($year) && $year == '4th Year') ? 'selected' : ''; ?>>4th Year</option>
                            </select>
                        </div>

                        <!-- Submit and Reset Buttons -->
                        <div class="col-12 mt-4 pt-2 border-top d-flex gap-2 justify-content-end">
                            <a href="view_students.php" class="btn btn-light px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4 fw-semibold">
                                <i class="bi bi-save me-1"></i> Save Student
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
