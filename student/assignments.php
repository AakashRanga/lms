<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - Submitted Assignments</title>
    <link rel="icon" type="image/png" href="../images/logo1.png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../stylesheet/responsive.css">
    <link rel="stylesheet" href="../stylesheet/styles.css">


    <style>
        .course-card {
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .document-list {
            display: none;
            margin-top: 15px;
            padding-left: 0;
            /* Remove default UL padding */
            list-style: none;
            /* Remove bullets */
        }

        .document-list li {
            margin-bottom: 8px;
            list-style-type: none;
        }

        .document-list a {
            text-decoration: none;
            /* Remove underline */
            color: #000;
            /* Black text color */
        }

        .document-list a:hover {
            color: #0d6efd;
            /* Optional: Bootstrap primary color on hover */
        }

        table tr th:nth-child(1),
        table tr td:nth-child(1) {
            width: 60px;
            text-align: center;
            /* optional for better look */
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <?php include('sidebar.php') ?>

            <!-- Main Content -->
            <div class="col-12 col-sm-10 col-md-10 col-lg-10 p-0">

                <!-- Topbar -->
                <?php include('topbar.php') ?>

                <!-- Page Content -->
                <div class="p-4">

                    <div class="row">
                        <!-- Left Column: Submit Assignment -->
                        <div class="col-md-6">
                            <form id="submit_assignment" method="POST" enctype="multipart/form-data" class="p-3">

                                <input type="hidden" name="c_id" id="c_id">
                                <input type="hidden" name="launch_id" id="launch_id">
                                <input type="hidden" name="title" id="title">

                                <!-- Assignment Dropdown -->
                                <div class="mb-3">
                                    <label for="assignmentSelect" class="form-label">Select Assignment</label>
                                    <select class="form-control" id="assignmentSelect" name="assignment_id" required>
                                        <option value="" selected disabled>Select Assignment</option>
                                        <!-- Assignment options will be loaded dynamically -->
                                    </select>
                                </div>

                                <!-- File Upload -->
                                <div class="mb-3 p-4 border rounded text-center bg-light"
                                    style="border-style: dashed; cursor: pointer;">
                                    <input type="file" name="files[]" multiple class="d-none" id="file-upload">
                                    <label for="file-upload" style="cursor:pointer;">
                                        <i class="bi bi-cloud-arrow-up" style="font-size:2rem;"></i>
                                        <p class="mb-0">Drag and drop your files here, or click to browse</p>
                                    </label>
                                    <div id="file-list" class="mt-2 text-muted small"></div>
                                </div>

                                <!-- Comments -->
                                <div class="mb-3">
                                    <label for="comments" class="form-label">Comments</label>
                                    <textarea name="comments" id="comments" class="form-control" rows="2"
                                        placeholder="Add comments..."></textarea>
                                </div>

                                <!-- Submit Button -->
                                <div class="text-end">
                                    <button type="submit" class="btn btn-secondary">Submit Assignment</button>
                                </div>
                            </form>


                        </div>

                        <!-- Right Column: Submitted Assignments -->
                        <div id="submittedAssignments" class="col-md-6"></div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Submitted Assignments Table -->
                            <div class="mt-5 shadow p-4">
                                <h5>Your Submitted Assignments</h5>
                                <table class="table table-bordered table-striped" id="submittedTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>S No</th>
                                            <th>Course</th>
                                            <th>Assignment</th>
                                            <th>Instruction</th>
                                            <th>Files</th>
                                            <th>Submitted On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will load here dynamically -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PDF Viewer Modal -->
                <div class="modal fade" id="pdfModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">PDF Viewer</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <iframe id="pdfFrame" width="100%" height="600px" style="border:none;"></iframe>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Show selected files dynamically -->
    <script>
        document.getElementById("file-upload").addEventListener("change", function () {
            const fileList = document.getElementById("file-list");
            fileList.innerHTML = "";
            for (let i = 0; i < this.files.length; i++) {
                fileList.innerHTML += `<div>📄 ${this.files[i].name}</div>`;
            }
        });
    </script>
    <script>
        const fileUpload = document.getElementById('file-upload');
        const label = fileUpload.nextElementSibling;

        label.addEventListener('dragover', (e) => {
            e.preventDefault();
            label.classList.add('bg-light');
        });

        label.addEventListener('dragleave', () => {
            label.classList.remove('bg-light');
        });

        label.addEventListener('drop', (e) => {
            e.preventDefault();
            label.classList.remove('bg-light');
            fileUpload.files = e.dataTransfer.files;
        });

    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <span class="badge ${sub.marks ? 'bg-success' : 'bg-warning text-dark'}">
                                    ${sub.marks ? 'Graded (' + sub.marks + ')' : 'Revision Requested'}
                                </span> -->
    <script>
        $(document).ready(function () {
            const urlParams = new URLSearchParams(window.location.search);
            const cm_id = urlParams.get('cm_id');
            const launch_c = urlParams.get('launch_c');

            if (!cm_id || !launch_c) {
                $('#submittedAssignments').html('<p class="text-danger">Invalid course material or launch ID.</p>');
                return;
            }

            $.ajax({
                url: 'api/fetch_submitted_assignments.php',
                method: 'GET',
                data: { cm_id: cm_id, launch_c: launch_c },
                dataType: 'json',
                success: function (res) {
                    if (res.status === 200 && res.data.length > 0) {
                        let html = '<div class="course-card shadow p-4 mb-4">';
                        html += '<h5><strong>Assignment Updates</strong></h5>';

                        res.data.forEach(sub => {
                            html += '<div class="border-bottom pb-3 shadow p-4 mb-3">';
                            html += `<h6 class="mb-1">${sub.title}</h6>`;

                            html += `<div class="small text-muted mb-2">Assigned Date : ${sub.submitted_at}</div>`;
                            html += `<div class="small text-muted mb-2">Dues Date : ${sub.due_date}</div>`;

                            // Uploaded files
                            html += '<div class="bg-light p-2 rounded mb-1"><strong>Uploaded Files</strong><br>';
                            if (sub.uploaded_files) {
                                sub.uploaded_files.split(',').forEach(file => {
                                    const filePath = file.trim(); // full path from your DB
                                    const fileName = filePath.split('/').pop(); // get filename

                                    if (filePath.endsWith('.pdf')) {
                                        const cleanName = fileName.split('_').slice(1).join('_'); // remove prefix

                                        // Mobile view: simple link
                                        html += `
                                            <div class="d-block d-md-none mb-2">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="bi bi-file-earmark-pdf-fill me-2 fs-4 text-danger"></i>
                                                     <a href="../faculty/${filePath}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                                    ${cleanName}
                                                </a>
                                                </div>
                                               
                                            </div>
                                            `;

                                        // Desktop view: button with modal
                                        html += `
                                            <div class="d-none d-md-block mb-2">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="bi bi-file-earmark-pdf-fill me-2 fs-4 text-danger"></i>
                                                     <button class="btn btn-outline-secondary btn-sm pdf-btn" data-pdf="../faculty/${filePath}">
                                                    ${cleanName}
                                                </button>
                                                </div>
                                               
                                            </div>
                                        `;

                                    } else {
                                        // Non-PDF files: just display the filename
                                        html += `📄 ${fileName}<br>`;
                                    }
                                });
                            }

                            html += '</div>';


                            // Feedback
                            html += `<div class="bg-light p-2 rounded"><strong>Instructor Feedback:</strong> ${sub.feedback || 'No feedback yet.'}</div>`;
                            html += '</div>';
                        });

                        html += '</div>';
                        $('#submittedAssignments').html(html);
                    } else {
                        $('#submittedAssignments').html('<p class="text-center">No submitted assignments found.</p>');
                    }
                },
                error: function () {
                    $('#submittedAssignments').html('<p class="text-danger text-center">Failed to load submissions.</p>');
                }
            });
        });
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('pdf-btn')) {
                e.preventDefault(); // prevent default link behavior
                const pdfPath = e.target.dataset.pdf;
                document.getElementById('pdfFrame').src = pdfPath;
                var pdfModal = new bootstrap.Modal(document.getElementById('pdfModal'));
                pdfModal.show();
            }
        });

    </script>
    <!-- fecth assignments -->
    <script>
        $(document).ready(function () {
            const urlParams = new URLSearchParams(window.location.search);
            const cm_id = urlParams.get('cm_id');
            const launch_c = urlParams.get('launch_c');

            if (!cm_id || !launch_c) {
                $('#assignmentSelect').append('<option value="">Invalid course or launch</option>');
                return;
            }

            $.ajax({
                url: 'api/fetch_assignments.php',
                method: 'GET',
                data: { cm_id: cm_id, launch_c: launch_c },
                dataType: 'json',
                success: function (res) {
                    if (res.status === 200 && res.data.length > 0) {
                        res.data.forEach(function (assignment) {
                            $('#assignmentSelect').append(`<option 
                    value="${assignment.ass_id}" 
                    data-cid="${assignment.c_id}" 
                    data-launchid="${assignment.launch_id}" data-title="${assignment.title}">
                    ${assignment.title}
                </option>`);
                        });
                    } else {
                        $('#assignmentSelect').append('<option value="">No assignments available</option>');
                    }
                },
                error: function () {
                    $('#assignmentSelect').append('<option value="">Failed to load assignments</option>');
                }
            });
            $('#assignmentSelect').on('change', function () {
                const selectedOption = $(this).find(':selected');
                $('#c_id').val(selectedOption.data('cid'));
                $('#launch_id').val(selectedOption.data('launchid'));
                $('#title').val(selectedOption.data('title')); // ✅ save assignment title

            });

        });
    </script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- submit assignment -->
    <script>
        // Load submissions on page load
        $(document).ready(function () {
            loadSubmissions();

            // Reload after form submission
            $("#submit_assignment").on("submit", function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "api/submit_assignment.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        try {
                            let res = JSON.parse(response);
                            if (res.status === 200) {
                                alert("✅ Assignment submitted successfully!");
                                $("#submit_assignment")[0].reset();
                                loadSubmissions(); // 🔄 refresh table
                            } else {
                                alert("⚠️ " + res.message);
                            }
                        } catch (err) {
                            alert("Unexpected response: " + response);
                        }
                    },
                    error: function () {
                        alert("❌ Error submitting assignment.");
                    }
                });
            });
        });
    </script>

    <!-- student submission fecth -->
    <script>
        function loadSubmissions() {
            $.ajax({
                url: "api/fetch_submissions.php",
                type: "GET",
                dataType: "json",
                success: function (res) {
                    let tbody = $("#submittedTable tbody");
                    tbody.empty();

                    if (res.status === 200 && res.data.length > 0) {
                        res.data.forEach((item, index) => {
                            // Handle uploaded files (multiple possible)
                            let files = "";
                            if (item.notes) {
                                item.notes.split(",").forEach(f => {
                                    let fileName = f.split("/").pop(); // get filename
                                    let cleanName = fileName.split("_").slice(1).join("_"); // remove timestamp prefix
                                    files += `<a href="${f}" target="_blank">${cleanName}</a><br>`;
                                });
                            }


                            tbody.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.course_name}</td>
                            <td>${item.title}</td>
                            <td>${item.instruction || '-'}</td>
                            <td>${files || '-'}</td>
                            <td>${item.submission_date}</td>
                        </tr>
                    `);
                        });
                    } else {
                        tbody.append('<tr><td colspan="7" class="text-center">No submissions yet</td></tr>');
                    }
                },
                error: function () {
                    alert("Failed to load submissions.");
                }
            });
        }
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>