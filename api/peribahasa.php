<?php
header("Content-Type: application/json");
require "simple_html_dom.php";

$web = "https://jagokata.com/";
$kata = isset($_GET['kata']) ? strtolower($_GET['kata']) : '';
if (!$kata) {
    echo json_encode([
        "status" => "404",
        "author" => "abdiputranar",
        "message" => "Parameter 'kata' wajib diisi, contoh: ?kata=air"
    ], JSON_PRETTY_PRINT);
    exit;
}

$url = $web . "peribahasa/$kata.html";
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

$penjelasan = [];
foreach ($html->find('#arti-kata ul.peribahasa') as $index => $ul) {
    if ($index == 0) continue;

    foreach ($ul->find('li') as $item) {
        $kalimat = $item->find('h3 a', 0)->plaintext ?? '';
        $link = $item->find('h3 a', 0)->href ?? '';
        $arti = $item->find('i', 0) ? $item->find('i', 0)->plaintext : '';
        $arti = str_replace("Arti: ", "", $arti);
        if (empty($kalimat) || empty($arti) || empty($link)) {
            continue;
        }

        $penjelasan[] = [
            "kalimat" => $kalimat,
            "arti" => $arti,
            "link" => $link
        ];
    }
}

if (empty($peribahasa) && empty($penjelasan)) {
    echo json_encode([
        "status" => "404",
        "author" => "abdiputranar",
        "message" => "No results found for the given keyword"
    ], JSON_PRETTY_PRINT);
    exit;
}

echo str_replace("\\/", "/", json_encode([
    "status" => "200",
    "author" => "abdiputranar",
    "data" => [
        "peribahasa" => $peribahasa,
        "penjelasan" => $penjelasan
    ]
], JSON_PRETTY_PRINT));
?>
