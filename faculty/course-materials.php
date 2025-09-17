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
        }

        .btn-small {
            height: 35px;
            line-height: 35px;
            padding: 0 20px;
            font-size: 0.9rem;
            border-radius: 0;
        }

        .question-index {
            gap: 13px;
        }

        .badge .bg-primary {
            padding: 10px !important;
            width: 120px !important;
        }

        li a {
            text-decoration: none !important;
        }
    </style>
</head>

<body>
    <div class="container-fluid students-section">
        <div class="row">
            <!-- Sidebar -->
            <?php include('sidebar.php') ?>

            <!-- Main Content -->
            <div class="col-12 col-sm-10 col-md-10 col-lg-10 p-0">
                <!-- Topbar -->
                <?php include('topbar.php') ?>

                <!-- Page Content -->
                <div class="p-4 content-scroll">
                    <?php
                    $pageTitles = [
                        "dashboard.php" => "Dashboard",
                        "course-admin.php" => "Course Admin",
                        "add-course.php" => "Add Course"
                    ];

                    $currentPage = basename($_SERVER['PHP_SELF']); // e.g. add-course.php
                    ?>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="active-course.php">Active Course</a></li>
                            <li class="breadcrumb-item"><a href="course-details.php">Course Details</a></li>

                            <li class="breadcrumb-item active" aria-current="page">
                                <?= $pageTitles[$currentPage] ?? ucfirst(pathinfo($currentPage, PATHINFO_FILENAME)) ?>
                            </li>
                        </ol>
                    </nav>
                    <div class="card-custom mt-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5>Upload Course Material</h5>

                            <div class="d-flex align-items-center justify-content-between mb-4 gap-2">
                                <button type="button" class="btn btn-secondary btn-sm" id="addModuleBtn">
                                    Add Another Module
                                </button>

                            </div>
                        </div>

                        <form action="upload-material.php" method="POST" enctype="multipart/form-data" class="row g-3">
                            <!-- Course Name -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="courseName" name="course_name"
                                        placeholder="Course Name" required>
                                    <label for="courseName">Course Name</label>
                                </div>
                            </div>

                            <!-- Module/Unit -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="unit" name="unit"
                                        placeholder="Module/Unit" required>
                                    <label for="unit">Module / Unit</label>
                                </div>
                            </div>

                            <!-- Dynamic Modules Container -->
                            <div id="modulesContainer" class="col-12">
                                <!-- First Module Block -->
                                <div class="module-block border rounded p-3 mb-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" name="chapter_number[]"
                                                    placeholder="Chapter Number" required>
                                                <label>Chapter Number</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" name="chapter_title[]"
                                                    placeholder="Chapter Title" required>
                                                <label>Chapter Title</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Course Material (Only PDF)</label>
                                            <input type="file" class="form-control" name="reading_material[]"
                                                accept=".pdf">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Flipped Class</label>
                                            <input type="file" class="form-control" name="video_material[]"
                                                accept="video/*">
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5>Practice Questions</h5>
                                            <span class="badge bg-primary"
                                                style="padding: 10px !important;width: 120px !important;font-size: 17px;font-weight: 400;">Total:
                                                <span class="question-count">0</span></span>
                                        </div>
                                        <div class="questions-container"></div>
                                        <div class="col-12 text-center mt-3 d-flex justify-content-start gap-2">
                                            <!-- Add Question Button -->
                                            <button type="button" class="btn btn-primary btn-sm mt-2 addQuestionBtn">
                                                + Add 10 Questions
                                            </button>

                                            <!-- CO Level Select -->
                                            <select class="form-select form-select-sm mt-2" style="width: auto;" id="coLevelSelect">
                                                <option selected disabled>Select CO Level</option>
                                                <option value="CO1">CO1</option>
                                                <option value="CO2">CO2</option>
                                                <option value="CO3">CO3</option>
                                                <option value="CO4">CO4</option>
                                                <option value="CO5">CO5</option>
                                                <option value="CO6">CO6</option>
                                            </select>
                                        </div>




                                    </div>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="col-12 text-center mt-3 d-flex justify-content-center gap-2">
                                <button type="submit" class="btn btn-secondary btn-small">Submit Modules</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JS Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modulesContainer = document.getElementById('modulesContainer');
            const addModuleBtn = document.getElementById('addModuleBtn');

            function createQuestionBlock(num) {
                const block = document.createElement('div');
                block.classList.add('question-block', 'border', 'p-3', 'mt-3', 'rounded');
                block.innerHTML = `
                <div class="d-flex justify-content-end mb-2">
                    <button type="button" class="btn btn-danger btn-sm removeQuestionBtn">Remove</button>
                </div>
                <div class="d-flex justify-content-between align-items-center question-index mb-2">
                    <h6 class="mb-0">Q${num}</h6>
                    <input type="text" class="form-control" name="question_text[]" placeholder="Enter Question ${num}" required>
                </div>
                <div class="row g-2">
                    ${['A', 'B', 'C', 'D'].map(opt => `
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check me-2">
                                <input class="form-check-input" type="radio" name="correct_answer_${num}" value="${opt}" required>
                            </div>
                            <input type="text" class="form-control" name="option_${opt.toLowerCase()}[]" placeholder="Option ${opt}" required>
                        </div>
                    `).join('')}
                </div>
            `;
                return block;
            }

            function attachQuestionLogic(moduleBlock) {
                const questionsContainer = moduleBlock.querySelector('.questions-container');
                const addQuestionBtn = moduleBlock.querySelector('.addQuestionBtn');
                const questionCount = moduleBlock.querySelector('.question-count');

                function updateQuestionNumbers() {
                    const blocks = questionsContainer.querySelectorAll('.question-block');
                    blocks.forEach((block, idx) => {
                        const num = idx + 1;
                        block.querySelector('h6').textContent = `Q${num}`;
                        block.querySelector('input[name="question_text[]"]').setAttribute('placeholder', `Enter Question ${num}`);
                        const radios = block.querySelectorAll('.form-check-input');
                        radios.forEach(r => r.setAttribute('name', `correct_answer_${num}`));
                    });
                    questionCount.textContent = blocks.length; // update count
                }

                addQuestionBtn.addEventListener('click', () => {
                    for (let i = 1; i <= 10; i++) {
                        const currentIndex = questionsContainer.querySelectorAll('.question-block').length + 1;
                        const block = createQuestionBlock(currentIndex);
                        questionsContainer.appendChild(block);
                        block.querySelector('.removeQuestionBtn').addEventListener('click', () => {
                            block.remove();
                            updateQuestionNumbers();
                        });
                    }
                    updateQuestionNumbers();
                });
            }

            // Attach logic to existing modules
            document.querySelectorAll('.module-block').forEach(attachQuestionLogic);

            // Add new module
            addModuleBtn.addEventListener('click', () => {
                const moduleBlock = document.createElement('div');
                moduleBlock.classList.add('module-block', 'border', 'rounded', 'p-3', 'mb-3');
                moduleBlock.innerHTML = `
                <div class="row g-3">
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-danger btn-sm removeModuleBtn">Remove</button>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="chapter_number[]" placeholder="Chapter Number" required>
                            <label>Chapter Number</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="chapter_title[]" placeholder="Chapter Title" required>
                            <label>Chapter Title</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Reading Material (PDF)</label>
                        <input type="file" class="form-control" name="reading_material[]" accept=".pdf">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Video Material</label>
                        <input type="file" class="form-control" name="video_material[]" accept="video/*">
                    </div>
                    <div class="col-12">
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Practice Questions</h5>
                            <span class="badge bg-primary">Total: <span class="question-count">0</span></span>
                        </div>
                        <div class="questions-container"></div>
                        <button type="button" class="btn btn-secondary btn-sm mt-2 addQuestionBtn"> + Add 10 Questions</button>
                    </div>
                </div>
            `;
                modulesContainer.appendChild(moduleBlock);
                moduleBlock.querySelector('.removeModuleBtn').addEventListener('click', () => {
                    moduleBlock.remove();
                });
                attachQuestionLogic(moduleBlock);
            });
        });
    </script>




</body>

</html>