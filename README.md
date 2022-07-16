#### Installation
- Install MySQL:
  - For Ubuntu: 
  
    `https://dev.mysql.com/doc/mysql-apt-repo-quick-guide/en/`
  
  - For Windows:
  
    `https://dev.mysql.com/doc/refman/8.0/en/windows-installation.html`

  - For MacOS:
  
    `https://dev.mysql.com/doc/refman/8.0/en/macos-installation.html`

- Clone the repository using HTTPS:

  `git clone https://github.com/tigrisko98/precoro-test-task.git`

- Switch to the project's directory:

  `cd precoro-test-task`

- Install dependencies:

  `composer install`

#### Configuration
- Create .env file:
 
  `cp .env.test .env`

- Change db_user and db_password in .env file on your current mysql user and password

- Create database:

  `php bin/console doctrine:database:create`

- Execute migrations:

  `php bin/console doctrine:migrations:migrate`


