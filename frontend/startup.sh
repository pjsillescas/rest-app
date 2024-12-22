#! /bin/bash

npm install --legacy-peer-deps
npm run build

serve -s build