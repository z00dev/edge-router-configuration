## Edge DNS

Edge router uses dnsmasq, the script will create the config for it

```bash
php dns.php form.csv > dns.sh
scp dns.sh edge:/tmp/dns.sh
ssh edge
chmod +x /tmp/dns.sh
/tmp/dns.sh
configure
save
```

## DHCP

You can also create a script to update dhcp entries on the router

```bash
php dhcp.php form.csv > dhcp.sh
scp dhcp.sh edge:/tmp/dhcp.sh
ssh edge
chmod +x /tmp/dhcp.sh
/tmp/dhcp.sh
configure
save
```
