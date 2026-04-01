export function clearRotten(){
    return fetch("http://localhost:8080/api/items", {
        method: "DELETE",
    })
}