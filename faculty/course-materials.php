<?php
session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    // Not logged in → redirect to login
    header("Location: ../index.php");
    exit;
}

if (!isset($_SESSION["user_type"]) || $_SESSION["user_type"] !== "Faculty") {
    // Logged in but not Faculty → force logout
    session_destroy();
    header("Location: ../index.php");
    exit;
}
$lauch_course_id = $_GET['launch_c_id'] ?? null;
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
            <div class="col-12 col-sm-10 col-md-9 col-lg-10 p-0">
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
                            <li class="breadcrumb-item"><a
                                    href="course-details.php?launch_c_id=<?php echo $lauch_course_id; ?>">Course Details
                                </a></li>

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

                        <form action="" method="POST" enctype="multipart/form-data" class="row g-3">
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

                            <!-- course type -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="file" class="form-control" id="thumbnail" name="thumbnail"
                                        placeholder="Thumbnail" readonly>
                                    <label for="unit">Thumbnail Image</label>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="learning_type" id="learningtype" class="form-select">
                                        <option value="" selected disabled>Select Learning Type</option>
                                        <option value="sequential">Sequential Learning</option>
                                        <option value="flexible">Flexible Learning</option>
                                    </select>
                                    <label for="unit">Learning Type </label>
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
                                                <label>Module Number</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" name="chapter_title[]"
                                                    placeholder="Chapter Title" required>
                                                <label>Module Title</label>
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

                                            <!-- CO Level Select -->
                                            <!-- <select class="form-select form-select-sm mt-2" style="width: auto;" id="coLevelSelect">
                                                <option selected disabled>Select CO Level</option>
                                                <option value="CO1">CO1</option>
                                                <option value="CO2">CO2</option>
                                                <option value="CO3">CO3</option>
                                                <option value="CO4">CO4</option>
                                                <option value="CO5">CO5</option>
                                                <option value="CO6">CO6</option>
                                            </select> -->
                                            <select class="form-select form-select-sm mt-2" style="width: auto;"
                                                id="coLevelSelect">
                                                <option selected disabled>Loading CO Levels...</option>
                                            </select>

                                            <!-- Add Question Button -->
                                            <button type="button" class="btn btn-primary btn-sm mt-2 addQuestionBtn">
                                                + Add 10 Questions
                                            </button>


                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="col-12 text-center mt-3 d-flex justify-content-center gap-2">
                                <button type="submit" id="submitBtn" class="btn btn-secondary btn-small">Submit
                                    Modules</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modulesContainer = document.getElementById('modulesContainer');
            const addModuleBtn = document.getElementById('addModuleBtn');

            function createQuestionBlock(num, coLevel, moduleIndex) {
                const block = document.createElement('div');
                block.classList.add('question-block', 'border', 'p-3', 'mt-3', 'rounded');
                block.innerHTML = `
            <div class="d-flex justify-content-between mb-2">
                <h5>CO Level: <span class="text-primary">${coLevel}</span></h5>
                <i class="bi bi-x-circle removeQuestionBtn" style="color:red;cursor:pointer;"></i>
            </div>
            <div class="d-flex justify-content-between align-items-center question-index mb-2">
                <h6 class="mb-0">Q${num}</h6>
                <input type="text" name="question_text[${moduleIndex}][]" class="form-control" placeholder="Enter Question ${num}">
                <input type="hidden" name="co_level[${moduleIndex}][]" value="${coLevel}">
            </div>
            <div class="row g-2">
                ${['A', 'B', 'C', 'D'].map(opt => `
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="form-check me-2">
                            <input class="form-check-input" type="radio" tabindex="-1" name="correct_answer_${moduleIndex}_${num}" value="${opt}">
                        </div>
                        <input type="text" class="form-control" name="option_${opt.toLowerCase()}[${moduleIndex}][]" placeholder="Option ${opt}">
                    </div>
                `).join('')}
            </div>
        `;
                return block;
            }

            function attachQuestionLogic(moduleBlock, moduleIndex) {
                const questionsContainer = moduleBlock.querySelector('.questions-container');
                const addQuestionBtn = moduleBlock.querySelector('.addQuestionBtn');
                const questionCount = moduleBlock.querySelector('.question-count');
                const coLevelSelect = moduleBlock.querySelector('#coLevelSelect');

                function updateQuestionNumbers() {
                    const blocks = questionsContainer.querySelectorAll('.question-block');
                    blocks.forEach((block, idx) => {
                        const num = idx + 1;
                        block.querySelector('h6').textContent = `Q${num}`;
                        block.querySelector("input[name^='question_text']").setAttribute('placeholder', `Enter Question ${num}`);
                        block.querySelectorAll('.form-check-input').forEach(r => r.setAttribute("name", `correct_answer_${moduleIndex}_${num}`));
                    });
                    questionCount.textContent = blocks.length;
                }

                addQuestionBtn.addEventListener('click', () => {
                    const selectedCO = coLevelSelect.value;
                    if (!selectedCO || selectedCO === "Select CO Level") {
                        Swal.fire({
                            icon: 'warning',
                            title: 'CO Level Required',
                            text: 'Please select a CO Level before adding questions!'
                        });
                        return;
                    }
                    for (let i = 1; i <= 10; i++) {
                        const currentIndex = questionsContainer.querySelectorAll('.question-block').length + 1;
                        const block = createQuestionBlock(currentIndex, selectedCO, moduleIndex);
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
            document.querySelectorAll('.module-block').forEach((mod, idx) => attachQuestionLogic(mod, idx));

            addModuleBtn.addEventListener('click', () => {
                const moduleIndex = modulesContainer.querySelectorAll('.module-block').length;
                const moduleBlock = document.createElement('div');
                moduleBlock.classList.add('module-block', 'border', 'rounded', 'p-3', 'mb-3');
                moduleBlock.innerHTML = `
                    <div class="row g-3">
                        <div class="col-12 text-end">
                            <i class="bi bi-x-circle removeModuleBtn" style="color:red;cursor:pointer;"></i>
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
                                <select class="form-select form-select-sm mt-2" style="width: auto;"
                                                        id="coLevelSelect">
                                                        <option selected disabled>Loading CO Levels...</option>
                                                    </select>
                                <button type="button" class="btn btn-primary btn-sm mt-2 addQuestionBtn">+ Add 10 Questions</button>
                            </div>
                        </div>
                    </div>
                `;
                modulesContainer.appendChild(moduleBlock);
                // Fetch CO levels for the newly added select
                const newSelect = moduleBlock.querySelector('#coLevelSelect');
                $.ajax({
                    url: 'api/get_co_levels.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $(newSelect).empty().append('<option selected disabled>Select CO Level</option>');
                        $.each(data, function (index, co) {
                            $(newSelect).append('<option value="' + co.co_level + '">' + co.co_level + ' - ' + co.course_description + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                        $(newSelect).html('<option disabled>Error loading CO Levels</option>');
                    }
                });
                moduleBlock.querySelector('.removeModuleBtn').addEventListener('click', () => moduleBlock.remove());
                attachQuestionLogic(moduleBlock, moduleIndex);
            });
        });
    </script>

    <!-- validation  -->
    <script>
        function validateModules() {
            const modules = document.querySelectorAll('.module-block');

            for (let i = 0; i < modules.length; i++) {
                const module = modules[i];
                const questionBlocks = module.querySelectorAll('.question-block');

                // If module has no questions at all
                if (questionBlocks.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Questions Added',
                        text: `Please add at least one question for Module ${i + 1}!`
                    });
                    return false;
                }
            }
            return true;
        }

        function validateQuestions() {
            const modules = document.querySelectorAll('.module-block');

            for (let m = 0; m < modules.length; m++) {
                const module = modules[m];
                const questionBlocks = module.querySelectorAll('.question-block');

                for (let q = 0; q < questionBlocks.length; q++) {
                    const block = questionBlocks[q];
                    const num = q + 1;

                    // ✅ Only validate if question text is filled
                    const questionText = block.querySelector(`input[name^="question_text"]`).value.trim();
                    if (questionText === "") {
                        continue; // skip empty question
                    }

                    // Validate options
                    const options = ['a', 'b', 'c', 'd'].map(opt =>
                        block.querySelector(`input[name="option_${opt}[${m}][]"]`).value.trim()
                    );

                    if (options.some(opt => opt === '')) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Missing Option',
                            text: `Please fill all options (A, B, C, D) for Question ${num} in Module ${m + 1}`
                        });
                        return false;
                    }

                    // Validate correct answer
                    const correctSelected = Array.from(
                        block.querySelectorAll(`input[type="radio"][name="correct_answer_${m}_${num}"]`)
                    ).some(r => r.checked);

                    if (!correctSelected) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Correct Answer Required',
                            text: `Please select a correct answer for Question ${num} in Module ${m + 1}`
                        });
                        return false;
                    }
                }
            }

            return true; // ✅ All good
        }
    </script>

    <!-- submit module -->
    <script>
        document.getElementById('submitBtn').addEventListener('click', function (e) {
            e.preventDefault();

            // ✅ Step 1: Validate modules
            if (!validateModules()) return;

            // ✅ Step 2: Validate questions
            if (!validateQuestions()) return;

            // ✅ Step 3: Submit form via AJAX (same as your jQuery code but in vanilla JS)
            const form = document.querySelector("form");
            const formData = new FormData(form);

            Swal.fire({
                title: "Uploading...",
                text: "Please wait while we save your course material.",
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch("api/upload_material.php", {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    Swal.close();
                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Saved!",
                            text: data.message
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: data.message || "Something went wrong."
                        });
                    }
                })
                .catch(error => {
                    Swal.close();
                    Swal.fire({
                        icon: "error",
                        title: "Request Failed",
                        text: error.message || "Server error occurred"
                    });
                });
        });
    </script>

    <!-- fetch co level -->
    <script>
        $(document).ready(function () {
            // Fetch CO levels from backend
            $.ajax({
                url: 'api/get_co_levels.php', // PHP file to fetch CO levels
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#coLevelSelect').empty().append('<option selected disabled>Select CO Level</option>');
                    $.each(data, function (index, co) {
                        $('#coLevelSelect').append('<option value="' + co.co_level + '">' + co.co_level + ' - ' + co.course_description + '</option>');
                    });
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    $('#coLevelSelect').html('<option disabled>Error loading CO Levels</option>');
                }
            });


        });
    </script>

</body>

</html>