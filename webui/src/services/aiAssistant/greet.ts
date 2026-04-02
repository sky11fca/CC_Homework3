export async function greet(username: string) {
  return fetch("/ai/greet", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      username: username,
    }),
  }).then((response) => {
    if (!response.ok) {
      throw new Error("Greeting failed");
    }
    return response.json();
  });
}
