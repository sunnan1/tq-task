<template>
  <nav class="navbar">
    <center>
      <a @click.prevent="navigateTo('/')">Home</a>
      &nbsp;
      <a v-if="isAuthenticated" @click.prevent="navigateTo('/create')">Create Post</a>
      &nbsp;
      <a v-if="!isAuthenticated" @click.prevent="navigateTo('/login')">Login</a>
      &nbsp;
      <a v-if="!isAuthenticated" @click.prevent="navigateTo('/register')">Register</a>
      &nbsp;
      <a v-if="isAuthenticated" @click.prevent="logout()">Logout</a>
  </center>
  </nav>
</template>

<script>
import axios from '../axios.js';
export default {
  computed: {
    isAuthenticated() {
      return !!localStorage.getItem('authToken');
    }
  },
  methods: {
    navigateTo(route) {
      this.$router.push(route);
    },
    logout() {
      axios.post(`/logout`, {} , {
            headers: {
              Authorization: `Bearer ${localStorage.getItem('authToken')}`,
              'Content-Type': 'application/json',
            }
          }).then(() => {
            localStorage.removeItem('authToken');
            window.location.href = '/login';
          });
    }
  },
  mounted() {
    if (localStorage.getItem('locale') != 'en' || localStorage.getItem('locale') != 'ar') {
        localStorage.setItem('locale', 'en');
    }
  }
};
</script>