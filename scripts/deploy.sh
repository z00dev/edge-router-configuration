#!/bin/bash
set -e
set -x
test $TRAVIS_BRANCH = "master"
test $TRAVIS_PULL_REQUEST = "false"
eval $(ssh-agent -s)
ssh-add fire.pem
ssh mcfedr@fire.ekreative.com ssh-keyscan -H edge.ekreative.com 2>&1 | tee -a $HOME/.ssh/known_hosts
ssh -o ProxyCommand='ssh -q mcfedr@fire.ekreative.com nc -q0 %h %p' admin@edge.ekreative.com < build/dhcp.sh
ssh -o ProxyCommand='ssh -q mcfedr@fire.ekreative.com nc -q0 %h %p' admin@edge.ekreative.com < build/dns.sh
