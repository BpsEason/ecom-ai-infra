import { mount } from '@vue/test-utils';
import { describe, it, expect, vi } from 'vitest';
import ProductView from '../views/ProductView.vue';
import * as api from '@/api/api';
vi.mock('@/api/api', () => ({
    fetchProductList: vi.fn(),
    createProduct: vi.fn(),
    deleteProduct: vi.fn()
}));
describe('ProductView', () => {
    it('renders list', async () => {
        api.fetchProductList.mockResolvedValue({ success: true, data: { data: [{ id: 1, name: 'Test' }] } });
        const wrapper = mount(ProductView, { global: { stubs: ['router-link'] } });
        await wrapper.vm.$nextTick();
        expect(wrapper.text()).toContain('Test');
    });
    it('handles error', async () => {
        api.fetchProductList.mockResolvedValue({ success: false, error: 'Error' });
        const wrapper = mount(ProductView, { global: { stubs: ['router-link'] } });
        await wrapper.vm.$nextTick();
        expect(wrapper.text()).toContain('無 Product 數據');
    });
    it('creates item', async () => {
        api.createProduct.mockResolvedValue({ success: true });
        const wrapper = mount(ProductView, { global: { stubs: ['router-link'] } });
        await wrapper.find('form').trigger('submit');
        expect(api.createProduct).toHaveBeenCalled();
    });
});
