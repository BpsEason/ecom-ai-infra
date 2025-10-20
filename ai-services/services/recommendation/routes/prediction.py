from fastapi import APIRouter, HTTPException
from pydantic import BaseModel
from ..models.recommendation_model import RecommendationModel
router = APIRouter()
ai_model = RecommendationModel()
class InputData(BaseModel):
    data: dict
@router.post("/predict")
async def predict_endpoint(input_data: InputData):
    try:
        result = ai_model.predict(input_data.data)
        return {'result': result, 'service': 'recommendation'}
    except ValueError as e:
        raise HTTPException(status_code=422, detail=str(e))
    except Exception as e:
        raise HTTPException(status_code=500, detail=f'Prediction failed: {str(e)}')
