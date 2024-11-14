import { createRouter, createWebHistory } from 'vue-router';

import PostData from '../components/PostData.vue';
import PostDetail from '../components/PostDetail.vue';
import CreatePost from '../components/CreatePost.vue';
// import EditBlog from '../views/EditBlog.vue';
import LoginUser from '../components/LoginUser.vue';
import RegisterUser from '../components/RegisterUser.vue';

const routes = [
  { path: '/', name: 'PostData', component: PostData },
  { path: '/post/:id', name: 'PostDetail', component: PostDetail },
  { path: '/create', name: 'CreatePost', component: CreatePost },
  // { path: '/edit/:id', name: 'EditBlog', component: EditBlog },
  { path: '/login', name: 'LoginUser', component: LoginUser },
  { path: '/register', name: 'RegisterUser', component: RegisterUser },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
