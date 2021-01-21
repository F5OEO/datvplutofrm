<?php
// when patch is not included in firmware, the config file is saved /mnt/jffs2/
// restore a config file from /mnt/jffs2/ to /www/

if (!file_exists('/mnt/jffs2/etc/settings-receiver.txt')){
    copy('/mnt/jffs2/etc/settings-receiver.txt', '/www/settings-receiver.txt');
}


?>