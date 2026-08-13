<?php
$pdfPath = "C:\\Users\\Sandip\\.gemini\\antigravity-ide\\brain\\40f7f0b3-a4a3-45e1-a11c-28a2d5f74828\\.tempmediaStorage\\e7ad812e651c41c3.pdf";
$content = file_get_contents($pdfPath);

preg_match_all('/stream[\r\n]+(.*?)[\r\n]+endstream/is', $content, $matches);

$log = "";
foreach ($matches[1] as $index => $stream) {
    $decompressed = @gzuncompress($stream);
    if ($decompressed === false) {
        $decompressed = @gzinflate(substr($stream, 2));
    }
    if ($decompressed === false) {
        $decompressed = @gzinflate($stream);
    }
    
    if ($decompressed !== false) {
        $log .= "Stream #$index (Decompressed):\n" . substr($decompressed, 0, 500) . "\n\n-------------------------\n";
    } else {
        $log .= "Stream #$index (Raw/Failed):\n" . substr($stream, 0, 100) . "\n\n-------------------------\n";
    }
}
file_put_contents(base_path('pdf_streams.txt'), $log);
echo "Wrote streams to pdf_streams.txt\n";
