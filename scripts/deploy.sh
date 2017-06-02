#!/bin/bash
set -e
eval $(ssh-agent -s)
openssl aes-256-cbc -K $encrypted_f5a3225ce721_key -iv $encrypted_f5a3225ce721_iv -in fire.pem.enc -out fire.pem -d
chmod 0400 fire.pem
ssh-add fire.pem
ssh mcfedr@fire.ekreative.com ssh-keyscan -t $TRAVIS_SSH_KEY_TYPES -H edge.ekreative.com 2>&1 | tee -a $HOME/.ssh/known_hosts
ssh -o ProxyCommand='ssh -q mcfedr@fire.ekreative.com nc -q0 %h %p' admin@edge.ekreative.com < build/dhcp.sh
ssh -o ProxyCommand='ssh -q mcfedr@fire.ekreative.com nc -q0 %h %p' admin@edge.ekreative.com < build/dns.sh
