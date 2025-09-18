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
            height: 100%;
            width: 50%;
            cursor: pointer;
        }

        .video-click-left {
            left: 0;
        }

        .video-click-right {
            right: 0;
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
                                    data-bs-target="#chapter1Resources" aria-expanded="false">View</button>
                            </div>
                            <!-- Inside your chapter collapse -->
                            <div class="collapse mt-2" id="chapter1Resources">
                                <div class="bg-white p-2 rounded border video-container" style="height:auto;">
                                    <video id="chapter1Video" class="w-100" src="../videos/someone.mp4"></video>
                                    <!-- Timeline / Progress -->
                                    <div class="video-progress mt-2 d-flex align-items-center">
                                        <span id="chapter1Current" class="me-2">0:00</span>
                                        <div class="progress flex-grow-1" style="height: 5px;">
                                            <div id="chapter1Bar" class="progress-bar bg-primary" role="progressbar"
                                                style="width: 0%;"></div>
                                        </div>
                                        <span id="chapter1Duration" class="ms-2">0:00</span>
                                    </div>

                                    <!-- Left click area -->
                                    <div id="chapter1Left" class="video-click-left"></div>
                                    <div id="chapter1Right" class="video-click-right"></div>

                                    <!-- Play button overlay -->
                                    <div id="chapter1PlayBtn" class="position-absolute top-50 start-50 translate-middle"
                                        style="font-size:48px; color:white; cursor:pointer; text-shadow:0 0 5px black;">
                                        ►
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
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

                        <!-- Chapter 2 -->
                        <div class="mb-3 p-3 bg-light rounded shadow-sm">
                            <h5 class="mb-2">Chapter 2: Market Analysis</h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div><i class="bi bi-file-earmark-pdf-fill me-2"></i>Reading Material</div>
                                <a href="../materials/CHAPTER2_MARKET_ANALYSIS.pdf" target="_blank"
                                    class="btn btn-outline-secondary btn-sm">View</a>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div><i class="bi bi-folder2-open me-2"></i>Flipped Class</div>
                                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#chapter2Resources" aria-expanded="false">View</button>
                            </div>
                            <div class="collapse mt-2" id="chapter2Resources">
                                <div class="bg-white p-2 rounded border video-container" style="height:auto;">
                                    <video id="chapter2Video" class="w-100" src="../videos/someone.mp4"></video>
                                    <div id="chapter2Left" class="video-click-left"></div>
                                    <div id="chapter2Right" class="video-click-right"></div>
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
                                    <a href="https://example.com/chapter2-test-2" target="_blank" class="d-block mb-1">
                                        <i class="bi bi-journal-check me-2"></i>Chapter 2 Additional Test
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Chapter 3 -->
                        <div class="mb-3 p-3 bg-light rounded shadow-sm">
                            <h5 class="mb-2">Chapter 3: Strategic Planning</h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div><i class="bi bi-file-earmark-pdf-fill me-2"></i>Reading Material</div>
                                <a href="../materials/CHAPTER3_STRATEGIC_PLANNING.pdf" target="_blank"
                                    class="btn btn-outline-secondary btn-sm">View</a>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div><i class="bi bi-folder2-open me-2"></i>Flipped Class</div>
                                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#chapter3Resources" aria-expanded="false">View</button>
                            </div>
                            <div class="collapse mt-2" id="chapter3Resources">
                                <div class="bg-white p-2 rounded border video-container" style="height:auto;">
                                    <video id="chapter3Video" class="w-100" src="../videos/someone.mp4"></video>
                                    <div id="chapter3Left" class="video-click-left"></div>
                                    <div id="chapter3Right" class="video-click-right"></div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div><i class="bi bi-pencil-square me-2"></i>Practice Test</div>
                                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#chapter3Test" aria-expanded="false">View</button>
                            </div>
                            <div class="collapse mt-2" id="chapter3Test">
                                <div class="bg-white p-2 rounded border">
                                    <a href="https://example.com/chapter3-test" target="_blank" class="d-block mb-1">
                                        <i class="bi bi-journal-check me-2"></i>Chapter 3 Practice Test
                                    </a>
                                    <a href="https://example.com/chapter3-test-2" target="_blank" class="d-block mb-1">
                                        <i class="bi bi-journal-check me-2"></i>Chapter 3 Additional Test
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
        const chapters = [1, 2, 3];

        chapters.forEach(id => {
            const video = document.getElementById(`chapter${id}Video`);
            const leftArea = document.getElementById(`chapter${id}Left`);
            const rightArea = document.getElementById(`chapter${id}Right`);
            const playBtn = document.getElementById(`chapter${id}PlayBtn`);

            const currentTimeEl = document.getElementById(`chapter${id}Current`);
            const durationEl = document.getElementById(`chapter${id}Duration`);
            const progressBar = document.getElementById(`chapter${id}Bar`);

            // Hide default controls
            video.controls = false;
            if (playBtn) playBtn.style.display = 'block'; // show overlay initially

            // Overlay click toggles play/pause
            if (playBtn) {
                playBtn.addEventListener('click', () => {
                    if (video.paused) {
                        video.play();
                    } else {
                        video.pause();
                    }
                });
            }

            // Left: skip backward 10s
            leftArea.addEventListener('click', e => {
                e.stopPropagation();
                video.currentTime = Math.max(0, video.currentTime - 10);
            });

            // Right: skip forward 10s
            rightArea.addEventListener('click', e => {
                e.stopPropagation();
                video.currentTime = Math.min(video.duration, video.currentTime + 10);
            });

            // Update overlay visibility based on play/pause
            video.addEventListener('play', () => {
                if (playBtn) playBtn.style.display = 'none';
            });
            video.addEventListener('pause', () => {
                if (playBtn) playBtn.style.display = 'block';
            });

            // Update duration once metadata is loaded
            video.addEventListener('loadedmetadata', () => {
                const durMinutes = Math.floor(video.duration / 60);
                const durSeconds = Math.floor(video.duration % 60).toString().padStart(2, '0');
                durationEl.textContent = `${durMinutes}:${durSeconds}`;
            });

            // Update progress bar and current time
            video.addEventListener('timeupdate', () => {
                if (video.duration) {
                    const percent = (video.currentTime / video.duration) * 100;
                    progressBar.style.width = percent + '%';

                    const curMinutes = Math.floor(video.currentTime / 60);
                    const curSeconds = Math.floor(video.currentTime % 60).toString().padStart(2, '0');
                    currentTimeEl.textContent = `${curMinutes}:${curSeconds}`;
                }
            });

            // Allow clicking **anywhere on the video** to play/pause
            video.addEventListener('click', () => {
                if (video.paused) video.play();
                else video.pause();
            });
        });



    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
    </script>
</body>


</html>