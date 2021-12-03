#!/bin/bash

# sudo git pull origin master

cd ../../../ 
echo "Fetching"
git fetch
echo "Pulling"
git pull origin master
echo "Done at `date`"