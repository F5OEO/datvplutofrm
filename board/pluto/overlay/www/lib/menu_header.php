    <?php
      require_once ('./lib/functions.php');
    function activ_menu($link) {
      
      if ($link == basename($_SERVER["SCRIPT_FILENAME"])) {
        echo 'class="nav-active"';
      }

    }
    activ_menu('tt');
    ?><header id="top">
      <div id="col1">
        <?php  if ((isset($datv_config['mainmode']))&& $datv_config['mainmode']==='datv')  {?>
        <div  style="margin-left: 15px; font-family: Arial Narrow, sans-serif;">
          Buffer : <span id='bufferstatus'></span>
        </div>
      <?php  } ?>
        <!--Selector-->
      </div>
      <div id="col2">
    <nav style="text-align: center;">
      <ul class="nav-menu nav-center">
        <li><a href="setup.php" <?php activ_menu('setup.php'); activ_menu('maintenance.php'); activ_menu('status.php'); ?> >System</a>
          <ul>
            <li><a href="setup.php"  >Setup</a><ul>
              <li><a href="setup.php#linkdatvmode" >Mode settings</a></li>
              <li><a href="setup.php#linkdatvsettings" >Transmiter settings</a></li>
              <li><a href="setup.php#linkreceiversettings" >Receiver settings</a></li>
              <li><a href="setup.php#linkplutosettings" >Pluto settings</a></li>
              </ul></li>
            <!--<li><a href="textgen.php">Text generator</a></li>-->
            <li><a href="status.php">Pluto status</a></li>
            <li><a href="maintenance.php">Maintenance</a></li>
          </ul>
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
        <li><a href="doc.php" <?php activ_menu('doc.php'); ?> >Documentation</a></li>
        <li><a href="credits.php" <?php activ_menu('credits.php'); ?>>Credits</a>
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
      <div class="anchor" style="margin-right: 15px;">version : <?php
        $fwver = shell_exec ( 'cat /www/fwversion.txt' );
        echo "$fwver";
        ?></div>
    </div>
    
    
  </header>
  <?php //var_dump($general_ini);
  if ((isset ($general_ini[1]['DATV']['menu_fixed'])) && ($general_ini[1]['DATV']['menu_fixed']=='on')) {
    
      echo '<script>menu_fixed=true;</script>';
    }
   else  {
    echo '<script>menu_fixed=false;</script>';
   }

   ?>
  
  <script>
    if (menu_fixed==true) {

      $('#top').addClass('fixed shadow');
      $('body').css('padding-top','90px');
     }

  </script>