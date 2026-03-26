<?php
$conn = new mysqli("localhost", "root", "", "countries_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM countries");

$countries = [];
while ($row = $result->fetch_assoc()) {
    $countries[] = $row;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Country Carousel</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f2f2f2;
        }

        /* Top Search Bar */
        .top-bar {
            width: 100%;
            background: #0315df;
            padding: 20px;
            text-align: center;
        }

        .top-bar input {
            padding: 10px;
            width: 300px;
            margin-right: 10px;
            font-size: 16px;
        }

        .top-bar button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background: #4CAF50;
            color: white;
            border: none;
        }

        /* Main Container */
        .container {
            width: 900px;
            margin: 40px auto;
            background: white;
            padding: 20px;
            border: 1px solid #1303a4;
            position: relative;
        }

        /* Carousel */
        .carousel {
            overflow: hidden;
            width: 100%;
            position: relative;
        }

        .slides {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .slide {
            min-width: 100%;
            text-align: center;
        }

        .slide img {
            width: 100%;
            max-width: 400px;
            height: auto;
            border: 2px solid #333;
        }

        .country-info {
            margin-top: 15px;
            font-size: 20px;
            padding: 15px;
            width: 400px;
            margin: 0 auto;
            text-align: center;
        }

        .nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 40px;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 10px 15px;
            cursor: pointer;
            user-select: none;
            border-radius: 5px;
        }

        .prev {
            left: 10px;
        }

        .next {
            right: 10px;
        }

        .country-info strong {
            font-size: 24px;
            display: block;
            margin-bottom: 10px;
        }

        .country-info span {
            display: block;
            font-size: 18px;
        }

        .nav-btn:hover {
            background: rgba(0, 0, 0, 0.8);
            transition: background 0.3s ease;
        }

        .nav-btn:active {
            transform: translateY(-50%) scale(0.95);
        }
    </style>
</head>

<body>

    <!-- SEARCH BAR AT TOP -->
    <div class="top-bar">
        <input type="text" id="searchInput" placeholder="Search country...">
        <button onclick="handleSearch()">Search</button>
    </div>

    <!-- CAROUSEL CONTAINER -->
    <div class="container">
        <div class="carousel">
            <div class="slides" id="slides">
                <?php foreach ($countries as $country): ?>
                    <div class="slide" data-country="<?= strtolower($country['name']) ?>">
                        <img src="<?= $country['flag_url'] ?>">
                        <div class="country-info">
                            <strong><?= $country['name'] ?></strong>
                            <span>Capital: <?= $country['capital'] ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="nav-btn prev" onclick="moveSlide(-1)">&#10094;</div>
            <div class="nav-btn next" onclick="moveSlide(1)">&#10095;</div>
        </div>
    </div>

    <script>
            let index = 0;
            let totalSlides = 0;
            let allSlides = [];

            function initCarousel() {
                allSlides = document.querySelectorAll('.slide');
                totalSlides = allSlides.length;
                
                updateSlidePosition();
                
                const slides = document.getElementById('slides');
                if (slides) {
                    slides.style.transition = 'transform 0.5s ease-in-out';
                }
            }

            function moveSlide(step) {
                if (totalSlides === 0) return;
                
                index += step;

                if (index >= totalSlides) {
                    index = 0;
                }
                if (index < 0) {
                    index = totalSlides - 1;
                }

                updateSlidePosition();
            }

            function updateSlidePosition() {
                const slides = document.getElementById('slides');
                slides.style.transform = 'translateX(' + (-index * 100) + '%)';
            }


            function handleSearch() {
                const searchInput = document.getElementById('searchInput').value.toLowerCase();
                
                if (searchInput === '') {
                    // If search is empty, reset to first slide
                    index = 0;
                    updateSlidePosition();
                    return;
                }

                // Find the first country that matches the search
                let foundIndex = -1;
                for (let i = 0; i < allSlides.length; i++) {
                    const countryName = allSlides[i].getAttribute('data-country');
                    if (countryName.includes(searchInput)) {
                        foundIndex = i;
                        break;
                    }
                }

                if (foundIndex !== -1) {
                    index = foundIndex;
                    updateSlidePosition();
                }
            }

            document.addEventListener('keydown', function(event) {
                if (event.key === 'ArrowLeft') moveSlide(-1);
                if (event.key === 'ArrowRight') moveSlide(1);
            });

            window.addEventListener('load', initCarousel);
            document.addEventListener('DOMContentLoaded', initCarousel);
        </script>

</body>

</html>