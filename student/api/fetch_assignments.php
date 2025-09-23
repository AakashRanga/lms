<?php
include('../../includes/config.php'); // DB connection

header('Content-Type: application/json');

// Fetch parameters from URL
$cm_id = isset($_GET['cm_id']) ? intval($_GET['cm_id']) : null;
$launch_c = isset($_GET['launch_c']) ? intval($_GET['launch_c']) : null;

// Validate parameters
if (!$cm_id || !$launch_c) {
    echo json_encode([
        'status' => 400,
        'message' => 'Missing cm_id or launch_c parameters',
        'data' => []
    ]);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT ass_id, title FROM assignment WHERE c_id = ? AND launch_id = ?");
    $stmt->bind_param("ii", $cm_id, $launch_c);
    $stmt->execute();
    $result = $stmt->get_result();

    $assignments = [];
    while ($row = $result->fetch_assoc()) {
        $assignments[] = $row;
    }

    if (empty($assignments)) {
        echo json_encode([
            'status' => 404,
            'message' => 'No assignments found',
            'data' => []
        ]);
    } else {
        echo json_encode([
            'status' => 200,
            'message' => 'Assignments fetched successfully',
            'data' => $assignments
        ]);
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    echo json_encode([
        'status' => 500,
        'message' => 'Server error: ' . $e->getMessage(),
        'data' => []
    ]);
}
