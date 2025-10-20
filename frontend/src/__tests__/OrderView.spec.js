import { mount } from '@vue/test-utils';
import { describe, it, expect, vi } from 'vitest';
import OrderView from '../views/OrderView.vue';
import * as api from '@/api/api';
vi.mock('@/api/api', () => ({
    fetchOrderList: vi.fn(),
    createOrder: vi.fn(),
    deleteOrder: vi.fn()
}));
describe('OrderView', () => {
    it('renders list', async () => {
        api.fetchOrderList.mockResolvedValue({ success: true, data: { data: [{ id: 1, name: 'Test' }] } });
        const wrapper = mount(OrderView, { global: { stubs: ['router-link'] } });
        await wrapper.vm.$nextTick();
        expect(wrapper.text()).toContain('Test');
    });
    it('handles error', async () => {
        api.fetchOrderList.mockResolvedValue({ success: false, error: 'Error' });
        const wrapper = mount(OrderView, { global: { stubs: ['router-link'] } });
        await wrapper.vm.$nextTick();
        expect(wrapper.text()).toContain('無 Order 數據');
    });
    it('creates item', async () => {
        api.createOrder.mockResolvedValue({ success: true });
        const wrapper = mount(OrderView, { global: { stubs: ['router-link'] } });
        await wrapper.find('form').trigger('submit');
        expect(api.createOrder).toHaveBeenCalled();
    });
});
