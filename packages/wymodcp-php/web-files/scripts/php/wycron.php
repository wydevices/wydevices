<script src='./scripts/js/wydev.js' type='text/javascript'></script>
<?php
// PHPCron v1.01
// written by Katy Coe - http://www.djkaty.com
// (c) Intelligent Streaming 2006


// WyCron.php v1.0
//
// updated the PHPCron v1.01 to fit with native cron for wydevices by Beats
// Not for commercial purpose
// Thanks goes by to Katy Coe for her code.
//
// Freedom to the Wy!

// File containing cronjobs
$cronFile = '/wymedia/usr/etc/cron.d/root';

// No timeout expiration
set_time_limit(0);

cron_edit($cronFile);
exit;

// Crontab editor
function cron_edit($cronFile)
{
    header("Content-type: text/html");
    ?>
    <html>
        <head>
            <title>Cron Editor</title>
        </head>
        <body style="font-family: Verdana, sans-serif; font-size: x-small">

<p> WyCron.php script, modification from PHPCron v1.01 written by Katy Coe
<a href="http://katyscode.wordpress.com/2006/10/17/phpcron-running-scheduled-tasks-from-php-on-a-web-server"/>http://www.djkaty.com</a>
(c) Intelligent Streaming 2006
</p>
            <?php
            if (isset($_GET['crontab'])):
                  ?><p style="color: red">The crontab file will be updated.</p><?php           
                // Negate escaping
                if (get_magic_quotes_gpc())
                {
        			if (ini_get('magic_quotes_sybase'))
        				$data = strtr($_GETT['crontab'], array("''" => "'"));
        			else
        				$data = stripslashes($_GET['crontab']);
                }
                else
                    $data = $_GET['crontab'];

		$data = str_replace("@","\r\n", $data);
		$data = str_replace("%","#", $data);
		echo "<pre>".$data."</pre>";

                $result = @file_put_contents($cronFile, $data);
                
                if ($result):
                    ?><p style="color: red">The crontab file was updated successfully.</p><?php
                else:
                    ?><p style="color: red">The crontab file could not be updated.</p><?php
                endif;

		?><p style="color: red">Trying to restart crond:</p><?php
	
			system ("/wymedia/usr/etc/init.d/crond stop");		      
			system ("/wymedia/usr/etc/init.d/crond start");
		      
		else:

		//echo "nodata";

            endif;
            ?>

            <form name="wycron" id="wycron" action="./scripts/php/wycron.php" method="post">
                <div>
                    <textarea name="crontab" cols="120" rows="30"><?php
                        if (file_exists($cronFile))
                            echo file_get_contents($cronFile);
			    ?></textarea>
                    
<input id="updatecron" name="updatecron" class="button" type="button" onclick="WyCron(document.wycron.crontab.value)" value="Update Crontab"/>
 
                </div>
            </form>
            
       </body>
    </html>
    <?php
}


//NOT NEEDED YET

// Class representing a single cronjob
// Rules for crontab file format and scheduling conditions taken from:
// http://en.wikipedia.org/wiki/Cron
class CronJob {
    public $minutes;
    public $hours;
    public $dates;
    public $months;    public $weekdays;
    public $job;
    public $name;
    
    public function __construct($jobText)
    {
        // Get parameters
        $jobText = trim($jobText);
        $jobText = preg_replace('#\s{2,}#', ' ', $jobText);
        
        // Determine if cron entry starts with a name
        if (preg_match('/[\*0-9]/', substr($jobText, 0, 1)) === 0)
        {
            $jobParams = split(' ', $jobText, 7);
            $this->name = str_replace('_', ' ', $jobParams[0]);
            array_shift($jobParams);
        }
        else
        {
            $jobParams = split(' ', $jobText, 6);
            $this->name = 'Unnamed job';
        }
        
        // If insufficient parameters supplied, abort silently
        if (count($jobParams) < 6)
            return;
        
        // Parse time parameters
        $this->minutes = $this->parse_param($jobParams[0], 0, 59);
        $this->hours = $this->parse_param($jobParams[1], 0, 23);
        $this->dates = $this->parse_param($jobParams[2], 1, 31);
        $this->months = $this->parse_param($jobParams[3], 1, 12);
        $this->weekdays = $this->parse_param($jobParams[4], 0, 7);
        
        // 0 and 7 are both counted as Sunday
        if ($this->weekdays == 7)
            $this->weekdays = 0;
        
        // Shell job command
        $this->job = implode(' ', array_slice($jobParams, 5));
    }
    
    private function parse_param($text, $min, $max)
    {
        $result = array();
        
        // * - all possible values
        if ($text == '*')
            for ($i = $min; $i <= $max; $i++)
                $result[] = $i;
                
        // "*/n" syntax - starts at $min and recurs every n
        elseif (substr($text, 0, 2) == '*/')
            for ($i = $min; $i <= $max; $i += substr($text, 2))
                $result[] = $i;
        
        else
        {
            // Split by commas
            $timeItems = split(',', $text);
            
            foreach ($timeItems as $timeItem)
            {
                // X-Y syntax - starts at X and increments by 1 to Y inclusive,
                // wrapping around from $max to $min if necessary
                if (strpos($timeItem, '-') !== false)
                {
                    list ($first, $last) = split('-', $timeItem, 2);
                    
                    // Bound specified range within min/max parameters
                    $first = max(min($first, $max), $min);
                    $last = max(min($last, $max), $min);
                    
                    // Non-wrapping range
                    if ($first <= $last)
                        for ($i = $first; $i <= $last; $i++)
                            $result[] = $i;
                    
                    // Wrapping range
                    else {
                        for ($i = $first; $i <= $max; $i++)
                            $result[] = $i;
                            
                        for ($i = $min; $i <= $last; $i++)
                            $result[] = $i;
                    }
                }
                
                // Single number
                else
                    $result[] = $timeItem;
            }
        }
        return $result;
    }
}



?>