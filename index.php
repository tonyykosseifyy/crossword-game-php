<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <title>Crossword Game</title>
</head>
<body>

<?php

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
    "darkgreen", "lightgreen", "orange", "pink", "blue", "red", "yellow", "purple", "cyan", "magenta", "lime", "navy"
];

$wordsList = ["DIV", "PHP", "STYLE", "SQL", "HTML", "CSS", "JAVASCRIPT", "MATRIX", "SEARCH", "ARRAY", "LIST", "WORD"];

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

function displayWords($wordsList, $activeWord = '') {
    foreach ($wordsList as $index => $word) {
        $isActive = (strtoupper($word) === strtoupper($activeWord));
        $style = $isActive ? ' style="background-color: '.$GLOBALS['colors'][$index].'; color: white;"' : '';
        echo '<form style="display: inline;" action="" method="post">';
        echo '<input type="hidden" name="word" value="' . htmlspecialchars($word) . '">';
        echo '<input type="hidden" name="activeWord" value="' . htmlspecialchars($activeWord) . '">';
        echo '<button type="submit"' . $style . '>' . htmlspecialchars($word) . '</button>';
        echo '</form> ';
    }
    $allStyle = (strtoupper($activeWord) === 'ALL') ? ' style="background-color: grey; color: white;"' : '';
    echo '<form style="display: inline;" action="" method="post">';
    echo '<input type="hidden" name="word" value="ALL">';
    echo '<input type="hidden" name="activeWord" value="ALL">';
    echo '<button type="submit"' . $allStyle . '>All</button>';
    echo '</form>';
}

function searchWordInGrid($grid, $word, $color) {
    $highlights = [];
    $wordLength = strlen($word);
    for ($row = 0; $row < count($grid); $row++) {
        for ($col = 0; $col < count($grid[$row]); $col++) {
            foreach ([0, -1, 1] as $dRow) {
                foreach ([0, -1, 1] as $dCol) {
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
                            $highlights[$newRow][$newCol] = $color;
                        }
                    }
                }
            }
        }
    }
    return $highlights;
}

$activeWord = '';
$highlights = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['word'])) {
    $activeWord = $_POST['word'];
    if ($activeWord === 'ALL') {
        foreach ($wordsList as $index => $word) {
            $color = $colors[$index];
            $wordHighlights = searchWordInGrid($grid, strtoupper($word), $color);
            foreach ($wordHighlights as $row => $cols) {
                foreach ($cols as $col => $color) {
                    $highlights[$row][$col] = $color;
                }
            }
        }
    } else {
        $wordToSearch = strtoupper($activeWord);
        $wordIndex = 0;
        foreach($wordsList as $index => $word) {
            if (strtoupper($word) === $wordToSearch) {
                $wordIndex = $index;
                break;
            }
        }
        $color = $colors[$wordIndex];
        $highlights = searchWordInGrid($grid, $wordToSearch, $color);
    }
}


displayGrid($grid, $highlights);
echo '<div class="words-list">';
displayWords($wordsList, $activeWord);
echo '</div>';

?>

</body>
</html>
