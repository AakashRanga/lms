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
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h5>Upload Course Material</h5>
                            <button type="button" class="btn btn-secondary btn-sm" id="addModuleBtn">
                                ➕ Add Another Module
                            </button>
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
                                            <label class="form-label">Reading Material (PDF)</label>
                                            <input type="file" class="form-control" name="reading_material[]"
                                                accept=".pdf">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Video Material</label>
                                            <input type="file" class="form-control" name="video_material[]"
                                                accept="video/*">
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <!-- Submit -->
                            <div class="col-12 text-center mt-3 d-flex justify-content-center gap-2">
                                <button type="submit" class="btn btn-submit btn-small">Submit Modules</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- External JS -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modulesContainer = document.getElementById('modulesContainer');
            const addModuleBtn = document.getElementById('addModuleBtn');

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

                
            </div>
        `;

                modulesContainer.appendChild(moduleBlock);

                // Handle remove
                moduleBlock.querySelector('.removeModuleBtn').addEventListener('click', () => {
                    moduleBlock.remove();
                });
            });
        });

    </script>
</body>

</html>