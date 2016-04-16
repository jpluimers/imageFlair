# imageFlair
StackOverflow imageFlair based on http://www.grumpydev.com/2009/07/11/stack-overflow-share-your-flair-now-in-png/

Uses the [StackOverflow JSON flair feed](http://web.archive.org/web/20100218040243/http://stackoverflow.com/users/flair) to create images using PHP.

Even though [the JSON interface to flair is now deprecated](http://meta.stackexchange.com/questions/15054/flair-json-returns-invalid-img-tag-in-gravatarhtml/104399#104399), I like these images better on hi-DPI screens than the StackOverflow ones.

## Dependencies

- [PHP-GD](https://en.wikipedia.org/wiki/GD_Graphics_Library) with package names like `php-gd` or `php5-gd`
- A truetype font (by default `Arial.TTF` from the [Microsoft Core Fonts for the Web](https://www.microsoft.com/typography/fonts/web.aspx)) now available from [corefonts at sourceforge](corefonts.sourceforge.net) or through scripts like [fetchmsttfonts](https://www.google.com/search?q=fetchmsttfonts)
- Apache configured to use `mod_rewrite` and `mod_expire` in `.htaccess`
