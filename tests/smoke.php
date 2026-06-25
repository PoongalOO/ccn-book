<?php

$root = dirname(__DIR__);
$expected = [
	'paquet.xml',
	'ccn_book_pipelines.php',
	'squelettes/rubrique-ccn-book.html',
	'css/ccn_book.css',
	'javascript/ccn_book.js',
	'lib/turnjs/turn.min.js',
	'lang/ccn_book_fr.php',
	'lang/paquet-ccn-book_fr.php',
];

foreach ($expected as $file) {
	if (!is_file($root . '/' . $file)) {
		fwrite(STDERR, "Fichier manquant: {$file}\n");
		exit(1);
	}
}

$manifest = simplexml_load_file($root . '/paquet.xml');
if (!$manifest || (string) $manifest['prefix'] !== 'ccn_book') {
	fwrite(STDERR, "Manifest SPIP invalide\n");
	exit(1);
}

$pipelines = file_get_contents($root . '/ccn_book_pipelines.php');
foreach (["find_in_path('squelettes/rubrique-ccn-book.html')", "substr(\$squelette, 0, -strlen('.html'))"] as $needle) {
	if (!str_contains($pipelines, $needle)) {
		fwrite(STDERR, "Selection de squelette invalide: {$needle}\n");
		exit(1);
	}
}

$html = file_get_contents($root . '/squelettes/rubrique-ccn-book.html');
foreach (['BOUCLE_articles_livre', 'ccn-book__prev', 'ccn-book__next'] as $needle) {
	if (!str_contains($html, $needle)) {
		fwrite(STDERR, "Marqueur de squelette manquant: {$needle}\n");
		exit(1);
	}
}

$js = file_get_contents($root . '/javascript/ccn_book.js');
foreach (['$.fn.turn', 'ArrowLeft', 'ArrowRight', "pages.turn('next')", "pages.turn('previous')"] as $needle) {
	if (!str_contains($js, $needle)) {
		fwrite(STDERR, "Marqueur JS manquant: {$needle}\n");
		exit(1);
	}
}

echo "Smoke tests OK\n";
