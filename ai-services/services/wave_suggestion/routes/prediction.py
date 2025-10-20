from fastapi import APIRouter, HTTPException
from pydantic import BaseModel
from ..models.wave_suggestion_model import WaveSuggestionModel
router = APIRouter()
ai_model = WaveSuggestionModel()
class InputData(BaseModel):
    data: dict
@router.post("/predict")
async def predict_endpoint(input_data: InputData):
    try:
        result = ai_model.predict(input_data.data)
        return {'result': result, 'service': 'wave_suggestion'}
    except ValueError as e:
        raise HTTPException(status_code=422, detail=str(e))
    except Exception as e:
        raise HTTPException(status_code=500, detail=f'Prediction failed: {str(e)}')
