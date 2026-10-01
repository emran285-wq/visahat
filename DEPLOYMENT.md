# Visa Hat Deployment

## Local setup

The site needs PHP 8.0 or newer and Apache with `mod_rewrite` enabled. From the repository root, start a local server with:

```powershell
php -S 127.0.0.1:8080 router.php
```

Copy `.env.example` to `.env` for local settings. The loader in `config.php` reads simple `NAME=value` entries; PHP does not read `.env` files automatically. Keep `.env` out of Git. The current `FORM_MODE=demo` only previews a request in the browser. `disabled` prevents that preview. No live form delivery is implemented.

Check `/`, `/visa`, each `/visa/<service-slug>`, `/search?q=work`, `/privacy`, `/sitemap.xml`, and an unknown URL. Also confirm `/index.php`, `/visa.php`, `/search.php`, `/privacy.php`, and `/sitemap.php` redirect to their clean public URL when Apache is serving the site.

## GitHub workflow

This directory is not currently an initialized Git repository. Initialize and connect it only after reviewing tracked files:

```powershell
git init
git add .
git status
git commit -m "Initial Visa Hat site"
git branch -M main
git remote add origin https://github.com/ACCOUNT/REPOSITORY.git
git push -u origin main
```

For later updates, verify changes, commit, and push. `.gitignore` prevents new ignored secrets and runtime files from being added; it cannot remove secrets that were already committed or erase Git history.

## cPanel layout and deployment

Recommended layout: clone the repository outside the web root, such as `/home/CPANEL_USER/repositories/visahat`, and deploy selected files to the domain document root, commonly `/home/CPANEL_USER/public_html` or an addon-domain path. A Git pull into a repository outside the document root does not deploy the website by itself.

1. In cPanel **Git Version Control**, clone the GitHub repository into a non-public repository path.
2. Copy `.env.example` to a private `.env` outside `public_html` when possible. If `.env` must live beside the application, `.htaccess` denies direct access. Set `SITE_URL=https://your-domain.example`, `FORM_MODE=disabled` or `demo`, and only real integration values approved for use.
3. Set the actual document-root path in `.cpanel.yml` by replacing `/home/CHANGE_ME/public_html`. The manifest intentionally fails until that placeholder is changed.
4. Use cPanel's **Update from Remote** and then **Deploy HEAD Commit**. The manifest copies only the public application files, assets, templates, and service data. It does not copy `.env`, `.git`, deployment documents, logs, uploads, cache, or storage directories. It never deletes destination files, resets a database, or clears runtime data.
5. Ensure directories that hold server-generated runtime data are owned by the hosting account and writable only where the eventual integration requires it. Do not make the full document root writable.

If the repository itself must be the document root, omit cPanel deployment and point the domain directly to that repository directory only if the host permits it. Keep `.env`, uploads, logs, and stored submissions outside that document root, or protect them with server rules.

## Dependencies and verification

This project has no Composer or Node dependencies and no production build step. If dependencies are added later, commit `composer.json` and `composer.lock`, run `composer install --no-dev --optimize-autoloader` outside the public root where available, or upload a reviewed production `vendor/` package only when Composer is unavailable.

After deployment, browse the routes above over HTTPS, inspect browser console errors, test the mobile menu, verify the canonical URLs use the production domain, and confirm the sitemap URL in `robots.txt` resolves. Purge any host/CDN cache after the visual check.

Before every update, take a cPanel backup of files and any future database. To roll back, select and deploy the last known good Git commit in cPanel or restore that backup; do not use destructive cleanup commands against the document root.
