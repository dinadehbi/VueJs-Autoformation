<template>
  <div class="container mt-5">
    <h2>Create New Article</h2>
    <form @submit.prevent="createArticle">
      <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" v-model="newArticle.title" required>
      </div>
      <div class="mb-3">
        <label for="content" class="form-label">Content</label>
        <textarea class="form-control" id="content" v-model="newArticle.content" required></textarea>
      </div>
      <div class="mb-3">
        <label for="category_id" class="form-label">Category</label>
        <select class="form-control" v-model="newArticle.category_id" required>
          <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
        </select>
      </div>
      <!-- Tags Selection: Multiple tags -->
      <div class="mb-3">
        <label for="tags" class="form-label">Tags</label>
        <select class="form-control" v-model="newArticle.tag_ids" multiple required>
          <option v-for="tag in tags" :key="tag.id" :value="tag.id">{{ tag.name }}</option>
        </select>
        <small class="form-text text-muted">Hold down the Ctrl (Windows) or Command (Mac) key to select multiple tags.</small>
      </div>
      <div class="mb-3">
        <label for="image" class="form-label">Image</label>
        <input type="file" class="form-control" @change="onFileChange">
      </div>
      <button type="submit" class="btn btn-primary">Create Article</button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();

// Article data
const newArticle = ref({
  title: '',
  content: '',
  category_id: null,
  tag_ids: [],  // Array to hold selected tag ids
  image: null,
});

// Categories data
const categories = ref([]);

// Tags data
const tags = ref([]);

// Fetch categories
const fetchCategories = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/categories');
    categories.value = response.data;
  } catch (error) {
    console.error('Error fetching categories:', error);
  }
};

// Fetch tags
const fetchTags = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/tags');
    tags.value = response.data;
  } catch (error) {
    console.error('Error fetching tags:', error);
  }
};

// On file change for image upload
const onFileChange = (event) => {
  newArticle.value.image = event.target.files[0];
};

// Function to create article
const createArticle = async () => {
  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    let formData = new FormData();
    formData.append('title', newArticle.value.title);
    formData.append('content', newArticle.value.content);
    formData.append('category_id', newArticle.value.category_id);
    formData.append('tags', newArticle.value.tag_ids);  // Append selected tags
    if (newArticle.value.image) {
      formData.append('image', newArticle.value.image);
    }

    const response = await axios.post('http://127.0.0.1:8000/api/articles', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        'X-CSRF-TOKEN': csrfToken,  // CSRF token
      },
    });

    console.log(response.data); // Check the response from the server

    // Redirect to the articles list page after creating
    router.push('/articles');  // Update this route as needed
  } catch (error) {
    console.error('Error creating article:', error);
  }
};

// Fetch categories and tags on mounted
onMounted(() => {
  fetchCategories();
  fetchTags();
});
</script>
