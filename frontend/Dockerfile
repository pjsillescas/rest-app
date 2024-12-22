# frontend/Dockerfile
FROM node:22

# Set working directory
WORKDIR /app

# Copy package.json and install dependencies
#COPY . ./
#RUN yarn install

# Copy the React app source code
COPY . .

# Build the app for production
#RUN npm install --legacy-peer-deps
#RUN npm run build

RUN npm install -g serve
#RUN serve -s build

# Serve the app using a lightweight HTTP server
#RUN yarn global add serve
# CMD ["serve", "-s", "build && npm install --legacy-peer-deps && npm run build"]
#CMD serve -s build || (npm install --legacy-peer-deps && npm run build)

CMD ["/bin/bash","-c","./startup.sh"]

#RUN npm install --legacy-peer-deps
#RUN npm run build
#CMD serve -s build

# Expose port 3000
EXPOSE 3000
