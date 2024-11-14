<template>
    <div class="create-post-container">
      <div class="create-post-card">
        <h2 class="create-post-title">Create a New Post</h2>
        <span v-if="error">Error : {{ error }}</span>
        <form @submit.prevent="submitPost">
          
          <!-- Title Field -->
          <label for="title" class="form-label">Title</label>
          <input type="text" id="title" v-model="title" class="form-input" placeholder="Enter the title" required />
          
          <!-- Excerpt Field -->
          <label for="excerpt" class="form-label">Excerpt</label>
          <textarea id="excerpt" v-model="excerpt" class="form-textarea" placeholder="Enter a short excerpt" required></textarea>
          
          <!-- Description Field -->
          <label for="description" class="form-label">Description</label>
          <textarea id="description" v-model="description" class="form-textarea" placeholder="Enter the post description" required></textarea>
          
          <!-- Image Upload Field -->
          <label for="image" class="form-label">Image</label>
          <input type="file" id="image" @change="handleImageUpload" class="form-input-file" />
          
          <!-- Tags Field -->
          <label for="tags" class="form-label">Tags</label>
          <input type="text" id="tags" v-model="tags" class="form-input" placeholder="Enter tags separated by commas" />
          
          <!-- SEO Metadata Fields -->
          <h3 class="seo-title">SEO Metadata</h3>
          
          <label for="metaTitle" class="form-label">Meta Title</label>
          <input type="text" id="metaTitle" v-model="metaTitle" class="form-input" placeholder="Enter meta title" />
          
          <label for="metaDescription" class="form-label">Meta Description</label>
          <textarea id="metaDescription" v-model="metaDescription" class="form-textarea" placeholder="Enter meta description"></textarea>
          
          <label for="metaDescription" class="form-label">Publish At</label>
          <input type="datetime-local" v-model="publish_at" class="form-input" placeholder="Enter Publish Time" @change="formatDateTime" />
          
          <!-- Publish Button -->
          <button type="submit" class="form-button">Publish</button>
          
        </form>
      </div>
    </div>
  </template>
  
  <script>
  import axios from '../axios.js';
  export default {
    data() {
      return {
        title: '',
        excerpt: '',
        description: '',
        image: null,
        tags: '',
        metaTitle: '',
        metaDescription: '',
        error : '',
        publish_at : '',
        formattedTime: '',
      };
    },
    methods: {
      formatDateTime() {
        if (this.datetime) {
            const date = new Date(this.publish_at);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const seconds = String(date.getSeconds()).padStart(2, '0');
            this.formattedTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        }
      },
      handleImageUpload(event) {
        this.image = event.target.files[0]; // Store the uploaded image
      },
      async submitPost() {
        try {
            const formData = new FormData();
            formData.append('title', this.title);
            formData.append('excerpt', this.excerpt);
            formData.append('description', this.description);
            formData.append('keywords', this.tags);
            formData.append('meta_title', this.metaTitle);
            formData.append('meta_description', this.metaDescription);
            formData.append('publish_at', this.formattedTime);
            if (this.image) formData.append('image', this.image);
            await axios.post(`/post/create`, formData , {
                headers: {
                Authorization: `Bearer ${localStorage.getItem('authToken')}`,
                'Content-Type': 'multipart/form-data'
                }
            }).then(() => {
                this.$router.go('/');
            });
        } catch (error) {
            this.error = error.response.data.message;
        }
        
      },
    },
  };
  </script>
  
  <style scoped>
  /* Center the create post container */
  .create-post-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    background-color: #f9f9f9;
    padding: 20px;
  }
  
  /* Card style for the form */
  .create-post-card {
    width: 100%;
    max-width: 600px;
    padding: 24px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }
  
  /* Title styling */
  .create-post-title {
    font-size: 1.75em;
    color: #333;
    margin-bottom: 24px;
    text-align: center;
  }
  
  /* Form labels */
  .form-label {
    display: block;
    font-size: 0.875em;
    color: #555;
    margin-bottom: 6px;
    text-align: left;
  }
  
  /* Input fields */
  .form-input,
  .form-textarea,
  .form-input-file {
    width: 100%;
    padding: 12px;
    font-size: 1em;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 16px;
    box-sizing: border-box;
    transition: border-color 0.3s;
  }
  
  .form-input:focus,
  .form-textarea:focus {
    border-color: #007bff;
    outline: none;
  }
  
  .form-textarea {
    min-height: 80px;
    resize: vertical;
  }
  
  /* SEO section title */
  .seo-title {
    font-size: 1.25em;
    color: #333;
    margin: 16px 0;
    text-align: left;
  }
  
  /* Publish button */
  .form-button {
    width: 100%;
    padding: 12px;
    font-size: 1em;
    font-weight: bold;
    color: #fff;
    background-color: #007bff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
  }
  
  .form-button:hover {
    background-color: #0056b3;
  }
  </style>
  