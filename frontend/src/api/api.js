import axios from 'axios';
const API_BASE_URL = '/api';
const handleError = (error, endpoint) => {
    let message = error.response?.data?.message || error.response?.data?.detail || 'Unknown error';
    return { success: false, data: null, error: message };
};
const fetchPrediction = async (service, data) => {
    try {
        const response = await axios.post(`/ai/${service}/predict`, { data });
        return { success: true, data: response.data.result };
    } catch (error) {
        return handleError(error, `${service}/predict`);
    }
};

export const fetchProductList = async (params = {}) => {
    try {
        const response = await axios.get(`${API_BASE_URL}/product`, { params });
        return { success: true, data: response.data.data };
    } catch (error) {
        return handleError(error, 'fetchProductList');
    }
};
export const getProduct = async (id) => {
    try {
        const response = await axios.get(`${API_BASE_URL}/product/${id}`);
        return { success: true, data: response.data.data };
    } catch (error) {
        return handleError(error, 'getProduct');
    }
};
export const createProduct = async (data) => {
    try {
        const response = await axios.post(`${API_BASE_URL}/product`, data);
        return { success: true, data: response.data.data };
    } catch (error) {
        return handleError(error, 'createProduct');
    }
};
export const updateProduct = async (id, data) => {
    try {
        const response = await axios.put(`${API_BASE_URL}/product/${id}`, data);
        return { success: true, data: response.data.data };
    } catch (error) {
        return handleError(error, 'updateProduct');
    }
};
export const deleteProduct = async (id) => {
    try {
        const response = await axios.delete(`${API_BASE_URL}/product/${id}`);
        return { success: true, data: response.data };
    } catch (error) {
        return handleError(error, 'deleteProduct');
    }
};
export const getProductRecommendations = (id) => fetchPrediction('recommendation', { product_id: id });
export const fetchOrderList = async (params = {}) => {
    try {
        const response = await axios.get(`${API_BASE_URL}/order`, { params });
        return { success: true, data: response.data.data };
    } catch (error) {
        return handleError(error, 'fetchOrderList');
    }
};
export const getOrder = async (id) => {
    try {
        const response = await axios.get(`${API_BASE_URL}/order/${id}`);
        return { success: true, data: response.data.data };
    } catch (error) {
        return handleError(error, 'getOrder');
    }
};
export const createOrder = async (data) => {
    try {
        const response = await axios.post(`${API_BASE_URL}/order`, data);
        return { success: true, data: response.data.data };
    } catch (error) {
        return handleError(error, 'createOrder');
    }
};
export const updateOrder = async (id, data) => {
    try {
        const response = await axios.put(`${API_BASE_URL}/order/${id}`, data);
        return { success: true, data: response.data.data };
    } catch (error) {
        return handleError(error, 'updateOrder');
    }
};
export const deleteOrder = async (id) => {
    try {
        const response = await axios.delete(`${API_BASE_URL}/order/${id}`);
        return { success: true, data: response.data };
    } catch (error) {
        return handleError(error, 'deleteOrder');
    }
};
export const getOrderEta = (id) => fetchPrediction('eta_prediction', { order_id: id });
export const fetchMarketingList = async (params = {}) => {
    try {
        const response = await axios.get(`${API_BASE_URL}/marketing`, { params });
        return { success: true, data: response.data.data };
    } catch (error) {
        return handleError(error, 'fetchMarketingList');
    }
};
export const getMarketing = async (id) => {
    try {
        const response = await axios.get(`${API_BASE_URL}/marketing/${id}`);
        return { success: true, data: response.data.data };
    } catch (error) {
        return handleError(error, 'getMarketing');
    }
};
export const createMarketing = async (data) => {
    try {
        const response = await axios.post(`${API_BASE_URL}/marketing`, data);
        return { success: true, data: response.data.data };
    } catch (error) {
        return handleError(error, 'createMarketing');
    }
};
export const updateMarketing = async (id, data) => {
    try {
        const response = await axios.put(`${API_BASE_URL}/marketing/${id}`, data);
        return { success: true, data: response.data.data };
    } catch (error) {
        return handleError(error, 'updateMarketing');
    }
};
export const deleteMarketing = async (id) => {
    try {
        const response = await axios.delete(`${API_BASE_URL}/marketing/${id}`);
        return { success: true, data: response.data };
    } catch (error) {
        return handleError(error, 'deleteMarketing');
    }
};
export const generateMarketingCopy = (description) => fetchPrediction('content_generation', { description });
export const fetchCustomerServiceList = async (params = {}) => {
    try {
        const response = await axios.get(`${API_BASE_URL}/customerservice`, { params });
        return { success: true, data: response.data.data };
    } catch (error) {
        return handleError(error, 'fetchCustomerServiceList');
    }
};
export const getCustomerService = async (id) => {
    try {
        const response = await axios.get(`${API_BASE_URL}/customerservice/${id}`);
        return { success: true, data: response.data.data };
    } catch (error) {
        return handleError(error, 'getCustomerService');
    }
};
export const createCustomerService = async (data) => {
    try {
        const response = await axios.post(`${API_BASE_URL}/customerservice`, data);
        return { success: true, data: response.data.data };
    } catch (error) {
        return handleError(error, 'createCustomerService');
    }
};
export const updateCustomerService = async (id, data) => {
    try {
        const response = await axios.put(`${API_BASE_URL}/customerservice/${id}`, data);
        return { success: true, data: response.data.data };
    } catch (error) {
        return handleError(error, 'updateCustomerService');
    }
};
export const deleteCustomerService = async (id) => {
    try {
        const response = await axios.delete(`${API_BASE_URL}/customerservice/${id}`);
        return { success: true, data: response.data };
    } catch (error) {
        return handleError(error, 'deleteCustomerService');
    }
};
export const getWaveSuggestion = (id) => fetchPrediction('wave_suggestion', { ticket_id: id });
export const fetchDashboardList = async (params = {}) => {
    try {
        const response = await axios.get(`${API_BASE_URL}/dashboard`, { params });
        return { success: true, data: response.data.data };
    } catch (error) {
        return handleError(error, 'fetchDashboardList');
    }
};
export const getDashboard = async (id) => {
    try {
        const response = await axios.get(`${API_BASE_URL}/dashboard/${id}`);
        return { success: true, data: response.data.data };
    } catch (error) {
        return handleError(error, 'getDashboard');
    }
};
export const createDashboard = async (data) => {
    try {
        const response = await axios.post(`${API_BASE_URL}/dashboard`, data);
        return { success: true, data: response.data.data };
    } catch (error) {
        return handleError(error, 'createDashboard');
    }
};
export const updateDashboard = async (id, data) => {
    try {
        const response = await axios.put(`${API_BASE_URL}/dashboard/${id}`, data);
        return { success: true, data: response.data.data };
    } catch (error) {
        return handleError(error, 'updateDashboard');
    }
};
export const deleteDashboard = async (id) => {
    try {
        const response = await axios.delete(`${API_BASE_URL}/dashboard/${id}`);
        return { success: true, data: response.data };
    } catch (error) {
        return handleError(error, 'deleteDashboard');
    }
};

export const login = async (email, password) => {
    try {
        const response = await axios.post(`${API_BASE_URL}/login`, { email, password });
        return { success: true, data: response.data };
    } catch (error) {
        return handleError(error, 'login');
    }
};
