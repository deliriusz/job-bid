#!/bin/bash
host="https://jobbid.rafal-kalinowski.pl/"
serverUri=""
fulluri="$host$serverUri/maintenance/scheduledjobscheck"
echo "$fulluri"
curl --request POST "$host$serverUri/maintenance/scheduledjobscheck" > /dev/null
