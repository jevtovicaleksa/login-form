<?php include 'header.php'; ?>
<?php 


$pom1 = array('1'=> 'Aleksa', '2' => 'Jevtovic');
$pom2 = array('3' => 'nestoo', '4' => 'opet nesto');


$txt = "Lorem <b>Ipsum</b> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's 			standard dummy <b><i>text ever since</i></b> the 1500s, when an unknown printer took a galley of type and scrambled it to 		make a type specimen book";
echo $txt;
echo "<br>";
$txt1 = strip_tags($txt);
echo str_word_count($txt1);
echo "<br>";

$txt2 = explode(" ", $txt1);
print_r(count($txt2));

$nizovi = "";
foreach ($pom1 as $niz1) {
	$nizovi .= $niz1;
}




// $broj = 12;

// if($broj <= 10){
// 	echo "Broj je u prvoj desetici";
// }elseif($broj > 10 AND $broj <=20){
// 	echo "Broj je u drugoj desetici";
// }elseif ($broj > 20 AND $broj <=30) {
// 	echo "Broj je u trecoj desetici";
// }elseif($broj > 30 AND $broj <=40){
// 	echo "Broj je u cetvrtoj desetici";
// }elseif ($broj > 40 AND $broj <=50) {
// 	echo "Broj je u petoj desetici";
// }

 ?>

