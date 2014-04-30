<?

/*

File: smppexample.php
Implements: exmaple for smppclass.php::SMPPCLass()
License: GNU Lesser Genercal Public License: http://www.gnu.org/licenses/lgpl.html
Commercial advertisement: Contact info@chimit.nl for SMS connectivity and more elaborate SMPP libraries in PHP and other languages.

*/

/*

Caution: Replace the following values with your own parameters.
Leaving the values like this is not going to work!

*/

$smpphost = "smpp.chimit.nl";
$smppport = 2345;
$systemid = "chimit";
$password = "smpppass";
$system_type = "client01";
$from = "31495595392";

$smpp = new SMPPClass();
$smpp->SetSender($from);
/* bind to smpp server */
$smpp->Start($smpphost, $smppport, $systemid, $password, $system_type);
/* send enquire link PDU to smpp server */
$smpp->TestLink();
/* send single message; large messages are automatically split */
$smpp->Send("31651931985", "This is my PHP message");
/* send unicode message */
$smpp->Send("31648072766", "&#1589;&#1576;&#1575;&#1581;&#1575;&#1604;&#1582;&#1610;&#1585;", true);
/* send message to multiple recipients at once */
$smpp->SendMulti("31648072766,31651931985", "This is my PHP message");
/* unbind from smpp server */
$smpp->End();

?>