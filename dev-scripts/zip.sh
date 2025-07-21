#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
CDIR=$( pwd )
cd $DIR/../themes
rm -rf ../zips/dcp.zip
zip -r ../zips/dcp.zip dcp -x "dcp/node_modules/*"
