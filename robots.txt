User-agent: *

# Directories
Disallow: /app/
Disallow: /bin/
Disallow: /dev/
Disallow: /lib/
Disallow: /phpserver/
Disallow: /pkginfo/
Disallow: /report/
Disallow: /setup/
Disallow: /update/
Disallow: /var/
Disallow: /vendor/

# Paths (clean URLs)
Disallow: /index.php/
Disallow: /catalog/product_compare/
Disallow: /catalog/category/view/
Disallow: /catalog/product/view/
Disallow: /catalogsearch/
Disallow: /checkout/
Disallow: /control/
Disallow: /contacts/
Disallow: /customer/
Disallow: /customize/
Disallow: /newsletter/
Disallow: /review/
Disallow: /sendfriend/
Disallow: /wishlist/

# Files
Disallow: /composer.json
Disallow: /composer.lock
Disallow: /CONTRIBUTING.md
Disallow: /CONTRIBUTOR_LICENSE_AGREEMENT.html
Disallow: /COPYING.txt
Disallow: /Gruntfile.js
Disallow: /LICENSE.txt
Disallow: /LICENSE_AFL.txt
Disallow: /nginx.conf.sample
Disallow: /package.json
Disallow: /php.ini.sample
Disallow: /RELEASE_NOTES.txt

# Do not index pages that are sorted or filtered.
Disallow: /*?*product_list_mode=
Disallow: /*?*product_list_order=
Disallow: /*?*product_list_limit=
Disallow: /*?*product_list_dir=

# Do not index session ID
Disallow: /*?SID=
Disallow: /*?
Disallow: /*.php$

# CVS, SVN directory and dump files
Disallow: /*.CVS
Disallow: /*.Zip$
Disallow: /*.Svn$
Disallow: /*.Idea$
Disallow: /*.Sql$
Disallow: /*.Tgz$
