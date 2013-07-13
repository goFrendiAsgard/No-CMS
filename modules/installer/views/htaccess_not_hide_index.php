<IfModule !mod_rewrite.c>
    # If we don't have mod_rewrite installed, all 404's
    # can be sent to index.php, and everything works as normal.
    # Submitted by: ElliotHaughin

    ErrorDocument 404 /index.php
</IfModule>

# These doesn't always work
#<IfModule mod_php5.c>
#   php_value output_handler none
#   php_flag register_globals off
#   php_flag safe_mode off
#</IfModule>
#<IfModule mod_php4.c>
#   php_value output_handler none
#   php_flag register_globals off
#   php_flag safe_mode off
#</IfModule>

<IfModule mod_expires.c>
    ExpiresActive on

    # Perhaps better to whitelist expires rules? Perhaps.
    ExpiresDefault                          "access plus 1 month"

    # cache.appcache needs re-requests in FF 3.6 (thx Remy ~Introducing HTML5)
    ExpiresByType text/cache-manifest       "access plus 0 seconds"

    # your document html
    ExpiresByType text/html                 "access plus 0 seconds"

    # data
    ExpiresByType text/xml                  "access plus 0 seconds"
    ExpiresByType application/xml           "access plus 0 seconds"
    ExpiresByType application/json          "access plus 0 seconds"

    # rss feed
    ExpiresByType application/rss+xml       "access plus 1 hour"

    # favicon (cannot be renamed)
    ExpiresByType image/x-icon              "access plus 1 week"

    # media: images, video, audio
    ExpiresByType image/gif                 "access plus 1 month"
    ExpiresByType image/png                 "access plus 1 month"
    ExpiresByType image/jpg                 "access plus 1 month"
    ExpiresByType image/jpeg                "access plus 1 month"
    ExpiresByType video/ogg                 "access plus 1 month"
    ExpiresByType audio/ogg                 "access plus 1 month"
    ExpiresByType video/mp4                 "access plus 1 month"
    ExpiresByType video/webm                "access plus 1 month"

    # htc files  (css3pie)
    ExpiresByType text/x-component          "access plus 1 month"

    # webfonts
    ExpiresByType font/truetype             "access plus 1 month"
    ExpiresByType font/opentype             "access plus 1 month"
    ExpiresByType application/x-font-woff   "access plus 1 month"
    ExpiresByType image/svg+xml             "access plus 1 month"
    ExpiresByType application/vnd.ms-fontobject "access plus 1 month"

    # css and javascript
    ExpiresByType text/css                  "access plus 2 months"
    ExpiresByType application/javascript    "access plus 2 months"
    ExpiresByType text/javascript           "access plus 2 months"

</IfModule>

<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/xhtml text/html text/plain text/xml text/javascript application/x-javascript text/css
</IfModule>