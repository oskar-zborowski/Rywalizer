#!/bin/bash

# sudo git pull origin master

cd ../../../
echo "Fetching"
git fetch
echo "Pulling"
sudo git pull https://BolleyVall7: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@github.com/BolleyVall7/Rywalizer.git master
echo "Done at `date`"