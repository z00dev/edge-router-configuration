<?php
$filename = $argv[1];
$f = fopen($filename, 'r');
$headerRow = fgetcsv($f);

$headers = array_flip($headerRow);
$ipCol = $headers['IP'];
$dnsCol = $headers['DNS'];
?>
#!/bin/vbash
source /opt/vyatta/etc/functions/script-template
configure
delete service dns forwarding options
<?php
while ($data = fgetcsv($f)) {
    if ($data[$ipCol] && $data[$dnsCol]) {
?>
set service dns forwarding options "address=/<?=$data[$dnsCol]?>/<?=$data[$ipCol]?>"
<?php
    }
}
?>
commit
