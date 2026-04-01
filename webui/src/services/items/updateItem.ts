export function updateItem(newItem: Item)
{
    return fetch(`http://localhost:8080/api/items/${newItem.id}`,{
        method: "PUT",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            name: newItem.name,
            quantity: newItem.quantity,
            quality: newItem.quality,
            price: newItem.price
        })
    })
}