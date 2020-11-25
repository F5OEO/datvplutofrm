  <?php 
  echo "<b>Release notes</b> (<a href='https://github.com/F5OEO/datvplutofrm/commits/master' target='_blank'>GitHub commits</a>)<br/>"; 

  echo nl2br(shell_exec ( 'cat /www/releasenote.txt' )); 
  
  ?>
