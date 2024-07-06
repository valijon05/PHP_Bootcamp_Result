<?php
require "Currency.php";

$currency = new Currency();
$amount = $_POST['amount'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Currency Converter</title>
</head>
<body>
<div class="container">
    <form action="" method="post">
        <select class="form-select" name="currency">
            <?php
            foreach ($currency->customCurrencies() as $currencyName => $rate) {
                echo "<option value='$currencyName'>$currencyName</option>";
            }
            ?>
        </select>
        <fieldset>
            <legend>Currency converter</legend>
            <div class="mb-3">
                <label for="amount" class="form-label">UZS => USD</label>
                <input type="text" id="amount" class="form-control" name="amount">
            </div>
            <div class="mb-3">
                <label for="converted" class="form-label">USD</label>
                <input type="text" id="converted" class="form-control" value="<?php if($amount) echo $currency->exchange((float)$amount, $_POST['currency']); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Exchange</button>
        </fieldset>
    </form>
</div>
</body>
</html>
