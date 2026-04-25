<!DOCTYPE html>
<html>
<body>

<h1>task 1</h1>

<?php
$len = 20;
$wid = 10;

$area = $len * $wid;
$perimeter = 2 * ($len + $wid);

echo "Area      : $area\n";
echo "Perimeter : $perimeter\n";

$amount = 50;
$vatrate = 15 / 100;
$vat = $amount * $vatrate;

echo "VAT   : $vat\n";

$num = 6;

if ($num % 2 == 0) {
    echo "$num is Even .\n";
} else {
    echo "$num is Odd .\n";
}

$a = 45;
$b = 82;
$c = 61;

if ($a >= $b && $a >= $c) {
    echo "Largest: " . $a;
} elseif ($b >= $a && $b >= $c) {
    echo "Largest: " . $b;
} else {
    echo "Largest: " . $c;
}

echo "Odd numbers (10–100): ";

for ($i = 10; $i <= 100; $i++) {
    if ($i % 2 != 0) {
        echo $i . " ";
    }
}


$fruits = ["apple", "banana", "mango", "orange", "grape"];
$target = "mango";
$found  = false;

for ($i = 0; $i < count($fruits); $i++) {
    if ($fruits[$i] === $target) {
        echo "'" . $target . "' found at index " . $i;
        $found = true;
        break;
    }
}

if (!$found) {
    echo "'" . $target . "' not found in the array.";
}

echo "<b>Shape 1 — Star Triangle</b><br>";
for ($r = 1; $r <= 3; $r++) {
    for ($c = 1; $c <= $r; $c++) {
        echo "* ";
    }
    echo "<br>";
}

echo "<br><b>Shape 2 — Descending Numbers</b><br>";
for ($r = 3; $r >= 1; $r--) {
    for ($c = 1; $c <= $r; $c++) {
        echo $c . " ";
    }
    echo "<br>";
}

echo "<br><b>Shape 3 — Alphabetical Staircase</b><br>";
$letter = 65; // ASCII code for 'A'
for ($r = 1; $r <= 3; $r++) {
    for ($c = 1; $c <= $r; $c++) {
        echo chr($letter) . " ";
        $letter++;
    }
    echo "<br>";
}




?>

</body>
</html>