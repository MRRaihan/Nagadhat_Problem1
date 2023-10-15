<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Problem</title>
</head>
<body>
<?php
function findPatternOccurrences($text, $pattern)
{
    // First we need to know about the length of the sentence
    $textLength = strlen($text);

    // Second we need to know about the length of the pattern
    $patternLength = strlen($pattern);

    // Declare occurrences initial value 0
    $occurrences = 0;

    // Travers every text character
    for ($i = 0; $i <= $textLength - $patternLength; $i++) {
        $match = true;

        // Traverse every pattern character
        for ($j = 0; $j < $patternLength; $j++) {

            // check first letter from text with pattern first letter
            // if first letter match then check first letter from text pattern second letter
            // if second letter match then check third letter from text pattern third letter
            // this process will continue until the length of the pattern is completed
            // if doesn't match any letter then assign match variable to false and terminate pattern loop

            if ($text[$i + $j] != $pattern[$j]) {
                $match = false;
                break;
            }
        }
        // after terminate the inner loop then check the match value
        // if the match value is true that min our pattern match then increment the occurrences value
        // otherwise nothing happen
        if ($match) {
            $occurrences++;
        }
    }

    return $occurrences;
}

$text = "tadadattaetadadadafadada";
$pattern = "dada";
$occurrences = findPatternOccurrences($text, $pattern);

echo "The pattern '{$pattern}' appears in the text '{$text}' {$occurrences} times.\n";

?>

</body>
</html>
