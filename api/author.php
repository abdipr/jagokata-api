<?php
header("Content-Type: application/json");
require "simple_html_dom.php";

$web = "https://jagokata.com/";
$query = isset($_GET['name']) ? $_GET['name'] : '';

if (!$query) {
    echo json_encode([
        "status" => "404",
        "author" => "abdiputranar",
        "message" => "Parameter 'name' wajib diisi, contoh: ?name=albert+einstein. Dan parameter 'page' adalah opsional."
    ], JSON_PRETTY_PRINT);
    exit;
}
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$url = $web . "kata-bijak/dari-" . str_replace(" ", "_", $query) . ".html";
if ($page > 1) {
    $url .= "?page=" . $page;
}
$html = file_get_html($url);

if (!$html) {
    echo json_encode(["status" => "404", "author" => "abdiputranar", "message" => "Page not found"], JSON_PRETTY_PRINT);
    exit;
}
$page = [
  "from" => $html->find('p.paginate strong', 0)->plaintext ?? '',
  "to" => $html->find('p.paginate strong', 1)->plaintext ?? '',
  "max" => $html->find('p.paginate strong', 2)->plaintext ?? '',
  ];
$profile = [];
if ($authorSection = $html->find('div.auteur-beschrijving-content', 0)) {
    $authorBirthDeath = explode(" Meninggal: ", str_replace("Lahir: ", "", $authorSection->find('p', 3)->plaintext));
    
    $profile = [
        "name" => $authorSection->find('h2', 0)->plaintext ?? '',
        "image" => $authorSection->find('img.authorpic', 0)->src ?? '',
        "title" => $authorSection->find('p', 0)->plaintext ?? '',
        "birth_death" => str_replace("Hidup: ", "", $authorSection->find('p', 1)->plaintext) ?? '',
        "birth" => $authorBirthDeath[0] ?? '',
        "death" => $authorBirthDeath[1] ?? '',
        "category" => $authorSection->find('span.auteur-categorie a', 0)->plaintext ?? '',
        "country" => $authorSection->find('p a', 1)->plaintext ?? '',
        "about" => $html->find('div#auteur-bio p', 0)->plaintext ?? ''
    ];
}

$quotes = [];
foreach ($html->find('ul#citatenrijen li') as $item) {
    $quoteText = $item->find('q.fbquote', 0)->plaintext ?? '';
    $authorName = $item->find('div.auteurfbnaam em', 0)->plaintext ?? '';

    if ($quoteText && $authorName) {
        $quotes[] = [
            "id" => str_replace("q", "", $item->id),
            "quote" => $quoteText,
            "source" => trim(str_replace("&nbsp;", " ", $item->find('div.bron-citaat', 0)->plaintext ?? '')),
            "url" => $web . ($item->find('a.quotehref', 0)->href ?? ''),
            "author" => $authorName
        ];
    }
}
if (empty($profile) && empty($quotes)) {
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
        "paginate" => $page,
        "profile" => $profile,
        "quotes" => $quotes
    ]
], JSON_PRETTY_PRINT));
?>
