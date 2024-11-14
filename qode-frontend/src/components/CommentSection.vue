<template>
    <div v-if="isAuthenticated">
        <div class="editor-container">
            <textarea v-model="newComment" rows="10" cols="110" placeholder="Add a comment..."></textarea>
            <button @click="submitComment">Post Comment</button>
        </div>
    </div>
    <p v-else>Please <router-link to="/login">log in</router-link> to comment.</p>
  </template>
  
  <script>
  import axios from '../axios.js';
  
  export default {
    props: ['postId'],
    computed: {
        isAuthenticated() {
            return !!localStorage.getItem('authToken');
        }
    },
    data() {
      return {
        newComment: '',
      };
    },
    methods: {
      submitComment() {
        if (this.newComment) {
          
          axios.post(`/post/${this.postId}/comment/create`, { content: this.newComment } , {
            headers: {
              Authorization: `Bearer ${localStorage.getItem('authToken')}`,
              'Content-Type': 'application/json',
            }
          }).then(() => {
            this.$router.go(0);
          });
        }
      },
    },
  };
  </script>
    <style scoped>
    .editor-container {
      max-width: 800px;
      margin: 0 auto;
    }
    
    .submit-button {
      margin-top: 10px;
      padding: 10px 20px;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    
    .submit-button:hover {
      background-color: #0056b3;
    }
    </style>
  