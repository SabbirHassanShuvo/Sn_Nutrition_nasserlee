<?php
$pdfPath = "C:\\Users\\Sandip\\.gemini\\antigravity-ide\\brain\\40f7f0b3-a4a3-45e1-a11c-28a2d5f74828\\.tempmediaStorage\\e7ad812e651c41c3.pdf";
if (!file_exists($pdfPath)) {
    die("File does not exist");
}
$content = file_get_contents($pdfPath);
// Let's search for any occurrences of "cancel" or "delete" with surrounding characters (like a URL)
preg_match_all('/[a-zA-Z0-9_\-\/]{2,100}/', $content, $matches);
foreach ($matches[0] as $str) {
    $lower = strtolower($str);
    if (strpos($lower, 'cancel') !== false || strpos($lower, 'delete') !== false || strpos($lower, 'deliveries') !== false) {
        echo $str . "\n";
    }
}
