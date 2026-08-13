# Maintenance Switch

Plugin WordPress publié sur le répertoire officiel (wordpress.org/plugins/maintenance-switch). Bascule en un clic le site en mode maintenance natif, depuis le backend ou l'admin bar.

## Environnement de développement

L'environnement Docker/WP-CLI et les scripts de release (bump, sync SVN, scan sécurité, PHPCS) vivent dans le repo séparé `/Users/vincent/Sites/wordpress/wordpress-dev`, qui monte ce répertoire en volume. Toutes les commandes `make ...` se lancent depuis `wordpress-dev`, pas ici. Voir son `CLAUDE.md` pour le détail du toolkit et du pipeline de release.

## Structure

- `maintenance-switch.php` — bootstrap du plugin, `Version:` header + constante `PLUGIN_VERSION`
- `includes/` — cœur du plugin, pattern boilerplate classique : `class-maintenance-switch.php` (orchestrateur), `class-maintenance-switch-loader.php` (hooks), `class-maintenance-switch-i18n.php`, `class-maintenance-switch-activator.php` / `-deactivator.php`, `config.php`
- `admin/` — écrans et assets d'administration (`css/`, `js/`, `views/`)
- `public/` — comportement front-end (`css/`, `js/`, `views/`)
- `templates/` — templates de page de maintenance
- `languages/` — fichiers i18n (text-domain `maintenance-switch`)

## Conventions de code

- Préfixe des fonctions globales : `maintenance_switch_` (ancien préfixe `ms_` abandonné, cf. historique de refactor).
- Respecter `phpcs.xml` : `WordPress-Extra` + `WordPress-Docs`, `testVersion` PHP 8.1-, text-domain `maintenance-switch`. Lancer via `make lint` (PHPCS) / `make fix` (PHPCBF) depuis `wordpress-dev`.
- Garde `WPINC`/`ABSPATH` en tête de chaque fichier PHP exécutable.
- Toute action AJAX : nonce (`wp_create_nonce` / `wp_verify_nonce`) + vérification de capacité (`current_user_can`).
- Sanitisation systématique des entrées (`sanitize_*`) et échappement en sortie (`esc_*`).
- Ces patterns sont aussi ce que vérifie le scan heuristique `make scan` côté wordpress-dev — un score en baisse signale souvent un oubli de nonce/sanitisation/capability check.

## Versioning et releases

- La version se pilote depuis `wordpress-dev` (`make bump`), qui met à jour ensemble `Version:` + `PLUGIN_VERSION` dans `maintenance-switch.php` et `Stable tag` dans `readme.txt` — ne jamais les modifier manuellement de façon désynchronisée.
- Tags Git : `vX.Y.Z` (avec préfixe `v`). Côté SVN WordPress.org, le tag est créé sans le `v` (conversion faite par `sync-github-svn.sh`).
- `readme.txt` : `Tested up to` doit être tenu à jour à chaque sortie majeure de WordPress — voir le skill `/wp-compat-check` du repo wordpress-dev.

## Convention de commit

Format observé dans l'historique : `Type: description courte`. Types utilisés : `Security:`, `Fix:`, `Feature:`, `Refactor:`, `Docs:`, `Chore:`, `UX:`, `DevTools:`.
