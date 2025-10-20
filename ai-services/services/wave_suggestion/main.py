from fastapi import FastAPI
from .routes.prediction import router as prediction_router
from .routes.training import router as training_router
app = FastAPI(title="WaveSuggestion Service")
app.include_router(prediction_router, tags=["Prediction"])
app.include_router(training_router, tags=["Training"])
@app.get("/")
def get_root():
    return {"status": "ok", "service": "wave_suggestion"}
