export function getItemById(id: string): Promise<Item> {
  return fetch(`/api/items/${id}`)
    .then((res) => res.json())
    .then((res) => {
      return res as Item;
    });
}
