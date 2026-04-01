export function purchaseItem(id: string, amount: number) {
    return fetch(`http://localhost:8080/api/purchase/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            ammount: amount
        })
    }).then(res => {
        if (!res.ok) {
            throw new Error(`Failed to purchase item: ${res.statusText}`);
        }
        return res.json();
    });
}