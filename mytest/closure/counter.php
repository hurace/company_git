<?php
function counter() {

$counter = 1;

return function() use(&$counter) {return $counter ++;};

}

$counter1 = counter();

$counter2 = counter();

echo "counter1: " . $counter1() . "<br />/n";

echo "counter1: " . $counter1() . "<br />/n";

echo "counter1: " . $counter1() . "<br />/n";

echo "counter1: " . $counter1() . "<br />/n";

echo "counter2: " . $counter2() . "<br />/n";

echo "counter2: " . $counter2() . "<br />/n";

echo "counter2: " . $counter2() . "<br />/n";

echo "counter2: " . $counter2() . "<br />/n";

?>