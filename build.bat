@echo off
title Building project!

mkdir build
cd .\src
dir /s /B *.java > sources.txt
javac @sources.txt -d ../build -encoding utf8
del sources.txt
cd ..