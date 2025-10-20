<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Marketing 管理</h1>
        <div class="mb-4">
            <button @click="showForm = true" class="bg-blue-500 text-white px-4 py-2 rounded">新增 Marketing</button>
        </div>
        <div v-if="showForm" class="mb-4 p-4 bg-gray-100 rounded">
            <h2 class="text-lg font-semibold">新增 Marketing</h2>
            <form @submit.prevent="submitForm">
                <div class="mb-2">
                    <label class="block">名稱/主題</label>
                    <input v-model="form.name" type="text" class="w-full border rounded px-2 py-1" required>
                </div>
                <div class="mb-2" v-if="['product', 'marketing'].includes('marketing')">
                    <label class="block">描述</label>
                    <textarea v-model="form.description" class="w-full border rounded px-2 py-1"></textarea>
                </div>
                <div class="mb-2" v-if="'product' === 'marketing'">
                    <label class="block">SKU</label>
                    <input v-model="form.sku" type="text" class="w-full border rounded px-2 py-1" required>
                </div>
                <div class="mb-2" v-if="'product' === 'marketing'">
                    <label class="block">價格</label>
                    <input v-model="form.price" type="number" step="0.01" class="w-full border rounded px-2 py-1" required>
                </div>
                <div class="mb-2" v-if="'order' === 'marketing'">
                    <label class="block">客戶 ID</label>
                    <input v-model="form.customer_id" type="number" class="w-full border rounded px-2 py-1" required>
                </div>
                <div class="mb-2" v-if="'order' === 'marketing'">
                    <label class="block">總金額</label>
                    <input v-model="form.total_amount" type="number" step="0.01" class="w-full border rounded px-2 py-1" required>
                </div>
                <div class="mb-2" v-if="'marketing' === 'marketing'">
                    <label class="block">預算</label>
                    <input v-model="form.budget" type="number" step="0.01" class="w-full border rounded px-2 py-1" required>
                </div>
                <div class="mb-2" v-if="'customerservice' === 'marketing'">
                    <label class="block">用戶 ID</label>
                    <input v-model="form.user_id" type="number" class="w-full border rounded px-2 py-1" required>
                </div>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">保存</button>
                <button type="button" @click="showForm = false" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded">取消</button>
            </form>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold mb-2">Marketing 列表</h2>
            <div v-if="loading">載入中...</div>
            <div v-else>
                <div v-for="item in items" :key="item.id" class="border-b py-2">
                    <span>{{ item.name || item.subject }} (ID: {{ item.id }})</span>
                    <button @click="deleteItem(item.id)" class="ml-4 text-red-500">刪除</button>
                </div>
                <div v-if="!items.length" class="text-gray-500">無 Marketing 數據</div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref } from 'vue';
import { fetchMarketingList, createMarketing, deleteMarketing } from '@/api/api';
const items = ref([]);
const loading = ref(false);
const showForm = ref(false);
const form = ref({});
async function loadItems() {
    loading.value = true;
    const response = await fetchMarketingList();
    items.value = response.success ? response.data.data : [];
    loading.value = false;
}
async function submitForm() {
    const response = await createMarketing(form.value);
    if (response.success) {
        showForm.value = false;
        form.value = {};
        loadItems();
    }
}
async function deleteItem(id) {
    const response = await deleteMarketing(id);
    if (response.success) {
        loadItems();
    }
}
loadItems();
</script>
