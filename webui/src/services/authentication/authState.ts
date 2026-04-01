import { ref } from 'vue';

// Initialize the state from local storage so it persists across page reloads
export const currentUser = ref(localStorage.getItem('username'));

export const setAuth = (token: string) => {
  localStorage.setItem('jwt_token', token);
  
  try {
    // Decode the JWT payload. We replace Base64-URL specific characters to standard Base64
    const payloadBase64 = token.split('.')[1].replace(/-/g, '+').replace(/_/g, '/');
    const decodedJson = atob(payloadBase64);
    const payload = JSON.parse(decodedJson);

    console.log('Decoded Payload:', payload);
    
    // Extract Username from payload based on the token structure you described
    const username = payload.name;
    
    currentUser.value = username;
    localStorage.setItem('username', username);
  } catch (error) {
    console.error('Failed to parse JWT', error);
  }
};

export const logout = () => {
  localStorage.removeItem('jwt_token');
  localStorage.removeItem('username');
  currentUser.value = null;
};