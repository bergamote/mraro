Welcome to mrAro
================

**mrAro** is a website muncher for Linux. It feeds on Markdown text files, digest them through PHP templates and "produces" steaming hot, fully formed webpages.  
**mrAro** includes a drag-and-drop menu manager, a text editor with markdown live preview and a simple caching system.


Install
-------

To install, pick a folder on your server and clone the repo from GitHub:

    cd /www/mysite-folder
    git clone https://github.com/bergamote/mraro.git .

Then go to the folder **mra/admin** and run the init script:

    cd mra/admin
    ./mra_init

This will create:

- the *content* folder with a couple of example pages
- the settings files (*mra.conf* and *menu.conf*)
- an empty cache directory (*mra/tmp*)

Make sure that the server's PHP has permission to write in *content* and *mra/tmp*.

### Credentials

Edit the hidden file **mra/.creds**, keeping the first line for your username and the second one for your password. By default it looks like this:

    user
    pass


### Important

You must tell your server to deny access to hidden files from the outside (or at least to the _.creds_ file). To do that, follow the instructions [here](http://www.unixpearls.com/nginx-how-to-deny-dot-file-requests/).

Editing the theme
-----------------

The template and CSS are located in the **mra/theme/default** folder. To edit the theme, first make a copy of the _default_ folder.

    cp mra/theme/default mra/theme/my-theme

Then in **mra/mra.conf**, edit the _theme_ line replacing _default_ by the name of your new theme (here _my-theme_).

## Dependencies

**mrAro** comes packaged with the following programs:

- The [PHP port][2] of [Markdown][1]
- [pagedown][3] (minified)
- [nativesortable][4] (minified)
    

  [1]: http://daringfireball.net/projects/markdown/
  [2]: http://michelf.ca/projects/php-markdown/
  [3]: https://code.google.com/p/pagedown/
  [4]: https://github.com/bgrins/nativesortable

**mrAro** and its dependencies, as far as I can tell, are released under [the MIT License](http://opensource.org/licenses/MIT).            

                      
