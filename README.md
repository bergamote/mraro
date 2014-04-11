
#  mraro

### Work in progress

mraro (or mra for cool cats) is a CMS written in PHP. It uses (and comes with):

- [Markdown][1] (the [php port][2])
- [pagedown][3]
- [nativesortable][4]
    

  [1]: http://daringfireball.net/projects/markdown/
  [2]: http://michelf.ca/projects/php-markdown/
  [3]: https://code.google.com/p/pagedown/
  [4]: https://github.com/bgrins/nativesortable

### Install

To install, pick a folder on your server and clone the repo:

    cd /www/mysite-folder
    git clone https://github.com/bergamote/mraro.git .

Then go to the folder **mra/admin** and run the init script:

    cd mra/admin
    ./mra_init

This will create:

- the *content* folder with a couple of example pages
- the settings files (*mra.conf* and *menu.conf*)
- an empty cache directory (*mra/tmp*)

Finaly, make sure that the server's PHP has permission to write in *content* and *mra/tmp*.

mraro and it's dependencies are released under [the MIT License][http://opensource.org/licenses/MIT].
