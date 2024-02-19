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
    "darkgreen", // 1
    "lightgreen", // 2
    "orange", // 3
    "pink", // 4
    "blue", // 5
    "red", // 6
    "yellow", // 7
    "purple", // 8
    "cyan", // 9
    "magenta", // 10
    "lime", // 11
    "navy" // 12
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
$highlights = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['word'])) {
    $wordToSearch = strtoupper($_POST['word']); // Ensure the case matches the grid
    $wordIndex = array_search(strtoupper($wordToSearch), array_map('strtoupper', $wordsList)); // Find the index of the word
    if ($wordIndex !== false && isset($colors[$wordIndex])) {
        $color = $colors[$wordIndex]; // Select color based on word index
        $highlights = searchWordInGrid($grid, $wordToSearch, $color);
    }
}

// Displaying the grid and words, with the searched words highlighted
displayGrid($grid, $highlights);
displayWords($wordsList);

?>

<style>
/* The royal decree for styling */
table, td {
    border: 1px solid black;
    border-collapse: collapse;
    padding: 5px;
    text-align: center;
}
</style>
