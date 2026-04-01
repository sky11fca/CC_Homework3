from ollama import AsyncClient
from typing import List, Dict


class ChatService:
    def __init__(self):
        self.model = "llama3.2:1b"
        self.client = AsyncClient()

    async def get_greeting(self, username: str) -> str:
        system_prompt = (
            "You are a friendly assistant. "
            "Keep your responses short, concise, and no longer than 1-2 sentences. "
            "Use plain text only. Do NOT use any markdown formatting tags. "
            f"Greet the user named {username}."
        )

        response = await self.client.chat(
            model=self.model,
            messages=[{"role": "user", "content": system_prompt}]
        )
        return response["message"]["content"]

    async def get_chat_response(self, message: str, history: List[Dict[str, str]] = None) -> str:
        system_message = {
            "role": "system",
            "content": (
                "You are a friendly assistant. "
                "Keep your responses very short and concise. "
                "Do NOT use any markdown formatting tags in your output."
            )
        }

        messages = ([system_message] + history + [{"role": "user", "content": message}]) or []
        messages.append({"role": "user", "content": message})

        response = await self.client.chat(
            model=self.model,
            messages=messages
        )
        return response["message"]["content"]
