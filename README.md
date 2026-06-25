# CCN Book

Plugin SPIP qui transforme automatiquement une rubrique publique en livre feuilletable lorsque la rubrique porte le mot-cle `book`.

## Fonctionnement

- Le pipeline `styliser` remplace le squelette de la page rubrique par `squelettes/rubrique-ccn-book.html` uniquement pour les rubriques marquees `book`.
- Chaque article publie de la rubrique devient une page du livre.
- Les pages sont triees par `num titre`, puis par date.
- La navigation fonctionne avec les boutons `Precedent` / `Suivant` et les fleches gauche / droite du clavier.
- La bibliotheque `turn.js` est embarquee localement dans `lib/turnjs/`.

## Installation

Copier ou symlinker le dossier `ccn-book` dans le repertoire `plugins/` du site SPIP, activer le plugin dans l'espace prive, puis associer le mot-cle `book` aux rubriques qui doivent etre affichees comme livres.

## Compatibilite

Le plugin cible SPIP 4.x et 5.x. Il ne modifie pas les autres rubriques du site.
