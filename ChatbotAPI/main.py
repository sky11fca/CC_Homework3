from fastapi import FastAPI, HTTPException, Depends
from pydantic import BaseModel
from typing import List, Optional

from starlette.middleware.cors import CORSMiddleware

from service.chat_service import ChatService

app = FastAPI(title="Ollama Chat API")


class GreetRequest(BaseModel):
    username: str


class ChatMessage(BaseModel):
    role: str
    content: str


class ChatRequest(BaseModel):
    message: str
    history: Optional[List[ChatMessage]] = None


# Dependency injection provider
def get_chat_service():
    return ChatService()


app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)


@app.post("/ai/greet")
async def greet(request: GreetRequest, service: ChatService = Depends(get_chat_service)):
    try:
        greeting = await service.get_greeting(request.username)
        return {"greeting": greeting}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))


@app.post("/ai/chat")
async def chat(request: ChatRequest, service: ChatService = Depends(get_chat_service)):
    # Convert Pydantic objects back into simple dictionaries for the service layer
    history_dicts = [{"role": msg.role, "content": msg.content} for msg in request.history] if request.history else []

    try:
        response = await service.get_chat_response(request.message, history_dicts)
        return {"response": response}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))
