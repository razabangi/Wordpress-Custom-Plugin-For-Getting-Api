# GIT-WP-Base
This project will create a base installation of Wordpress using the wp-cli/wp-cli package. This project also uses the vlucas/phpdotenv package to avoid committing various changes in the wp-config.php file.

## Installation
Begin by installing this package through Composer.

    composer create-project cooperative-computing/git-wp-base <destination_directory>

After composer process is finished, update the parameter values in `.env` file to be used in wp-config.php.

Open the site in your browser and proceed with the WP installation. 