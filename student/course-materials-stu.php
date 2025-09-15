<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal</title>
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

        .chapter-accordion .accordion-button {
            font-weight: 500;
        }

        .course-card ul {
            margin-bottom: 0;
            /* Removes bottom space of the list */
            padding-left: 0;
            /* Removes default left padding */
            list-style-type: none;
            /* Removes bullets */
        }

        .course-card li {
            margin-bottom: 5px;
            /* Optional: Small spacing between list items */
        }

        .course-card li a {
            text-decoration: none;
            /* Removes underline */
            color: #000;
            /* Bootstrap primary color (optional for consistency) */
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
                    <div class="course-card bg-white p-4">
                        <h4 class="mb-4">📘 Course: Business Strategy and Organizational Dynamics</h4>

                        <div class="accordion chapter-accordion" id="chaptersAccordion">

                            <!-- Chapter 1 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="chapterOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Chapter 1: Introduction to Business Strategy
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="chapterOne"
                                    data-bs-parent="#chaptersAccordion">
                                    <div class="accordion-body">

                                        <div class="accordion chapter-inner" id="chapter1Inner">

                                            <!-- Materials -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingMaterials1">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseMaterials1"
                                                        aria-expanded="true" aria-controls="collapseMaterials1">
                                                        📂 Materials
                                                    </button>
                                                </h2>
                                                <div id="collapseMaterials1" class="accordion-collapse collapse "
                                                    aria-labelledby="headingMaterials1" data-bs-parent="#chapter1Inner">
                                                    <div class="accordion-body">
                                                        <ul class="list-unstyled mb-0">
                                                            <li><a href="../materials/LARAVEL BASICS FOM SCRATCH.pdf"
                                                                    target="_blank">📂 Chapter Notes (PDF)</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Video -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingVideo1">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseVideo1"
                                                        aria-expanded="false" aria-controls="collapseVideo1">
                                                        🎥 Video Lecture
                                                    </button>
                                                </h2>
                                                <div id="collapseVideo1" class="accordion-collapse collapse"
                                                    aria-labelledby="headingVideo1" data-bs-parent="#chapter1Inner">
                                                    <div class="accordion-body">
                                                        <div class="ratio ratio-16x9">
                                                            <video id="videoChapter1" controls
                                                                controlslist="nodownload noremoteplayback noplaybackrate nofullscreen"
                                                                disablepictureinpicture>
                                                                <source src="../videos/someone.mp4" type="video/mp4">
                                                                Your browser does not support the video tag.
                                                            </video>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Practice Test -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTest1">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTest1"
                                                        aria-expanded="false" aria-controls="collapseTest1">
                                                        📝 Practice Test
                                                    </button>
                                                </h2>
                                                <div id="collapseTest1" class="accordion-collapse collapse"
                                                    aria-labelledby="headingTest1" data-bs-parent="#chapter1Inner">
                                                    <div class="accordion-body">
                                                        <a href="../tests/ch1-test.html"
                                                            class="btn btn-primary btn-sm">Take Test</a>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Chapter 2 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="chapterTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Chapter 2: Competitive Analysis
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="chapterTwo"
                                    data-bs-parent="#chaptersAccordion">
                                    <div class="accordion-body">

                                        <div class="accordion" id="chapter2Inner">

                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingMaterials2">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseMaterials2"
                                                        aria-expanded="true" aria-controls="collapseMaterials2">
                                                        📂 Materials
                                                    </button>
                                                </h2>
                                                <div id="collapseMaterials2" class="accordion-collapse collapse "
                                                    aria-labelledby="headingMaterials2" data-bs-parent="#chapter2Inner">
                                                    <div class="accordion-body">
                                                        <ul>
                                                            <li><a href="../materials/LARAVEL BASICS FOM SCRATCH.pdf"
                                                                    target="_blank">📂
                                                                    Chapter
                                                                    Notes (PDF)</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingVideo2">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseVideo2"
                                                        aria-expanded="false" aria-controls="collapseVideo2">
                                                        🎥 Video Lecture
                                                    </button>
                                                </h2>
                                                <div id="collapseVideo2" class="accordion-collapse collapse"
                                                    aria-labelledby="headingVideo2" data-bs-parent="#chapter2Inner">
                                                    <div class="accordion-body">
                                                        <div class="ratio ratio-16x9">
                                                            <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                                                                title="Chapter 2 Video" allowfullscreen></iframe>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTest2">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTest2"
                                                        aria-expanded="false" aria-controls="collapseTest2">
                                                        📝 Practice Test
                                                    </button>
                                                </h2>
                                                <div id="collapseTest2" class="accordion-collapse collapse"
                                                    aria-labelledby="headingTest2" data-bs-parent="#chapter2Inner">
                                                    <div class="accordion-body">
                                                        <a href="../tests/ch2-test.html"
                                                            class="btn btn-primary btn-sm">Take Test</a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Chapter 3 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="chapterThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseThree" aria-expanded="false"
                                        aria-controls="collapseThree">
                                        Chapter 3: Organizational Dynamics
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse"
                                    aria-labelledby="chapterThree" data-bs-parent="#chaptersAccordion">
                                    <div class="accordion-body">

                                        <div class="accordion" id="chapter3Inner">

                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingMaterials3">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseMaterials3"
                                                        aria-expanded="true" aria-controls="collapseMaterials3">
                                                        📂 Materials
                                                    </button>
                                                </h2>
                                                <div id="collapseMaterials3" class="accordion-collapse collapse"
                                                    aria-labelledby="headingMaterials3" data-bs-parent="#chapter3Inner">
                                                    <div class="accordion-body">
                                                        <ul>
                                                            <li><a href="../materials/LARAVEL BASICS FOM SCRATCH.pdf"
                                                                    target="_blank">📂
                                                                    Chapter
                                                                    Notes (PDF)</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingVideo3">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseVideo3"
                                                        aria-expanded="false" aria-controls="collapseVideo3">
                                                        🎥 Video Lecture
                                                    </button>
                                                </h2>
                                                <div id="collapseVideo3" class="accordion-collapse collapse"
                                                    aria-labelledby="headingVideo3" data-bs-parent="#chapter3Inner">
                                                    <div class="accordion-body">
                                                        <div class="ratio ratio-16x9">
                                                            <iframe src="https://www.youtube.com/embed/aqz-KE-bpKQ"
                                                                title="Chapter 3 Video" allowfullscreen></iframe>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTest3">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTest3"
                                                        aria-expanded="false" aria-controls="collapseTest3">
                                                        📝 Practice Test
                                                    </button>
                                                </h2>
                                                <div id="collapseTest3" class="accordion-collapse collapse"
                                                    aria-labelledby="headingTest3" data-bs-parent="#chapter3Inner">
                                                    <div class="accordion-body">
                                                        <a href="../tests/ch3-test.html"
                                                            class="btn btn-primary btn-sm">Take Test</a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div> <!-- End Chapters Accordion -->

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function enableResume(videoId, key) {
            const video = document.getElementById(videoId);
            if (!video) return;

            // Resume from saved time
            video.addEventListener('loadedmetadata', () => {
                const saved = localStorage.getItem(key);
                if (saved) video.currentTime = parseFloat(saved);
            });

            // Save progress
            video.addEventListener('timeupdate', () => {
                localStorage.setItem(key, video.currentTime);
            });

            video.addEventListener('pause', () => {
                localStorage.setItem(key, video.currentTime);
            });

            video.addEventListener('ended', () => {
                localStorage.removeItem(key);
            });

            // ✅ SINGLE CLICK → play/pause
            video.addEventListener('click', () => {
                if (video.paused) {
                    video.play();
                } else {
                    video.pause();
                }
            });

            // ✅ DOUBLE CLICK → skip left/right
            video.addEventListener('dblclick', (e) => {
                const rect = video.getBoundingClientRect();
                const clickX = e.clientX - rect.left;

                if (clickX < rect.width / 2) {
                    // Left side = rewind 10s
                    video.currentTime = Math.max(video.currentTime - 10, 0);
                } else {
                    // Right side = forward 10s
                    video.currentTime = Math.min(video.currentTime + 10, video.duration);
                }
            });
        }

        enableResume('videoChapter1', 'progress_song');
        enableResume('videoChapter2', 'progress_ch2');
        enableResume('videoChapter3', 'progress_ch3');
    </script>
</body>

</html>