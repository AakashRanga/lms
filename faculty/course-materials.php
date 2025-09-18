<?php
session_start();
?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_name = $_POST['course_name'] ?? '';
    $course_code = $_POST['course_code'] ?? '';
    $course_id = $_POST['course_id'] ?? '';
    $faculty_id = $_POST['faculty_id'] ?? '';
    $launch_c_id = $_POST['launch_c_id'] ?? '';

    
}
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
                            <li class="breadcrumb-item"><a href="course-details.php">Course Details </a></li>

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
                                        value="<?php echo $course_name; ?>" placeholder="Course Name" readonly>
                                    <label for="courseName">Course Name</label>
                                </div>
                            </div>

                            <!-- Module/Unit -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="courseCode" name="course_code"
                                        value="<?php echo $course_code; ?>" placeholder="Course Code" readonly>
                                    <label for="unit">Course Code </label>

                                </div>
                                <input type="hidden" class="form-control" id="course_id" name="c_id"
                                    value="<?php echo $course_id; ?>" placeholder="Course ID" readonly>
                                <input type="hidden" class="form-control" id="launch_c_id" name="launch_c_id"
                                    value="<?php echo $launch_c_id; ?>" placeholder="Launch Course Code" readonly>
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
                                            <select class="form-select form-select-sm mt-2" style="width: auto;"
                                                id="coLevelSelect">
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

    <!-- JS Script -->
    <!-- <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modulesContainer = document.getElementById('modulesContainer');
            const addModuleBtn = document.getElementById('addModuleBtn');

            function createQuestionBlock(num) {
                const block = document.createElement('div');
                block.classList.add('question-block', 'border', 'p-3', 'mt-3', 'rounded');
                block.innerHTML = `
                <div class="d-flex justify-content-end mb-2">
                    
                    <i class="bi bi-x-circle removeQuestionBtn" style="color:red;"></i>
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
                    questionCount.textContent = blocks.length; 
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

            document.querySelectorAll('.module-block').forEach(attachQuestionLogic);

            addModuleBtn.addEventListener('click', () => {
                const moduleBlock = document.createElement('div');
                moduleBlock.classList.add('module-block', 'border', 'rounded', 'p-3', 'mb-3');
                moduleBlock.innerHTML = `
                <div class="row g-3">
                    <div class="col-12 text-end">
                       
                        <i class="bi bi-x-circle removeModuleBtn" style="color:red;"></i>
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
                        <div class="col-12 text-center mt-3 d-flex justify-content-start gap-2">
                                          
                                            <button type="button" class="btn btn-primary btn-sm mt-2 addQuestionBtn">
                                                + Add 10 Questions
                                            </button>

                                            
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
            `;
                modulesContainer.appendChild(moduleBlock);
                moduleBlock.querySelector('.removeModuleBtn').addEventListener('click', () => {
                    moduleBlock.remove();
                });
                attachQuestionLogic(moduleBlock);
            });
        });
    </script> -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 optional -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- <script>
        $(function() {
            // Add module button
            $('#addModuleBtn').on('click', function() {
                const moduleHtml = getModuleBlockHtml();
                $('#modulesContainer').append(moduleHtml);
                updateQuestionCounts();
            });

            // Initialize with one module block (if none present)
            if ($('#modulesContainer .module-block').length === 0) {
                $('#addModuleBtn').trigger('click');
            }

            // Add 10 question inputs to a module
            $('#modulesContainer').on('click', '.addQuestionBtn', function() {
                const $module = $(this).closest('.module-block');
                const coLevel = $module.find('#coLevelSelect').val() || $module.find('#coLevelSelect').data('selected') || '';
                // add 10 question rows
                const questionsContainer = $module.find('.questions-container');
                for (let i = 0; i < 10; i++) {
                    questionsContainer.append(getQuestionRowHtml());
                }
                updateQuestionCounts();
            });

            // Remove a module block
            $('#modulesContainer').on('click', '.removeModuleBtn', function() {
                $(this).closest('.module-block').remove();
                updateQuestionCounts();
            });

            // Remove a question row
            $('#modulesContainer').on('click', '.removeQuestionBtn', function() {
                $(this).closest('.question-row').remove();
                updateQuestionCounts();
            });

            // Update question counts when inputs change
            $('#modulesContainer').on('input change', '.question-row input, .question-row textarea, .question-row select', function() {
                updateQuestionCounts();
            });

            // Submit form via AJAX
            $('form').on('submit', function(e) {
                e.preventDefault();
                const $form = $(this);
                const $btn = $form.find('button[type="submit"]');
                $btn.prop('disabled', true);

                // Build FormData manually to control which question rows are included
                const fd = new FormData();

                // Basic course info (read-only inputs)
                fd.append('course_name', $.trim($('#courseName').val() || ''));
                fd.append('course_code', $.trim($('#courseCode').val() || ''));
                fd.append('c_id', $.trim($('#course_id').val() || ''));
                fd.append('launch_c_id', $.trim($('#launch_c_id').val() || ''));

                // Iterate modules
                $('#modulesContainer .module-block').each(function(mIndex) {
                    const $mod = $(this);
                    const chapter_number = $.trim($mod.find('[name="chapter_number[]"]').val());
                    const chapter_title = $.trim($mod.find('[name="chapter_title[]"]').val());
                    const coLevel = $mod.find('[name="co_level[]"]').val() || '';

                    // Skip module entirely if both chapter_number and chapter_title are empty
                    if (!chapter_number && !chapter_title && $mod.find('[name="reading_material[]"]')[0].files.length === 0 && $mod.find('[name="video_material[]"]')[0].files.length === 0 && $mod.find('.questions-container .question-row').length === 0) {
                        // nothing filled in this module -> skip
                        return true; // continue
                    }

                    // Add module metadata (arrays)
                    fd.append('chapter_number[]', chapter_number);
                    fd.append('chapter_title[]', chapter_title);
                    fd.append('co_level[]', coLevel);

                    // Files: reading_material[] and video_material[] are file inputs (single file per module)
                    const readingInput = $mod.find('[name="reading_material[]"]')[0];
                    if (readingInput && readingInput.files && readingInput.files[0]) {
                        fd.append('reading_material_files[]', readingInput.files[0]);
                    } else {
                        fd.append('reading_material_files[]', ''); // placeholder so indices match
                    }

                    const videoInput = $mod.find('[name="video_material[]"]')[0];
                    if (videoInput && videoInput.files && videoInput.files[0]) {
                        fd.append('video_material_files[]', videoInput.files[0]);
                    } else {
                        fd.append('video_material_files[]', '');
                    }

                    // Questions for this module — only include rows with required fields (question + answer + at least one option)
                    $mod.find('.questions-container .question-row').each(function(qIndex) {
                        const $q = $(this);
                        const question = $.trim($q.find('[name="question_text[]"]').val());
                        const option1 = $.trim($q.find('[name="option1[]"]').val());
                        const option2 = $.trim($q.find('[name="option2[]"]').val());
                        const option3 = $.trim($q.find('[name="option3[]"]').val());
                        const option4 = $.trim($q.find('[name="option4[]"]').val());
                        const answer = $.trim($q.find('[name="answer[]"]').val());
                        const q_co_level = $.trim($q.find('[name="q_co_level[]"]').val());

                        // consider a question valid if question text and answer exist
                        if (question && answer) {
                            fd.append('questions_module_index[]', mIndex); // we track which module index
                            fd.append('question_text[]', question);
                            fd.append('option1[]', option1);
                            fd.append('option2[]', option2);
                            fd.append('option3[]', option3);
                            fd.append('option4[]', option4);
                            fd.append('answer[]', answer);
                            fd.append('q_co_level[]', q_co_level);
                        }
                    });
                });

                // Send AJAX
                $.ajax({
                    url: 'api/upload_material.php',
                    type: 'POST',
                    data: fd,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    headers: {
                        'Accept': 'application/json'
                    },
                    success: function(res) {
                        if (res && res.status === 200) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Saved',
                                text: res.message
                            });
                            // reset form
                            $form[0].reset();
                            $('#modulesContainer').html('');
                            $('#addModuleBtn').trigger('click');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: res.message || 'Failed to save'
                            });
                        }
                    },
                    error: function(xhr) {
                        let msg = 'Something went wrong';
                        if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                        else if (xhr.responseText) {
                            try {
                                msg = JSON.parse(xhr.responseText).message || msg;
                            } catch (e) {}
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: msg
                        });
                    },
                    complete: function() {
                        $btn.prop('disabled', false);
                    }
                });
            });

            function getModuleBlockHtml() {
                return `
        <div class="module-block border rounded p-3 mb-3">
            <div class="d-flex justify-content-between mb-2">
                <strong>Module</strong>
                <div>
                    <button type="button" class="btn btn-sm btn-danger removeModuleBtn">Remove Module</button>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="chapter_number[]" placeholder="Chapter Number">
                        <label>Chapter Number</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="chapter_title[]" placeholder="Chapter Title">
                        <label>Chapter Title</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Course Material (Only PDF)</label>
                    <input type="file" class="form-control" name="reading_material[]" accept=".pdf">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Flipped Class (Video)</label>
                    <input type="file" class="form-control" name="video_material[]" accept="video/*">
                </div>

                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6>Practice Questions</h6>
                        <span class="badge bg-primary question-count" style="padding:8px;">0</span>
                    </div>
                    <div class="questions-container"></div>

                    <div class="mt-2 d-flex gap-2">
                        <button type="button" class="btn btn-primary btn-sm addQuestionBtn">+ Add 10 Questions</button>
                        <select class="form-select form-select-sm ms-auto" name="co_level[]" style="width: 150px;">
                            <option value="">Module CO Level (optional)</option>
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
        </div>`;
            }

            function getQuestionRowHtml() {
                return `
        <div class="question-row border rounded p-2 mb-2">
            <div class="d-flex justify-content-between">
                <strong>Question</strong>
                <button type="button" class="btn btn-sm btn-outline-danger removeQuestionBtn">Remove</button>
            </div>
            <div class="row g-2 mt-2">
                <div class="col-12">
                    <textarea name="question_text[]" class="form-control" placeholder="Question"></textarea>
                </div>
                <div class="col-md-6"><input class="form-control" name="option1[]" placeholder="Option 1"></div>
                <div class="col-md-6"><input class="form-control" name="option2[]" placeholder="Option 2"></div>
                <div class="col-md-6"><input class="form-control" name="option3[]" placeholder="Option 3"></div>
                <div class="col-md-6"><input class="form-control" name="option4[]" placeholder="Option 4"></div>
                <div class="col-md-6"><input class="form-control" name="answer[]" placeholder="Answer (exact text)"></div>
                <div class="col-md-6">
                    <select class="form-select form-select-sm" name="q_co_level[]">
                        <option value="">CO Level (optional)</option>
                        <option value="CO1">CO1</option>
                        <option value="CO2">CO2</option>
                        <option value="CO3">CO3</option>
                        <option value="CO4">CO4</option>
                        <option value="CO5">CO5</option>
                        <option value="CO6">CO6</option>
                    </select>
                </div>
            </div>
        </div>`;
            }

            function updateQuestionCounts() {
                $('#modulesContainer .module-block').each(function() {
                    const count = $(this).find('.questions-container .question-row').length;
                    $(this).find('.question-count').text(count);
                });
            }
        });
    </script> -->
    <script>
        $(function() {
            // Add module button
            $('#addModuleBtn').on('click', function() {
                const moduleHtml = getModuleBlockHtml();
                const $module = $(moduleHtml);
                $('#modulesContainer').append($module);
                updateQuestionCounts();
            });

            // Initialize with one module block
            if ($('#modulesContainer .module-block').length === 0) {
                $('#addModuleBtn').trigger('click');
            }

            // Remove a module block
            $('#modulesContainer').on('click', '.removeModuleBtn', function() {
                $(this).closest('.module-block').remove();
                updateQuestionCounts();
            });

            // Add 10 Questions (delegated binding works for all modules)
            $('#modulesContainer').on('click', '.addQuestionBtn', function() {
                const $module = $(this).closest('.module-block');
                const $questionsContainer = $module.find('.questions-container');

                for (let i = 1; i <= 10; i++) {
                    const currentIndex = $questionsContainer.find('.question-block').length + 1;
                    const blockHtml = createQuestionBlock(currentIndex);
                    const $block = $(blockHtml);

                    // Remove question button
                    $block.find('.removeQuestionBtn').on('click', function() {
                        $block.remove();
                        updateQuestionNumbers($questionsContainer, $module);
                    });

                    $questionsContainer.append($block);
                }

                updateQuestionNumbers($questionsContainer, $module);
            });

            // Submit form via AJAX
            $('form').on('submit', function(e) {
                e.preventDefault();
                const $form = $(this);
                const $btn = $form.find('button[type="submit"]');
                $btn.prop('disabled', true);

                const fd = new FormData();
                fd.append('course_name', $.trim($('#courseName').val() || ''));
                fd.append('course_code', $.trim($('#courseCode').val() || ''));
                fd.append('c_id', $.trim($('#course_id').val() || ''));
                fd.append('launch_c_id', $.trim($('#launch_c_id').val() || ''));

                $('#modulesContainer .module-block').each(function(mIndex) {
                    const $mod = $(this);
                    const chapter_number = $.trim($mod.find('[name="chapter_number[]"]').val());
                    const chapter_title = $.trim($mod.find('[name="chapter_title[]"]').val());
                    const coLevel = $mod.find('[name="co_level[]"]').val() || '';

                    if (!chapter_number && !chapter_title &&
                        $mod.find('[name="reading_material[]"]')[0].files.length === 0 &&
                        $mod.find('[name="video_material[]"]')[0].files.length === 0 &&
                        $mod.find('.questions-container .question-block').length === 0) {
                        return true; // skip empty module
                    }

                    fd.append('chapter_number[]', chapter_number);
                    fd.append('chapter_title[]', chapter_title);
                    fd.append('co_level[]', coLevel);

                    const readingInput = $mod.find('[name="reading_material[]"]')[0];
                    fd.append('reading_material_files[]', readingInput?.files[0] || '');

                    const videoInput = $mod.find('[name="video_material[]"]')[0];
                    fd.append('video_material_files[]', videoInput?.files[0] || '');

                    // Collect valid questions
                    // Collect valid questions
                    $mod.find('.questions-container .question-block').each(function() {
                        const $q = $(this);
                        const question = $.trim($q.find('[name="question_text[]"]').val());
                        const options = {
                            A: $.trim($q.find('[name="option_a[]"]').val()),
                            B: $.trim($q.find('[name="option_b[]"]').val()),
                            C: $.trim($q.find('[name="option_c[]"]').val()),
                            D: $.trim($q.find('[name="option_d[]"]').val())
                        };

                        // get which option is selected
                        const chosenKey = $q.find('.form-check-input:checked').val();
                        const answer = chosenKey ? options[chosenKey] : '';

                        const q_co_level = $.trim($q.find('[name="q_co_level[]"]').val());

                        if (question && answer) {
                            fd.append('questions_module_index[]', mIndex);
                            fd.append('question_text[]', question);
                            fd.append('option1[]', options.A);
                            fd.append('option2[]', options.B);
                            fd.append('option3[]', options.C);
                            fd.append('option4[]', options.D);
                            fd.append('answer[]', answer); // ✅ now full text, not just A/B/C/D
                            fd.append('q_co_level[]', q_co_level);
                        }
                    });

                });

                $.ajax({
                    url: 'api/upload_material.php',
                    type: 'POST',
                    data: fd,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(res) {
                        if (res && res.status === 200) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Saved',
                                text: res.message
                            });
                            $form[0].reset();
                            $('#modulesContainer').html('');
                            $('#addModuleBtn').trigger('click');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: res.message || 'Failed to save'
                            });
                        }
                    },
                    error: function(xhr) {
                        let msg = 'Something went wrong';
                        if (xhr.responseJSON?.message) msg = xhr.responseJSON.message;
                        else if (xhr.responseText) {
                            try {
                                msg = JSON.parse(xhr.responseText).message || msg;
                            } catch (e) {}
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: msg
                        });
                    },
                    complete: function() {
                        $btn.prop('disabled', false);
                    }
                });
            });

            // --- Helpers ---
            function createQuestionBlock(num) {
                return `
        <div class="question-block border p-3 mt-3 rounded">
            <div class="d-flex justify-content-end mb-2">
                <i class="bi bi-x-circle removeQuestionBtn" style="color:red;cursor:pointer;"></i>
            </div>
            <div class="d-flex justify-content-between align-items-center question-index mb-2">
                <h6 class="mb-0">Q${num}</h6>
                <input type="text" class="form-control" name="question_text[]" placeholder="Enter Question ${num}">
            </div>
            <div class="row g-2">
                ${['A','B','C','D'].map(opt=>`
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="form-check me-2">
                            <input class="form-check-input" type="radio" name="correct_answer_${num}" value="${opt}">
                        </div>
                        <input type="text" class="form-control" name="option_${opt.toLowerCase()}[]" placeholder="Option ${opt}">
                    </div>
                `).join('')}
            </div>
        </div>`;
            }

            function updateQuestionNumbers($questionsContainer, $module) {
                $questionsContainer.find('.question-block').each(function(idx) {
                    const num = idx + 1;
                    $(this).find('h6').text(`Q${num}`);
                    $(this).find('[name="question_text[]"]').attr('placeholder', `Enter Question ${num}`);
                    $(this).find('.form-check-input').attr('name', `correct_answer_${num}`);
                });
                $module.find('.question-count').text($questionsContainer.find('.question-block').length);
            }

            function getModuleBlockHtml() {
                return `
        <div class="module-block border rounded p-3 mb-3">
            <div class="d-flex justify-content-between mb-2">
                <strong>Module</strong>
                <button type="button" class="btn btn-sm btn-danger removeModuleBtn">Remove Module</button>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="chapter_number[]" placeholder="Chapter Number">
                        <label>Chapter Number</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="chapter_title[]" placeholder="Chapter Title">
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
                        <h6>Practice Questions</h6>
                        <span class="badge bg-primary question-count">0</span>
                    </div>
                    <div class="questions-container"></div>
                    <div class="d-flex gap-2 mt-2">
                        <button type="button" class="btn btn-primary btn-sm addQuestionBtn">+ Add 10 Questions</button>
                        <select class="form-select form-select-sm ms-auto" name="co_level[]" style="width:auto;">
                            <option value="">Module CO Level</option>
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
        </div>`;
            }

            function updateQuestionCounts() {
                $('#modulesContainer .module-block').each(function() {
                    const count = $(this).find('.questions-container .question-block').length;
                    $(this).find('.question-count').text(count);
                });
            }
        });
    </script>



</body>

</html>