<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Student Portal - Assignments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="../stylesheet/responsive.css" />
    <link rel="stylesheet" href="../stylesheet/styles.css" />
    <style>
        .course-card {
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .status-tag {
            border-radius: 12px;
            padding: 3px 10px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-graded {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .status-revision {
            background-color: #f8d7da;
            color: #842029;
        }

        .assignment-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgb(0 0 0 / 0.1);
            margin-bottom: 20px;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include('sidebar.php') ?>

            <div class="col-12 col-sm-10 col-md-9 col-lg-10 p-4">
                <?php include('topbar.php') ?>

                <!-- <h4 class="mb-4">Overall Assignments</h4> -->
                <div class="row pt-3">
                    <!-- Right Column: Submitted Assignments -->
                    <div class="col-lg-12">
                        <div class="course-card shadow p-4 mb-4">
                            <h4 class="mb-4">Overall Assignments</h4>


                            <!-- <div class="row g-4">
                                <div class="col-12 col-md-6">
                                    <div class="border-bottom pb-3 shadow p-3 rounded mb-3 h-100">
                                        <h6 class="mb-1">Essay: The Impact of AI on Society</h6>
                                        <div class="d-flex justify-content-between small">
                                            <span class="text-muted">Introduction to Artificial Intelligence</span>
                                            <span class="badge bg-success">Graded (A+)</span>
                                        </div>
                                        <div class="small text-muted mb-2">2023-10-26, 14:30 PM</div>
                                        <div class="bg-light p-2 rounded mb-2">
                                            <strong>Uploaded Files / Links</strong><br>
                                            <a href="#" class="text-primary">📄 AI_Impact_Essay_JohnDoe.pdf</a>
                                        </div>
                                        <div class="bg-light p-2 rounded">
                                            <strong>Instructor Feedback:</strong> Excellent analysis and well-structured
                                            arguments. Good use of examples.
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="border-bottom pb-3 shadow p-3 rounded mb-3 h-100">
                                        <h6 class="mb-1">Project Proposal: Sustainable Urban Farming</h6>
                                        <div class="d-flex justify-content-between small">
                                            <span class="text-muted">Environmental Science I</span>
                                            <span class="badge bg-warning text-dark">Revision Requested</span>
                                        </div>
                                        <div class="small text-muted mb-2">2023-11-01, 23:59 PM</div>
                                        <div class="bg-light p-2 rounded mb-2">
                                            <strong>Uploaded Files / Links</strong><br>
                                            <a href="#" class="text-primary">📄 Urban_Farming_Proposal.docx</a><br>
                                            <a href="#" class="text-primary">🔗 Research Link</a>
                                        </div>
                                        <div class="bg-light p-2 rounded">
                                            <strong>Instructor Feedback:</strong> The concept is strong, but refine the
                                            methodology section. Provide more specific details on resource management.
                                        </div>
                                    </div>
                                </div>

                            </div> -->
                            <div class="row g-4" id="assignment-container">
                                <p class="text-center">Loading assignments...</p>
                            </div>

                        </div>

                    </div>
                </div>
            </div>


        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            function fetchAssignments() {
                $.ajax({
                    url: 'api/overall_assignments.php', // your API endpoint
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        const container = $('#assignment-container');
                        container.empty();

                        if (!data.length) {
                            container.html('<p class="text-center">No assignments found.</p>');
                            return;
                        }

                        data.forEach(function (assignment) {
                            // Determine status badge
                            let statusClass = '';
                            let statusText = 'Pending';
                            if (assignment.status?.toLowerCase().includes('graded')) {
                                statusClass = 'bg-success';
                                statusText = 'Graded';
                            } else if (assignment.status?.toLowerCase().includes('revision')) {
                                statusClass = 'bg-warning text-dark';
                                statusText = 'Revision Requested';
                            } else if (assignment.status?.toLowerCase().includes('late')) {
                                statusClass = 'bg-danger';
                                statusText = 'Late Submission';
                            } else {
                                statusClass = 'bg-secondary text-white';
                                statusText = 'Pending';
                            }

                            // Uploaded files (if any)
                            let filesHtml = '';
                            if (assignment.notes) {
                                let files = [];
                                try {
                                    files = JSON.parse(assignment.notes);
                                } catch (e) {
                                    files = [assignment.notes];
                                }
                                filesHtml = `<div class="bg-light p-2 rounded mb-2"><strong>Uploaded Files / Links</strong><br>`;
                                files.forEach(f => {
                                    let icon = '📄';
                                    if (f.endsWith('.doc') || f.endsWith('.docx')) icon = '📝';
                                    else if (f.endsWith('.pdf')) icon = '📄';
                                    else if (f.startsWith('http')) icon = '🔗';

                                    // Extract filename only (remove path & timestamp)
                                    let parts = f.split('/');
                                    let filename = parts[parts.length - 1];
                                    let cleanName = filename.includes('_') ? filename.split('_').slice(1).join('_') : filename;

                                    filesHtml += `<a href="${f}" target="_blank" class="text-dark text-decoration-none">${icon} ${cleanName}</a><br>`;
                                });
                                filesHtml += `</div>`;
                            }

                            // Build card HTML with data attributes
                            const cardHtml = `
                        <div class="col-12 col-md-6">
                            <div class="border-bottom pb-3 shadow p-3 rounded mb-3 h-100 assignment-card" 
                                 data-cmid="${assignment.cm_id}" 
                                 data-launchcourseid="${assignment.launch_id}">
                                <h6 class="mb-1">${assignment.assignment_title}</h6>
                                <div class="d-flex justify-content-between small">
                                    <span class="text-muted">Course Name : ${assignment.course_name || ''}</span>
                                    <span class="badge ${statusClass}">${statusText}</span>
                                </div>
                                <div class="small text-muted mb-2">Due Date : ${assignment.due_date || ''}</div>
                                ${filesHtml}
                                <div class="bg-light p-2 rounded">
                                    <strong>Instructor Instruction:</strong> ${assignment.instruction || 'No Instruction Provided.'}
                                </div>
                            </div>
                        </div>
                    `;
                            container.append(cardHtml);
                        });
                    },
                    error: function (xhr, status, error) {
                        $('#assignment-container').html('<p class="text-danger text-center">Error loading assignments.</p>');
                        console.error(error);
                    }
                });
            }

            // Fetch assignments on page load
            fetchAssignments();

            // Handle card click using delegated event
            $(document).on('click', '.assignment-card', function () {
                const cId = $(this).data('cmid');
                const launchId = $(this).data('launchcourseid');

                // Redirect to view page with query params
                window.location.href = `assignments.php?cm_id=${cId}&launch_c=${launchId}`;
            });
        });
    </script>


</body>

</html>