<?php
function redirectToSoldat2($destination) {
  $steamRunSoldat2 = "steam://run/474220//s2:%2F%2F";
  $redirect = "Location: " . $steamRunSoldat2 . $destination;
  header($redirect);
  exit();
}

function errorFeedback($message) {
  header('Content-type: text/plain; charset=utf-8');
  echo "There was an error.\n";
  echo $message;
  exit();
}

function getDestination() {
  return $_GET["destination"];
}

function isIPv4($str) {
  return (bool) filter_var($str, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
}

function isIPv6($str) {
  return (bool) filter_var($str, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
}

function isIPv4WithPort($str) {
  $colonPosition = strpos($str, ":");
  if ($colonPosition === false) return false;

  $port = substr($str, $colonPosition + 1);
  if (strlen($port) < 1) return false;

  $ip = substr($str, 0, $colonPosition);
  return isIPv4($ip);
}

function isHexEncodedIPv4($str) {
  if (strlen($str) < 9) return false; // IPv4 in first 8 chars, plus port
  if (strlen($str) > 12) return false; // 8 for IP, max 4 for port
  if (strpos($str, ".") !== false) return false; // IPv4 has period chars
  return true; // very optimistic
}

function decodeHexEncodedIPv4($str) {
  $ipOctet1 = substr($str, 0, 2);
  $ipOctet2 = substr($str, 2, 2);
  $ipOctet3 = substr($str, 4, 2);
  $ipOctet4 = substr($str, 6, 2);
  $port = substr($str, 8);
  return hexdec($ipOctet1) . "." . hexdec($ipOctet2) . "." .
    hexdec($ipOctet3) . "." . hexdec($ipOctet4) . ":" . hexdec($port);
}

function isQueueAddress($str) {
  return strpos($str, "-") !== false; // e.g. EU-CTF-Standard-6
}

function decodeQueueAddress($str) {
  $firstHyphenPosition = strpos($str, "-");
  $region = substr($str, 0, $firstHyphenPosition);
  $playlist = substr($str, $firstHyphenPosition + 1);
  return $region . "%2F" . $playlist;
}

function init() {
  $destination = getDestination();

  // IPv6
  if (isIPv6($destination)) {
    errorFeedback("Soldat 2 does not support IPv6 join links.");
  }

  // EU-CTF-Standard-6
  if (isQueueAddress($destination)) {
    redirectToSoldat2(decodeQueueAddress($destination));
  }

  // AABBCCDDEE
  if (isHexEncodedIPv4($destination)) {
    redirectToSoldat2(decodeHexEncodedIPv4($destination));
  }

  // 12.34.56.78:32000
  if (isIPv4WithPort($destination)) {
    redirectToSoldat2($destination);
  }

  errorFeedback("The 'destination' parameter must contain 'IPv4:port' or 'region-playlist'.");
}

init();
?>