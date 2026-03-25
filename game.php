<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quiz Game</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: linear-gradient(135deg, #1e3c72, #2a5298);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* CARD */
.card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    width: 360px;
    text-align: center;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

.image-container img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 10px;
}

.options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 15px;
}

button {
    padding: 10px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    transition: 0.3s;
}

.options button {
    background: #eee;
}

.options button:hover {
    background: #2a5298;
    color: white;
}

.correct {
    background: green !important;
    color: white;
}

.incorrect {
    background: red !important;
    color: white;
}

.feedback {
    margin-top: 10px;
    font-weight: bold;
}

.next-btn {
    margin-top: 15px;
    background: #2a5298;
    color: white;
    display: none;
    width: 100%;
}

.show {
    display: block !important;
}

.score-screen {
    display: none;
}
</style>
</head>

<body>

<div class="card" id="quizCard">
    <h3 id="questionTitle">Guess the Country</h3>

    <div class="image-container">
        <img id="questionImage">
    </div>

    <div class="options" id="options"></div>

    <div class="feedback" id="feedback"></div>

    <button class="next-btn" id="nextBtn">Next</button>
</div>

<div class="card score-screen" id="scoreScreen">
    <h2>Quiz Finished 🎉</h2>
    <h3 id="finalScore"></h3>
    <button onclick="location.reload()">Play Again</button>
</div>

<?php
$conn = new mysqli("localhost", "root", "", "countries_db");

$sql = "SELECT * FROM game ORDER BY RAND() LIMIT 10";
$result = $conn->query($sql);

$questions = [];

while ($row = $result->fetch_assoc()) {
   $answers = [$row['ans1'], $row['ans2'], $row['ans3'], $row['ans4']];
    shuffle($answers);

    $questions[] = [
        "image" => $row['question'],
        "correct" => $row['correct'],
        "answers" => $answers
    ];
}

echo "<script>const questions = " . json_encode($questions) . ";</script>";

$conn->close();
?>

<script>
let current = 0;
let score = 0;
let answered = false;

const img = document.getElementById("questionImage");
const optionsDiv = document.getElementById("options");
const feedback = document.getElementById("feedback");
const nextBtn = document.getElementById("nextBtn");

function loadQuestion() {
    answered = false;
    feedback.textContent = "";
    nextBtn.classList.remove("show");

    const q = questions[current];
    img.src = q.image;

    optionsDiv.innerHTML = "";

    q.answers.forEach(ans => {
        const btn = document.createElement("button");
        btn.textContent = ans;
        btn.onclick = () => checkAnswer(btn, ans);
        optionsDiv.appendChild(btn);
    });
}

function checkAnswer(button, answer) {
    if (answered) return;
    answered = true;

    const correct = questions[current].correct;

    if (answer === correct) {
        button.classList.add("correct");
        feedback.textContent = "✅ Correct!";
        score++;
    } else {
        button.classList.add("incorrect");
        feedback.textContent = "❌ Correct answer: " + correct;

        document.querySelectorAll("#options button").forEach(btn => {
            if (btn.textContent === correct) {
                btn.classList.add("correct");
            }
        });
    }

    document.querySelectorAll("#options button").forEach(btn => btn.disabled = true);

    nextBtn.classList.add("show");
}

nextBtn.onclick = () => {
    current++;

    if (current < questions.length) {
        loadQuestion();
    } else {
        document.getElementById("quizCard").style.display = "none";
        document.getElementById("scoreScreen").style.display = "block";
        document.getElementById("finalScore").textContent =
            "Your Score: " + score + " / " + questions.length;
    }
};

loadQuestion();
</script>

</body>
</html>