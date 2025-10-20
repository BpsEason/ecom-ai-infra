from fastapi import APIRouter, HTTPException
from pydantic import BaseModel
from ..models.eta_prediction_model import EtaPredictionModel
import numpy as np
router = APIRouter()
ai_model = EtaPredictionModel()
class TrainingData(BaseModel):
    X: list[list[float]]
    y: list[float]
@router.post("/train")
async def train_model_endpoint(data: TrainingData):
    try:
        if len(data.X) < 2 or len(data.y) != len(data.X):
            raise ValueError('Invalid training data')
        result = ai_model.train_and_save(np.array(data.X), np.array(data.y))
        return {'message': 'Model trained successfully', 'result': result}
    except ValueError as e:
        raise HTTPException(status_code=422, detail=str(e))
    except Exception as e:
        raise HTTPException(status_code=500, detail=f'Training failed: {str(e)}')
@router.get("/status")
def get_model_status():
    return ai_model.get_status()
