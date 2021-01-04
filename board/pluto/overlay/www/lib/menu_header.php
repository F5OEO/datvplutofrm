    <?php
    
    function activ_menu($link) {
      
      if ($link == basename($_SERVER["SCRIPT_FILENAME"])) {
        echo 'class="nav-active"';
      }

    }
    activ_menu('tt');
    ?><header id="top">
      <div id="col1">
        &nbsp;
      </div>
      <div id="col2">
    <nav style="text-align: center;">
      <ul class="nav-menu nav-center">
        <li><a href="setup.php" <?php activ_menu('setup.php'); ?> >Setup</a></li>
        <li><a href="pluto.php" <?php activ_menu('pluto.php'); ?> >Controller</a>
          <!--<ul>
            <li><a href="#">Sub Nav Link</a></li>
            <li><a href="hello">Documentation</a>
              <ul>
                <li><a href="index.html">PlutoDVB documentation</a></li>
                <li><a href="https://wiki.batc.org.uk/QO-100_WB_Bandplan" target="_blank">QO-100 Wideband Bandplan</a></li>
              </ul>
            </li>
            <li><a href="#">Sub Nav Link</a></li>
            <li><a href="#">Sub Nav Link</a></li>
          </ul> -->
        </li>
        <li><a href="analysis.php" <?php activ_menu('analysis.php'); ?> >Analysis</a></li>
        <li><a href="index.html">Documentation</a>
          <ul>
            <li><a href="index.html">PlutoDVB documentation</a></li>
            <li><a href="https://wiki.batc.org.uk/QO-100_WB_Bandplan" target="_blank">QO-100 Wideband Bandplan</a></li>
          </ul>
        </li>
        <li><a href="credits.php" <?php activ_menu('credits.php'); ?>>Credits and updates</a>
          <ul>
            <li><a href="credits.php#f5oeo">F5OEO</a></li>
            <li><a href="credits.php#f5uii">F5UII</a></li>
          </ul>
        </li>
      </ul>
    </nav>
    <!--
    <nav style="text-align: center;">
      <a class="button" href="analysis.php" >Analysis</a> 

      <a class="button" href="index.html" >Documentation</a>
      <a class="button" href="https://wiki.batc.org.uk/QO-100_WB_Bandplan" target="_blank">QO-100 WB Bandplan</a>
    </nav>
  -->

  </div>
      <div id='col3'>   
      <div class="anchor">
        Firmware version : <?php
        $fwver = shell_exec ( 'cat /www/fwversion.txt' );
        echo "$fwver";
        ?>

        <!--<br/> 
        <a href="https://twitter.com/F5OEOEvariste/" title="Go to Tweeter">F5OEO: <img style="width: 32px;" src="./img/tw.png" alt="Twitter Logo"></a>
      -->
      </div>
    </div>
    
    
  </header>