<?php
/**
 * Plugin Name: DKO AddThis Shortcode
 * Plugin URI: https://github.com/davidosomething/dko-addthis
 * Description: AddThis shortcode since the real AddThis plugin doesn't provide one
 * Version: 1.0
 * Author: David O'Trakoun (@davidosomething)
 * Author Email: me@davidosomething.com
 * Author URI: http://www.davidosomething.com/
 */

class DKOAddThis
{
  private $pubid            = '';
  private $services_exclude = '';
  public $widgets = 0;
  public $widget_settings = array(
    'facebook'        => '',
    'twitter'         => '',
    'tumblr'          => '',
    'pinterest'       => '',
    'google_plusone'  => ''
  );

  public function __construct() {
    add_action('wp_footer', array(&$this, 'html_addthis_script'));
    add_shortcode('dko-addthis',   array(&$this, 'html_addthis_button'));
  }

  /**
   * callback for wp_footer hook
   * adds the <script> tag to the page with the publisher id if provided
   */
  public function html_addthis_script() {
    $pubid = '';
    if ($this->pubid) {
      $pubid = '#pubid=' . $this->pubid;
    }
    ?><script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js<?php echo $pubid; ?>"></script><script>var addthis_config = {
      services_exclude: '<?php echo $this->services_exclude; ?>'
    }; var svcs = <?php echo json_encode($this->widget_settings); ?>;<?php
    // @TODO change to foreach, iterate over $widgets array that has settings for each toolbox
    for ($i = 0; $i < $this->widgets; $i++) { $selector = "dkoaddthis-widget-$i"; ?>
      for (var s in svcs) document.getElementById("<?php echo $selector; ?>").innerHTML += '<a class="addthis_button_'+s+'">'+svcs[s]+'</a>';
      addthis.toolbox("#<?php echo $selector; ?>");<?php
    } ?></script><?php
  }

  /**
   * @return string addthis button html
   */
  public function html_addthis_button($args) {
    // extract(shortcode_atts(array(), $args));
    $html = '<div id="dkoaddthis-widget-' . $this->widgets . '" class="dkoaddthis-widget"></div>';
    $this->widgets = $this->widgets + 1;
    return $html;
  }

}

$DKOAddThis = new DKOAddThis();
