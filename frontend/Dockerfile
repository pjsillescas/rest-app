# frontend/Dockerfile
FROM node:22

# Set working directory
WORKDIR /app

# Copy the React app source code
COPY . .

RUN npm install -g serve

CMD ["/bin/bash","-c","./startup.sh"]

# Expose port 3000
EXPOSE 3000
