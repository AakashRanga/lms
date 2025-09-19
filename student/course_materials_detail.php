<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Student Portal</title>
    <link rel="icon" type="image/png" href="../images/logo1.png" />

    <!-- Bootstrap CSS -->
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

        .video-container {
            position: relative;
        }

        .video-click-left,
        .video-click-right {
            position: absolute;
            top: 0;
            height: 50%;
            width: 30%;
            cursor: pointer;
            z-index: 1;
        }

        .video-click-left {
            left: 0;
        }

        .video-click-right {
            right: 0;
        }

        /* Styling for the play/pause overlay icon */
        .video-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
            font-size: 3rem;
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 2;
            pointer-events: none;
        }

        .video-container:hover .video-overlay,
        .video-overlay.visible {
            opacity: 1;
        }

        .bg-white {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .bg-white:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {

            .video-click-left,
            .video-click-right {
                width: 40% !important;
                height: 100% !important;
            }

            .video-overlay {
                font-size: 2.5rem;
            }

            .p-4 {
                padding: 1.5rem !important;
            }
        }

        @media (max-width: 576px) {
            .nav-pills .nav-link {
                padding: 0.4rem 0.75rem;
                font-size: 0.9rem;
            }

            .nav-pills {
                flex-direction: row !important;
            }

            .col-6 {
                width: 100% !important;
                flex: none !important;
            }

            .text-test {
                font-size: medium;
            }
        }

        /* Container for each option */
        .option-wrapper {
            margin-bottom: 10px;
        }

        /* Hide the native radio */
        /* input[type="radio"] {
            display: none;
        } */

        /* Style the label as a block box */
        label {
            display: block;
            padding: 15px 20px;
            border: 1.5px solid #ddd;
            border-radius: 6px;
            cursor: pointer;
            color: #111;
            font-weight: 100;
            transition: background-color 0.3s, border-color 0.3s, color 0.3s;
        }

        /* Two options side by side */
        .row>.col-6 {
            padding-left: 5px;
            padding-right: 5px;
            margin-bottom: 15px;
        }

        /* On hover */
        label:hover {
            border-color: #999;
        }

        /* When radio is checked, style the label */
        input[type="radio"]:checked+label {
            background-color: #2e7d32;
            /* green background */
            border-color: #2e7d32;
            color: white;
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

                <div class="p-4">
                    <div
                        class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-3 gap-sm-0">
                        <h3 class="fw-bold mb-0">Chapter 1: Html Essentials</h3>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="#" class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                                <i class="bi bi-arrow-left me-1"></i> Previous Chapter
                            </a>
                            <a href="#" class="btn btn-primary btn-sm d-flex align-items-center">
                                Next Chapter <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-4 shadow-sm p-4 mb-4">
                        <h6 class="fw-semibold mb-2">Chapter Progress</h6>
                        <p class="text-muted small mb-2">Keep up the great work!</p>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-semibold small">Progress</span>
                            <span class="text-muted small">60% Complete</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: 60%;"></div>
                        </div>
                    </div>

                    <!-- Tabs -->
                    <ul class="nav nav-pills mb-3 d-flex flex-column flex-sm-row overflow-auto" id="chapterTabs"
                        role="tablist" style="white-space: nowrap;">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="reading-tab" data-bs-toggle="pill"
                                data-bs-target="#reading" type="button" role="tab">
                                Reading Material
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="videos-tab" data-bs-toggle="pill" data-bs-target="#videos"
                                type="button" role="tab">
                                Interactive Videos
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="test-tab" data-bs-toggle="pill" data-bs-target="#test"
                                type="button" role="tab">
                                Practice Test
                            </button>
                        </li>
                    </ul>


                    <div class="tab-content" id="chapterTabContent">
                        <!-- Reading Material -->
                        <div class="tab-pane fade show active" id="reading" role="tabpanel">
                            <div class="bg-white rounded-4 shadow-sm p-4">

                                <!-- Desktop: iframe visible only on md and up -->
                                <div class="d-none d-md-block">
                                    <iframe src="../materials/LARAVEL BASICS FOM SCRATCH.pdf" width="100%"
                                        height="600px" style="border:none;">
                                        This browser does not support PDFs. Please download the PDF to view it:
                                        <a href="../materials/LARAVEL BASICS FOM SCRATCH.pdf" target="_blank">Download
                                            PDF</a>.
                                    </iframe>
                                </div>

                                <!-- Mobile: button visible only below md -->
                                <div class="d-block d-md-none">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="bi bi-file-earmark-pdf-fill me-2 fs-4 text-danger"></i>
                                        <span class="fw-semibold">Reading Material</span>
                                    </div>
                                    <a href="../materials/LARAVEL BASICS FOM SCRATCH.pdf" target="_blank"
                                        class="btn btn-outline-secondary btn-sm">
                                        Open PDF
                                    </a>
                                </div>

                            </div>

                        </div>

                        <!-- Videos -->
                        <div class="tab-pane fade" id="videos" role="tabpanel">
                            <div class="bg-white rounded-4 shadow-sm p-4 text-center position-relative video-container">
                                <div class="ratio ratio-16x9">
                                    <video id="chapter1Video" class="w-100 h-100" src="../videos/someone.mp4" controls
                                        controlsList="nodownload noremoteplayback" oncontextmenu="return false;">
                                    </video>
                                </div>
                                <div id="chapter1Left" class="video-click-left"></div>
                                <div id="chapter1Right" class="video-click-right"></div>
                                <i id="chapter1Overlay" class="video-overlay bi bi-play-fill"></i>
                            </div>
                        </div>

                        <!-- Practice Test -->
                        <div class="tab-pane fade" id="test" role="tabpanel">
                            <div class="bg-white rounded-4 shadow-sm p-4">
                                <h4 class="mb-4 text-center text-test">Practice Test: Web Development Fundamentals</h4>

                                <form id="quiz-form">
                                    <!-- Question 1 -->
                                    <div class="mb-3">
                                        <p>1. Which programming language is known for its use in web development and
                                            often described as "the language of the web"?</p>
                                        <div class="row">
                                            <div class="col-6 option-wrapper">
                                                <label for="q1a"><strong> <input type="radio" id="q1a" name="q1"
                                                            value="Python">
                                                        Python</strong></label>
                                            </div>
                                            <div class="col-6 option-wrapper">
                                                <label for="q1b"><strong><input type="radio" id="q1b" name="q1"
                                                            value="Java"> Java</strong></label>
                                            </div>
                                            <div class="col-6 option-wrapper">
                                                <label for="q1c"><strong><input type="radio" id="q1c" name="q1"
                                                            value="JavaScript"> JavaScript</strong></label>
                                            </div>
                                            <div class="col-6 option-wrapper">
                                                <label for="q1d"><strong><input type="radio" id="q1d" name="q1"
                                                            value="C++"> C++</strong></label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Question 2 -->
                                    <div class="mb-3">
                                        <p>2. What does CSS stand for?</p>
                                        <div class="row">
                                            <div class="col-6 option-wrapper">
                                                <label for="q2a"><strong><input type="radio" id="q2a" name="q2"
                                                            value="Cascading Style Sheets"> Cascading Style
                                                        Sheets</strong></label>
                                            </div>
                                            <div class="col-6 option-wrapper">
                                                <label for="q2b"><strong><input type="radio" id="q2b" name="q2"
                                                            value="Creative Style Solutions"> Creative Style
                                                        Solutions</strong></label>
                                            </div>
                                            <div class="col-6 option-wrapper">
                                                <label for="q2c"><strong><input type="radio" id="q2c" name="q2"
                                                            value="Computer Science Syntax"> Computer Science
                                                        Syntax</strong></label>
                                            </div>
                                            <div class="col-6 option-wrapper">
                                                <label for="q2d"><strong><input type="radio" id="q2d" name="q2"
                                                            value="Colorful Styling System"> Colorful Styling
                                                        System</strong></label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Question 3 -->
                                    <div class="mb-3">
                                        <p>3. Which of the following is a popular library for building user interfaces
                                            in React?</p>
                                        <div class="row">
                                            <div class="col-6 option-wrapper">
                                                <label for="q3a"><strong><input type="radio" id="q3a" name="q3"
                                                            value="Express.js"> Express.js</strong></label>
                                            </div>
                                            <div class="col-6 option-wrapper">
                                                <label for="q3b"><strong><input type="radio" id="q3b" name="q3"
                                                            value="Next.js"> Next.js</strong></label>
                                            </div>
                                            <div class="col-6 option-wrapper">
                                                <label for="q3c"><strong><input type="radio" id="q3c" name="q3"
                                                            value="Redux"> Redux</strong></label>
                                            </div>
                                            <div class="col-6 option-wrapper">
                                                <label for="q3d"><strong><input type="radio" id="q3d" name="q3"
                                                            value="Material-UI"> Material-UI</strong></label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Question 4 -->
                                    <div class="mb-3">
                                        <p>4. What is the primary purpose of a "git commit" command?</p>
                                        <div class="row">
                                            <div class="col-6 option-wrapper">
                                                <label for="q4a"><strong><input type="radio" id="q4a" name="q4"
                                                            value="push"> To push changes to a remote
                                                        repository</strong></label>
                                            </div>
                                            <div class="col-6 option-wrapper">
                                                <label for="q4b"><strong><input type="radio" id="q4b" name="q4"
                                                            value="merge"> To merge branches in the local
                                                        repository</strong></label>
                                            </div>
                                            <div class="col-6 option-wrapper">
                                                <label for="q4c"><strong><input type="radio" id="q4c" name="q4"
                                                            value="save"> To save changes to the local repository
                                                        history</strong></label>
                                            </div>
                                            <div class="col-6 option-wrapper">
                                                <label for="q4d"><strong><input type="radio" id="q4d" name="q4"
                                                            value="branch"> To create a new branch</strong></label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Question 5 -->
                                    <div class="mb-3">
                                        <p>5. In object-oriented programming, what concept allows objects of different
                                            classes to be treated as objects of a common base class?</p>
                                        <div class="row">
                                            <div class="col-6 option-wrapper">
                                                <label for="q5a"><strong><input type="radio" id="q5a" name="q5"
                                                            value="Encapsulation"> Encapsulation</strong></label>
                                            </div>
                                            <div class="col-6 option-wrapper">
                                                <label for="q5b"><strong><input type="radio" id="q5b" name="q5"
                                                            value="Inheritance"> Inheritance</strong></label>
                                            </div>
                                            <div class="col-6 option-wrapper">
                                                <label for="q5c"><strong><input type="radio" id="q5c" name="q5"
                                                            value="Polymorphism"> Polymorphism</strong></label>
                                            </div>
                                            <div class="col-6 option-wrapper">
                                                <label for="q5d"><strong><input type="radio" id="q5d" name="q5"
                                                            value="Abstraction"> Abstraction</strong></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-secondary">Submit Answers</button>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add all your chapters here
        const chapters = [1, 2, 3, 4]; // Add more as needed

        function unlockChapter(chapterId) {
            const chapter = document.getElementById(`chapter${chapterId}`);
            const lockIcon = document.getElementById(`lockIcon${chapterId}`);
            if (chapter) {
                chapter.classList.remove('disabled');
                chapter.style.pointerEvents = 'auto';
                chapter.style.opacity = 1;
            }
            if (lockIcon) {
                lockIcon.style.display = 'none';
            }
        }

        chapters.forEach(id => {
            const video = document.getElementById(`chapter${id}Video`);
            const leftArea = document.getElementById(`chapter${id}Left`);
            const rightArea = document.getElementById(`chapter${id}Right`);
            const overlayIcon = document.getElementById(`chapter${id}Overlay`);
            const collapseElement = document.getElementById(`chapter${id}Resources`);
            const progressBar = document.getElementById(`chapter${id}Progress`);
            const progressText = document.getElementById(`chapter${id}ProgressText`);
            const videoStateKey = `videoTime_chapter${id}`;
            const chapterCompleteKey = `chapter${id}Completed`;

            if (!video) return;

            // Restore progress
            const savedTime = localStorage.getItem(videoStateKey);
            if (savedTime) {
                video.currentTime = parseFloat(savedTime);
            }

            // Unlock next chapter if already completed
            const isCompleted = localStorage.getItem(chapterCompleteKey);
            if (isCompleted === 'true') {
                unlockChapter(id + 1);
            }

            // Handle video end
            video.addEventListener('ended', () => {
                localStorage.setItem(chapterCompleteKey, 'true');
                unlockChapter(id + 1);
            });

            function togglePlayPause() {
                if (video.paused) video.play();
                else video.pause();
            }

            function updateOverlayIcon() {
                if (video.paused) {
                    overlayIcon.classList.remove('bi-pause-fill');
                    overlayIcon.classList.add('bi-play-fill', 'visible');
                } else {
                    overlayIcon.classList.remove('bi-play-fill', 'visible');
                    overlayIcon.classList.add('bi-pause-fill');
                }
            }

            function updateProgressBar() {
                if (!video.duration) return;
                const percent = (video.currentTime / video.duration) * 100;
                if (progressBar) progressBar.style.width = `${percent}%`;
                if (progressText) progressText.textContent = `${Math.floor(percent)}% completed`;
            }

            leftArea?.addEventListener('click', e => {
                e.stopPropagation();
                video.currentTime = Math.max(0, video.currentTime - 10);
                togglePlayPause();
            });

            rightArea?.addEventListener('click', e => {
                e.stopPropagation();
                video.currentTime = Math.min(video.duration, video.currentTime + 10);
                togglePlayPause();
            });

            const videoContainer = video.closest('.video-container');
            videoContainer?.addEventListener('dblclick', togglePlayPause);

            video.addEventListener('play', updateOverlayIcon);
            video.addEventListener('pause', updateOverlayIcon);

            videoContainer?.addEventListener('mouseleave', () => {
                if (!video.paused) overlayIcon.classList.remove('visible');
            });

            videoContainer?.addEventListener('mouseenter', updateOverlayIcon);

            video.addEventListener('timeupdate', () => {
                updateProgressBar();
                localStorage.setItem(videoStateKey, video.currentTime);
            });

            window.addEventListener('beforeunload', () => {
                localStorage.setItem(videoStateKey, video.currentTime);
            });

            collapseElement?.addEventListener('hide.bs.collapse', () => {
                video.pause();
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>