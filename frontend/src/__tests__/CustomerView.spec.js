import { mount } from '@vue/test-utils';
import { describe, it, expect, vi } from 'vitest';
import CustomerView from '../views/CustomerView.vue';
import * as api from '@/api/api';
vi.mock('@/api/api', () => ({
    fetchCustomerList: vi.fn(),
    createCustomer: vi.fn(),
    deleteCustomer: vi.fn()
}));
describe('CustomerView', () => {
    it('renders list', async () => {
        api.fetchCustomerList.mockResolvedValue({ success: true, data: { data: [{ id: 1, name: 'Test' }] } });
        const wrapper = mount(CustomerView, { global: { stubs: ['router-link'] } });
        await wrapper.vm.$nextTick();
        expect(wrapper.text()).toContain('Test');
    });
    it('handles error', async () => {
        api.fetchCustomerList.mockResolvedValue({ success: false, error: 'Error' });
        const wrapper = mount(CustomerView, { global: { stubs: ['router-link'] } });
        await wrapper.vm.$nextTick();
        expect(wrapper.text()).toContain('無 Customer 數據');
    });
    it('creates item', async () => {
        api.createCustomer.mockResolvedValue({ success: true });
        const wrapper = mount(CustomerView, { global: { stubs: ['router-link'] } });
        await wrapper.find('form').trigger('submit');
        expect(api.createCustomer).toHaveBeenCalled();
    });
});
