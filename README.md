## Edge Router Configuration

This makes it easy to keep a list of computers/devices in a Google Sheet (or other CSV file)
and use it as the basis for static dhcp allocation and dns overrides.

## DNS

Edge router uses dnsmasq, the script will create the config script

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

## Travis

This is deployed automatically with Travis

The secret env var `CSV_URL` contains url of Google sheet csv link: `https://docs.google.com/spreadsheets/d/{ID}/gviz/tq?tqx=out:csv`

The deploy script logs into the router and deploys the script

## Google Docs

There is a Google Apps script linked to the sheet, the sheet looks something like this - extra columns are ignored

| Timestamp           | Full Name | DNS                  | MAC               | Computer | Connection    | IP       | Name |
|---------------------|-----------|----------------------|-------------------|----------|---------------|----------|------|
| 10/10/2016 09:00:00 | Fire      | fire.ekreative.com   | 00:25:22:98:a4:64 |          | Switch-second | 10.0.1.2 | fire |
| 10/10/2016 09:00:00 | Docker    | docker.ekreative.com |                   |          |               | 10.0.1.2 |      |

[Add a button](https://stackoverflow.com/a/6966272/859027) to sheet to trigger the script

```
function trigger() {
   var data = {
     'request': {
       'branch': 'master'
     }
 };
 var options = {
   'method' : 'post',
   'contentType': 'application/json',
   'payload' : JSON.stringify(data),
   'headers': {
     'Travis-API-Version': '3',
     'Authorization': 'token TOKEN_HERE_',
     'Accept': 'application/json'
   }
 };
 response = UrlFetchApp.fetch('https://api.travis-ci.org/repo/ekreative%2Fedge-router-configuration/requests', options);
 Logger.log(response);
}
```
