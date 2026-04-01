interface LoginPrompt{
    email: string
    plainPassword: string
}

export function login(prompt: LoginPrompt)
{
    return fetch("http://localhost:5000/api/auth/login", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            email: prompt.email,
            plainPassword: prompt.plainPassword
        })
    }).then(response => {
        if(!response.ok){
            throw new Error("Login failed")
        }
        return response
    })
}
