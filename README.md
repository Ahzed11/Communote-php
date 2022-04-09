<a href="https://www.digitalocean.com/?refcode=78f0fe66f9b0&utm_campaign=Referral_Invite&utm_medium=Referral_Program&utm_source=badge"><img src="https://web-platforms.sfo2.cdn.digitaloceanspaces.com/WWW/Badge%201.svg" alt="DigitalOcean Referral Badge" /></a><br/>

# Communote
Communoté, a platform to share notes between college students.

## Run it locally

### Prerequisite
- [PHP 8.0^](https://www.php.net/)
- [Composer](https://getcomposer.org/)
- [Node](https://nodejs.org/en/)
- [Yarn](https://yarnpkg.com/)
- [Postgres](https://www.postgresql.org/)
- [Symfony CLI](https://symfony.com/download)
- An Amazon S3 Bucket

### Installation
1. `git clone git@github.com:Ahzed11/Communote.git && cd Communote`
2. `composer install && yarn`
3. Fill the required parameters in the .env file. This will require an Amazon S3 Bucket and an Azure app id. If you don't want to create an app in Azure,
 you can still change the way of authenticating in security.yaml and re-enable the needed routes/pages.
4. `php bin/console doctrine:databse:create && php bin/console doctrine:schema:create`
5. `symfony server:start` in one terminal.
6. `yarn encore dev --watch` on an other.

## Bug Report
Do not hesitate to open an issue if you discover a bug. However, if it is security related, please do not open an issue and send me an email.
