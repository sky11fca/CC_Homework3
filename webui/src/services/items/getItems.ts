export function getItems(): Promise<Item[]> {
  return fetch("/api/items/")
    .then((res) => res.json())
    .then((res) => {
      return res as Item[];
    });
}
