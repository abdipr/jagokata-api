<?php
header("Content-Type: application/json");
require "simple_html_dom.php";

$web = "https://jagokata.com/";
$url = $web . "kata-bijak/popular.html";
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
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
$quotes = [];

foreach ($html->find('ul#citatenrijen li') as $item) {
    $quoteId = str_replace("q", "", $item->id);
    $quoteText = $item->find('q.fbquote', 0);
    if (!$quoteText) continue;
    $quoteText = $quoteText->plaintext;
    $authorName = $item->find('a.auteurfbnaam em', 0);
    if (!$authorName) continue;
    $authorName = $authorName->plaintext;
    $authorLink = $item->find('a.auteurfbnaam', 0);
    if (!$authorLink) continue;
    $authorLink = $web . $authorLink->href;
    $quoteLink = $item->find('a.quotehref', 0);
    $quoteLink = $quoteLink ? $web . $quoteLink->href : '';
    $authorImage = $item->find('img', 0);
    $authorImage = $authorImage->getAttribute('data-src') ?: $web . $authorImage->src;
    $authorDescription = $item->find('span.auteur-beschrijving', 0);
    $authorDescription = $authorDescription ? $authorDescription->plaintext : '';
    $authorBirthDeath = $item->find('span.auteur-gebsterf', 0);
    $authorBirthDeath = $authorBirthDeath ? $authorBirthDeath->plaintext : '';
    $sourceDescription = $item->find('div.bron-citaat', 0);
    $sourceDescription = $sourceDescription ? $sourceDescription->plaintext : '';
    $sourceDescription = str_replace("&nbsp;", " ", $sourceDescription);
    $sourceDescription = trim($sourceDescription);

    $quotes[] = [
        "id" => $quoteId,
        "quote" => $quoteText,
        "source" => $sourceDescription,
        "url" => $quoteLink,
        "author" => [
            "name" => $authorName,
            "img" => $authorImage,
            "link" => $authorLink,
            "description" => $authorDescription,
            "birth_death" => $authorBirthDeath
        ]
    ];
}
if (empty($quotes)) {
    echo json_encode([
        "status" => "404",
        "author" => "abdiputranar",
        "message" => "Page not found"
    ], JSON_PRETTY_PRINT);
    exit;
}
echo str_replace("\\", "", json_encode([
    "status" => "200",
    "author" => "abdiputranar",
    "data" => [
        "paginate" => $page,
        "quotes" => $quotes
      ]
], JSON_PRETTY_PRINT));
?>
