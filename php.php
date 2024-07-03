



<?php

class IshKun {
    private $sana;
    private $kelishVaqti;
    private $chiqishVaqti;
    
    public function __construct($sana, $kelishVaqti, $chiqishVaqti) {
        $this->sana = $sana;
        $this->kelishVaqti = $kelishVaqti;
        $this->chiqishVaqti = $chiqishVaqti;
    }
    
    public function hisoblash() {
        $ishSoatiSoniSekund = 9 * 3600; 
        
        $kelishVaqtiSekund = strtotime($this->kelishVaqti);
        $chiqishVaqtiSekund = strtotime($this->chiqishVaqti);
        
        $vaqtFarqi = $chiqishVaqtiSekund - $kelishVaqtiSekund;
        $qolganVaqt = $ishSoatiSoniSekund - $vaqtFarqi;
        
        if ($qolganVaqt < 0) {
            $overtimeSoat = abs($qolganVaqt) / 3600;
            $overtimeDaqiqa = (abs($qolganVaqt) % 3600) / 60;
            $overtimeSoniya = abs($qolganVaqt) % 60;
            
            $overtimeString = sprintf("-%02d:%02d:%02d", $overtimeSoat, $overtimeDaqiqa, $overtimeSoniya);
            
            return "Sana: {$this->sana}\nOrtiqcha ish vaqti: $overtimeString";
        } else {
            $qolganSoat = $qolganVaqt / 3600;
            $qolganDaqiqa = ($qolganVaqt % 3600) / 60;
            $qolganSoniya = $qolganVaqt % 60;
            
            $qolganVaqtString = sprintf("%02d:%02d:%02d", $qolganSoat, $qolganDaqiqa, $qolganSoniya);
            
            return "Sana: {$this->sana}\nQolgan vaqt: $qolganVaqtString";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kunlar = ["Dushanba", "Seshanba", "Chorshanba", "Payshanba", "Juma"];
    
    foreach ($kunlar as $kun) {
        $sana = $_POST[$kun . '_kun'] ?? '';
        $kelishVaqti = $_POST[$kun . '_kelish'] ?? '';
        $chiqishVaqti = $_POST[$kun . '_chiqish'] ?? '';
        
        $ishKun = new IshKun($sana, $kelishVaqti, $chiqishVaqti);
        
        echo "<h3>$kun</h3>";
        echo "<pre>";
        echo $ishKun->hisoblash();
        echo "</pre>";
    }
}

?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <?php
    $kunlar = ["Dushanba", "Seshanba", "Chorshanba", "Payshanba", "Juma"];
    
    foreach ($kunlar as $kun) {
        echo "<label>$kun:</label><br>";
        echo "<label>Sana:</label>";
        echo "<input type='date' name='{$kun}_kun'><br>";
        echo "<label>Kelish vaqti:</label>";
        echo "<input type='time' name='{$kun}_kelish'><br>";
        echo "<label>Chiqish vaqti:</label>";
        echo "<input type='time' name='{$kun}_chiqish'><br><br>";
    }
    ?>
    <button type="submit">Jo'natish</button>
</form>
