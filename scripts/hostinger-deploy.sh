#!/usr/bin/env bash
# Runs ON the Hostinger server (streamed over SSH by .github/workflows/deploy-hostinger.yml).
# See docs/DEPLOY-HOSTINGER.md.
#
# Layout under $DEPLOY_PATH (outside public_html, so code and .env are never web-reachable):
#   releases/<release>/     one unpacked build per deploy (the last $KEEP_RELEASES are kept)
#   shared/.env             production settings, created once, kept across deploys
#   shared/storage/         logs, sessions, cache, uploaded photos (storage/app/media)
#   current -> releases/…   the live release (switched atomically)
#
# public_html keeps everything already in it (e.g. AndroidFiles/, AndroidDriver/, SendEmails/ used
# by the Android apps). The deploy only adds the site's public assets and an index.php that loads
# $DEPLOY_PATH/current. The original index.php and .htaccess are backed up on the first deploy.
set -euo pipefail

: "${DEPLOY_PATH:?DEPLOY_PATH is required}"
: "${PUBLIC_HTML:?PUBLIC_HTML is required}"
: "${ARCHIVE:?ARCHIVE is required}"
: "${RELEASE:?RELEASE is required}"
PHP_BIN="${PHP_BIN:-php}"
KEEP_RELEASES="${KEEP_RELEASES:-5}"
RUN_MIGRATIONS="${RUN_MIGRATIONS:-true}"
SEED_ACCOUNTS="${SEED_ACCOUNTS:-true}"

DEPLOY_PATH="${DEPLOY_PATH%/}"
PUBLIC_HTML="${PUBLIC_HTML%/}"
releases="$DEPLOY_PATH/releases"
shared="$DEPLOY_PATH/shared"
new="$releases/$RELEASE"

step() { printf '\n==> %s\n' "$*"; }
artisan() { "$PHP_BIN" artisan "$@"; }

step "Checking PHP ($PHP_BIN)"
"$PHP_BIN" -r 'exit(version_compare(PHP_VERSION, "8.3.0", "<") ? 1 : 0);' || {
    echo "PHP 8.3 or newer is required on the command line; found $("$PHP_BIN" -r 'echo PHP_VERSION;')."
    echo "Set the site to PHP 8.4 in hPanel and/or set the HOSTINGER_PHP variable, e.g. /opt/alt/php84/usr/bin/php."
    exit 1
}
"$PHP_BIN" -v | head -1

step "Preparing $DEPLOY_PATH"
mkdir -p "$releases" "$shared/storage/app/public" "$shared/storage/app/media" \
    "$shared/storage/framework/cache/data" "$shared/storage/framework/sessions" \
    "$shared/storage/framework/views" "$shared/storage/framework/testing" "$shared/storage/logs"
[ -d "$PUBLIC_HTML" ] || { echo "public_html not found: $PUBLIC_HTML"; exit 1; }

step "Unpacking release $RELEASE"
rm -rf "$new"
mkdir -p "$new"
tar -xzf "$ARCHIVE" -C "$new"
rm -f "$ARCHIVE"

if [ ! -f "$shared/.env" ]; then
    cp "$new/.env.example" "$shared/.env"
    chmod 600 "$shared/.env"
    rm -rf "$new"
    echo
    echo "First deploy: created $shared/.env from .env.example."
    echo "Edit it (APP_URL, APP_ENV=production, APP_DEBUG=false, DB_*, MAIL_*, ADMIN_*, DEVELOPER_*),"
    echo "then run the workflow again. See docs/DEPLOY-HOSTINGER.md."
    exit 1
fi

rm -rf "$new/storage"
ln -s "$shared/storage" "$new/storage"
ln -sfn "$shared/.env" "$new/.env"
mkdir -p "$new/bootstrap/cache"
cd "$new"

if ! grep -q '^APP_KEY=base64:' .env; then
    step "Generating APP_KEY (first deploy)"
    artisan key:generate --force
fi

if [ -L "$DEPLOY_PATH/current" ] && [ -f "$DEPLOY_PATH/current/artisan" ]; then
    step "Maintenance mode on the live release"
    (cd "$DEPLOY_PATH/current" && "$PHP_BIN" artisan down --retry=15) || true
fi
bring_up() { (cd "$DEPLOY_PATH/current" 2>/dev/null && "$PHP_BIN" artisan up) || true; }
trap 'echo "Deploy failed; the previous release stays live."; bring_up' ERR

if [ "$RUN_MIGRATIONS" = "true" ]; then
    step "Migrations (idempotent)"
    artisan migrate --force
fi
if [ "$SEED_ACCOUNTS" = "true" ]; then
    step "Administrator and developer accounts"
    artisan db:seed --class=AdminAccountsSeeder --force
fi

step "Caching config, routes, views and events"
artisan optimize:clear >/dev/null
artisan config:cache
artisan route:cache
artisan view:cache
artisan event:cache || true

step "Switching current -> $RELEASE"
ln -sfn "$new" "$DEPLOY_PATH/current.next"
mv -Tf "$DEPLOY_PATH/current.next" "$DEPLOY_PATH/current"

step "Publishing public files into $PUBLIC_HTML"
if [ ! -f "$PUBLIC_HTML/.panobooking-deploy" ]; then
    backup="$DEPLOY_PATH/public_html-backup-$(date +%Y%m%d%H%M%S)"
    mkdir -p "$backup"
    for f in index.php .htaccess; do [ -e "$PUBLIC_HTML/$f" ] && cp -a "$PUBLIC_HTML/$f" "$backup/"; done
    echo "Backed up the original index.php / .htaccess to $backup"
fi
copy_dir() { # copy_dir SRC DEST [--delete]
    if command -v rsync >/dev/null 2>&1; then rsync -a ${3:-} "$1/" "$2/"
    else [ "${3:-}" = "--delete" ] && rm -rf "$2"; mkdir -p "$2"; cp -a "$1/." "$2/"; fi
}
for item in "$new/public"/* "$new/public"/.[!.]*; do
    [ -e "$item" ] || continue
    name="$(basename "$item")"
    case "$name" in
        index.php|.htaccess|storage) ;;                                   # handled below
        build) copy_dir "$item" "$PUBLIC_HTML/build" --delete ;;          # hashed assets: mirror exactly
        *) if [ -d "$item" ]; then copy_dir "$item" "$PUBLIC_HTML/$name"; else cp -a "$item" "$PUBLIC_HTML/"; fi ;;
    esac
done
cp "$new/public/.htaccess" "$PUBLIC_HTML/.htaccess"
cat > "$PUBLIC_HTML/index.php" <<PHP
<?php
// Written by scripts/hostinger-deploy.sh: loads the live Panobooking release.
require '$DEPLOY_PATH/current/public/index.php';
PHP
ln -sfn "$shared/storage/app/public" "$PUBLIC_HTML/storage"
date -u +%FT%TZ > "$PUBLIC_HTML/.panobooking-deploy"

trap - ERR
step "Back online"
artisan up || true

step "Removing old releases (keeping $KEEP_RELEASES)"
ls -1dt "$releases"/*/ 2>/dev/null | tail -n +"$((KEEP_RELEASES + 1))" | while read -r old; do rm -rf "$old"; echo "removed $old"; done

echo
echo "Deployed $RELEASE"
