#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
CDIR=$( pwd )
cd $DIR/../themes
rm -f ../zips/nossas-dcp.zip
zip -r ../zips/nossas-dcp.zip nossas-dcp -x "nossas-dcp/node_modules/*"
