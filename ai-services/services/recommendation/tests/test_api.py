import pytest
from fastapi.testclient import TestClient
from ..main import app
client = TestClient(app)
def test_predict_success():
    response = client.post('/predict', json={"data": {"product_id": 1}})
    assert response.status_code == 200
    assert 'prediction' in response.json()['result']
def test_predict_invalid_input():
    response = client.post('/predict', json={'data': {}})
    assert response.status_code == 422
def test_train_success():
    response = client.post('/train', json={'X': [[1.0, 2.0], [3.0, 4.0]], 'y': [1.0, 2.0]})
    assert response.status_code == 200
    assert response.json()['message'] == 'Model trained successfully'
def test_train_invalid_data():
    response = client.post('/train', json={'X': [[1.0]], 'y': [1.0, 2.0]})
    assert response.status_code == 422
def test_status_endpoint():
    response = client.get("/status")
    assert response.status_code == 200
    assert "model_type" in response.json()
