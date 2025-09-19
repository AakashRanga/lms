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
                            <div class="course-card shadow p-4 mb-4">
                                <h5><strong>Submit Assignment</strong></h5>
                                <p class="text-muted">Upload your files and add comments for your submission.</p>

                                <div class="mb-3 p-4 border rounded text-center"
                                    style="border-style: dashed; cursor: pointer;">
                                    <input type="file" id="file-upload" multiple style="display:none;">
                                    <label for="file-upload" style="cursor: pointer;">
                                        <i class="bi bi-cloud-arrow-up" style="font-size: 2rem;"></i>
                                        <p>Drag and drop your files here, or click to browse</p>
                                        <small class="text-muted">Max file size: 10MB (PDF, DOCX, JPEG, PNG,
                                            etc.)</small>
                                    </label>
                                </div>

                                <!-- <div class="mb-3">
                                    <textarea class="form-control" rows="5"
                                        placeholder="Add any comments or descriptions for your assignment..."></textarea>
                                </div> -->

                                <div class="text-end">
                                    <button class="btn btn-primary">Submit Assignment</button>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Submitted Assignments -->
                        <div class="col-md-6">
                            <div class="course-card shadow p-4 mb-4">
                                <h5><strong>Submitted Assignments</strong></h5>
                                <p class="text-muted">View your past submissions and instructor feedback.</p>

                                <!-- Assignment 1 -->
                                <div class="border-bottom pb-3 shadow p-2 mb-3">
                                    <h6 class="mb-1">Essay: The Impact of AI on Society</h6>
                                    <div class="d-flex justify-content-between small">
                                        <span class="text-muted">Introduction to Artificial Intelligence</span>
                                        <span class="badge bg-success">Graded (A+)</span>
                                    </div>
                                    <div class="small text-muted mb-2">2023-10-26, 14:30 PM</div>
                                    <div class="bg-light p-2 rounded mb-1">
                                        <strong>Uploaded Files / Links</strong><br>
                                        <a href="#" class="text-primary">📄 AI_Impact_Essay_JohnDoe.pdf</a>
                                    </div>
                                    <div class="bg-light p-2 rounded">
                                        <strong>Instructor Feedback:</strong> Excellent analysis and well-structured
                                        arguments. Good use of examples.
                                    </div>
                                </div>
                                <br>
                                <!-- Assignment 2 -->
                                <div class="border-bottom shadow p-4 pb-3 mb-3">
                                    <h6 class="mb-1">Project Proposal: Sustainable Urban Farming</h6>
                                    <div class="d-flex justify-content-between small">
                                        <span class="text-muted">Environmental Science I</span>
                                        <span class="badge bg-warning text-dark">Revision Requested</span>
                                    </div>
                                    <div class="small text-muted mb-2">2023-11-01, 23:59 PM</div>
                                    <div class="bg-light p-2 rounded mb-1">
                                        <strong>Uploaded Files / Links</strong><br>
                                        <a href="#" class="text-primary">📄 Urban_Farming_Proposal.docx</a><br>
                                        <a href="#" class="text-primary">🔗 Research Link</a>
                                    </div>
                                    <div class="bg-light p-2 rounded">
                                        <strong>Instructor Feedback:</strong> The concept is strong, but refine the
                                        methodology section. Provide more specific details on resource management.
                                    </div>
                                </div>

                                <!-- Add more assignments as needed -->
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>