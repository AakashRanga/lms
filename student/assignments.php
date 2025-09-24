<?php
session_start();
$c_id = $_GET['cm_id'] ?? null;
$launch_id = $_GET['launch_c'] ?? null;
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
            /* border-radius: 12px; */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            height: 100%;
            max-height: 400px;
            overflow-y: scroll;
        }

        .document-list {
            display: none;
            margin-top: 15px;
            padding-left: 0;
            list-style: none;
        }

        .document-list li {
            margin-bottom: 8px;
            list-style-type: none;
        }

        .document-list a {
            text-decoration: none;
            color: #000;
        }

        .document-list a:hover {
            color: #0d6efd;
        }

        table tr th:nth-child(1),
        table tr td:nth-child(1) {
            width: 60px;
            text-align: center;
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
                        <div class="col-md-6 shadow p-2 rounded">
                            <form id="submit_assignment" method="POST" enctype="multipart/form-data" class="p-1">
                                <input type="hidden" name="c_id" id="c_id">
                                <input type="hidden" name="launch_id" id="launch_id">
                                <input type="hidden" name="title" id="title">

                                <!-- Assignment Dropdown -->
                                <div class="mb-3">
                                    <label for="assignmentSelect" class="form-label">Select Assignment</label>
                                    <select class="form-control" id="assignmentSelect" name="assignment_id" required>
                                        <option value="" selected disabled>Select Assignment</option>
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
                            <div class="mt-5 shadow p-4 ">
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
                                            <th>Marks</th>
                                            <!-- <th>Course ID</th>
                                            <th>Launch Id</th> -->
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
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

    <!-- JS Dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- File Upload Preview -->
    <script>
        const fileUpload = document.getElementById('file-upload');
        const label = fileUpload.nextElementSibling;

        fileUpload.addEventListener("change", function () {
            const fileList = document.getElementById("file-list");
            fileList.innerHTML = "";
            for (let i = 0; i < this.files.length; i++) {
                fileList.innerHTML += `<div>📄 ${this.files[i].name}</div>`;
            }
        });

        label.addEventListener('dragover', (e) => { e.preventDefault(); label.classList.add('bg-light'); });
        label.addEventListener('dragleave', () => { label.classList.remove('bg-light'); });
        label.addEventListener('drop', (e) => {
            e.preventDefault(); label.classList.remove('bg-light'); fileUpload.files = e.dataTransfer.files;
        });
    </script>

    <!-- Load Assignments Dynamically -->
    <script>
        $(document).ready(function () {
            const urlParams = new URLSearchParams(window.location.search);
            const cm_id = urlParams.get('cm_id');
            const launch_c = urlParams.get('launch_c');

            if (!cm_id || !launch_c) return;

            // Fetch Assignments
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
                                data-launchid="${assignment.launch_id}" 
                                data-title="${assignment.title}">
                                ${assignment.title}
                            </option>`);
                        });
                    } else {
                        $('#assignmentSelect').append('<option value="">No assignments available</option>');
                    }
                }
            });

            $('#assignmentSelect').on('change', function () {
                const selected = $(this).find(':selected');
                $('#c_id').val(selected.data('cid'));
                $('#launch_id').val(selected.data('launchid'));
                $('#title').val(selected.data('title'));
            });

            // Load Submissions Table
            function loadSubmissions(courseId, launchId) {
                $.ajax({
                    url: `api/fetch_submissions.php?cm_id=${courseId}&launch_c=${launchId}`,
                    type: "GET",
                    dataType: "json",
                    success: function (res) {
                        let tbody = $("#submittedTable tbody"); tbody.empty();
                        if (res.status === 200 && res.data.length > 0) {
                            res.data.forEach((item, index) => {
                                let files = "";
                                if (item.notes) {
                                    item.notes.split(",").forEach(f => {
                                        let fileName = f.split("/").pop();
                                        let cleanName = fileName.split("_").slice(1).join("_");
                                        files += `<a href="${f}" target="_blank">${cleanName}</a><br>`;
                                    });
                                }
                                tbody.append(`<tr>
                                    <td>${index + 1}</td>
                                    <td>${item.course_name}</td>
                                    <td>${item.title}</td>
                                    <td>${item.instruction || '-'}</td>
                                    <td>${files || '-'}</td>
                                    <td>${item.submission_date}</td>
                                    <td>${item.marks}</td>
                                    <td class="d-none">${item.c_id}</td>
                                    <td class="d-none">${item.launch_id}</td>
                                </tr>`);
                            });
                        } else {
                            tbody.append('<tr><td colspan="8" class="text-center">No submissions for this course/launch</td></tr>');
                        }
                    },
                    error: function () { alert("Failed to load submissions."); }
                });
            }

            // Initial load
            loadSubmissions(cm_id, launch_c);
        });
    </script>

    <!-- Submit Assignment -->
    <script>
        $(document).ready(function () {

            // Load assignments dynamically into the dropdown
            const launch_c_id = new URLSearchParams(window.location.search).get('launch_c_id');

            function loadAssignments() {
                if (!launch_c_id) return;

                $.ajax({
                    url: 'api/fetch_assignments_faculty_evaluvation.php',
                    type: 'GET',
                    data: { launch_c_id: launch_c_id },
                    dataType: 'json',
                    success: function (response) {
                        let select = $("#assignmentSelect");
                        select.empty().append('<option value="" selected disabled>Select Assignment</option>');

                        if (response.status === 200 && response.data.length > 0) {
                            response.data.forEach(a => {
                                select.append(`<option value="${a.ass_id}" 
                            data-title="${a.title}" 
                            data-cid="${a.c_id}" 
                            data-launchid="${a.launch_id}">${a.title}</option>`);
                            });
                        }
                    },
                    error: function (err) { console.error(err); }
                });
            }

            loadAssignments();

            // Update hidden fields when assignment is selected
            $("#assignmentSelect").on("change", function () {
                const selected = $(this).find('option:selected');
                $("#title").val(selected.data('title'));
                $("#c_id").val(selected.data('cid'));
                $("#launch_id").val(selected.data('launchid'));
            });

            // Show selected files
            $("#file-upload").on("change", function () {
                let fileList = $("#file-list");
                fileList.empty();
                let files = this.files;
                for (let i = 0; i < files.length; i++) {
                    fileList.append("<div>" + files[i].name + "</div>");
                }
            });

            // Submit assignment via AJAX
            $("#submit_assignment").on("submit", function (e) {
                e.preventDefault();
                let formData = new FormData(this);

                // Append multiple files manually if needed
                let files = $("#file-upload")[0].files;
                for (let i = 0; i < files.length; i++) {
                    formData.append("files[]", files[i]);
                }

                $.ajax({
                    url: "api/submit_assignment.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        let res = typeof response === 'string' ? JSON.parse(response) : response;
                        if (res.status === 200) {
                            alert("✅ Assignment submitted successfully!");
                            $("#submit_assignment")[0].reset();
                            $("#file-list").empty();
                            loadAssignments(); // Optional: reload assignments
                        } else {
                            alert("⚠️ " + res.message);
                        }
                    },
                    error: function () { alert("❌ Error submitting assignment."); }
                });
            });
        });
    </script>


    <!-- PDF Modal -->
    <script>
        $(document).on('click', '.pdf-btn', function () {
            const pdfPath = $(this).data('pdf');
            $('#pdfFrame').attr('src', pdfPath);
            const pdfModal = new bootstrap.Modal(document.getElementById('pdfModal'));
            pdfModal.show();
        });
    </script>

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
                        let html = '<div class="course-card shadow rounded p-4 ">';
                        html += '<h5><strong>Assignment Updates</strong></h5>';

                        res.data.forEach(sub => {
                            html += '<div class="border-bottom pb-3 shadow p-4 mb-3">';
                            html += `<div class="d-flex gap-2 align-items-center justify-content-between">
                                <h6 class="mb-1">${sub.title}</h6>`;

                            if (sub.obtained_marks || sub.grade) {
                                let displayValue = sub.grade ? sub.grade : sub.obtained_marks;
                                html += `<div class="bg-light p-2 rounded"><strong>Grade:</strong> ${displayValue}</div>`;
                            } else {
                                html += `<div class="bg-light p-2 rounded"><strong>Grade:</strong> Not Graded.</div>`;
                            }

                            html += `</div>`; // close d-flex div


                            html += `<div class="small text-muted mb-2">Assigned Date : ${sub.submission_date}</div>`;
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
                            html += `<div class="bg-light p-2 rounded"><strong>Instructor Instruction:</strong> ${sub.instruction || 'No instruction yet.'}</div>`;


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
</body>

</html>