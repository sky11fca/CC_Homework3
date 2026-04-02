export function deleteItem(id: string) {
  return fetch(`/api/items/${id}`, {
    method: "DELETE",
    headers: {
      "Content-Type": "application/json",
    },
  });
}
