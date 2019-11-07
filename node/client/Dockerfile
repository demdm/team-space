FROM node:12.2.0-alpine

ARG APP_DIR=/var/www/node_client
#RUN mkdir -p ${APP_DIR}
WORKDIR ${APP_DIR}

#COPY /var/www/project/node/client ${APP_DIR}

ENV PATH ${APP_DIR}/node_modules/.bin:$PATH

COPY package.json ${APP_DIR}/package.json
RUN npm install --silent
RUN npm install react-scripts@3.0.1 -g --silent

#RUN npm install
# Используется при сборке кода в продакшене
# RUN npm install --only=production

EXPOSE 3000

#CMD [ "npm", "run", "dev" ]
#CMD [ "node", "index.js" ]
CMD [ "npm", "start" ]
