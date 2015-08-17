To use the PHP-CLI version of this plugin, you need php5-cli and php5-curl.
It's recommended to run this script every hour. Don't forget to apt-update first.

Example Crontab line:

    0 * * * * apt-get update && /usr/bin/php /home/user/apt-check-git/php/apt-check.php -k YOUR_NEW_RELIC_KEY >/dev/null 2>&1
