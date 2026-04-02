import os
import re
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
        msg = (message or "").strip().lower()

        if not msg:
            return "Please type a short question and I will help."

        if any(word in msg for word in ["hello", "hi", "hey", "salut"]):
            return "Hello! I can help with inventory, prices, and quick shopping tips."

        if any(word in msg for word in ["price", "cost", "pret", "how much"]):
            return "You can load the inventory and check each product card for the current price in RON."

        if any(word in msg for word in ["stock", "quantity", "stoc", "cantitate"]):
            return "Use the inventory section to refresh items and see current quantities in stock."

        if any(word in msg for word in ["buy", "purchase", "cumpar", "shop"]):
            return "Select an item from the shop section and create a purchase with the required amount."

        if any(word in msg for word in ["quality", "calitate", "rotten", "stricat"]):
            return "Product quality is visible in the item badges. Rotten items can be filtered or cleared."

        if any(word in msg for word in ["auth", "login", "register", "account"]):
            return "Use the login card on the dashboard to authenticate and continue with protected actions."

        # Small arithmetic helper for common questions like "2+2".
        math_match = re.search(r"(-?\d+(?:\.\d+)?)\s*([+\-*/])\s*(-?\d+(?:\.\d+)?)", msg)
        if math_match:
            left = float(math_match.group(1))
            op = math_match.group(2)
            right = float(math_match.group(3))
            if op == "+":
                result = left + right
            elif op == "-":
                result = left - right
            elif op == "*":
                result = left * right
            else:
                if right == 0:
                    return "Division by zero is not defined."
                result = left / right

            if result.is_integer():
                return f"The result is {int(result)}."
            return f"The result is {round(result, 4)}."

        cleaned = (message or "").strip()
        if len(cleaned) > 120:
            cleaned = cleaned[:117] + "..."

        return (
            f"I understood: '{cleaned}'. "
            "Ask me about inventory, prices, purchases, login, or a simple math expression."
        )

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
