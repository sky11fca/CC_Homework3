export function clearRotten() {
  return fetch("/api/items/", {
    method: "DELETE",
  });
}
