from fastapi import APIRouter, HTTPException
from pydantic import BaseModel
from ..models.eta_prediction_model import EtaPredictionModel
router = APIRouter()
ai_model = EtaPredictionModel()
class InputData(BaseModel):
    data: dict
@router.post("/predict")
async def predict_endpoint(input_data: InputData):
    try:
        result = ai_model.predict(input_data.data)
        return {'result': result, 'service': 'eta_prediction'}
    except ValueError as e:
        raise HTTPException(status_code=422, detail=str(e))
    except Exception as e:
        raise HTTPException(status_code=500, detail=f'Prediction failed: {str(e)}')
