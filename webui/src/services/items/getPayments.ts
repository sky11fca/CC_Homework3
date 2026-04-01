export function getPayments(){
    return fetch('http://localhost:8080/api/purchases')
        .then(res => res.json())
        .then(res => {
            return res as Purchase[]
        })
}