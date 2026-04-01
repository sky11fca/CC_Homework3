export function deleteItem(id: string){
    return fetch(`http://localhost:8080/api/items/${id}`, {
        method: "DELETE",
        headers: {
            "Content-Type": "application/json"
        }
    })
}