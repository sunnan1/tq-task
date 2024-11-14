<template>
  <div class="blog-card">
    <img v-if="post.image != ''" :src="post.image" class="blog-image"/>
    <div class="blog-content">
      <h2 class="blog-title">{{ post.title }}</h2>
      <p class="blog-excerpt">{{ post.excerpt }}</p>
      <p class="blog-meta">
        Published on {{ post.published_at }}
      </p>
      <router-link :to="{ name: 'PostDetail', params: { id: post.id } }" class="read-more">Read More</router-link>
      <p>
        <button @click="destroy(post.id)">Remove</button>
      </p>
    </div>
  </div>
</template>

<script>
export default {
  props: ['post'],
  methods: {
    destroy(id) {
      this.$emit('removePost' , id); // Emit the event to trigger the parent method
    },
  },
};
</script>

<style scoped>
.blog-card {
  display: flex;
  align-items: flex-start;
  border: 1px solid #ddd;
  border-radius: 8px;
  overflow: hidden;
  margin: 16px 0;
  padding: 16px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  transition: box-shadow 0.3s;
}

.blog-card:hover {
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.blog-image {
  width: 150px;
  height: 150px;
  object-fit: cover;
  border-radius: 8px;
  margin-right: 16px;
}

.blog-content {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  max-width: 100%;
}

.blog-title {
  font-size: 1.25em;
  margin: 0 0 8px;
  color: #333;
}

.blog-excerpt {
  font-size: 1em;
  color: #555;
  margin: 8px 0;
}

.blog-meta {
  font-size: 0.875em;
  color: #888;
}

.read-more {
  margin-top: 8px;
  font-weight: bold;
  color: #007bff;
  text-decoration: none;
}

.read-more:hover {
  text-decoration: underline;
}
</style>
