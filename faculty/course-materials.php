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

                                        <div class="col-12">
                                            <hr>
                                            <h5>Practice Questions</h5>
                                            <div class="questions-container"></div>
                                            <button type="button"
                                                class="btn btn-secondary btn-sm mt-2 addQuestionBtn">Add Practice
                                                Question</button>
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

            // Function to attach question logic inside a module block
            function attachQuestionLogic(moduleBlock) {
                const questionsContainer = moduleBlock.querySelector('.questions-container');
                const addQuestionBtn = moduleBlock.querySelector('.addQuestionBtn');

                addQuestionBtn.addEventListener('click', () => {
                    const questionBlock = document.createElement('div');
                    questionBlock.classList.add('border', 'p-3', 'mt-3', 'rounded');
                    questionBlock.innerHTML = `
                    <div class="text-end mb-2">
                  <button type="button" class="btn btn-danger btn-sm removeQuestionBtn">Remove </button>
              </div>
              <div class="mb-2">
                  <input type="text" class="form-control" name="question_text[]" placeholder="Enter Question" required>
              </div>
              <div class="row g-2">
                  <div class="col-md-6">
                      <input type="text" class="form-control" name="option_a[]" placeholder="Option A" required>
                  </div>
                  <div class="col-md-6">
                      <input type="text" class="form-control" name="option_b[]" placeholder="Option B" required>
                  </div>
                  <div class="col-md-6">
                      <input type="text" class="form-control" name="option_c[]" placeholder="Option C" required>
                  </div>
                  <div class="col-md-6">
                      <input type="text" class="form-control" name="option_d[]" placeholder="Option D" required>
                  </div>
              </div>
              <div class="mt-2">
                  <select class="form-select" name="correct_answer[]" required>
                      <option value="" disabled selected>Select Correct Answer</option>
                      <option value="A">Option A</option>
                      <option value="B">Option B</option>
                      <option value="C">Option C</option>
                      <option value="D">Option D</option>
                  </select>
              </div>
              
          `;

                    questionsContainer.appendChild(questionBlock);

                    questionBlock.querySelector('.removeQuestionBtn').addEventListener('click', () => {
                        questionBlock.remove();
                    });
                });
            }

            // Attach to the first module on page load
            document.querySelectorAll('.module-block').forEach(attachQuestionLogic);

            // Add new module block
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
                  <h5>Practice Questions</h5>
                  <div class="questions-container"></div>
                  <button type="button" class="btn btn-secondary btn-sm mt-2 addQuestionBtn">Add Question</button>
              </div>
          </div>
        `;

                modulesContainer.appendChild(moduleBlock);

                // remove button
                moduleBlock.querySelector('.removeModuleBtn').addEventListener('click', () => {
                    moduleBlock.remove();
                });

                // attach logic to new module
                attachQuestionLogic(moduleBlock);
            });
        });
    </script>
</body>

</html>