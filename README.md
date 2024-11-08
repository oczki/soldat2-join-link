> [!NOTE]
> As [Soldat 2 has officially reached end of development](https://store.steampowered.com/news/app/474220/view/4656249009454303927?l=english), this repository is archived and will no longer be maintained.

# Soldat 2 link service

A redirect service that opens Soldat 2 on Steam and joins a server.

Normally you'd use `steam://` links, but that isn't supported everywhere as a clickable hyperlink (e.g. Discord).

Since this service does exactly that &ndash; redirect to a `steam://` URL &ndash; it has the same limitations this Steam URL has.  
So it won't work if Soldat 2 is already open. The game needs to be closed for the link to work.

# Installation

Run `index.php` on a server that supports PHP.

Tested on PHP 7.2.34.

# Usage

Visit `index.php?destination=something`, where `something` is:

| Type             | Description                          | Example                                  |
|------------------|--------------------------------------|------------------------------------------|
| IPv4             | IPv4 address and port of the server. | `index.php?destination=12.34.56.78:9999` |
| Hex-encoded IPv4 | Hex-encoded IPv4 address and hex-encoded port.<br><br>Each part of the IP is encoded separately and has exactly two characters.<br>So 8 characters for the IP.<br><br>Port is hex-encoded too, and appended at the end, without any separator.<br>It has 1 to 4 characters.<br><br>Total of 9-12 characters. | `index.php?destination=0c22384e270f` |
| Ranked queue     | Region and playlist name of the queue.<br><br>Use a hyphen (`-`) between the region and the playlist. | `index.php?destination=EU-CTF-Standard-6` |
| IPv6             | Soldat 2 doesn't support IPv6 join links. | N/A |

For neater formatting, you could set up your `.htaccess` like this:

`RewriteRule ^s2-link/(.*)$ s2-link/index.php?destination=$1 [QSA]`

This is how https://oczki.pl/s2-link/ is set up.

# License

MIT. See the LICENSE file.
