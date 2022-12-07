import { createRouter, createWebHistory } from "vue-router";
import 'bootstrap';
import Blog from '../pages/Blog.vue';
import NotFound from '../NotFound.vue';
import Create from '../pages/Create.vue';
import Show from '../pages/Show.vue';

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
        path: '/show/:slug',
        component: Show,
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
