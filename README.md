Description
===========
CinemaCashier: REST-service for booking cinema tickets.
Based on Silex, Doctrine, SQLite, phpUnit.

Installation
============
The easiest way to try it out is to use Cloud9 service: all necessary environment
is pre-configured - everything is ready to go.
So if you got an email invitation to C9 CinemaCashier project - you're lucky,
because you don't need to mess up with all of the installation instructions below.
If you didn't - please don't be upset, just follow these straight-forward steps:
1) Download this repository to your local machine and setup it as a site to your
server.
  Apache or built-in PHP server are welome.
2) Download Composer.
  Use getcomposer.org as a reference.
3) Run 'composer install' (or 'php composer.phar install' - it depends on how
  Composer is installed) at root of the folder, containing the project.
  It will download all necessary libraries.
4) (optional as pre-filled database is included, required for re-testing)
  Run './bin/doctrine orm:schema-tool:update --force'.
  It will create SQLite database schema.
5) (optional as pre-filled database is included, required for re-testing)
  Make your server to handle 'default_content.php' file.
  Just make sure your server is running - and open the file using the corresponding
  site URL.
  It will fill the database in with default content.
  Expected result: you see "Default content successfully created!" message.
6) Open the home page of the site.
  It should display the list of all available API calls.
7) Click on the link - and make sure it works.
  You could also check how the application handles any variations of the request:
  - Existing id vs non-existing id.
  - Optional parameter "hall" present vs absent.
  - Optional parameter "hall" existing vs non-existing id.

Explaining decisions
====================
Silex - easy to start though quite powerful; switching to Symfony2 is simple.
Doctrine - helps to avoid DB-related stuff; making DB-agnostic, which means
  simple switching to any DB.
SQLite - no installation (bundled with PHP since PHP 5.0); no migration (due to
  Doctrine).

Features & Limitations
======================
- phpUnit testing is done on the same database (no re-creation during testing).
  It is necessary to manually re-create tables and fill them in before each test.
  Run './bin/doctrine' to get help of how to manipulate the database schema.
  Otherwise each test will add new portion of default content.
- Tests cover all of the basic functionality needed - as TDD requires.
  Though implemented (and passed tests) only the first.
- There are plenty of growing points to make the application even better.
  Search for "@todo" to find them: https://github.com/PatchRanger/cinema_cashier/search?q="%40todo"