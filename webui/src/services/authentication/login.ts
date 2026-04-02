interface LoginPrompt {
  email: string;
  plainPassword: string;
}

export async function login(prompt: LoginPrompt): Promise<string> {
  const normalizedEmail = prompt.email.trim();
  const normalizedPassword = prompt.plainPassword.trim();

  console.log("[auth/login] request:start", {
    email: normalizedEmail,
    passwordLength: normalizedPassword?.length ?? 0,
  });

  const response = await fetch("http://localhost:5000/api/auth/login", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      email: normalizedEmail,
      plainPassword: normalizedPassword,
    }),
  });

  console.log("[auth/login] response:received", {
    status: response.status,
    ok: response.ok,
  });

  if (!response.ok) {
    const errorText = await response.text();
    let message = errorText;

    try {
      const parsedError = JSON.parse(errorText);
      if (typeof parsedError === "string") {
        message = parsedError;
      } else if (Array.isArray(parsedError?.errors)) {
        message = parsedError.errors.join(", ");
      }
    } catch {
      message = errorText.replace(/^\"|\"$/g, "");
    }

    console.error(
      "[auth/login] response:error",
      message || `HTTP ${response.status}`,
    );
    throw new Error(message || `Login failed with status ${response.status}`);
  }

  const rawBody = await response.text();
  console.log("[auth/login] response:body", rawBody);

  if (!rawBody) {
    console.error("[auth/login] response:empty-body");
    throw new Error("Login succeeded but no token was returned");
  }

  try {
    const parsed = JSON.parse(rawBody);
    if (typeof parsed === "string") {
      console.log(
        "[auth/login] token:parsed-string",
        `${parsed.slice(0, 20)}...`,
      );
      return parsed;
    }
    if (parsed && typeof parsed.token === "string") {
      console.log(
        "[auth/login] token:parsed-object",
        `${parsed.token.slice(0, 20)}...`,
      );
      return parsed.token;
    }
  } catch {
    console.log("[auth/login] token:raw-text", `${rawBody.slice(0, 20)}...`);
    return rawBody;
  }

  console.error("[auth/login] token:invalid-shape", rawBody);
  throw new Error("Login response did not contain a valid token");
}
