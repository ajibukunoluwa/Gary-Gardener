import Login from './components/LoginComponent.vue'
import ToDo from './components/ToDoComponent.vue'

export const routes = [
    {
        path: '/',
        name: 'home',
        component: Login
    },
    {
		path:'/login',
        name: 'login',
        component: Login
    },
    {
		path:'/to-do',
        name: 'to-do',
        component: ToDo,
        meta: {
            requiresAuth: true
        }
    }
];

