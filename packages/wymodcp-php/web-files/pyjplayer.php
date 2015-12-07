<?php $dbfile = new PDO('sqlite:/wymedia/.wyradio/wyradio.db3'); ?>
<!DOCTYPE html> <html> <head>
  <link type="text/css" href="/scripts/dist/skin/pink.flag/css/jplayer.pink.flag.min.css" rel="stylesheet" />
  <script type="text/javascript" src="/scripts/js/jquery.min.js"></script>
  <script type="text/javascript" src="/scripts/js/jquery.jplayer.min.js"></script>

<?php  $selectsql = 'SELECT url, acronym FROM streamsources';	

foreach ($dbfile->query($selectsql) as $returnrow) {
$acronym = $returnrow['acronym'];

$file_headers = @get_headers( $returnrow['url'] ); 

if(strpos($file_headers[0] , 'ICY') !== false) { 
         $streamurl = $returnrow['url'].";stream/1 ";
 } 

else { 

$streamurl = $returnrow['url'];

}



?>

  <script type="text/javascript">
    $(document).ready(function(){
      $("#jquery_jplayer_<?php echo $acronym; ?> ").jPlayer({
        ready: function () {
          $(this).jPlayer("setMedia", {
            title: "<?php echo $acronym; ?>",
            mp3: " <?php echo $streamurl; ?>"
          });
        },
        cssSelectorAncestor: "#jp_container_<?php echo $acronym; ?>",
        swfPath: "/scripts/js",
        supplied: "mp3",
        useStateClassSkin: true,
        autoBlur: false,
        smoothPlayBar: true,
        keyEnabled: true,
        remainingDuration: true,
        toggleDuration: true
      });
    });
  </script>
<?php } ?>

 </head> <body>

<?php  $selectsql = 'SELECT url, acronym FROM streamsources';	

	foreach ($dbfile->query($selectsql) as $returnrow) {		

$streamurl = $returnrow['url']; 
$acronym = $returnrow['acronym'];

echo "<h6>".$streamurl."</h6>";
?>
 <div id="jquery_jplayer_<?php echo $acronym; ?>" class="jp-jplayer"></div> <div id="jp_container_<?php echo $acronym; ?>" class="jp-audio" 
role="application" aria-label="media player">
  <div class="jp-type-single">
    <div class="jp-gui jp-interface">
      <div class="jp-volume-controls">
        <button class="jp-mute" role="button" tabindex="0">mute</button>
        <button class="jp-volume-max" role="button" tabindex="0">max volume</button>
        <div class="jp-volume-bar">
          <div class="jp-volume-bar-value"></div>
        </div>
      </div>
      <div class="jp-controls-holder">
        <div class="jp-controls">
          <button class="jp-play" role="button" tabindex="0">play</button>
          <button class="jp-stop" role="button" tabindex="0">stop</button>
        </div>
        <div class="jp-progress">
          <div class="jp-seek-bar">
            <div class="jp-play-bar"></div>
          </div>
        </div>
        <div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
        <div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
        <div class="jp-toggles">
          <button class="jp-repeat" role="button" tabindex="0">repeat</button>
        </div>
      </div>
    </div>
    <div class="jp-details">
      <div class="jp-title" aria-label="title">&nbsp;</div>
    </div>
    <div class="jp-no-solution">
      <span>Update Required</span>
      To play the media you will need to either update your browser to a recent version or update your <a 
href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
    </div>
  </div> </div>

<?php } ?>

 </body>
</html>
