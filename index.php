<?php
    $accessKey = '02b78fb6ba63bbc2be66de4afd0b814c';
    $endpoint = 'latest';
    $url = "http://api.exchangeratesapi.io/v1/{$endpoint}?access_key={$accessKey}";

    // Initialize CURL:
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Store the data:
    $json = curl_exec($ch);
    curl_close($ch);
    // Decode JSON response:
    $exchangeRates = json_decode($json, true);
    // Access all exchange rates:
    $rates = $exchangeRates['rates'];
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $amount = $_POST["amount"];
        $fromCurrency = $_POST["from_currency"];
        $toCurrency = $_POST["to_currency"];

        $fromRate = $rates[$fromCurrency];
        $toRate = $rates[$toCurrency];

        $convertedAmount = $amount * ($toRate / $fromRate);
        $conversionResult = number_format($convertedAmount, 3);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>


    
    <section id="form-container">
        <div id="blur"></div>
        <form method="post">
            <label for="amount">Montant:</label>
            <input type="text" id="amount" name="amount" required><br>

            <label for="from_currency">De la monnaie:</label>
            <select id="from_currency" name="from_currency">
                <?php
                foreach ($exchangeRates['rates'] as $currency => $rate) {
                    echo "<option value='$currency'>$currency</option>";
                }
                ?>
            </select><br>

            <label for="to_currency">À la monnaie:</label>
            <select id="to_currency" name="to_currency">
                <?php
                foreach ($exchangeRates['rates'] as $currency => $rate) {
                    echo "<option value='$currency'>$currency</option>";
                }
                ?>
            </select><br>
            <?php
                echo "<h3>Résultat de la conversion :</h3><p> $amount $fromCurrency équivaut à $conversionResult $toCurrency</p>";
            ?>

            <input type="submit" name="convert" value="Convertir">
        </form>
    </section>

</body>
</html>
