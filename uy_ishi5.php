
<form action="1.php" method="post">
    <?php
    $days=["Dushanba","Seshanba","Chorshanba","Payshanba","Juma"];
    foreach($days as $day){

        echo "<h1>$day</h1>";
    
        echo "Date";
        echo "<input type='date' name='kun'>";
        echo "Arrived at";
        echo "<input type='time' name='soat'>";
        echo "Leaved at";
        echo "<input type='time' name='soat2'> <br><br>";
        echo "<button> Send </button>";}

    ?>
</form>

<pre>
<?php

echo ($_POST['soat'])."\n";
echo ($_POST['soat2'])."\n";
$qol=(9*3600-(strtotime($_POST['soat2'])-strtotime($_POST['soat'])));
if ($qol<0)
{
    $a=(-$qol/3600);
    $b=(-$qol%3600)/60;
    $c=(-$qol%60);
} 
else{
    $a=($qol/3600);
    $b=($qol%3600)/60;
    $c=($qol%60);
}
$time=sprintf("%02d:%02d:%02d",$a,$b,$c);
if ($qol<=0){
    echo $_POST['kun']."\n";
    echo "Ishlab berishi kerak emas.\nOrtiqcha ishlangan vaqt $time";
}
else{
    echo $_POST['kun']."\n";
    echo "Qarz vaqti $time";
}
?>



 