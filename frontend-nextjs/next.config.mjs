const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost';
const backendHostname = new URL(apiUrl).hostname;

const nextConfig = {
  reactStrictMode: false,
  images: {
    remotePatterns: [
      {
        protocol: 'http',
        hostname: '**.svc.cluster.local',
      },
      {
        protocol: 'http',
        hostname: 'localhost',
      },
      {
        protocol: 'http',
        hostname: backendHostname,
      },
    ],
  },
};
export default nextConfig;
