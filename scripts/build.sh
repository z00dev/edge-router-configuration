#!/bin/bash
set -e
php dns.php "$CSV_URL" > build/dns.sh
php dhcp.php "$CSV_URL" > build/dhcp.sh
