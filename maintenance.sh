#!/bin/bash
host="http://localhost/"
serverUri="PAI-proj"
fulluri="$host$serverUri/maintenance/scheduledjobscheck"
echo "$fulluri"
curl --request POST "$host$serverUri/maintenance/scheduledjobscheck" > /dev/null