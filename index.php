<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Cross word game</title>
</head>
<body>

<?php

// The noble declaration of your grid and words list
$grid = [
    ['A', 'B', 'M', 'S', 'E', 'A', 'R', 'C', 'H', 'O'],
    ['J', 'A', 'V', 'A', 'S', 'C', 'R', 'I', 'P', 'T'],
    ['U', 'V', 'S', 'X', 'C', 'Z', 'A', 'B', 'C', 'M'],
    ['E', 'F', 'T', 'H', 'S', 'R', 'K', 'L', 'M', 'L'],
    ['O', 'P', 'Y', 'L', 'S', 'D', 'R', 'O', 'W', 'L'],
    ['Y', 'H', 'L', 'H', 'I', 'A', 'R', 'R', 'A', 'Y'],
    ['D', 'P', 'E', 'T', 'M', 'S', 'O', 'P', 'Q', 'R'],
    ['I', 'T', 'T', 'M', 'T', 'S', 'I', 'L', 'A', 'L'],
    ['V', 'C', 'E', 'L', 'G', 'H', 'I', 'P', 'S', 'Q'],
    ['M', 'A', 'T', 'R', 'I', 'X', 'S', 'T', 'U', 'S']
];

$colors = [
    "darkgreen", 
    "lightgreen", 
    "orange", 
    "pink",
    "blue", 
    "red", 
    "yellow", 
    "purple", 
    "cyan",
    "magenta", 
    "lime", 
    "navy" 
];

$wordsList = ["DIV", "PHP", "STYLE", "SQL", "HTML", "CSS", "JAVASCRIPT", "MATRIX", "SEARCH", "ARRAY", "LIST", "WORD"];

// A function to display the grid, now aware of the words to be highlighted
function displayGrid($grid, $highlights = []) {
    echo '<table>';
    foreach ($grid as $rowIndex => $row) {
        echo '<tr>';
        foreach ($row as $colIndex => $cell) {
            $style = isset($highlights[$rowIndex][$colIndex]) ? ' style="background-color: '.$highlights[$rowIndex][$colIndex].';"' : '';
            echo "<td$style>" . htmlspecialchars($cell) . '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
}

// Function to display the list of words, each a button to invoke the search
function displayWords($wordsList) {
    foreach ($wordsList as $word) {
        echo '<form style="display: inline;" action="" method="post">';
        echo '<input type="hidden" name="word" value="' . htmlspecialchars($word) . '">';
        echo '<button type="submit">' . htmlspecialchars($word) . '</button>';
        echo '</form> ';
    }
}

// The function to search for words in the grid and return their positions
function searchWordInGrid($grid, $word, $color) {
    $highlights = [];
    $wordLength = strlen($word);

    for ($row = 0; $row < count($grid); $row++) {
        for ($col = 0; $col < count($grid[$row]); $col++) {
            // Search in horizontal and vertical directions only
            foreach ([0, -1, 1] as $dRow) {
                foreach ([0, -1, 1] as $dCol) {
                    // Skip diagonal and in-place searches
                    if (($dRow == 0 && $dCol == 0) || ($dRow != 0 && $dCol != 0)) continue;

                    $found = true;
                    for ($i = 0; $i < $wordLength; $i++) {
                        $newRow = $row + $i * $dRow;
                        $newCol = $col + $i * $dCol;

                        if ($newRow < 0 || $newCol < 0 || $newRow >= count($grid) || $newCol >= count($grid[$row]) || $grid[$newRow][$newCol] != $word[$i]) {
                            $found = false;
                            break;
                        }
                    }

                    if ($found) {
                        for ($i = 0; $i < $wordLength; $i++) {
                            $newRow = $row + $i * $dRow;
                            $newCol = $col + $i * $dCol;
                            $highlights[$newRow][$newCol] = $color; // Use the specified color for highlights
                        }
                    }
                }
            }
        }
    }

    return $highlights;
}


// Handling the search request
$allHighlights = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['word'])) {
    if (strtoupper($_POST['word']) === 'ALL') {
        // Loop through each word and merge highlights
        foreach ($wordsList as $index => $word) {
            $color = $colors[$index]; // Cycle through colors
            $wordHighlights = searchWordInGrid($grid, strtoupper($word), $color);

            // Merge highlights carefully without overwriting
            foreach ($wordHighlights as $row => $cols) {
                foreach ($cols as $col => $color) {
                    $allHighlights[$row][$col] = $color; // Set or overwrite color
                }
            }
        }
        $highlights = $allHighlights;
    } else {
        $wordToSearch = strtoupper($_POST['word']);
        $wordIndex = 0 ;
        foreach($wordsList as $w_index => $w) {
            if (strcmp($w, $wordToSearch) == 0) {
                $wordIndex = $w_index ;
                break ;
            }
        }
        $color = $colors[$wordIndex];
        $highlights = searchWordInGrid($grid, $wordToSearch, $color);
    }
}


// Displaying the grid and words, with the searched words highlighted
displayGrid($grid, $highlights);
echo '<div class="words-list">';
displayWords($wordsList);
echo '<form style="display: inline;" action="" method="post">';
echo '<input type="hidden" name="word" value="ALL">';
echo '<button type="submit">All</button>';
echo '</form>';
echo '</div>';

echo '</div>';


?>


</body>
</html>
