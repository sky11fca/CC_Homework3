export function markAsRotten(id: string) {
  return fetch(`/api/items/${id}/rotten`, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
    },
  });
  /*.then(res => {
            if (!res.ok) {
                throw new Error(`Failed to mark item as rotten: ${res.statusText}`);
            }
            return res.json();
        })*/
}
