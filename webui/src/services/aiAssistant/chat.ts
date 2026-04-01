export async function chat(message: string, history: Array<{ role: string, content: string }> = []) {
    return fetch("http://localhost:8000/ai/chat", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            message: message,
            history: history
        })
    }).then(response => {
        if(!response.ok){
            throw new Error("Chat request failed");
        }
        return response.json();
    });
}