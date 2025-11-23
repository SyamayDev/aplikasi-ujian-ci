<!DOCTYPE html>
<html lang="id">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <title>Kalkulator</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="<?= base_url('assets/css/fitur_islami.css') ?>">
    <style>
        .calculator-card {
            max-width: 350px;
            margin: 2rem auto;
            border-radius: 15px;
        }

        .calculator-display {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            font-size: 2.5rem;
            text-align: right;
            padding: 10px 15px;
            overflow-x: auto;
            white-space: nowrap;
            color: #343a40;
        }

        .calculator-buttons {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            padding: 20px 0 0;
        }

        .calculator-buttons .btn {
            font-size: 1.5rem;
            padding: 15px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }

        .calculator-buttons .btn:active {
            transform: translateY(2px);
            box-shadow: none;
        }

        .btn-operator {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-equal {
            background-color: #28a745;
            grid-column: span 2;
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <div id="content">
            <header class="main_haeder multi_item bg-success text-white shadow">
                <div class="em_side_right">
                    <a class="btn btn__back rounded-circle bg-light text-success" href="<?= base_url('siswa/beranda') ?>">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
                <div class="title_page">
                    <span class="page_name">Kalkulator</span>
                </div>
                <div class="em_side_right"></div>
            </header>

            <main class="container mt-4 animate__animated animate__fadeIn">
                <div class="card shadow-sm calculator-card">
                    <div class="card-body p-4">
                        <div id="display" class="calculator-display mb-3">0</div>
                        <div class="calculator-buttons">
                            <button class="btn btn-danger" onclick="clearDisplay()">C</button>
                            <button class="btn btn-secondary" onclick="deleteLast()"><i class="fas fa-backspace"></i></button>
                            <button class="btn btn-operator" onclick="appendToDisplay('/')">÷</button>
                            <button class="btn btn-operator" onclick="appendToDisplay('*')">×</button>

                            <button class="btn btn-light" onclick="appendToDisplay('7')">7</button>
                            <button class="btn btn-light" onclick="appendToDisplay('8')">8</button>
                            <button class="btn btn-light" onclick="appendToDisplay('9')">9</button>
                            <button class="btn btn-operator" onclick="appendToDisplay('-')">-</button>

                            <button class="btn btn-light" onclick="appendToDisplay('4')">4</button>
                            <button class="btn btn-light" onclick="appendToDisplay('5')">5</button>
                            <button class="btn btn-light" onclick="appendToDisplay('6')">6</button>
                            <button class="btn btn-operator" onclick="appendToDisplay('+')">+</button>

                            <button class="btn btn-light" onclick="appendToDisplay('1')">1</button>
                            <button class="btn btn-light" onclick="appendToDisplay('2')">2</button>
                            <button class="btn btn-light" onclick="appendToDisplay('3')">3</button>
                            <button class="btn btn-success btn-equal" onclick="calculateResult()">=</button>

                            <button class="btn btn-light" onclick="appendToDisplay('0')">0</button>
                            <button class="btn btn-light" onclick="appendToDisplay('.')">.</button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        const display = document.getElementById('display');

        function appendToDisplay(value) {
            if (display.innerText === '0' && value !== '.') display.innerText = value;
            else display.innerText += value;
        }

        function clearDisplay() {
            display.innerText = '0';
        }

        function deleteLast() {
            display.innerText = display.innerText.length > 1 ? display.innerText.slice(0, -1) : '0';
        }

        function calculateResult() {
            try {
                let result = eval(display.innerText.replace(/×/g, '*').replace(/÷/g, '/'));
                display.innerText = (result === Infinity || isNaN(result)) ? 'Error' : Math.round(result * 1e8) / 1e8;
            } catch (error) {
                display.innerText = 'Error';
            }
        }
    </script>
</body>

</html>