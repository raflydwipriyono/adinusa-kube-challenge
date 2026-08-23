/**
 * Get base API URL based on execution environment
 * - Server-side (container): Use internal Docker network
 * - Client-side (browser): Use public IP
 */
const getBaseUrl = () => {
  // Check if running on server or client
  const isServer = typeof window === 'undefined';
  
  if (isServer) {
    // Server-side: Use Docker service name
    return process.env.API_URL || 'http://backend/api';
  } else {
    // Client-side: Use public URL from env or fallback
    return process.env.NEXT_PUBLIC_API_URL || 'http://192.168.88.10:8000/api';
  }
};

const getFetch = async (url, headers = {}) => {
  const baseUrl = getBaseUrl();
  const fullUrl = `${baseUrl}${url}`;
  
  console.log(`[Fetch] ${fullUrl}`); // Debug log
  
  const res = await fetch(fullUrl, {
    cache: 'no-store',
    headers: {
      'Content-Type': 'application/json',
      Accept: 'application/json',
      ...headers,
    },
  });

  if (res.ok) {
    const data = await res.json();
    return data.data;
  } else {
    throw new Error(`Problem on getting information:${res.status}`);
  }
};

const postFetch = async (url, body, headers = {}) => {
  const baseUrl = getBaseUrl();
  const fullUrl = `${baseUrl}${url}`;
  
  console.log(`[Post] ${fullUrl}`); // Debug log
  
  const res = await fetch(fullUrl, {
    method: 'POST',
    cache: 'no-store',
    headers: {
      'Content-Type': 'application/json',
      Accept: 'application/json',
      ...headers,
    },
    body: JSON.stringify(body),
  });

  return await res.json();
};

export { getFetch, postFetch };