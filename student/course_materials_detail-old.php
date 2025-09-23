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

    <!-- vidoplayer.js library -->

    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
    <script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>


    <link rel="stylesheet" href="../stylesheet/responsive.css" />
    <link rel="stylesheet" href="../stylesheet/styles.css" />

    <style>
        .course-card {
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }



        .bg-white {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .bg-white:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
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

        .ratio::before {
            display: block;
            padding-top: 0px !important;
            content: "";
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

                <div class="p-4">

                    <!-- Chapter Header -->
                    <div
                        class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-3 gap-sm-0">
                        <h3 class="fw-bold mb-0" id="chapter-title">Chapter Title</h3>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="#" class="btn btn-outline-secondary btn-sm d-flex align-items-center"
                                id="prev-chapter">
                                <i class="bi bi-arrow-left me-1"></i> Previous Chapter
                            </a>
                            <a href="#" class="btn btn-primary btn-sm d-flex align-items-center" id="next-chapter">
                                Next Chapter <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Chapter Progress -->
                    <div class="bg-white rounded-4 shadow-sm p-4 mb-4">
                        <h6 class="fw-semibold mb-2">Chapter Progress</h6>
                        <p class="text-muted small mb-2">Keep up the great work!</p>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-semibold small">Progress</span>
                            <span class="text-muted small" id="chapter-progress-text">0% Complete</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 8px;">
                            <div class="progress-bar bg-primary" id="chapter-progress-bar" style="width: 0%;"></div>
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

                    <!-- Tab Content -->
                    <div class="tab-content" id="chapterTabContent">

                        <!-- Reading Material -->
                        <div class="tab-pane fade show active" id="reading" role="tabpanel">
                            <div class="bg-white rounded-4 shadow-sm p-4">
                                <div class="d-none d-md-block">
                                    <iframe id="chapter-pdf" width="100%" height="600px" style="border:none;">
                                        This browser does not support PDFs. Please download the PDF to view it:
                                        <a id="chapter-pdf-link" href="" target="_blank">Download PDF</a>.
                                    </iframe>
                                </div>
                                <div class="d-block d-md-none">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="bi bi-file-earmark-pdf-fill me-2 fs-4 text-danger"></i>
                                        <span class="fw-semibold">Reading Material</span>
                                    </div>
                                    <a id="chapter-pdf-mobile" href="" target="_blank"
                                        class="btn btn-outline-secondary btn-sm">Open PDF</a>
                                </div>
                            </div>
                        </div>

                        <!-- Videos -->
                        <div class="tab-pane fade" id="videos" role="tabpanel">
                            <div class="bg-white rounded-4 shadow-sm p-4 text-center position-relative video-container">
                                <div class="ratio ratio-16x9">
                                    <video id="chapter1Video" playsinline controls
                                        controlsList="nodownload noremoteplayback" oncontextmenu="return false">
                                        <source src="../videos/someone.mp4" type="video/mp4" />
                                        Your browser does not support the video tag.
                                    </video>
                                </div>

                                <!-- Left/Right click areas for 10s skip -->
                                <div id="chapter1Left" class="video-click-left"></div>
                                <div id="chapter1Right" class="video-click-right"></div>
                            </div>
                        </div>


                        <!-- Practice Test -->
                        <div class="tab-pane fade" id="test" role="tabpanel">
                            <div class="bg-white rounded-4 shadow-sm p-4">
                                <h4 class="mb-4 text-center text-test" id="quiz-title">Practice Test</h4>
                                <form id="quiz-form"></form>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- video player -->
    <script>
        // Initialize Plyr for all video players
        const players = Array.from(document.querySelectorAll('video')).map(p => new Plyr(p, {
            controls: [
                'play-large',
                'play',
                'progress',
                'current-time',
                'mute',
                'volume',
                'settings',
                'fullscreen'
            ],
            disableContextMenu: true
        }));

        const video = document.querySelector('#chapter1Video');
        const videoWrapper = video.parentElement;
        const storageKey = "chapter1VideoTime"; // unique key for this video

        // ✅ Restore playback time if saved in sessionStorage
        if (sessionStorage.getItem(storageKey)) {
            video.currentTime = parseFloat(sessionStorage.getItem(storageKey));
        }

        // ✅ Save playback time every 2s
        video.addEventListener("timeupdate", () => {
            sessionStorage.setItem(storageKey, video.currentTime);
        });

        // ✅ Double-click left/right side to skip
        videoWrapper.addEventListener('dblclick', function(e) {
            e.preventDefault(); // stop default fullscreen

            const rect = videoWrapper.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const width = rect.width;

            if (x < width / 2) {
                video.currentTime -= 10;
            } else {
                video.currentTime += 10;
            }
        });

        // ✅ Keyboard shortcuts (ArrowLeft & ArrowRight)
        document.addEventListener('keydown', function(e) {
            if (e.key === "ArrowLeft") {
                video.currentTime -= 10;
            } else if (e.key === "ArrowRight") {
                video.currentTime += 10;
            }
        });
    </script>

    <!-- fetch detials -->
    <script>
        $(document).ready(function() {
            const params = new URLSearchParams(window.location.search);
            const chapter_id = params.get("chapter_id");
            const cm_id = params.get("cm_id");

            $.ajax({
                url: "api/get_chapter_details.php",
                type: "GET",
                data: {
                    chapter_id,
                    cm_id
                },
                dataType: "json",
                success: function(res) {
                    if (!res.success) {
                        Swal.fire("Error", res.message, "error");
                        return;
                    }

                    const chapter = res.chapter;
                    const quiz = res.quiz;

                    // --- Chapter Info ---
                    $("h3.fw-bold.mb-0").text(`${chapter.chapter_no}: ${chapter.chapter_title}`);
                    $(".progress-bar").css("width", chapter.progress + "%");
                    $(".text-muted.small").first().text(chapter.progress + "% Complete");

                    // --- PDF ---
                    const pdfPath = `../faculty/${chapter.materials}`;
                    $("#reading iframe").attr("src", pdfPath);
                    $("#reading a.btn-outline-secondary").attr("href", pdfPath);

                    // --- Video ---
                    $("#videos video").attr("src", `../faculty/${chapter.flipped_class}`);

                    // --- Quiz ---
                    const quizContainer = $("#quiz-form").empty();
                    if (!quiz || quiz.length === 0) {
                        quizContainer.append("<p>No practice questions available.</p>");
                    } else {
                        quiz.forEach((q, i) => {
                            quizContainer.append(`
                        <div class="mb-3">
                            <p>${i + 1}. ${q.question} <span class="badge bg-info">${q.co_level}</span></p>
                            <div class="row">
                                <div class="col-6 option-wrapper">
                                    <label><input type="radio" name="q${q.p_id}" value="A"> ${q.option1}</label>
                                </div>
                                <div class="col-6 option-wrapper">
                                    <label><input type="radio" name="q${q.p_id}" value="B"> ${q.option2}</label>
                                </div>
                                <div class="col-6 option-wrapper">
                                    <label><input type="radio" name="q${q.p_id}" value="C"> ${q.option3}</label>
                                </div>
                                <div class="col-6 option-wrapper">
                                    <label><input type="radio" name="q${q.p_id}" value="D"> ${q.option4}</label>
                                </div>
                            </div>
                        </div>
                    `);
                        });

                        quizContainer.append(`
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-secondary">Submit Answers</button>
                    </div>
                `);
                    }
                },
                error: function(xhr) {
                    Swal.fire("Error", xhr.responseText, "error");
                }
            });
        });
    </script>

    <!-- submit quiz -->
    <script>
        $(document).on("submit", "#quiz-form", function(e) {
            e.preventDefault();

            const params = new URLSearchParams(window.location.search);
            const cm_id = params.get("cm_id");
            const chapter_id = params.get("chapter_id");

            // --- Collect answers ---
            let answers = [];
            $("#quiz-form div.mb-3").each(function() {
                const questionId = $(this).find("input[type=radio]").attr("name").replace("q", "");
                const selected = $(this).find("input[type=radio]:checked").val() || "";
                answers.push({
                    qust_id: questionId,
                    answer: selected
                });
            });

            $.ajax({
                url: "api/submit_quiz.php",
                type: "POST",
                data: JSON.stringify({
                    cm_id,
                    chapter_id,
                    answers
                }),
                contentType: "application/json",
                dataType: "json",
                success: function(res) {
                    if (res.success) {
                        Swal.fire({
                            title: "🎯 Quiz Result",
                            html: `
                                        <div style="font-size: 18px; margin-bottom: 10px;">
                                            You scored <b style="color:#007bff;">${res.correct_count}</b> 
                                            out of <b>${res.total_questions}</b>
                                        </div>
                                        <div style="font-size: 18px; margin-bottom: 10px;">
                                            Percentage: <b style="color:#28a745;">${res.percentage}%</b>
                                        </div>
                                        <div style="font-size: 20px; font-weight: bold; color:${res.result === "Pass" ? "#28a745" : "#dc3545"};">
                                            ${res.result === "Pass" ? "✅ Congratulations! You Passed 🎉" : "❌ Oops! You Failed 😢"}
                                        </div>
                                    `,
                            icon: res.result === "Pass" ? "success" : "error",
                            confirmButtonText: "OK",
                            confirmButtonColor: res.result === "Pass" ? "#28a745" : "#dc3545",
                            });


                        // --- Update progress dynamically ---
                        $("#chapter-progress-bar").css("width", res.chapter_percent + "%");
                        $("#chapter-progress-text").text(res.chapter_percent + "% Complete");
                    } else {
                        Swal.fire("Error", res.message, "error");
                    }
                },
                error: function(xhr) {
                    Swal.fire("Error", xhr.responseText, "error");
                }
            });
        });
    </script>

    <!-- <script>
        document.addEventListener('DOMContentLoaded', () => {
            const video = document.getElementById('chapter1Video');
            const leftArea = document.getElementById('chapter1Left');
            const rightArea = document.getElementById('chapter1Right');
            const overlayIcon = document.getElementById('chapter1Overlay');
            const videoContainer = video.closest('.video-container');
            const progressKey = 'chapter1VideoTime';

            // Restore saved time
            const savedTime = localStorage.getItem(progressKey);
            if (savedTime) video.currentTime = parseFloat(savedTime);

            // Update overlay icon
            const updateOverlay = () => {
                overlayIcon.classList.toggle('bi-play-fill', video.paused);
                overlayIcon.classList.toggle('bi-pause-fill', !video.paused);
                overlayIcon.classList.toggle('visible', video.paused);
            }

            // Play/pause toggle
            const togglePlay = () => video.paused ? video.play() : video.pause();

            // Left/right click 10s
            leftArea.addEventListener('click', e => {
                e.stopPropagation();
                video.currentTime = Math.max(0, video.currentTime - 10);
            });
            rightArea.addEventListener('click', e => {
                e.stopPropagation();
                video.currentTime = Math.min(video.duration, video.currentTime + 10);
            });

            // Double-click toggle
            videoContainer.addEventListener('dblclick', togglePlay);

            // Update overlay icon on play/pause
            video.addEventListener('play', updateOverlay);
            video.addEventListener('pause', updateOverlay);

            // Hide overlay on mouse leave
            videoContainer.addEventListener('mouseleave', () => {
                if (!video.paused) overlayIcon.classList.remove('visible');
            });
            videoContainer.addEventListener('mouseenter', updateOverlay);

            // Save progress in localStorage
            video.addEventListener('timeupdate', () => {
                localStorage.setItem(progressKey, video.currentTime);
            });
            window.addEventListener('beforeunload', () => {
                localStorage.setItem(progressKey, video.currentTime);
            });

            // Video ended
            video.addEventListener('ended', () => {
                localStorage.setItem('chapter1Completed', 'true');
                // Optional: unlock next chapter logic here
            });

            // Initial overlay update
            updateOverlay();
        });
    </script> -->

</body>

</html>