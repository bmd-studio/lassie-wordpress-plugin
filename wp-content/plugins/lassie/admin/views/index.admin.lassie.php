<div class="wrap">
  <h1>Lassie Administration System Plugin</h1>
  <p>This is the administrative page for the Lassie API-connection. Please fill<br />
    in the full Lassie Host URL (https://yourinstance.lassie.cloud) to connect to<br />
    your Lassie instance. Provide the correct and valid API-keys that can be<br />
    generated in the API-module of Lassie. The keys can be private, as long as you<br />
    specify the server-URL or domain. Don't forget to apply the correct rights for the model-keys to<br />
    be able to implement the model functions you need.</p>

  <?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'lassie_host'; ?>

  <h2 class="nav-tab-wrapper">
      <a href="?page=lassie&tab=lassie_host" class="nav-tab <?php echo $active_tab == 'lassie_host' ? 'nav-tab-active' : ''; ?>">API Settings</a>
      <!--<a href="?page=lassie&tab=settings" class="nav-tab <?php //echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>">Other Options</a>-->
  </h2>

  <form method="post" action="options.php">
    <?php if( $active_tab == 'lassie_host' ) {
	       settings_fields("lassie_host");
	       do_settings_sections("lassie");
       } else if( $active_tab == 'settings' ) {
       }

	     submit_button(); ?>

	    </form>
</div>
