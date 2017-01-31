
INSTALLATION

npm install webpack -g
npm install webpack-dev-server -g

npm install

RUN SITE

webpack-dev-server --progress --colors

now go to http://localhost:8888/

PRODUCTION BUILD

NODE_ENV=production webpack -p