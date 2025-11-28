<!DOCTYPE html>
<html>
<head>
<title>Subnetting Manual CIDR</title>
<style>
body{background:#020617;color:white;font-family:Arial}
.box{width:450px;margin:auto;margin-top:40px;background:#0f172a;padding:20px;border-radius:10px}
input,button{width:100%;padding:10px;margin-top:10px}
.result{background:#020617;margin-top:15px;padding:15px;border-radius:5px}
</style>
</head>
<body>

<div class="box">
<h2>Subnet Calculator (CIDR Manual)</h2>

<form method="POST">
IP Address:
<input type="text" name="ip" placeholder="192.168.1.10" required>
CIDR:
<input type="number" name="cidr" placeholder="24" required min="1" max="32">
<button name="hitung">Hitung Subnet</button>
</form>

<?php
if(isset($_POST['hitung'])){

$ip = $_POST['ip'];
$cidr = (int)$_POST['cidr'];

// Validasi IP
if(!filter_var($ip, FILTER_VALIDATE_IP)){
    echo "<div class='result'>IP tidak valid</div>";
    exit;
}

// CIDR → Subnet Mask
function cidrToMask($cidr){
    return long2ip((int)(0xFFFFFFFF << (32 - $cidr)));
}

// Convert IP → Long (fix PHP 8)
$ipLong = (int)sprintf("%u", ip2long($ip));
$mask = cidrToMask($cidr);
$maskLong = (int)sprintf("%u", ip2long($mask));

// NETWORK
$networkLong = $ipLong & $maskLong;

// BROADCAST
$broadcastLong = $networkLong | (~$maskLong & 0xFFFFFFFF);

// Convert kembali ke IP
$network = long2ip($networkLong);
$broadcast = long2ip($broadcastLong);

// Host
if($cidr >= 31){
    $firstHost = "N/A";
    $lastHost = "N/A";
    $totalHost = 0;
} else {
    $firstHost = long2ip($networkLong + 1);
    $lastHost = long2ip($broadcastLong - 1);
    $totalHost = pow(2, 32 - $cidr) - 2;
}

$totalIP = pow(2, 32 - $cidr);

// OUTPUT
echo "<div class='result'>
============
<b>IP:</b> $ip/$cidr <br><br>
==========
<b>Subnet Mask:</b> $mask <br>
<b>Network ID:</b> $network <br>
<b>Broadcast:</b> $broadcast <br>
<b>IP Awal Host:</b> $firstHost <br>
<b>IP Akhir Host:</b> $lastHost <br>
<b>Total IP:</b> $totalIP <br>
<b>Host usable:</b> $totalHost
</div>";

}
?>

</div>
</body>
</html>
