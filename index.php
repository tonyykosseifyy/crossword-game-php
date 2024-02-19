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
function searchWordInGrid($grid, $word) {
    $highlights = [];
    $wordLength = strlen($word);

    for ($row = 0; $row < count($grid); $row++) {
        for ($col = 0; $col < count($grid[$row]); $col++) {
            // Search in all 8 directions
            foreach ([-1, 0, 1] as $dRow) {
                foreach ([-1, 0, 1] as $dCol) {
                    if ($dRow == 0 && $dCol == 0) continue; // Skip searching in place

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
                            $highlights[$newRow][$newCol] = 'yellow'; // Use any color or logic for different words
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
    $highlights = searchWordInGrid($grid, $wordToSearch);
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
