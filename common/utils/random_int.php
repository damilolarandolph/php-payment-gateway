<?php


/**
 * Get a random string of integers
 * 
 * @param int $min The smallest number allowed.
 * @param int $max The largest number allowed.
 * @param int $length The number of random numbers to generate.
 * 
 * @return int
 */
function getRandomInts($min, $max, $length)
{
    $randString = "";

    for (; $length > 0; --$length) {
        $randString .= strval(random_int($min, $max));
    }

    return $randString;
}
