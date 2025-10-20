import { mount } from '@vue/test-utils';
import { describe, it, expect, vi } from 'vitest';
import MarketingView from '../views/MarketingView.vue';
import * as api from '@/api/api';
vi.mock('@/api/api', () => ({
    fetchMarketingList: vi.fn(),
    createMarketing: vi.fn(),
    deleteMarketing: vi.fn()
}));
describe('MarketingView', () => {
    it('renders list', async () => {
        api.fetchMarketingList.mockResolvedValue({ success: true, data: { data: [{ id: 1, name: 'Test' }] } });
        const wrapper = mount(MarketingView, { global: { stubs: ['router-link'] } });
        await wrapper.vm.$nextTick();
        expect(wrapper.text()).toContain('Test');
    });
    it('handles error', async () => {
        api.fetchMarketingList.mockResolvedValue({ success: false, error: 'Error' });
        const wrapper = mount(MarketingView, { global: { stubs: ['router-link'] } });
        await wrapper.vm.$nextTick();
        expect(wrapper.text()).toContain('無 Marketing 數據');
    });
    it('creates item', async () => {
        api.createMarketing.mockResolvedValue({ success: true });
        const wrapper = mount(MarketingView, { global: { stubs: ['router-link'] } });
        await wrapper.find('form').trigger('submit');
        expect(api.createMarketing).toHaveBeenCalled();
    });
});
