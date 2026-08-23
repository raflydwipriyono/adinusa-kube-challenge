# Use Node 20 Alpine
FROM node:20-alpine

# Install pnpm globally
RUN npm install -g pnpm

# Set working directory
WORKDIR /app

# Copy dependency manifests first
COPY package*.json pnpm-lock.yaml ./

# Install dependencies
RUN pnpm install

# Copy all source code
COPY . .

# Expose port
EXPOSE 3000

# Run dev server
CMD ["npm", "run", "dev", "--", "-H", "0.0.0.0", "-p", "3000"]
