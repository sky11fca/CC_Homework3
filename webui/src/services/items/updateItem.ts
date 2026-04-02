export function updateItem(newItem: Item) {
  return fetch(`/api/items/${newItem.id}`, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      name: newItem.name,
      quantity: newItem.quantity,
      quality: newItem.quality,
      price: newItem.price,
    }),
  });
}
