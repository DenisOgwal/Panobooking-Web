# Deploying to Hostinger (web / cloud hosting)

Deploys run from GitHub: **Actions → Deploy to Hostinger → Run workflow**. They are manual only.

Each run:
1. runs the test suite (optional)
2. builds the app on GitHub (Composer packages, front-end assets)
3. uploads it over SSH
4. runs the migrations and creates the administrator and developer accounts
5. switches the site to the new version

On the server it uses `.github/workflows/deploy-hostinger.yml` and `scripts/hostinger-deploy.sh`.

## How the files are laid out

```
/home/u123456789/domains/example.com/
├── public_html/              the website root: existing files stay (AndroidFiles/, AndroidDriver/, SendEmails/ …)
│   ├── index.php             written by the deploy: loads panobooking/current
│   ├── .htaccess, build/, images/ …   the site's public files, copied on each deploy
│   └── storage -> ../panobooking/shared/storage/app/public
└── panobooking/              outside public_html: code and settings are never reachable from the web
    ├── current -> releases/20261127193000-5be1f78
    ├── releases/             the last 5 deploys (instant rollback)
    ├── shared/.env           production settings, kept across deploys
    ├── shared/storage/       logs, sessions, cache, uploaded photos (storage/app/media)
    └── public_html-backup-…  the original index.php / .htaccess, saved on the first deploy
```

The deploy never deletes anything in `public_html` except the `build/` folder, which holds the compiled assets. Folders the Android apps use stay where they are.

## One-time setup

### 1. Hostinger (hPanel)

1. **PHP version:** Websites → your site → Advanced → PHP Configuration → **PHP 8.4**, or 8.3 at minimum. Enable the `pdo_mysql`, `mbstring`, `gd`, `zip`, `exif`, `bcmath` and `intl` extensions; most are on by default.
2. **Database:** Databases → MySQL Databases. Create a database and a user, or use the existing Panobooking database. Note the name, user and password; the host is `localhost`.
3. **SSH:** Advanced → SSH Access → enable it. Note the IP, port (usually `65002`) and username (`u123456789`).
4. **Deploy key:** on your computer, create a key used only for deploys:
   ```bash
   ssh-keygen -t ed25519 -C "github-deploy" -f hostinger_deploy -N ""
   ```
   In hPanel → SSH Access → SSH keys, add the contents of `hostinger_deploy.pub`.
5. **Paths:** connect once (`ssh -p 65002 u123456789@IP`) and run `ls ~/domains`. Your site root is usually `/home/u123456789/domains/example.com/public_html`.
6. **PHP on the command line:** run `php -v` there. If it shows an older PHP than the site uses, find the 8.4 binary (often `/opt/alt/php84/usr/bin/php`, or run `ls /opt/alt/`) and set it as `HOSTINGER_PHP` below.

### 2. GitHub

Repository → Settings → **Environments** → New environment **`production`**. You can add yourself under "Required reviewers" so each deploy needs your approval.

In that environment, add these **secrets**:

| Secret | Value |
|--------|-------|
| `HOSTINGER_SSH_HOST` | the SSH IP from hPanel |
| `HOSTINGER_SSH_PORT` | `65002`, or your port |
| `HOSTINGER_SSH_USER` | `u123456789` |
| `HOSTINGER_SSH_KEY` | the whole private key file `hostinger_deploy`, including the BEGIN/END lines |
| `HOSTINGER_SSH_KNOWN_HOSTS` | recommended: the output of `ssh-keyscan -p 65002 IP`. Without it the workflow trusts whatever host key it sees and shows a warning. |

And these **variables**:

| Variable | Example |
|----------|---------|
| `HOSTINGER_DEPLOY_PATH` | `/home/u123456789/domains/example.com/panobooking` |
| `HOSTINGER_PUBLIC_HTML` | `/home/u123456789/domains/example.com/public_html` |
| `HOSTINGER_PHP` | optional: `/opt/alt/php84/usr/bin/php` |

After that, delete the private key from your computer, or keep it somewhere safe.

### 3. First run: create the production `.env`

1. Run the workflow once. It creates `panobooking/shared/.env` from `.env.example` and stops, telling you to edit it.
2. Edit that file on the server, using hPanel → File Manager or `nano` over SSH. At least set:
   ```dotenv
   APP_NAME=Panobooking
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://example.com

   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=u123456789_panobooking
   DB_USERNAME=u123456789_panobooking
   DB_PASSWORD=…

   SESSION_DRIVER=database
   CACHE_STORE=file

   MAIL_MAILER=smtp            # Hostinger email: smtp.hostinger.com, port 465, MAIL_ENCRYPTION=ssl
   MAIL_HOST=smtp.hostinger.com
   MAIL_PORT=465
   MAIL_ENCRYPTION=ssl
   MAIL_USERNAME=no-reply@example.com
   MAIL_PASSWORD=…
   MAIL_FROM_ADDRESS=no-reply@example.com
   MAIL_FROM_NAME=Panobooking

   ADMIN_EMAIL=you@example.com          # created on deploy if missing; strong passwords
   ADMIN_PASSWORD=…
   DEVELOPER_EMAIL=dev@example.com
   DEVELOPER_PASSWORD=…

   PANOBOOKING_MEDIA_URL=https://example.com   # where AndroidFiles/… photos are served from
   PROVIDER_NOTIFICATIONS=true                 # call the SendEmails/*.php scripts after bookings
   ```
   `APP_KEY` is generated on the first real deploy.
3. Run the workflow again. That's the real deploy.

## Deploying

**Actions → Deploy to Hostinger → Run workflow**. The options:

| Option | Meaning |
|--------|---------|
| `ref` | what to deploy: a branch, tag or commit (default `master`) |
| `run_tests` | run the test suite first; the deploy stops if a test fails |
| `run_migrations` | run the database migrations. Safe to repeat: existing tables, columns and indexes are skipped. |
| `seed_accounts` | create the administrator and developer accounts if they don't exist. Existing passwords are never reset. |

While the migrations run, the site briefly shows the maintenance page. If any step fails, the previous version stays live.

## Rolling back

```bash
ssh -p 65002 u123456789@IP
cd ~/domains/example.com/panobooking
ls releases                                   # pick the previous one
ln -sfn "$PWD/releases/<previous>" current.next && mv -Tf current.next current
cd current && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

Database changes are not undone by a rollback. The migrations only ever add things, so an older release still works with the newer database.

## Restoring the old site

The previous `index.php` and `.htaccess` are in `panobooking/public_html-backup-<date>/`. Copy them back into `public_html` to undo the switch. The old code itself is preserved on the `legacy` branch.
