<?php
$page_title = "Dashboard";
require_once 'db.php';

$total_students = 0;
$query = "SELECT COUNT(*) AS total FROM students";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $total_students = $row['total'];
    }
    $stmt->close();
}

// Prepared statement to fetch recent 5 students
$recent_students = [];
$query_recent = "SELECT * FROM students ORDER BY id DESC LIMIT 5";
$stmt_recent = $conn->prepare($query_recent);

if ($stmt_recent) {
    $stmt_recent->execute();
    $recent_result = $stmt_recent->get_result();
    while ($row = $recent_result->fetch_assoc()) {
        $recent_students[] = $row;
    }
    $stmt_recent->close();
}

include 'includes/header.php';
?>

<!-- Welcome Banner -->
<div class="card mb-4 bg-primary text-white shadow-sm overflow-hidden border-0">
    <div class="card-body p-4 p-md-5">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-2">Welcome to Student Management System</h2>
                <p class="mb-3 lead opacity-75">Easily manage student records, registrations, updates, and departments all in one place.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="view_students.php" class="btn btn-light text-primary fw-semibold">
                        <i class="bi bi-people-fill me-1"></i> View All Students
                    </a>
                    <a href="add_student.php" class="btn btn-outline-light">
                        <i class="bi bi-person-plus-fill me-1"></i> Register New Student
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-center d-none d-lg-block">
                <i class="bi bi-mortarboard display-1 opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<!-- Metrics & Statistics -->
<div class="row g-4 mb-4">
    <!-- Total Students Card -->
    <div class="col-md-4">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.8rem; letter-spacing: 0.5px;">Total Students</h6>
                    <h2 class="fw-bold mb-0 text-dark"><?php echo $total_students; ?></h2>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                <a href="view_students.php" class="small text-primary text-decoration-none fw-semibold">
                    View list <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Add Student Card -->
    <div class="col-md-4">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <div>
                    <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.8rem; letter-spacing: 0.5px;">New Enrollment</h6>
                    <h5 class="fw-bold mb-0 text-dark">Add Student</h5>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                <a href="add_student.php" class="small text-success text-decoration-none fw-semibold">
                    Open form <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- System Info Card -->
    <div class="col-md-4">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="bi bi-database-check"></i>
                </div>
                <div>
                    <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.8rem; letter-spacing: 0.5px;">Database Status</h6>
                    <h5 class="fw-bold mb-0 text-success">Connected</h5>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                <span class="small text-muted">Core PHP & MySQL Prepared Statements</span>
            </div>
        </div>
    </div>
</div>

<!-- Recently Registered Students Table -->
<div class="card shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Recently Added Students</h5>
        <a href="view_students.php" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($recent_students)): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Reg No</th>
                            <th>Student Name</th>
                            <th>Department</th>
                            <th>Year</th>
                            <th>Phone</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_students as $student): ?>
                            <tr>
                                <td><span class="badge bg-secondary-subtle text-secondary fw-semibold"><?php echo htmlspecialchars($student['reg_no']); ?></span></td>
                                <td class="fw-semibold text-dark"><?php echo htmlspecialchars($student['name']); ?></td>
                                <td><?php echo htmlspecialchars($student['department']); ?></td>
                                <td><span class="badge bg-primary-subtle text-primary"><?php echo htmlspecialchars($student['year']); ?></span></td>
                                <td><?php echo htmlspecialchars($student['phone']); ?></td>
                                <td class="text-end">
                                    <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-people display-4 d-block mb-3 opacity-50"></i>
                <p class="mb-2">No student records found in the database.</p>
                <a href="add_student.php" class="btn btn-sm btn-primary">Add First Student</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
