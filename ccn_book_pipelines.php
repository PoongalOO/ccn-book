<?php

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Utilise le squelette livre uniquement pour les rubriques associees au mot-cle book.
 */
function ccn_book_styliser(array $flux): array {
	$fond = $flux['args']['fond'] ?? '';
	$id_rubrique = (int) ($flux['args']['id_rubrique'] ?? 0);

	if ($fond === 'rubrique' && $id_rubrique > 0 && ccn_book_rubrique_est_livre($id_rubrique)) {
		$squelette = find_in_path('squelettes/rubrique-ccn-book.html');
		if ($squelette) {
			$flux['data'] = substr($squelette, 0, -strlen('.html'));
		}
	}

	return $flux;
}

/**
 * Ajoute les styles publics du livre. Ils restent scopes sous .ccn-book.
 */
function ccn_book_insert_head_css(string $flux): string {
	$css = find_in_path('css/ccn_book.css');
	if ($css) {
		$flux .= '<link rel="stylesheet" href="' . attribut_html(timestamp($css)) . '">' . "\n";
	}

	return $flux;
}

/**
 * Charge turn.js puis l'initialisation du plugin dans la pile jQuery SPIP.
 */
function ccn_book_jquery_plugins(array $scripts): array {
	$scripts[] = 'lib/turnjs/turn.min.js';
	$scripts[] = 'javascript/ccn_book.js';

	return $scripts;
}

/**
 * Indique si une rubrique porte un mot-cle dont le titre est book.
 */
function ccn_book_rubrique_est_livre(int $id_rubrique): bool {
	static $cache = [];

	if (!array_key_exists($id_rubrique, $cache)) {
		$cache[$id_rubrique] = (bool) sql_countsel(
			'spip_mots AS mots INNER JOIN spip_mots_liens AS liens ON mots.id_mot = liens.id_mot',
			[
				'liens.objet = ' . sql_quote('rubrique'),
				'liens.id_objet = ' . (int) $id_rubrique,
				'mots.titre = ' . sql_quote('book'),
			]
		);
	}

	return $cache[$id_rubrique];
}
