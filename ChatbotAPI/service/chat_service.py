import os
from typing import List, Dict

from ollama import AsyncClient


class ChatService:
    def __init__(self):
        self.model = "llama3.2:1b"
        ollama_host = os.getenv("OLLAMA_HOST", "").strip()
        self.client = AsyncClient(host=ollama_host) if ollama_host else None

    def _fallback_greeting(self, username: str) -> str:
        return f"Hello, {username}. How can I help you today?"

    def _fallback_chat_response(self, message: str) -> str:
        return f"I received your message: {message}"

    async def get_greeting(self, username: str) -> str:
        system_prompt = (
            "You are a friendly assistant. "
            "Keep your responses short, concise, and no longer than 1-2 sentences. "
            "Use plain text only. Do NOT use any markdown formatting tags. "
            f"Greet the user named {username}."
        )

        if self.client is None:
            return self._fallback_greeting(username)

        try:
            response = await self.client.chat(
                model=self.model,
                messages=[{"role": "user", "content": system_prompt}]
            )
            return response["message"]["content"]
        except Exception:
            return self._fallback_greeting(username)

    async def get_chat_response(self, message: str, history: List[Dict[str, str]] = None) -> str:
        system_message = {
            "role": "system",
            "content": (
                "You are a friendly assistant. "
                "Keep your responses very short and concise. "
                "Do NOT use any markdown formatting tags in your output."
            )
        }

        messages = [system_message] + (history or []) + [{"role": "user", "content": message}]

        if self.client is None:
            return self._fallback_chat_response(message)

        try:
            response = await self.client.chat(
                model=self.model,
                messages=messages
            )
            return response["message"]["content"]
        except Exception:
            return self._fallback_chat_response(message)
