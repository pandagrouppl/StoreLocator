#!/bin/bash
# Install Peterjacksons script

(rm -rf tools)
(cd vendor/PandaGroup/Frontools && npm install && gulp setup)
