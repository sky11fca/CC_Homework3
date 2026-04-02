interface RegisterPrompt {
  username: string;
  email: string;
  password: string;
}

export async function register(registerPrompt: RegisterPrompt) {
  const response = await fetch("http://localhost:5000/api/auth/register", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      username: registerPrompt.username,
      email: registerPrompt.email,
      plainPassword: registerPrompt.password,
    }),
  });

  if (!response.ok) {
    const errorText = await response.text();
    throw new Error(
      errorText || `Registration failed with status ${response.status}`,
    );
  }

  return response.json();
}
