<template>
    <div class="post-detail-container" v-if="post">
    <!-- Post Card -->
    <div class="post-card">
      <img :src="post.image" alt="Post Image" class="post-image" />
      <div class="post-content">
        <h1 class="post-title">{{ post.title }}</h1>
        <p class="post-meta">Published on {{ post.published_at }}</p>
        <p class="post-description">{{ post.description }}</p>
      </div>
    </div>

    <!-- Comments Section -->
    <div class="comments-section">
      <h2 class="comments-title">Comments</h2>
      <ul class="comments-list">
        <li v-for="comment in post.comments" :key="comment.id" class="comment-card">
          <p class="comment-author">{{ comment.user }} says:  | {{  comment.created_at }}</p>
          <p class="comment-content">{{ comment.content }}</p>
        </li>
      </ul>
    </div>
    <CommentSection :postId="post.id" :comments="post.comments" />
  </div>
  </template>
  
  <script>
  import axios from '../axios.js';
  import CommentSection from '../components/CommentSection.vue';
  
  export default {
    components: { CommentSection },
    data() {
      return {
        post: null,
      };
    },
    mounted() {
      axios.get(`/${this.$route.params.id}/comment`).then((response) => {
        this.post = response.data.data;
      });
    },
  };
  </script>
  <style scoped>
  /* Main container */
  .post-detail-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
  }
  
  /* Post Card */
  .post-card {
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 24px;
  }
  
  .post-image {
    width: 100%;
    height: auto;
    object-fit: cover;
  }
  
  .post-content {
    padding: 16px;
  }
  
  .post-title {
    font-size: 1.75em;
    color: #333;
    margin: 0 0 8px;
  }
  
  .post-meta {
    font-size: 0.875em;
    color: #888;
    margin: 0 0 16px;
  }
  
  .post-description {
    font-size: 1.125em;
    line-height: 1.6;
    color: #555;
  }
  
  /* Comments Section */
  .comments-section {
    background-color: #f9f9f9;
    border-radius: 8px;
    padding: 16px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }
  
  .comments-title {
    font-size: 1.5em;
    margin-bottom: 16px;
    color: #333;
  }
  
  .comments-list {
    list-style-type: none;
    padding: 0;
    margin: 0;
  }
  
  .comment-card {
    background-color: #fff;
    border-radius: 6px;
    padding: 12px;
    margin-bottom: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  }
  
  .comment-author {
    font-weight: bold;
    font-size: 0.875em;
    color: #007bff;
    margin: 0 0 4px;
  }
  
  .comment-content {
    font-size: 1em;
    color: #555;
    margin: 0;
  }
  </style>