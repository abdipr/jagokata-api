<?php
header("Content-Type: application/json");
echo str_replace("\\", "", json_encode(["status" => "200","author" => "abdiputranar","message" => "Cek https://github.com/abdipr/jagokata-api untuk dokumentasi"], JSON_PRETTY_PRINT));
?>
