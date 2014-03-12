<?php

    $usortedFormFieldValuesAsArray = array();

    ksort($usortedFormFieldValuesAsArray);
    $sortedFormFieldValuesAsString = implode('', array_values($usortedFormFieldValuesAsArray));



