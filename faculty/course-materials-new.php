<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Materials</title>
    <link rel="icon" type="image/png" href="../images/logo1.png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="../stylesheet/responsive.css">
    <link rel="stylesheet" href="../stylesheet/styles.css">

    <style>
        .card-custom {
            background-color: #fff;
            border-radius: 12px;
            padding: 2rem;
            border: 1px solid #e5e7eb;
        }

        .btn-small {
            height: 35px;
            line-height: 35px;
            padding: 0 20px;
            font-size: 0.9rem;
            border-radius: 0;
        }

        .btn-submit {
            background-color: #45B6AF;
            color: #fff;
            border: none;
        }

        .btn-submit:hover {
            background-color: #3ca19a;
        }

        .btn-clear {
            background-color: #9FA6B2;
            color: #fff;
            border: none;
        }

        .btn-clear:hover {
            background-color: #8c929e;
        }

        .block-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }

        .block-card:hover {
            border-color: #cbd5e1;
        }

        .remove-btn {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container-fluid students-section">
        <div class="row">
            <!-- Sidebar -->
            <?php include('sidebar.php') ?>

            <!-- Main Content -->
            <div class="col-12 col-sm-10 col-md-9 col-lg-10 p-0">
                <!-- Topbar -->
                <?php include('topbar.php') ?>

                <!-- Page Content -->
                <div class="container py-4">
                    <h2 class="mb-4 fw-semibold">Create Course Material</h2>

                    <!-- BUTTONS -->
                    <div class="d-flex justify-content-between mt-4 gap-3">
                        <button class="btn btn-sm btn-outline-primary flex-fill" id="addTitleBtn">
                            <i class="bi bi-plus"></i> Add Title
                        </button>
                        <button class="btn btn-sm btn-outline-primary flex-fill" id="addDescriptionBtn">
                            <i class="bi bi-plus"></i> Add Description
                        </button>
                        <button class="btn btn-outline-primary flex-fill" id="addModuleBtn">
                            <i class="bi bi-plus"></i> Add Module
                        </button>
                        <button class="btn btn-outline-success flex-fill" id="addAssignmentBtn">
                            <i class="bi bi-plus"></i> Add Assignment
                        </button>
                    </div>

                    <!-- DYNAMIC AREAS -->
                    <div id="titileArea" class="d-flex flex-column gap-3 mb-5"></div>
                    <div id="titileDescription" class="d-flex flex-column gap-3 mb-5"></div>
                    <div id="modulesArea" class="d-flex flex-column gap-3 mb-5"></div>
                    <div id="assignmentsArea" class="d-flex flex-column gap-3 mb-5"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const titleArea = document.getElementById('titileArea');
        const descriptionArea = document.getElementById('titileDescription');
        const addTitleBtn = document.getElementById('addTitleBtn');
        const addDescriptionBtn = document.getElementById('addDescriptionBtn');

        const modulesArea = document.getElementById('modulesArea');
        const addModuleBtn = document.getElementById('addModuleBtn');

        const assignmentsArea = document.getElementById('assignmentsArea');
        const addAssignmentBtn = document.getElementById('addAssignmentBtn');

        // Add dynamic title
        addTitleBtn.addEventListener('click', () => {
            const titleBlock = document.createElement('div');
            titleBlock.classList.add('card-custom');
            titleBlock.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Title</h6>
                <i class="bi bi-x-lg remove-btn text-danger" title="Remove Title"></i>
            </div>
            <input type="text" class="form-control" placeholder="Enter title">
        `;
            titleArea.appendChild(titleBlock);
        });

        // Add dynamic description
        addDescriptionBtn.addEventListener('click', () => {
            const descBlock = document.createElement('div');
            descBlock.classList.add('card-custom');
            descBlock.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Description</h6>
                <i class="bi bi-x-lg remove-btn text-danger" title="Remove Description"></i>
            </div>
            <textarea class="form-control" rows="2" placeholder="Enter description"></textarea>
        `;
            descriptionArea.appendChild(descBlock);
        });

        // Add module block
        addModuleBtn.addEventListener('click', () => {
            const moduleBlock = document.createElement('div');
            moduleBlock.classList.add('card-custom');
            moduleBlock.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Module</h5>
                <i class="bi bi-x-lg remove-btn text-danger" title="Remove Module"></i>
            </div>
            <div class="mb-3">
                <label class="form-label">Module Title</label>
                <input type="text" class="form-control" placeholder="Enter module title">
            </div>
            <div class="mb-3">
                <label class="form-label">Module Description</label>
                <textarea class="form-control" rows="2" placeholder="Enter description"></textarea>
            </div>

            <!-- Materials -->
            <div class="materials-section">
                <h6>Materials</h6>
                <div class="materials-list d-flex flex-column gap-2"></div>
                <button class="btn btn-sm btn-outline-success mt-2 add-material">
                    <i class="bi bi-plus"></i> Add Material
                </button>
            </div>
        `;
            modulesArea.appendChild(moduleBlock);
        });

        // Add assignment block
        addAssignmentBtn.addEventListener('click', () => {
            const assignmentBlock = document.createElement('div');
            assignmentBlock.classList.add('card-custom');
            assignmentBlock.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Assignment</h5>
                <i class="bi bi-x-lg remove-btn text-danger" title="Remove Assignment"></i>
            </div>
            <div class="mb-3">
                <label class="form-label">Assignment Title</label>
                <input type="text" class="form-control" placeholder="Enter assignment title">
            </div>
            <div class="mb-3">
                <label class="form-label">Due Date</label>
                <input type="date" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Upload PDF</label>
                <input type="file" class="form-control">
            </div>
        `;
            assignmentsArea.appendChild(assignmentBlock);
        });

        // Event delegation
        document.body.addEventListener('click', (e) => {
            // ✅ Remove material (module-card) only
            if (e.target.classList.contains('remove-btn') && e.target.closest('.module-card')) {
                e.target.closest('.module-card').remove();
                return; // stop here so it doesn't also remove module card
            }

            // ✅ Remove whole title/description/module/assignment block
            if (e.target.classList.contains('remove-btn') && e.target.closest('.card-custom')) {
                e.target.closest('.card-custom').remove();
                return;
            }

            // ✅ Add material inside a module
            if (e.target.classList.contains('add-material')) {
                const list = e.target.closest('.materials-section').querySelector('.materials-list');
                const item = document.createElement('div');
                item.classList.add('module-card', 'p-3', 'border', 'rounded');
                item.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Material</h6>
                    <i class="bi bi-x-lg remove-btn text-danger" title="Remove Material" style="cursor:pointer;"></i>
                </div>

                <div class="mb-2">
                    <label class="form-label">Material Title</label>
                    <input type="text" class="form-control" placeholder="Enter material title">
                </div>

                <div class="mb-2">
                    <label class="form-label">Material Description</label>
                    <textarea class="form-control" rows="2" placeholder="Enter material description"></textarea>
                </div>

                <div class="mb-2">
                    <label class="form-label">Material Type</label>
                    <select class="form-select">
                        <option value="">Select Type</option>
                        <option value="pdf">PDF</option>
                        <option value="video">Video</option>
                        <option value="word">Word File</option>
                    </select>
                </div>

                <div class="mb-2">
                    <label class="form-label">Upload File</label>
                    <input type="file" class="form-control">
                </div>
            `;
                list.appendChild(item);
            }
        });
    </script>

</body>

</html>