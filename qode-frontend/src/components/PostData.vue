<template>
  <div>
    <h1>Posts</h1>
    <input type="text" v-model="searchQuery" @input="debouncedSearch" placeholder="Search posts..." />
    <div v-for="post in posts" :key="post.id">
      <PostList :post="post" @removePost="deletePost" />
    </div>
    <button v-if="loading" disabled>Loading...</button>
  </div>
</template>

<script>
import axios from '../axios.js';
import PostList from '../components/PostList.vue';

export default {
  components: { PostList },
  data() {
    return {
      posts: [],
      loading: false,
      page: 1,
      searchQuery: '',
      searchTimeout: null, // Used for debouncing
    };
  },
  mounted() {
    this.loadPosts();
    window.addEventListener('scroll', this.handleScroll);
  },
  methods: {
    loadPosts() {
      this.loading = true;
      axios.get(`posts?page=${this.page}`).then((response) => {
        this.posts.push(...response.data.data);
        this.page++;
        this.loading = false;
      });
    },
    searchPosts() {
      this.loading = true;
      axios.get(`/search?query=${this.searchQuery}`).then((response) => {
        this.posts.push(...response.data.data); // Replace posts with search results
        this.loading = false;
      });
    },
    handleScroll() {
      if (
          window.innerHeight + window.scrollY >= document.body.offsetHeight - 500 &&
          !this.loading &&
          !this.searchQuery
      ) {
        this.loadPosts();
      }
    },
    debouncedSearch() {
      clearTimeout(this.searchTimeout);
      this.searchTimeout = setTimeout(() => {
        this.handleSearch();
      }, 1000);
    },
    handleSearch() {
      this.posts = [];
      this.page = 1;
      if (this.searchQuery) {
        this.searchPosts();
      } else {
        this.loadPosts();
      }
    },
    async deletePost(id) {
      try {
        await axios.post(`/post/delete/${id}` , {} , {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('authToken')}`,
            'Content-Type': 'application/json'
          }
        }).then(() => {
          alert('Post Removed Successfully');
          this.handleSearch();
        });
      }catch (error) {
        alert(error.response.data.message)
      }
    }
  },
};
</script>
