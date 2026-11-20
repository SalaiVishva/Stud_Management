<?php
// ============================================================
// Delete Student Record (Core PHP - MySQL Prepared Statement)
// ============================================================

require_once 'db.php';

// Validate the ID from GET parameter
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $student_id = intval($_GET['id']);

    if ($student_id > 0) {
        // Prepare delete statement
        $delete_query = "DELETE FROM students WHERE id = ?";
        $stmt = $conn->prepare($delete_query);

        if ($stmt) {
            $stmt->bind_param("i", $student_id);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $stmt->close();
                    header("Location: view_students.php?msg=deleted");
                    exit;
                } else {
                    $stmt->close();
                    header("Location: view_students.php?msg=notfound");
                    exit;
                }
            } else {
                $stmt->close();
                header("Location: view_students.php?msg=error");
                exit;
            }
        }
    }
}

// Redirect back if ID is invalid or missing
header("Location: view_students.php?msg=error");
exit;
?>
