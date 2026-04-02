interface addItemReq {
  name: string;
  quantity: number;
  quality: string;
  price: number;
}

export function addItem(req: addItemReq): Promise<any> {
  return fetch(`/api/items/`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      name: req.name,
      quantity: req.quantity,
      quality: req.quality,
      price: req.price,
    }),
  }).then((res) => {
    if (!res.ok) {
      throw new Error(`Failed to add item: ${res.statusText}`);
    }
    return res.json();
  });
}
