export function getItems(): Promise<Item[]>{
    return fetch('http://localhost:8080/api/items')
        .then(res => res.json())
        .then(res => {
            return res as Item[]
        })
}