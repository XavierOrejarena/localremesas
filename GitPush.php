<?php
system ( 'cd '.ABSPATH.'; git add -A;' );
system ( 'cd '.ABSPATH.'; git commit -a -m "GitPush.php;";' );
system ( 'cd '.ABSPATH.'; git push localremesas master;' );