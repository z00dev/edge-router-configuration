<?php
declare(strict_types=1);

$filename = $argv[1];
$f = fopen($filename, 'r');
$headerRow = fgetcsv($f);
$afterHeader = ftell($f);

$headers = array_flip($headerRow);
$ipCol = $headers['IP'];
$macCol = $headers['MAC'];
$nameCol = $headers['Name'];

$subnetNames = [
    '10.0.1.0/24' => 'LAN',
    '10.0.3.0/24' => 'LAN-extension',
    '10.0.4.0/23' => 'WIFI'
];

function cidrMatch(string $ip, string $cidr): bool {
    [$subnet, $bits] = explode('/', $cidr);
    $ip = ip2long($ip);
    $subnet = ip2long($subnet);
    $mask = -1 << (32 - $bits);
    $subnet &= $mask;
    return ($ip & $mask) == $subnet;
}

$subnets = [];
while ($row = fgetcsv($f)) {
    $ip = $row[$ipCol] ?? '';
    $mac = $row[$macCol] ?? '';
    $name = $row[$nameCol] ?? '';
    if (!$ip || !$mac || !$name) {
        continue;
    }
    foreach ($subnetNames as $cidr => $subnetName) {
        if (cidrMatch($ip, $cidr)) {
            $subnets[$cidr][] = [$ip, $mac, $name];
        }
    }
}
?>
#!/bin/vbash
source /opt/vyatta/etc/functions/script-template
configure
<?php
foreach($subnetNames as $cidr => $subnetName) {
?>
delete service dhcp-server shared-network-name <?=$subnetName?> subnet <?=$cidr?> static-mapping
<?php
}

foreach ($subnets as $cidr => $entries) {
    foreach ($entries as [$ip, $mac, $name]) {
        ?>
set service dhcp-server shared-network-name <?=$subnetNames[$cidr]?> subnet <?=$cidr?> static-mapping <?=$name?> ip-address <?=$ip?>

set service dhcp-server shared-network-name <?=$subnetNames[$cidr]?> subnet <?=$cidr?> static-mapping <?=$name?> mac-address <?=strtolower($mac)?>

<?php
    }
}
?>
compare
commit
