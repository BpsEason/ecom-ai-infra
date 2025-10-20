import { mount } from '@vue/test-utils';
import { describe, it, expect, vi } from 'vitest';
import DashboardView from '../views/DashboardView.vue';
import * as api from '@/api/api';
vi.mock('@/api/api', () => ({
    fetchDashboardList: vi.fn(),
    createDashboard: vi.fn(),
    deleteDashboard: vi.fn()
}));
describe('DashboardView', () => {
    it('renders list', async () => {
        api.fetchDashboardList.mockResolvedValue({ success: true, data: { data: [{ id: 1, name: 'Test' }] } });
        const wrapper = mount(DashboardView, { global: { stubs: ['router-link'] } });
        await wrapper.vm.$nextTick();
        expect(wrapper.text()).toContain('Test');
    });
    it('handles error', async () => {
        api.fetchDashboardList.mockResolvedValue({ success: false, error: 'Error' });
        const wrapper = mount(DashboardView, { global: { stubs: ['router-link'] } });
        await wrapper.vm.$nextTick();
        expect(wrapper.text()).toContain('無 Dashboard 數據');
    });
    it('creates item', async () => {
        api.createDashboard.mockResolvedValue({ success: true });
        const wrapper = mount(DashboardView, { global: { stubs: ['router-link'] } });
        await wrapper.find('form').trigger('submit');
        expect(api.createDashboard).toHaveBeenCalled();
    });
});
