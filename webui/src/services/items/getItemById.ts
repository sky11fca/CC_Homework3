export function getItemById(id: string): Promise<Item>{
    return fetch(`http://localhost:8080/api/items/${id}`)
        .then(res => res.json())
        .then(res => {
            return res as Item
        })
}