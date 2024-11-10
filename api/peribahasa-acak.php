<?php
header("Content-Type: application/json");
require "simple_html_dom.php";

$web = "https://jagokata.com/peribahasa/peribahasa-acak.html?";
$time = time();
$url = $web . $time;
$html = file_get_html($url);

if (!$html) {
    echo json_encode(["status" => "404", "author" => "abdiputranar", "message" => "Page not found"], JSON_PRETTY_PRINT);
    exit;
}

$peribahasa = [];
$peribahasa_list = $html->find('#arti-kata ul.peribahasa', 0);

if ($peribahasa_list) {
    foreach ($peribahasa_list->find('li') as $item) {
        $kalimat = $item->find('h3 a', 0)->plaintext ?? '';
        $link = $item->find('h3 a', 0)->href ?? '';
        $arti = $item->find('i', 0) ? $item->find('i', 0)->plaintext : '';
        $arti = str_replace("Arti: ", "", $arti);
        if (empty($kalimat) || empty($arti) || empty($link)) {
            continue;
        }

        $peribahasa[] = [
            "kalimat" => $kalimat,
            "arti" => $arti,
            "link" => $link
        ];
    }
}

echo str_replace("\\/", "/", json_encode([
    "status" => "200",
    "author" => "abdiputranar",
    "data" => $peribahasa
], JSON_PRETTY_PRINT));
?>
