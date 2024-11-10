<?php
header("Content-Type: application/json");
require "simple_html_dom.php";

$web = "https://jagokata.com/";
$huruf = isset($_GET['huruf']) ? strtolower($_GET['huruf']) : '';
if (!$huruf) {
    echo json_encode([
        "status" => "404",
        "author" => "abdiputranar",
        "message" => "Parameter 'huruf' wajib diisi, contoh: ?huruf=a"
    ], JSON_PRETTY_PRINT);
    exit;
}

$url = $web . "tokoh/semua-tokoh" . "-$huruf" . ".html";
$html = file_get_html($url);

if (!$html) {
    echo json_encode(["status" => "404", "author" => "abdiputranar", "message" => "Page not found"], JSON_PRETTY_PRINT);
    exit;
}

$popular = [];
foreach ($html->find('ul.auteur-lijst li') as $item) {
    $name = $item->find('img', 0)->alt;
    $link = $web . $item->find('a', 0)->href;
    $image = $item->find('img', 0) ? $item->find('img', 0)->src : '';
    $description = $item->find('span.auteur-beschrijving', 0) ? $item->find('span.auteur-beschrijving', 0)->plaintext : '';
    $birthDeath = $item->find('span.auteur-gebsterf', 0) ? $item->find('span.auteur-gebsterf', 0)->plaintext : '';
    $totalQuotes = $item->find('em', 0) ? $item->find('em', 0)->plaintext : '0';
    if (!empty($description)) {
        $name = preg_replace('/\d+$/', '', trim($name));
        $popular[] = [
            "name" => $name,
            "link" => $link,
            "image" => $image,
            "description" => $description,
            "birth_death" => $birthDeath,
            "total_quotes" => $totalQuotes
        ];
    }
}

$all_authors = [];
foreach ($html->find('ul.auteur-lijst.lijstklein li') as $item) {
    $name = $item->find('a', 0)->plaintext;
    $link = $web . $item->find('a', 0)->href;
    $totalQuotes = $item->find('em', 0) ? $item->find('em', 0)->plaintext : '0';

    $name = preg_replace('/\d+$/', '', trim($name));
    $all_authors[] = [
        "name" => $name,
        "link" => $link,
        "total_quotes" => $totalQuotes
    ];
}

if (empty($popular) && empty($all_authors)) {
    echo json_encode([
        "status" => "404",
        "author" => "abdiputranar",
        "message" => "Page not found"
    ], JSON_PRETTY_PRINT);
    exit;
}

echo str_replace("\\/", "/", json_encode([
    "status" => "200",
    "author" => "abdiputranar",
    "data" => [
        "popular" => $popular,
        "all" => $all_authors
    ]
], JSON_PRETTY_PRINT));
?>
