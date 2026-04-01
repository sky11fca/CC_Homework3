interface RegisterPrompt{
    username: string
    email: string
    password: string
}

export async function register(registerPrompt: RegisterPrompt){
    return fetch("http://localhost:5000/api/auth/register", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            username: registerPrompt.username,
            email: registerPrompt.email,
            plainPassword: registerPrompt.password
        })
    })
        .then(response => {
            if(!response.ok){
                throw new Error(`Registration failed ${response}`)
            }
            return response.json()
        })
}