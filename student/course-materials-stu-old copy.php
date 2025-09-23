<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal</title>
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
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include('sidebar.php') ?>

            <!-- Main Content -->
            <div class="col-12 col-sm-10 col-md-9 col-lg-10 p-0">
                <!-- Topbar -->
                <?php include('topbar.php') ?>

                <!-- Page Content -->
                <div class="p-4">
                    <div class="course-card bg-white p-4">
                        <h4 class="mb-4">📘 Course: Business Strategy and Organizational Dynamics</h4>

                        <!-- Chapter 1 -->
                        <div class="mb-3 p-3 bg-light rounded shadow-sm">
                            <h5 class="mb-2">Chapter 1: Introduction to Business Strategy</h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div><i class="bi bi-file-earmark-pdf-fill me-2"></i>Reading Material</div>
                                <a href="../materials/LARAVEL BASICS FOM SCRATCH.pdf" target="_blank"
                                    class="btn btn-outline-secondary btn-sm">View</a>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div><i class="bi bi-folder2-open me-2"></i>Flipped Class</div>
                                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#chapter1Resources" aria-expanded="false"
                                    aria-controls="chapter1Resources">
                                    View
                                </button>
                            </div>
                            <div class="collapse mt-2" id="chapter1Resources">
                                <div class="bg-white p-2 rounded border video-container">
                                    <video id="chapter1Video" class="w-100" src="../videos/someone.mp4" controls
                                        controlsList="nodownload noremoteplayback"
                                        oncontextmenu="return false;"></video>
                                    <div id="chapter1Left" class="video-click-left"></div>
                                    <div id="chapter1Right" class="video-click-right"></div>
                                    <i id="chapter1Overlay" class="video-overlay bi bi-play-fill"></i>
                                </div>
                            </div>


                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div><i class="bi bi-pencil-square me-2"></i>Practice Test</div>
                                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#chapter1Test" aria-expanded="false">View</button>
                            </div>
                            <div class="collapse mt-2" id="chapter1Test">
                                <div class="bg-white p-2 rounded border">
                                    <a href="https://example.com/chapter1-test" target="_blank" class="d-block mb-1">
                                        <i class="bi bi-journal-check me-2"></i>Chapter 1 Practice Test
                                    </a>
                                    <a href="https://example.com/chapter1-test-2" target="_blank" class="d-block mb-1">
                                        <i class="bi bi-journal-check me-2"></i>Chapter 1 Additional Test
                                    </a>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted">✅ Complete this chapter to unlock the next one.</small>

                        <!-- chapter 2 -->
                        <!-- Chapter 2 (Initially Locked) -->
                        <div id="chapter2" class="mb-3 p-3 bg-light rounded shadow-sm disabled"
                            style="opacity: 0.5; pointer-events: none;">
                            <h5 class="mb-2">
                                Chapter 2: Competitive Analysis
                                <i class="bi bi-lock-fill text-danger ms-2" id="lockIcon2"></i>
                            </h5>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div><i class="bi bi-file-earmark-pdf-fill me-2"></i>Reading Material</div>
                                <a href="../materials/Chapter2.pdf" target="_blank"
                                    class="btn btn-outline-secondary btn-sm">View</a>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div><i class="bi bi-folder2-open me-2"></i>Flipped Class</div>
                                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#chapter2Resources" aria-expanded="false"
                                    aria-controls="chapter2Resources">
                                    View
                                </button>
                            </div>

                            <div class="collapse mt-2" id="chapter2Resources">
                                <div class="bg-white p-2 rounded border video-container">
                                    <video id="chapter2Video" class="w-100" src="../videos/someone.mp4" controls
                                        controlsList="nodownload noremoteplayback"
                                        oncontextmenu="return false;"></video>
                                    <div id="chapter2Left" class="video-click-left"></div>
                                    <div id="chapter2Right" class="video-click-right"></div>
                                    <i id="chapter2Overlay" class="video-overlay bi bi-play-fill"></i>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div><i class="bi bi-pencil-square me-2"></i>Practice Test</div>
                                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#chapter2Test" aria-expanded="false">View</button>
                            </div>

                            <div class="collapse mt-2" id="chapter2Test">
                                <div class="bg-white p-2 rounded border">
                                    <a href="https://example.com/chapter2-test" target="_blank" class="d-block mb-1">
                                        <i class="bi bi-journal-check me-2"></i>Chapter 2 Practice Test
                                    </a>
                                </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
    </script>
</body>


</html>