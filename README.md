Welcome to the Pop PHP Framework 1.0.3 Release!
===============================================

RELEASE INFORMATION
-------------------
Pop PHP Framework 1.0.3 Release
Released November 21, 2012


OVERVIEW
--------

The Pop PHP Framework is a robust, yet easy-to-use PHP framework
with a verbose API. It was forked from the Moc10 PHP Library, which
is now at its end of life. With the adoption of PHP 5.3 gaining, the
decision was made to build the next version of the library as a
full-fledged framework that supports only PHP 5.3+ moving forward.

The beginnings of this framework were humble. Originally containing only
9 components, the focus was placed in simplicity and being lightweight.
It attempted to provide solutions in areas such as graphics and images,
which were found lacking (or completely ignored) in other frameworks
and libraries.

Today, the Pop PHP Framework maintains its simplicity and is still
lightweight. And, even though many new features have been built in,
the framework can still easily be used as merely a toolbox, or as a
major framework for the foundation of your applications.

To see a list of the new and vastly improved features in the framework,
view the CHANGELOG.md file.

FEATURES
--------

The Pop PHP Framework is an object-oriented PHP framework with an
easy-to-use API to access and utilize the following components:

* Archive
* Auth
* Cache
* Cli
* Code
* Color
* Compress
* Config
* Curl
* Data
* Db
* Dom
* Feed
* File
* Filter
* Font
* Form
* Ftp
* Geo
* Graph
* Http
* Image
* Loader
* Locale
* Mail
* Mvc
* Paginator
* Payment
* Pdf
* Project
* Record
* Validator
* Version
* Web


INSTALLATION
------------

Please see INSTALL.TXT.


SYSTEM REQUIREMENTS
-------------------

The Pop PHP Framework requires PHP 5.3.0 or later.

Some dependencies for the framework to fully function are as follows:

* The Phar, Rar, Tar and Zip extensions for the archive component
* The Bzip2, Lzf and ZLib extenstions for the compress component
* The Mcrypt extension for the crypt filter component
* The basic MySQL extension for basic MySQL database connections and transactions
* The MySQLi extension to utilize MySQLi to connect to and interact with MySQL databases
* The PostgreSQL extension for PostgreSQL database connections and transactions
* The SQLite3 extension for SQLite database connections and transactions
* The SqlSrv extension for MSSQL database connections and transactions
* The OCI8 extension for Oracle database connections and transactions
* The PDO extension for utilize PDO for database connections and transactions
* The GeoIP extension to utilize the Geo component
* The GD library for image manipulation (including FreeType support)
* The Imagick extension (with ImageMagick & Ghostscript) for advanced image manipulation
* The Memcache extension for the Cache component (using the Memcached adapter)
* The DOMDocument extension for the Svg component
* The SimpleXML extension for the Feed, Data, Locale and Svg components
* The PHP mail function and SMTP server correctly set for the mail component
* The cURL extension for the Curl component
* FTP support enabled for the FTP component

Most of these extensions are generally included in PHP 5.3.0+, but should there be
any issues in any of these areas, please verify that the related extensions are
installed and configured properly. PHP's mail function is dependant on the whichever
mail program is available and correctly installed on the server.

A Note on ImageMagick: As of July 28th, 2011, stable testing was successful with the
following versions of the required software:

* ImageMagick 6.5.*
* Ghostscript 8.70 or 8.71
* Imagick PHP Extension 3.0.1

Any variation in the versions of the required software may contribute to the
Pop\Image\Imagick component not functioning properly.

A Note on Permissions: The following classes may require the correct permissions
to be set for the files and the directories that they access in order to work
properly. If the permissions are not set correctly, an exception or error could
be thrown within any of the following components:

* Pop\File\File
    - Pop\Archive\Archive
    - Pop\Code\Generator
    - Pop\Font\Font
    - Pop\Image\Gd
    - Pop\Image\Imagick
    - Pop\Image\Svg
    - Pop\Pdf\Pdf


QUESTIONS AND FEEDBACK
----------------------

An online overview and documentation can be found at
http://www.popphp.org/

The Pop PHP Framework is available for anonymous checkout via
GitHub at https://github.com/nicksagona/PopPHP

Further contact or comments can be emailed to info@popphp.org.


LICENSE
-------

The files in this archive are released under the Pop PHP Framework license.
You can find a copy of this license in LICENSE.TXT.
