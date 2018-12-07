<?php
// from an input array of numbers, return the first pair of elements that added up equals to totalSum
function getPairForSum($inputArray, $totalSum) {
    // array to store the complements of each element
    $complements = array();
    foreach( $inputArray as $currentValue ){
        $complement = $totalSum - $currentValue;
        // if the current element is in the complement array, it means a previous element is
        // the complement of the current
        if (array_key_exists($currentValue, $complements)) {
            // returns the first ocassion a pair is found
            return array($complement, $currentValue);
        } else {
            // adds complement to complements array
            $complements[$complement] = true;
        }
    }
    // returns empty array if no pair is found
    return array();
}

echo print_r(getPairForSum([5, 2, 8, 14, 0] , 10));