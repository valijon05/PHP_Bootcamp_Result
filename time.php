<?php

class Malumotlarbazasi {
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $pdo;

    public function __construct($host, $dbname, $username, $password) {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
    }

    public function ulanish() {
        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Ulanishda xatolik: " . $e->getMessage();
            die();
        }
    }

    public function barchaQatorlar() {
        try {
            $surov = "SELECT * FROM daily";
            $tayyorlash = $this->pdo->query($surov);
            $qatorlar = $tayyorlash->fetchAll(PDO::FETCH_ASSOC);
            return $qatorlar;
        } catch (PDOException $e) {
            echo "Xatolik: " . $e->getMessage();
            return [];
        }
    }

    public function malumotKiritish($kelganVaqti, $ketganVaqti) {
        try {
            $surov = "INSERT INTO daily (arrived_at, leaved_at) VALUES (:kelganVaqti, :ketganVaqti)";
            $tayyorlash = $this->pdo->prepare($surov);
            $tayyorlash->bindValue(':kelganVaqti', $kelganVaqti);
            $tayyorlash->bindValue(':ketganVaqti', $ketganVaqti);
            $tayyorlash->execute();
        } catch (PDOException $e) {
            echo "Xatolik: " . $e->getMessage();
        }
    }

    public function farqniHisoblashVaSaqlash($kelganVaqti, $ketganVaqti, $qarzId) {
        try {
            $kelganVaqtZaman = new DateTime($kelganVaqti);
            $ketganVaqtZaman = new DateTime($ketganVaqti);
            $farq = $ketganVaqtZaman->getTimestamp() - $kelganVaqtZaman->getTimestamp();
            
            $oxirgiFarq = $farq - 32400;
            
            $surov = "UPDATE daily SET remaining_time = :qolganVaqt WHERE id = :qarzId";
            $tayyorlash = $this->pdo->prepare($surov);
            $tayyorlash->bindValue(':qolganVaqt', $oxirgiFarq);
            $tayyorlash->bindValue(':qarzId', $qarzId);
            $tayyorlash->execute();
        } catch (PDOException $e) {
            echo "Xatolik: " . $e->getMessage();
        }
    }
}

$malumotlarbazasi = new Malumotlarbazasi('localhost', 'vaqt', 'root', 'Valijon9601!');
$malumotlarbazasi->ulanish();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kelganVaqti = $_POST['kelgan_vaqti'];
    $ketganVaqti = $_POST['ketgan_vaqti'];

    $malumotlarbazasi->malumotKiritish($kelganVaqti, $ketganVaqti);
    $malumotlarbazasi->farqniHisoblashVaSaqlash($kelganVaqti, $ketganVaqti, 1);

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

$qatorlar = $malumotlarbazasi->barchaQatorlar();

?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vaqt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input[type="datetime-local"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-group input[type="submit"]:hover {
            background-color: #45a049;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            margin-bottom: 10px;
            padding: 5px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1> Vaqt</h1>

    <div class="container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-group">
                <label for="kelgan_vaqti">Kelgan vaqti:</label>
                <input type="datetime-local" id="kelgan_vaqti" name="kelgan_vaqti" required>
            </div>

            <div class="form-group">
                <label for="ketgan_vaqti">Ketgan vaqti:</label>
                <input type="datetime-local" id="ketgan_vaqti" name="ketgan_vaqti" required>
            </div>

            <div class="form-group">
                <input type="submit" value="Junatish">
            </div>
        </form>
    </div>

    <?php if (!empty($qatorlar)) : ?>
        <ul>
            <?php foreach ($qatorlar as $qator) : ?>
                <li>ID: <?php echo $qator['id']; ?>, Kelgan vaqti: <?php echo $qator['arrived_at']; ?>, Ketgan vaqti: <?php echo $qator['leaved_at']; ?>, Qolgan vaqt: <?php echo gmdate("H:i:s", $qator['remaining_time']); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
