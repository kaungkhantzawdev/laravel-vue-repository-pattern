import { createRouter, createWebHistory } from "vue-router";

import Blog from '../pages/Blog.vue';
import NotFound from '../NotFound.vue';
import Create from '../pages/Create.vue'

const routes = [

    {
        path: '/',
        component: Blog,
    },
    {
        path: '/create-blog',
        component: Create,
    },
    {
        path: '/:pathMatch(.*)*',
        component: NotFound
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
