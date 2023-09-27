import { createRouter, createWebHistory } from 'vue-router';
import index from '../vue/room/components/Index.vue';
import create from '../vue/room/components/Create.vue';

const routes = [
	{
		path: '/room/',
		name: 'index',
		components: {
			default: index,
		},
	},
	{
		path: '/room/create/',
		name: 'create',
		props: true,
		components: {
			default: create,
		},
	},
];

const router = createRouter({
	history: createWebHistory(),
	mode: 'hash',
	routes,
});

export default router;
